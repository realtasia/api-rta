<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of user
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Model/User/
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_User extends Document {

    /**
     * Retrieve a user by email, username or id
     *
     * @param MongoID|string $id
     * @return array|null
     */
    public function get($id, array $fields = [], $object = TRUE)
    {
        if($this->load(self::_get_one($id), $fields))
        {
            $ret = $this->stringify($this->as_array());
            return $object ? (object) $ret : $ret;
        }

        return null;
    }

    /**
     * Retrieve only user public fields by email, username or id
     *
     * @param MongoID|string $id
     * @return array|null
     */
    public function get_public($id)
    {
        return $this->get($id, static::$protected_fields);
    }

    /**
     * Create a default user with new values.
     *
     * @param array $params
     * @return boolean
     */
    public function create($params)
    {
        // if agent, we check for company and if it's an array.
        if (isset($params['company']) && !is_array($params['company']))
        {
            // retrieve the company
            $company = Mongo_Document::factory('page');
            $company->load($params['company'], ['name' => 1, '_id'  => 1]);

            // set the user's company
            $params['company']        = $company->to_array();
        }
        //die();
        // overwrite defaults
        $data                     = Arr::overwrite(static::$user_fields, $params);
        // merge with defaults
        $data                     = Arr::merge(static::$user_fields, $data);
        $data['info']['birthday'] = (int) $data['info']['birthday'];
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
            return null;
        }

        return (array) $this->as_array();
    }

    /**
     * Unsets the 'created' field which was extracted from the MongoID
     * sets the active timestamp
     */
    public function before_save($action)
    {
        $this->active = time();
        unset($this->created);
        if (isset($this->birthday) && !is_numeric($this->birthday))
        {
            $this->birthday = strtotime($this->birthday);
        }
        if(isset($this->counts))
        {
            $counts = Arr::merge(static::$user_fields['counts'], $this->counts);

            foreach($counts as $k => $v)
            {
                if($v < 0)
                {
                    $counts[$k] = 0;
                }
            }

            $this->counts = $counts;
        }
        return true;
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
        if(is_numeric($this->active))
        {
            $this->active  = date('c', $this->active);
        }
        //
        if (isset($this->info['birthday']))
        {
            $this->birthday = date('c', $this->info['birthday']);
        }

        if ($this->current_user === NULL)
            return $this;

        $current_user = (object) $this->get_default_user_fields();

        // check if it's the same user
        if ($this->_id->__toString() === $current_user->_id->__toString())
        {
            $this->can_edit      = true;
            $this->can_like      = false;
            $this->can_subscribe = false;
            $this->connections   = static::get_all_connection_routes($this->_id);
        }
        else
        {
            $interactions       = Mongo_Document::factory('interaction', NULL, $current_user->_id);
            $this->interactions = $interactions->interacting($this->_id, 'user');
            $this->actions      = static::get_action_routes($this->_id);
            $this->connections  = static::get_public_connection_routes($this->_id);
            $this->password     = '';
            $this->logins       = [];
        }

        return $this;
    }

    /**
     * Updates privacy options.
     *
     * @param array $params
     * @param string|MongoID $user_id
     * @return type
     */
    public function update_privacy($params, $user_id)
    {
        $field = static::$user_fields['settings']['privacy'];
        $values = Arr::overwrite($field, $params);

        $this->load_values([
            '_id' => new MongoID($user_id),
            'settings.privacy' => $values,
        ]);
        $this->save();

        return $values;
    }

    /**
     * Updates notification options.
     *
     * @param array $params
     * @param string|MongoID $user_id
     * @return type
     */
    public function update_notifications($params, $user_id)
    {
        $field = static::$user_fields['settings']['notifications'];
        $values = Arr::overwrite($field, $params);

        $this->load_values([
            '_id' => new MongoID($user_id),
            'settings.notifications' => $values,
        ]);
        $this->save();

        return $values;
    }

    /**
     * Updates user info.
     *
     * @param array $params
     * @param string|MongoID $user_id
     * @return type
     */
    public function update_password($password, $user_id)
    {
        $this->load_values(['_id' => new MongoID($user_id)]);
        $this->password = static::hash($password);
        return $this->save();
    }

    /**
     * Updates user info.
     *
     * @param array $params
     * @param string|MongoID $user_id
     * @return type
     */
    public function update_info($params, $user_id)
    {
        $field  = static::$user_fields['info'];
        $values = Arr::overwrite($field, $params);

        $this->load_values([
            '_id'  => new MongoID($user_id),
            'info' => $values,
        ]);
        $this->save();

        return $values;
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
        $can_edit = Arr::get($arr, 'can_edit');

        $settings = Arr::get($arr, 'settings');

        if($settings && !$can_edit && $privacy = Arr::get($settings, 'privacy'))
        {
            //switch ($privacy['contacts']['is'])
            switch ($privacy['contacts'])
            {
                case 'SUBSCRIBERS':
                    if (Arr::path($arr, 'interactions.subscribe'))
                    {
                        $arr['info']['telephone'] = '';
                        $arr['info']['social']    = '';
                        $arr['email']             = '';
                    }
                    break;

                case 'PRIVATE':
                    $arr['info']['telephone'] = '';
                    $arr['info']['social']    = '';
                    $arr['email']             = '';
                    break;

                case 'CUSTOM': //check if current user id is in this list
                    //$arr['info']['telephone'] = '';
                    //$arr['info']['social']    = '';
                    //$arr['email']             = '';
                    break;
            }
        }

        return parent::stringify($arr);
    }

    /**
     * Small utility function to reduce typing. returns an $or array for use in
     * a typical Mongo query
     *
     * @param string $username
     * @param bool $regex
     * @return array
     */
    protected static function _get_one($username, $regex = FALSE)
    {
        // Used if an actual keyword search is taking place. Uses MongoRegex()
        if ($regex)
        {
            $username = new MongoRegex("/^$username/i");
            return [
                '$or' => [
                    ['username' => $username],
                    ['email' => $username],
                    ['company.name' => $username]
                ]
            ];
        }
        // just a regular lookup
        return [
            '$or' => [
                ['_id' => new MongoID($username)],
                ['username' => $username],
                ['email' => $username],
            ]
        ];
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

        foreach(['like', 'unlike', 'subscribe', 'unsubscribe'] as $k => $v)
        {
            $actions[$v] = Router::user($v, $user_id);
        }

        return $actions;
    }

    /**
     * Gets all connection links, including private ones belonging to a
     * logged in user
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_all_connection_routes($user_id)
    {
        $actions = [];

        foreach(['privacy', 'notifications'] as $k => $v)
        {
            $actions[$v] = Router::user($v, $user_id);
        }

        return Arr::merge($actions, static::get_public_connection_routes($user_id));
    }

    /**
     * Gets all publically available connection links for a given user.
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_public_connection_routes($user_id)
    {
        $actions = [];

        foreach(['likers', 'avatar', 'subscribers', 'info'] as $k => $v)
        {
            $actions[$v] = Router::user($v, $user_id);
        }

        return $actions;
    }

    /**
     * These are fields that are not needed for public consumption.
     *
     * @var array
     */
    private static $protected_fields = [
        'password' => false,
        'settings.billing' => false,
        'settings.notifications' => false,
        'logins'   => false
    ];

    /**
     * The standard user document.
     *
     * @var array
     */
    private static $user_fields = [
        'password' => '',
        'logins'   => [
            'user_id'      => '',
            'type'         => '',
            'username'     => '',
            'email'        => '',
            'access_token' => '',
            'token_type'   => '',
            'expires_in'   => '',
            'name'         => '',
            'picture'      => '',
        ],
        'settings'     => [
            'locale'  => 'en_SG',
            'billing' => [
                'mode'      => 'cash',
                'frequency' => 'monthly',
                'plan_id'   => NULL
            ],
            'privacy'   => [
                'is'            => 'PUBLIC',
                'contacts'      => ['is' => 'PUBLIC'],
                'subscriptions' => ['is' => 'PUBLIC'],
                'messages'      => ['is' => 'PUBLIC'],
                'mentions'      => ['is' => 'PUBLIC'],
                'wallposts' => [
                    'others_posts'            => ['is' => 'PUBLIC'],
                    'others_posts_visibility' => ['is' => 'PUBLIC']
                ]
            ],
            'notifications'           => [
                'email'  => '',
                'likers' => [
                    'own_post'    => TRUE,
                    'other_posts' => TRUE,
                    'profile'     => TRUE,
                    'comments'    => TRUE,
                ],
                'subscribers' => [
                    'own_post'    => TRUE,
                    'other_posts' => TRUE,
                    'profile'     => TRUE,
                ],
                'comments'    => [
                    'own_post'    => TRUE,
                    'other_posts' => TRUE,
                ],
                'mentions'    => TRUE,
                'testimonial' => TRUE,
                'wallpost'    => TRUE,
                'messages'    => TRUE
            ]
        ],
        'username'    => '',
        'email'       => '',
        'first_name'  => '',
        'middle_name' => '',
        'last_name'   => '',
        'name'        => '',
        'status'      => 'active',
        'actions'     => [],
        'company' => [],
        'designation' => '',
        'roles'       => ['user'],
        'avatar' => [
            150           => 'beta.realtasia.com.sg/assets/images/avatar-150X150.jpg',
            60            => 'beta.realtasia.com.sg/assets/images/avatar-60X60.jpg',
            32            => 'beta.realtasia.com.sg/assets/images/avatar-32X32.jpg',
        ],
        'uri'         => '',
        'subscribers' => [],
        'likers' => [],
        'active' => '',
        'counts' => [
            'attachments'  => 0,
            'comments'     => 0,
            'events'       => 0,
            'likers'       => 0,
            'listings'     => 0,
            'posts'        => 0,
            'subscribers'  => 0,
            'testimonials' => 0
        ],
        'info'         => [
            'telephone' => ['mobile'    => ''],
            'languages' => [],
            'birthday'     => '',
            'gender'       => '',
            'achievements' => [],
            'education' => [],
            'social' => [],
            'websites' => []
        ]
    ];

}