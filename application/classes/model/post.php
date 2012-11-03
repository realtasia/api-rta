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
            'status' => 1, 'user._id' => 1, 'page._id' => 1, 'privacy.is' => 1, 'modified' => -1
        ], ['name' => 'post_id_fields', 'safe' => true]);
        $this->collection()->ensureIndex([
            'page_parent._id' => 1
        ], ['sparse' => true, 'safe' => true]);
        $this->collection()->ensureIndex([
            'to._id' => 1
        ], ['sparse' => true, 'safe' => true]);
        $this->collection()->ensureIndex([
            'type' => 1,
        ]);
        $this->collection()->ensureIndex([
            'listing.price' => 1, 'listing.rooms' => 1, 'listing.built_size' => 1,
            'listing.property_type' => 1
        ]);
        // overwrite defaults
        $data                     = Arr::overwrite(static::$post_fields, $params);
        // merge with defaults
        $data                     = Arr::merge(static::$post_fields, $data);

        if(in_array($data['type'], ['testimonial, subscription, like']))
        {
            
        }

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

    /**
     * The standard post document.
     *
     * @var array
     */
    private static $post_fields = [
        'user'  => [], // '_id', 'name' of the user who created this
        'to' => [], // '_id', 'name' if this is a private post, the user this post was posted on.
        'page' => [], // '_id', 'name', 'type' the page it was written on (location, user profile etc...)
        'page_parent' => [], // '_id', 'name', 'type' the page's parent (company or estate)
        'listing' => [
            'price' => 0,
            'rooms' => 1,
            'built_size' => 0,
            'property_type' => 0,
            'tenure' => 0,
            'land_size' => 0,
            'value' => 0,
            'cobroke' => 0,
        ], // '_id'
        'message' => '', // the content
        'message_tags' => [], // names of existing people within the content are parsed
        'picture' => '', // if this post has an image, this will be the url
        'link'    => [], // 'name', 'caption', 'description', 'source' the original link if there is one
        'video' => [], // 'name', 'caption', 'description', 'source', 'properties' if this post has a video
        'type' => 'wallpost', // wallpost, testimonial, message
        'status'     => 'active', // pending, active, expired,
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