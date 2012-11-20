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
     * Create a page with new values.
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
            'type' => 1, 'subtype' => 1
        ]);

        $type = Arr::get($params, 'type');
        $subtype = Arr::get($params, 'subtype');

        if(isset($params['name']))
        {
            $params['uri'] = "/$type/$subtype/" . URL::title($params['name']);
            $params['slug'] = URL::title($params['name']);
        }


        // overwrite defaults
        $data                     = Arr::overwrite(static::_page_defaults($type), $params);
        // merge with defaults
        $data                     = Arr::merge(static::_page_defaults($type), $data);

        if($id = Arr::get($params, '_id'))
        {
            $this->atomic($id);
            unset($params['_id']);
        }

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
                    "MongoException while trying to create page: :page \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", [
                ':post'    => print_r($data, TRUE),
                ':message' => $e->getMessage(),
                ':code'    => $e->getCode()
            ]);
            return false;
        }

        return $this;
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
        $id = parent::check_id($this->_id);

        $this->created = $id->getTimestamp();

        // if there is no current user, no further formatting required.
        if ($this->current_user === NULL)
            return $this;

        $current_user = (object) $this->get_default_user_fields();

        // check if it's the same user
        if ((string) $this->user['_id'] === (string) $current_user->_id)
        {
            $this->can_edit      = true;
            $this->connections   = static::get_all_connection_routes($this->_id, $this->type, $this->subtype);
        }
        else
        {
            $interactions       = parent::factory('interaction', NULL, $current_user->_id);
            $this->interactions = $interactions->interacting($this->_id, 'page');
            $this->actions      = static::get_action_routes($this->_id);
            $this->connections  = static::get_public_connection_routes($this->_id, $this->type, $this->subtype);
        }
        // comments processing in stringify

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
    protected static function get_action_routes($_id)
    {
        $actions = [];

        foreach(['like', 'unlike', 'subscribe', 'unsubscribe'] as $v)
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
    protected static function get_all_connection_routes($_id, $type, $subtype = NULL)
    {
        $actions = ['privacy' => Router::page('privacy', $_id)] ;

        return Arr::merge($actions, static::get_public_connection_routes($_id, $type));
    }

    /**
     * Gets all publically available connection links for a given post.
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_public_connection_routes($_id, $type, $subtype = NULL)
    {
        $actions = [
            'likers',
            'feed',
            'subscribers',
            'testimonials',
        ];

        switch($type)
        {
            case 'location':
                if($subtype && $subtype === 'estate')
                {
                    $actions[] = 'child-locations';
                }
                $actions[] = 'pictures';
                break;
            case 'company':
                $actions[] = 'members';
                break;
        }

        foreach($actions as $v)
        {
            $actions[$v] = Router::page($v, $_id);
        }

        return $actions;
    }


    protected static function _page_defaults($type)
    {
        $counts = $type == 'library' ? static::library_counts() : static::counts();

        $defaults = array(
            'content' => '',
            'counts'  => $counts,
            'likers'       => [],
            'modified' => time(),
            'parent'   => [],
            'privacy'   => ['is' => 'PUBLIC'],
            'stats' => [],
            'status'      => 'active',
            'subscribers' => [],
            'subtype' => '',
            'name'    => '',
            'type'    => $type,
            'slug'    => '',
            'uri'     => '',
            'user'    => [],
            'attachments' => [],
        );

        switch ($type)
        {
            case 'location': return Arr::merge($defaults, ['attachments' => []]);
            case 'company' : return Arr::merge($defaults, static::_company_defaults());
                default: return $defaults;
        }
    }

    protected static function _company_defaults()
    {
        return ['license' => '', 'counts' => ['members' => 0], 'members' => []];
    }

    protected static function counts()
    {
        return [
            'attachments'  => 0,
            'checkins'     => 0,
            'events'       => 0,
            'likers'       => 0,
            'listings'     => 0,
            'posts'        => 0,
            'subscribers'  => 0,
            'members'      => 0,
            'testimonials' => 0
        ];
    }

    protected static function library_counts()
    {
        return [
            'pictures'       => 0,
            'videos'         => 0,
            'documents'      => 0,
            'albums' => [
                'public' => 0,
                'private' => 0,
            ]
        ];
    }
}