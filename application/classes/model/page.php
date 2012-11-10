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
class Model_Page extends Document {

    /**
     * Retrieve a single post
     *
     * @param MongoID|string|array $id or an array of query params
     * @return array|null
     */
    public function get($id, array $fields = [], $object = TRUE)
    {
        if($this->load($id, $fields))
        {
            $ret = $this->stringify($this->as_array());
            return $object ? (object) $ret : $ret;
        }
        return false;
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
            'status' => 1, 'user._id' => 1, 'privacy.is' => 1, 'modified' => -1
        ], ['name' => 'post_id_fields', 'safe' => true]);
        $this->collection()->ensureIndex([
            'parent._id' => 1
        ], ['sparse' => true, 'safe' => true]);
        $this->collection()->ensureIndex([
            'type' => 1,
        ]);

        $type = Arr::get($params, 'type');

        if(isset($params['name']) && $type === 'agency')
            $params['uri'] = '/agency/' . URL::title($params['name']);

        // overwrite defaults
        $data                     = Arr::overwrite(static::_page_defaults($type), $params);
        // merge with defaults
        $data                     = Arr::merge(static::_page_defaults($type), $data);

        // sort - for some sanity in debugging.. :-)
        ksort($data);
        //return $data;
        // load the document
        $this->load_values($data);

        try
        {
            // save
            $this->save();
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoException while trying to create post: :post \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", [
                ':post'    => print_r($data, TRUE),
                ':message' => $e->getMessage(),
                ':code'    => $e->getCode()
            ]);
            return false;
        }

        return (array) $this->as_array();
    }


    /**
     * Searches for a document that has unique values. Returns false if none is
     * found and the id of the document if found.
     *
     * @param array $params
     * @return boolean|array
     */
    public function find_unique(array $params)
    {
        $query = [
            'listing.unit' => $params['unit'],
            'listing.type' => $params['listing_type'],
            'page.id'      => parent::check_id($params['page_id']),
            'user.id'      => parent::check_id($params['user_id'])
        ];

        return $this->get($query, ['_id' => 1], false);
    }

    /**
     * Unsets the 'created' field which was extracted from the MongoID
     * sets the modified timestamp
     */
    public function before_save($action)
    {
        if ($action === parent::SAVE_UPDATE)
        {
            $this->modified = time();

            // during testing a whole lot of counts went into negative. Just a
            // small sanity check.
            if (!isset($this->_changed['counts']) && isset($this->counts))
            {
                foreach ($this->counts as $k => $v)
                {
                    if ($v < 0)
                    {
                        $this->set('counts.' . $k, 0);
                    }
                }
            }
            foreach(['interactions', 'private_actions', 'connections', 'actions', 'created'] as $v)
            {
                if( !isset($this->_changed[$v]) && isset( $this->{$v} ) )
                {
                    unset( $this->{$v} );
                }
            }
        }
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
        // if there is no current user, no further formatting required.
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
        // comments processing in stringify

        return $this;
    }

    /**
     * Using this to format some fields. Can't set subarray values via model.
     * Keep getting error that modifying overloaded property has no effect.
     * Apparently, that is the way __get() works. So have to do it here instead.
     *
     * @param array $arr
     * @return array
     */
    public function stringify(array $arr)
    {
        $comments = Arr::get($arr, 'comments');
        $current_user = (object) $this->get_default_user_fields();

        // we should trigger the comment model's formatting so each comment in
        // this post is properly formatted.
        if(!empty($comments))
        {
            foreach ($comments as $key => $value)
            {
                $comment = Mongo_Document::factory('comment');
                if($current_user)
                {
                    // check if there is a current user
                    $comment->current_user = $current_user;
                }
                // trigger comment formatting and defaults
                $comment->after_load();

                // now each comment in the post is properly formatted.
                $arr['comments'][$key] = $comment->stringify($comment->to_array());
            }
        }

        return parent::stringify($arr);
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


    protected static function _page_defaults($type)
    {
        $defaults = array(
            'content' => '',
            'counts'  => [
                'attachments'  => 0,
                'comments'     => 0,
                'events'       => 0,
                'likers'       => 0,
                'listings'     => 0,
                'posts'        => 0,
                'subscribers'  => 0,
                'testimonials' => 0
            ],
            'likers'       => [],
            'modified' => time(),
            'parent'   => [],
            'slug'  => '',
            'stats' => [],
            'status'      => 'active',
            'subscribers' => [],
            'subtype' => '',
            'name'    => '',
            'type'    => $type,
            'uri'     => '',
            'user'    => [],
            'members' => [],
            'attachments' => [],
        );

        switch ($type)
        {
            case 'location': return $defaults;
            case 'agency' : return Arr::merge($defaults, static::_company_defaults());
        }
    }

    protected static function _company_defaults()
    {
        return ['license' => '', 'counts' => ['members' => 0]];
    }
}