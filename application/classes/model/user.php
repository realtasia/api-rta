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
            $company->load($params['company'], ['_id'  => 1, 'name' => 1]);

            // set the user's company
            $params['company']        = $company->to_array();
        }

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
        // Updates
        if ($action === parent::SAVE_UPDATE)
        {
            $this->modified = time();
            unset($this->created);

            if (isset($this->info['birthday']) && !is_numeric($this->info['birthday']))
            {
                $this->set('info.birthday', strtotime($this->info['birthday']));
            }

            // during testing a whole lot of counts went into negative. Just a
            // small sanity check.
            if (isset($this->counts))
            {
                foreach ($this->counts as $k => $v)
                {
                    if ($v < 0)
                    {
                        $this->set('counts.' . $k, 0);
                    }
                }
            }

            unset(
                    $this->interactions,
                    $this->private_actions,
                    $this->connections,
                    $this->actions
           );
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

        // add a created
        $this->created = date('c', $id->getTimestamp());

        // change to iso date
        if(is_numeric($this->active))
        {
            $this->active  = date('c', $this->active);
        }

        // same, change to iso date
        if (isset($this->info['birthday']) && is_numeric($this->info['birthday']))
        {
            $this->set('info.birthday', date('c', $this->info['birthday']));
        }

        if ($this->current_user === NULL)
            return $this;

        $current_user = (object) $this->get_default_user_fields();

        // check if it's the same user
        if ((string) $this->_id === (string) $current_user->_id)
        {
            $this->private_actions = [
                'can_edit' => true,
                'can_like' => false,
                'can_subscribe' => false,
            ];
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

        if($this->settings && !$this->can_edit)
        {
            //switch ($privacy['contacts']['is'])
            switch ($this->settings['privacy']['contacts']['is'])
            {
                case 'SUBSCRIBERS':
                    if ($this->interactions['subscribe'])
                    {
                        $this->set('info.telephone', NULL);
                        $this->set('info.social', NULL);
                        $this->set('info.email', NULL);
                    }
                    break;

                case 'SELF':
                    $this->set('info.telephone', NULL);
                    $this->set('info.social', NULL);
                    $this->set('info.email', NULL);
                    break;

                case 'CUSTOM': //check if current user id is in this list
                    //$arr['info']['telephone'] = '';
                    //$arr['info']['social']    = '';
                    //$arr['email']             = '';
                    break;
            }
            switch ($this->settings['privacy']['personal']['is'])
            {
                case 'SUBSCRIBERS':
                    if (!$this->interactions['subscribe'])
                    {
                        $this->set('info.gender', NULL);
                        $this->set('info.birthday', NULL);
                    }
                    break;

                case 'SELF':
                    $this->set('info.gender', NULL);
                    $this->set('info.birthday', NULL);
                    break;

                case 'CUSTOM': //check if current user id is in this list
                    //$arr['info']['telephone'] = '';
                    //$arr['info']['social']    = '';
                    //$arr['email']             = '';
                    break;
            }
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
        $this->clear();
        $this->set('_id', parent::check_id($user_id));
        $this->set('settings.privacy', $values);

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

        $this->clear();

        $this->set('_id', parent::check_id($user_id));
        $this->set('settings.notifications', $values);

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
        $this->clear();

        $this->set('_id', parent::check_id($user_id));

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

        $this->clear();

        $this->set('_id', parent::check_id($user_id));

        $this->info = $values;

        $this->save();

        return $values;
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
                'personal'      => ['is' => 'PUBLIC'],
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
                'messages'    => TRUE,
                'pages'       => 'Weekly',
            ]
        ],
        'username'    => '',
        'email'       => '',
        'first_name'  => '',
        'middle_name' => '',
        'last_name'   => '',
        'name'        => '',
        'status'      => 'active',
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
            'testimonials' => 0,
            'messages'     => 0
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