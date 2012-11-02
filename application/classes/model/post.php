<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of user
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Model/Post/
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Post extends Document {

    /**
     * Retrieve a user by email, username or id
     *
     * @param MongoID|string $id
     * @return array|null
     */
    public function get($id, array $fields = [], $object = TRUE)
    {
        if($this->load($id, $fields))
        {
            $ret = $this->stringify($this->as_array());
            return $object ? (object) $ret : $ret;
        }
        return $object ? (object) [] : [];
    }

    /**
     * Create a default post with new values.
     *
     * @param array $params
     * @return boolean
     */
    public function create($params)
    {
        $this->collection()->ensureIndex([
            'page.id' => 1
        ]);
        $this->collection()->ensureIndex([
            'page_parent.id' => 1
        ]);
        $this->collection()->ensureIndex([
            'type' => 1,
        ]);
        $this->collection()->ensureIndex([
            'status' => 1,
        ]);
        $this->collection()->ensureIndex([
            'from.id' => 1,
        ]);
        $this->collection()->ensureIndex([
            'to.id' => 1,
        ]);
        // if agent, we check for company and if it's an array.
        // overwrite defaults
        $data                     = Arr::overwrite(static::$post_fields, $params);
        // merge with defaults
        $data                     = Arr::merge(static::$post_fields, $data);

        // sort - for some sanity in debugging.. :-)
        ksort($data);

        // load the document
        $this->load_values($data);

        try
        {
            // save
            $this->save();
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoCursorException while trying to create user :user \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", [
                ':user'    => print_r($data, TRUE),
                ':message' => $e->getMessage(),
                ':code'    => $e->getCode()
            ]);
            return false;
        }

        return (array) $this->as_array();
    }

    /**
     * Unsets the 'created' field which was extracted from the MongoID
     * sets the modified timestamp
     */
    public function before_save($action)
    {
        $this->modified = time();
        unset($this->created);

        if(isset($this->counts))
        {
            $counts = Arr::merge(static::$post_fields['counts'], $this->counts);

            foreach($counts as $k => $v)
            {
                if($v < 0)
                {
                    $counts[$k] = 0;
                }
            }

            $this->counts = $counts;
        }
        return $this;
    }

    /**
     * Converts timestamps to dates.
     * Checks and sets current user attributes. (if user returned is the user logged in)
     * Otherwise, checks current user's interaction with the returned user
     */
    public function after_load()
    {
        $id = $this->_id instanceof MongoID ? $this->_id : new MongoID($this->_id);

        $this->created = date('c', $id->getTimestamp());
        if(is_numeric($this->modified))
        {
            $this->modified  = date('c', $this->modified);
        }
        //
        if ($this->current_user === NULL)
            return $this;

        $current_user = (object) $this->get_default_user_fields();

        // check if it's the same user
        if ($this->user['_id']->__toString() === $current_user->_id->__toString())
        {
            $this->can_edit      = true;
            $this->connections   = static::get_all_connection_routes($this->_id);
        }
        else
        {
            $interactions       = Mongo_Document::factory('interaction', NULL, $current_user->_id);
            $this->interactions = $interactions->interacting($this->_id, 'post');
            $this->actions      = static::get_action_routes($this->_id);
            $this->connections  = static::get_public_connection_routes($this->_id);
        }

        return $this;
    }

    /**
     * Updates privacy settings of a post.
     *
     * @param array $params
     * @param string|MongoID $user_id
     * @return type
     */
    public function update_privacy($params, $_id)
    {
        $field = static::$post_fields['privacy'];
        $values = Arr::overwrite($field, $params);

        $this->load_values([
            '_id' => new MongoID($_id),
            'privacy' => $values,
        ]);
        $this->save();

        return $values;
    }

    /**
     * Gets all the available action links that can be done to a user
     * (by a logged in user)
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_action_routes($user_id)
    {
        $actions = [];

        foreach(['like', 'unlike', 'subscribe', 'unsubscribe', 'comment'] as $k => $v)
        {
            $actions[$v] = Router::post($v, $user_id);
        }

        return $actions;
    }

    /**
     * Gets all connection links, including private ones that a logged in user
     * can perform on this post
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_all_connection_routes($_id)
    {
        $actions = ['privacy' => Router::post('privacy', $_id)] ;

        return Arr::merge($actions, static::get_public_connection_routes($_id));
    }

    /**
     * Gets all publically available connection links for a given post.
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_public_connection_routes($_id)
    {
        $actions = [];

        foreach(['likers', 'comments', 'subscribers'] as $k => $v)
        {
            $actions[$v] = Router::post($v, $_id);
        }

        return $actions;
    }

    /**
     * The standard post document.
     *
     * @var array
     */
    private static $post_fields = [
        'from'  => [], // '_id', 'name' of the user who created this
        'to' => [], // '_id', 'name' if this is a private post, the user this post was posted on.
        'page' => [], // '_id', 'name', 'type' the page it was written on (location, user profile etc...)
        'page_parent' => [], // '_id', 'name', 'type' the page's parent (company or estate)
        'listing' => [], // '_id', 'name', 'type' the page's parent (company or estate)
        'message' => '', // the content
        'message_tags' => [], // names of existing people within the content are parsed
        'picture' => '', // if this post has an image, this will be the url
        'link'    => [], // 'name', 'caption', 'description', 'source' the original link if there is one
        'video' => [], // 'name', 'caption', 'description', 'source', 'properties' if this post has a video
        'type' => '', // wallpost, testimonial
        'status'     => '', // pending, active, expired,
        'uri'     => '',
        'modified'     => '',
        'privacy'     => ['is' => 'PUBLIC'],
        'counts'     => [
            'attachments'  => 0,
            'comments'     => 0,
            'events'       => 0,
            'likers'       => 0,
            'subscribers'  => 0
        ],
        'comments'    => [],
        'subscribers' => [],
        'likers' => []
    ];

}