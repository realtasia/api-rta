<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model. Does not extend Kohana Model. All models extends this
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model
{

    /**
    *
    * @var mixed MongoID || string. Used if a record(s) is retrieved for a
    * authenticated in user
    */
    protected $_user_id;

    /**
     *
     * @var MongoCursor. This is the MongDB cursor object. Used globally within
     * the Model object
     */
    public $cursor;

    /**
     *
     * @var MongoCollection. The MongoDB Collection. Also used globally within
     * the Model
     */
    public $collection;

    /**
     *
     * @var string Another global string property. The name of the current
     * collection
     */
    public $collname;

    /**
     *
     * @var array This field is used in most document schemas
     */
    public static $counts = array(
          'attachments'	 => 0,
          'comments'	 => 0,
          'events'	 => 0,
          'likers'	 => 0,
          'listings'	 => 0,
          'posts'        => 0,
          'subscribers'	 => 0,
          'testimonials' => 0,
    );

    /**
     * Create a new model instance. A [Database] instance or configuration
     * group name can be passed to the model. If no database is defined, the
     * "default" database group will be used.
     *
     *     $model = Model::factory($name);
     *
     * @param   string   model name
     * @param   mixed    Database instance object or string
     * @return  Model
     */

    public static function factory($collection, $config = 'default')
    {
        // Add the model prefix
        $class = 'Model_' . ucfirst($collection);

        return new $class($collection, $config);
    }

    /**
     * Loads the collection.
     *
     *     $model = new Foo_Model('user', $db_config);
     *
     * @param   mixed  Database instance object or string
     * @return  void
     */
    public function __construct($collection, $config = 'default')
    {
        // Using Collin's database instantating but accessing MongoDB directly
        $this->db = Mongo_Database::instance($config)->db();
        //
        $this->collname = $collection;
        // set the collection
        $this->collection = $this->db->{Inflector::plural($collection)};

    }


    /**
     * Creates or updates document
     *
     * @param array $document
     * @return boolean
     */
    public function save($document)
    {
        if(empty($document)){
            return false;
        }
        try
        {
            $this->collection->update($document, array('safe' => TRUE, 'upsert' => TRUE));
        }
        catch(MongoCursorException $e)
        {
            Debug::error(
                  "Error Message- :message \n"
                  ."Error Code- :code", array(
                     ':interaction' => $i::action(), ':message' => $e->getMessage(),
                     ':code' => $e->getCode()
            ));
        }
        return true;
    }
    /**
     * This is the global utility method that generally extends each model's
     * _recurse() function
     *
     *    $results = $this->as_array();
     *
     * @return array|NULL an array of documents (arrays) or NULL if empty
     */
    public function as_array()
    {
        return $this->_recurse(FALSE);
    }

    /**
     * This is the global utility method that generally extends each model's
     * _recurse() function
     *
     *    $results = $this->as_object();
     *
     * @return array|NULL an array of documents (objects) or NULL if empty
     */
    public function as_object()
    {
        return $this->_recurse();
    }

    /**
     * This is the global utility function that generally extends each model's
     * _recurse() function
     *
     *    $results = $this->as_json();
     *
     * @return array|NULL a json array of Documents (json objects) or NULL if empty
     */
    public function as_json()
    {
        return json_encode($this->as_object());
    }

    /**
     * Testing revealed that can't query the DB with string IDs. Utility to
     * ensure all ids are in fact instances of the MongoID object.
     *
     * @todo Consider DBRefs??
     *
     *
     * @param string|MongoID $id
     * @return type
     */
    public static function check_id($id)
    {
        return ($id instanceof MongoID) ? $id : new MongoID($id);
    }

    /**
     * Utility to convert mongoids to string for comparisons
     *
     * @param MongoID $id
     * @return string
     */
    public static function string_id($id)
    {
        return $id instanceof MongoID ? $id->__toString() : $id;
    }

    /**
     * Utility function to create DBRefs in Mongo.
     *
     * @param String $collection
     * @param MongoID $dbid
     * @return bool
     */
    public static function create_ref($collection, $dbid)
    {
        if(MongoDBRef::isRef((array) $dbid))
            return $dbid;

        return MongoDBRef::create($collection, $dbid);
    }

    /**
     * Utility function to get DBRefs in Mongo.
     *
     * @param array $ref the MongoDB DBRef
     * @return MongoDocument
     */
    public function get_ref(array $ref)
    {
          return MongoDBRef::get( Mongo_Database::instance($this->_config), $ref);
    }

    /**
     * Interaction method processes positive (like, subscribe) or negative
     * (unlike, unsubscribe) actions.
     * Functionality:
     * <ol>
     *      <li>Updates an Object, removing or adding an Interactor(user)</li>
     *      <li>Updates an Interactor(user), removing or adding Object </li>
     *      <li>Increments or Decrements the relevant Interaction Counts</li>
     * </ol>
     *
     * This method can handle existing entries, i.e. no duplicates. Considered
     * using DBRefs as a user will have many different MongoDocument types in
     * his subscription list. Might be worth to implement in future.
     *
     * <b>The following specs are excerpts of each Schema to describe the way
     * interactions are stored </b>
     *
     * Common Interaction-based Document Schemas
     * (array of subscribers, likers & counts)
     *
     * <pre>
     * array( subscribers =>
     *          array(
     *            _id => MongoID (userid),
     *            _id => MongoID (userid),
     *          ),
     *        likers =>
     *          array(
     *            _id => MongoID (userid),
     *            _id => MongoID (userid),
     *          ),
     *        counts =>
     *          array(
     *            likers => Integer,
     *            subscribers => Integer,
     *          )
     * )
     * </pre>
     *
     * Standard User Document Schema (array of subscriptions, likes & counts)
     *
     * <pre>
     * array( subscriptions =>
     *          array(
     *              locations => array(_id => MongoID, ...),
     *              profiles  => array(_id => MongoID, ...),
     *              posts     => array(_id => MongoID, ...),
     *              listings  => array(_id => MongoID, ...),
     *          ),
     *        likes =>
     *          array(
     *              locations => array(_id => MongoID, ...),
     *              profiles  => array(_id => MongoID, ...),
     *              posts     => array(_id => MongoID, ...),
     *              listings  => array(_id => MongoID, ...),
     *          ),
     *        counts =>
     *          array(
     *              likes => Integer,
     *              subscriptions  => Integer,
     *              // ... contains other unrelated counters too...
     *          )
     * )
     * </pre>
     *
     * @param string|MongoID $user_id
     * @param string $action The interaction, (subscribe, unsubscribe, like etc)
     * @param string|MongoID $_id the current object id;
     * @param Interact $i an instantated Interact object
     * @return Model
     */
    public static function interact($_id, $user_id, Interact $i)
    {
        // verify the user_id and _id to ensure they are MongoIDs
        $_id     = self::check_id($_id);
        $user_id = self::check_id($user_id);

        // comments are special. We don't remove the subscriber just because his
        // comment is deleted. He might have other comments. check if there are
        // existing comments by this user if this user has an existing comment,
        // we don't touch the subscriptions. The query is created by Interactions
        if(($i::object() == 'comments' AND $i::action() == 'delete') AND self::factory('comment')->get_by_post($_id, $user_id))
            $update = $i::get_update_query($user_id);
        else
            $update = $i::get_update_query($user_id, TRUE);

        // these are the standard query criterias for both doc and user, the
        // estate portion is automatically ignored for user type documents.
        $doc_criteria = array(
           '_id' => $_id
        );

        // this adds another criteria to ensure there are no duplicated
        // interactions
        $doc_criteria = array_merge($doc_criteria, $i::get_criteria_query($user_id));
        //Debug::log(print_r(self::factory('post')->collection->findOne($doc_criteria), TRUE));
        //Debug::log(print_r($doc_criteria, TRUE));

        // Update the document (object).
        try
        {
            // the modified field is non-existant in a user type object (document).
            // this will create one but will only be used internally for admins
            // user activity is only updated on own activity, via "active" field
            self::factory($i::model())
                ->collection
                ->update($doc_criteria, $update, array('safe' => TRUE));
        }
        catch(MongoCursorException $e)
        {
            // log the error and return
            Debug::error(
                  "MongoCursorException while trying to update document's (id-:id) ':interaction' by user (id-:uid). \n"
                  ."Error Message- :message \n"
                  ."Error Code- :code", array(
                     ':id' => $_id, ':uid' => $user_id,
                     ':interaction' => $i::action(), ':message' => $e->getMessage(),
                     ':code' => $e->getCode()
            ));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * retrieve a list of members subscribing to an object.
     *
     *
     * @param string|MongoID $_id the post id
     * @param int $skip The offset to retrieve records from
     * @param int $limit The number of records to retrieve
     * @param string|MongoID $user_id
     * @return MongoCursor of user objects
     */
    public function browse_subscribers($_id, $skip = 0, $limit = 11)
    {
        $subscribers = $this->collection
                            ->findOne(
                                  array('_id' => self::check_id($_id)),
                                  array('subscribers' => array('$slice' => array($skip, $limit)))
                            );
        if(!empty($subscribers))
            return self::factory('user')->search(array(
               'include_ids' => $subscribers
            ));

        return NULL;

    }

    /**
     * retrieve a list of members liking an object.
     *
     *
     * @param string|MongoID $_id the post id
     * @param int $skip The offset to retrieve records from
     * @param int $limit The number of records to retrieve
     * @param string|MongoID $user_id
     * @return MongoCursor of user objects
     */
    public function browse_likers($_id, $skip = 0, $limit = 11)
    {
        $likers = $this->collection
                            ->findOne(
                                  array('_id' => self::check_id($_id)),
                                  array('likers' => array('$slice' => array($skip, $limit)))
                            );
        if(!empty($likers))
            return self::factory('user')->search(array(
               'include_ids' => $likers
            ));

        return NULL;
    }

    /**
     *
     * A method that retrieves subscriptions from an object
     *
     *
     * @param string|MongoID $_id the post id
     * @param int $skip The offset to retrieve records from
     * @param int $limit The number of records to retrieve
     * @param string|MongoID $user_id
     * @return array of comments
     */
    public function browse_subscriptions($_id, $skip = 0, $limit = 11)
    {
        $this->collection
              ->find(
                    array('_id' => self::check_id($_id)),
                    array('subscriptions' => TRUE)
              )
              ->skip((int) $skip)
              ->limit((int) $limit);

        return $this;
    }

    /**
     * This checks an object to see if a current logged in user is interacting
     * with it. I.E. Whether he is liking or subscribing to it.
     *
     * @param array|MongoDocument $document
     * @param string|MongoID $user_id
     * @return array the formatted document object
     */
    protected function interacting($document, $user_id)
    {
        // if this document does not have a counts field then it should be a
        // machine generated post. No need to do more, just return.
        if(!isset($document['counts']))
            return $document;

        $results = Model::factory('interaction')->interacting($id, $user_id);

        $return = [];

        while ($results->hasNext())
        {
            $interaction = $results->getNext();
            $return[$interaction['interaction']] = true;
        }

        return $return;
    }

    /**
     * This is a utility method that updates all relevant ancestors of a document
     * if applicable. Normally updates timestamps and counts.
     *
     * @param Interact $i An instantated Interact object
     *
     * return bool
     */
    public static function update_parent_documents($id, Interact $i)
    {
        // verify the id
        $id = self::check_id($id);
        // set empty arrays
        $pids = $criteria = array();

        // only update parent counts if the interaction was 'new_..' or 'delete..'
        // this is because any other interaction like subscribe or like pertains
        // to a specific document, not its parents
        $count = (strpos($i->action(), 'new') === 0 OR strpos($i->action(), 'delete') === 0);

        // decide what to update. If the interacted object was a user, we do nothing
        switch($i->model())
        {
            case 'post':
            case 'comment':
                // fetch the post
                $post = self::factory('post')->get($id);
            break;
            case 'page':
                // fetch page
                $page = self::factory('page')->get($id);
            break;
            // do nothing
            case 'user': return TRUE;
        }

        // add counts if $count is true
        if($count)
            $new = $i->get_count_query();

        // the default updating query;
        $new['$set'] = array('modified' => time());

        if(isset($post))
        {
            if(property_exists($post, 'parent_id') AND !empty($post->parent_id))
            {
                $criteria['_id'] = $post->parent_id;
                try
                {
                    self::factory('post')
                         ->collection
                         ->update(
                              $criteria, $new, array('safe' => TRUE, 'multiple' => TRUE)
                    );
                }
                catch(MongoException $e)
                {
                    // log the error and return
                    Debug::error(
                          "MongoCursorException while trying to update post's parent (id-:id). \n"
                          ."Error Message- :message \n"
                          ."Error Code- :code", array(
                             ':id' => $_id, ':message' => $e->getMessage(),
                             ':code' => $e->getCode()
                    ));
                    return FALSE;
                }
            }

            // also if it's a post, we can fetch the relevant parent ids directly
            $pids[] = $post->page_id;
            // if the page the post resides in is a child of another page
            if(property_exists($post, 'page_parent_id'))
                  $pids[] = $post->page_parent_id;
        }
        // in case this is a page object, we need it's parent id if exists
        if(isset($page))
        {
            if(property_exists($page, 'parent_id'))
                  $pids[] = $page->parent_id;
        }

        // build the query based on the count or return if empty
        switch(count($pids))
        {
            case 0: return TRUE;
            case 1: $criteria['_id'] = Arr::map('Model::check_id', array_filter($pids)); break;
            default: $criteria['_id'] = array('$in' => Arr::map('Model::check_id', array_filter($pids))); break;
        }

        // update the page model.
        try
        {
            $r = self::factory('page')
                 ->collection
                 ->update($criteria, $new, array('safe' => TRUE, 'multiple' => TRUE)
            );
        }
        catch(MongoException $e)
        {
            // log the error and return
            Debug::error(
                  "MongoCursorException while trying to update post's parent (id-:id). \n"
                  ."Error Message- :message \n"
                  ."Error Code- :code", array(
                     ':id' => implode(" ", $pids), ':message' => $e->getMessage(),
                     ':code' => $e->getCode()
            ));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * This method is for returning selected fields from the given document. It
     * checks to see if a MongoDocument object was given and those fields already exist.
     * It returns just those fields or queries the DB to fetch a new document
     * based on the given fields.
     *
     * @param string|MongoID|object $id string id, MongoID or MongoDocument object
     * @param array $fields cannot be empty, the selected fields
     * @param boolean $object defaults to true, decides how to return record
     *
     * @return mixed document object or array if found. NULL if not found.
     */
    public function get_selective($id, $fields = array(), $object = true)
    {
        if (empty($id))
            return false;

        // check if this is a document object by checking the existance of _id
        if(is_object($id) AND property_exists($id, '_id'))
        {
            // if no fields were specified, we just return the document
            if(empty($fields))
                return $id;

            // if fields are set, we check if it exists in the user object
            // add the _id to the list of needed fields
            $fields[] = '_id';
            // set $exists to false
            $exists   = FALSE;
            // check if the fields exist
            foreach($fields as $k => $v)
            {
                // break the moment it doesn't exist
                if(!$exists = property_exists($id, $v))
                    break;
            }
            // if $exists is still positive, meaning we already have the needed info,
            // return the extracted fields from the user object.
            if($exists)
                return Arr::extract ($id, $fields);
        }

        // remove uneeded fields from query
        $doc = $this->collection
              ->findOne( array('_id' => self::check_id($id)), self::make_selection($fields));

        // if no record found, return NULL
        if (empty($doc)) return NULL;

        return ($object) ? (object) $doc : $doc;
    }

    /**
     *
     * Utility method that checks the current subscribing status of a person in
     * reference to a document. Uses the button() class to generate HTML.
     * Needs a document array.
     *
     * @see button::create()
     * @param MongoDocument $document raw document array from the DB
     * @return MongoDocument $document formatted document array with HTML links
     */
    protected static function _subscribing($document, $user)
    {
        //echo Debug::vars($user);
        // set to NULL first
        $document['subscribing'] = NULL;

        $class = array();

        // check if this is a member type document and if is the current user
        if($user->_id == $document['_id'])
            return $document;

        // if this document does not have a counts field then it should be a
        // machine generated post. No need to do more, just return.
        if(!isset($document['counts']))
            return $document;

        // get the user subscription field from the doc. If not available,
        // then it's a user
        $controller = isset($document['type']) ? $document['type'] : 'user';

        // now we get the array to check e.g. $user['subscriptions']['locations']
        $subscriptions  = $user->subscriptions[Inflector::plural($controller)];


        // this is for locations, need to add more CSS classes if child location
        if (isset($document['subtype']))
        {
            // If an estate, no need to do more. No Estate subscriptions
            if ($document['subtype'] == 'estate')
                return $document;

            // If not, we add the child classes
            array_push(
                  $class,
                  'parent-' . $document['parent_id'],
                  'subtype-' . $document['subtype']
            );
        }

        $is_post = self::is_post($document);

        // ease typing, get the uri for the object
        $id   = $document['_id']->__toString();
        $controller = self::retrieve_controller($controller);

        $count = (int) $document['counts']['subscribers'];

        // check if the subscriber count is 0 or that this user is not subscribing
        //echo debug::vars($document['_id'], $subscriptions);
        if($count == 0 OR !in_array($document['_id'], $subscriptions))
        {
            $document['subscribing'] = ($is_post)
                                        ? button::post_subscribe($controller, $id, $class)
                                        : button::subscribe($controller, $id, $class);

            return $document;
        }

        // we add a new css class to the list
        array_push($class, 'subscribing');
        // set the subscription button to Subscribing
        $document['subscribing'] = ($is_post)
                                    ? button::post_unsubscribe($controller, $id, $class, 'Subscribing')
                                    : button::unsubscribe($controller, $id, $class, 'Subscribing');

        return $document;
    }

    /**
     *
     * Utility method that checks the current like status of a person in
     * reference to a document. Also returns the HTML required for the link to
     * be displayed. Needs a document array.
     *
     * @param MongoDocument $document raw document array from the DB
     * @return MongoDocument $document formatted document array with HTML links
     */
    protected static function _liking($document, $user)
    {
        // set to NULL
        $document['liking'] = NULL;

        // this document does not have counts and thus is not likeable
        if(!isset($document['counts']))
            return $document;

        // check if this is a member type document and if is the current user
        if($user->_id == $document['_id'])
            return $document;

        // shortcut vars
        $id         = $document['_id'];
        // the number of likers in this document
        $counts     = $document['counts']['likers'];
        // get the user liking key from the doc. If not available, then it's a user
        $controller = isset($document['type']) ? $document['type'] : 'user';
        $controller = self::retrieve_controller($controller);
        // now we get the array to check e.g. $user['subscriptions']['locations']
        $likes      = $user->likes[Inflector::plural($controller)];

        // check if user has liked this document
        if (in_array($id, $likes))
        {
            // set the unlike link + middot + the opening span HTML
            $document['liking'] = button::unlike($controller, $id, $counts, array('liking'));

            return $document;
        }

        $document['liking'] = button::like($controller, $id, $counts);

        return $document;
    }

    /**
     * Checks to see if a user is the owner of this document
     *
     * @param MongoDocument $document array
     * @param MongoID|string|object $user
     * @return bool
     */
    protected static function _is_owner($document, $user)
    {
        if(is_object($user) AND property_exists($user, '_id'))
              $id = $user->_id;
        else
            $id = self::check_id($user);

        if(isset($document['page_id']))
            return $document['user_id'] == $id OR $document['page_id'] == $id;

        return $document['user_id'] == $id ;
    }

    /**
     *
     * Small utility function to retrive a document from the database
     *
     * @param string $action
     * @param string $doc_type
     * @return type
     */
    protected static function _fetch_document($_id, $doc_type)
    {
        switch($doc_type)
        {
            case 'post':
            case 'listing':
            case 'event':
            case 'testimonial':
                return self::factory('post')->get($_id);
            case 'location':
            case 'page':
                return self::factory('page')->get($_id);
            case 'user':
                return self::factory('user')->get($_id);
        }
    }

    /**
     * A utility method to return a respective Controller based on a document
     * type. This is used in the creation of links, since routes are named by
     * their respective controller. The convention used is that all 'page' types
     * return the plural of a controller, eg. user -> members, location -> locations
     * and post types return the singular e.g post -> post, listing -> listing
     *
     * @param string $doc_type
     * @return string
     */
    protected static function retrieve_controller($doc_type)
    {
        if(self::_post_types($doc_type))
            return 'post';

        switch($doc_type)
        {
            case 'user': return 'members';
            case 'location': return 'locations';
            case 'comment': return 'comment';
        }
    }

    /**
     * Checks a document to see if it is a post
     *
     * @param MongoDocument $document array or object
     * @return bool
     */
    protected static function is_post($document)
    {
        if(empty($document))
            return FALSE;

        if(is_object($document))
            $document = (array) $document;

        return self::_post_types(Arr::get($document, 'type', FALSE));
    }

    /**
     * Utility method that identifies a post-type document
     *
     * @param string $type the document type
     * @return bool
     */
    protected static function _post_types($type)
    {
        switch($type)
        {
            case 'post':
            case 'listing':
            case 'event':
            case 'testimonial':
            case 'link':
            case 'status':
            case 'attachment':
                return TRUE;
            default:
                return FALSE;
        }
    }

    /**
     * Returns a formatted field select array for use in a MongoDB query
     *
     * fields can be flat arrays (field1, field2)
     * or already formatted (field1 => TRUE, field2 =>TRUE)
     *
     * @param array $fields
     * @param bool $positive to include or exclude fields
     * @return array
     */
    protected static function make_selection(array $fields, $positive = TRUE)
    {
        // we always return _id. This is just in case it's not added.
        $return = array('_id' => 1);

        //if keys are numeric, then the array isn't formatted as a Mongo field query.
        if(array_keys($fields) === range(0, count($fields) - 1))
        {
            foreach($fields as $k => $v)
            {
                $return[$v] = (int) $positive; // ints seem to work better than bools
            }

            return $return;
        }

        // already formatted. merge and return
        return array_merge($fields, $return);
    }

}