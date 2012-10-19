<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mongo Page Model.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model_Page extends Model{

    /**
     * Retrieves a single document from the database. If $user_id is set
     * (i.e. Logged in user) then does further checks to see
     * if that user is subscribed. Implemented caching previously.
     * @todo A more robust higher level caching is required.
     *
     * @param string $value . Uses regex to search the entity. Can also be MongoID string
     * @param string $user_id The user MongoID string
     * @param bool $object to return document as object
     * @return mixed False if not found, user object if set above or array
     */
    public function get($value, $user_id = false, $object = true)
    {
        // create a regex object. Does indexing apply if the first case of the $or is found?
        $regexObj = new MongoRegex("/$value/i");
        $params = array(
           '$or' => array(
              array('_id' => parent::check_id($value)),
              array('slug' => $regexObj)
           ));

        $page = $this->collection->findOne($params);

        // if empty, nothing further.
        if (empty($page))  return NULL;

        if(isset($page['parent_id']))
        {
            $parent = parent::get_selective($page['parent_id'], array(
               'name', 'uri'
            ));

            $page['parent_link'] = empty($parent) ? NULL : HTML::anchor($parent->uri, $parent->name);
        }

        // check for subscriptions
        if ($user_id)
        {
            $user = Model::factory('user')->get_selective($user_id, $fields = array(
               'subscriptions', 'counts', 'likes'
            ));

            $page = parent::interacting($page, $user, TRUE);
        }

        return $object ? (object) $page : $page;
    }

    /**
     * Does a search, sort and limit. $args is an array that defaults to:
     *
     *  array(
     *          'limit' => 11, 'offset' => 0, 'type' => '',
     *          'subtypes' => array(), 'sort' => 'alpha',
     *          's' => '', 'include_ids' => FALSE,
     *          'object_id' => FALSE, 'user_id' => false, 'children' => FALSE
     *  );
     *
     * @param array $args
     * @return self Sets the $cursor property and returns itself
     */
    public function search($args = array())
    {
        $defaults = array(
           'limit' => 11, 'offset' => 0, 'type' => '',
           'subtypes' => array(), 'sort' => 'alpha',
           's' => '', 'include_ids' => FALSE,
           'object_id' => FALSE, 'user_id' => FALSE, 'children' => FALSE
        );
        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        // Now we build the query
        // if there is a user id, set the class property for interaction
        // retrieval later (subscripts & likes)
        if ($user_id)
            $this->_user_id = parent::check_id($user_id);

        // set some empty arrays
        $query = $ids = array();

        /**
         * subtypes =>
         *      Locations (estate, hdb, private-apartment, private-landed,
         *                 serviced-residences, cluster-housing)
         */
        if (!empty($subtypes))
            $query['subtype'] = array('$in' => $subtypes);

        // not yet implemented as searches are currently already type-based
        if (!empty($type))
            $query['type'] = $type;

        // if specific records are needed add to the ids array
        if (!empty($include_ids))
            $ids = array_merge($ids, $include_ids);

        // this is the search term typed.
        if ($s)
        {
            $regexObj = new MongoRegex("/$s/i");
            $query['$or'] = array(
               array('slug' => $regexObj)
            );
        }

        // this is different from include_ids as we are looking in the
        // parent_id property to find children. @TODO dbrefs instead?
        if ($children)
        {
            if (!is_array($children))
                $children = array($children);

            $query['parent_id'] = array(
               '$in' => array_map(array($this, 'check_id'), $children)
            );
        }

        // similar to the above, we don't return the parent object in results
        if ($object_id)
            $query['_id']['$ne'] = $this->check_id($object_id);

        // now we validate the consodilated ids and continue building
        if (!empty($ids))
            $query['_id']['$in'] = array_map(array($this, 'check_id'), $ids);

        // we query the db and set it to the cursor property for global access
        $this->cursor = $this->collection
              ->find($query)
              ->limit($limit);

        switch($sort)
        {
            case 'popular': $this->cursor->sort(array('subscribers' => -1)); break;
            case 'newest': $this->cursor->sort(array('modified' => -1)); break;
            default: $this->cursor->sort(array('slug' => 1)); break;
        }

        // the last bit, only done if offset is set
        if ($offset > 0)
            $this->cursor->skip($offset);

        return $this;
    }

    /**
     * A utility method that helps with updating timestamps and counts
     *
     *
     * @param object $page MongoDocument
     * @param Interact $i an instantated Interact object
     * @return type
     */

    public function add($data, $type)
    {
        if(isset($data['name']) && !isset($data['uri']))
            $data['uri'] = '/' . URL::title($data['name']);
        
        if(!isset($data['type']))
            $data['type'] = $type;
        
        if(!isset($data['status']))
            $data['status'] = 'active';        
        
        return Arr::merge(static::_page_defaults($type), $data);
    }
    
    /**
     * Because of possible ajax calls (json data), this recursal method is
     * prevalant in all models. Does all needed formatting and checks for
     * current user interactions relative to each record if user is logged in
     *
     * @param bool $object Defaults to true to return array of document objects
     * @return array of MongoDocuments
     */
    protected function _recurse($object = TRUE)
    {
        //die(Debug::vars($this->cursor->explain()));
        $return = array();

        // we check if the cursor is valid
        if (is_object($this->cursor))
        {
            if ($this->_user_id instanceof MongoID)
            {
                $user = Model::factory('user')->get_selective($this->_user_id, $fields = array(
                   'subscriptions', 'counts', 'likes'
                ));
            }
            // internal MongoDB recursal methods
            while ($this->cursor->hasNext())
            {
                $page = $this->cursor->getNext();
                //echo Debug::vars($page);
                // checking for user interactions
                if($this->_user_id)
                $page = parent::interacting($page, $user);

                // while we're at it, dates can be formatted too
                $page['created'] = date('c', $page['_id']->getTimestamp());
                $page['modified'] = date('c', $page['modified']);

                // the MongoID object needs to be converted to string
                // if json data, so we just do it anyway
                $page['_id'] = $page['_id']->__toString();

                // add this record to the return array
                $return[] = ($object) ? (object) $page : $page;
            }
        }

        return $return;
    }

    protected static function _page_defaults($type)
    {
        $defaults = array(
           'content'      => '',
           'counts'       => parent::$counts,
           'likers'       => array(),
           'modified'     => time(),
           'parent'       => array('_id' => '', 'name' => '', 'uri' => ''),                    
           'slug'         => '',
           'stats'        => array(),
           'status'       => '',
           'subscribers'  => array(),
           'subtype'      => '',
           'name'        => '',
           'type'         => '',
           'uri'          => '',
           'user_id'      => '',           
        );

        switch($type)
        {
            case 'location': return $defaults;
            case 'company' : return Arr::merge($defaults, static::_company_defaults());
            case 'profile' : return Arr::merge($defaults, static::_profile_defaults());
        }
    }

    protected static function _profile_defaults()
    {
        return array(
           'company'      => '',
           'corp_web'     => '',
           'designation'  => '',
           'gender'       => '',
           'license'      => '',
           'telephone'    => array(
              'mobile'     => '',
              'business'   => '',
              'fax'        => '',
           ),
           'websites'     => array(),
           'languages'    => array(),
        );
    }

    protected static function _company_defaults()
    {
        return array('license' => '', 'counts' => array('members' => 0));
    }

}