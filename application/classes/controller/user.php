<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Description of user
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Controller/User
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_User extends Controller_Resource {

    private static $valid_client_get_actions  = [
        'has_social_login', 'is_unique', 'get', 'search', 'get_by_mobile',
        'get_company', 'get_session'
    ];
    private static $valid_client_post_actions = [
        'registration', 'beta_interest', 'activate', 'send_sms', 'send_email',
        'is_unique', 'admin_email'
    ];
    private static $valid_admin_actions       = [
        'admin_change_role', 'admin_change_status', 'admin_get_users',
        'admin_get_signups', 'admin_get_presentations', 'admin_get_active_by_user',
        'admin_get_contact_by_user', 'admin_get_pending', 'admin_create_session',
        'admin_delete_session', 'admin_get_feedback'
    ];
    private static $valid_user_actions        = [
        'create_social_login', 'password_reset', 'update_social_login', 'is_unique',
        'update_avatar'
    ];

    public function before()
    {
        parent::before();
        if (parent::logged_in())
        {
            static::$model =
                    Document::factory('user', null, static::$user_id);
        }
        else
        {
            static::$model = Document::factory('user');
        }
    }

    /*     * ************************************************************************
     * Default Actions
     * *********************************************************************** */

    /**
     * Fetch by id/username/email if available. If not, search users
     * Check if there is a logged in user
     * Fetch requested user and check relation to logged_in user
     *
     * GET User
     * Usage: Issue a GET to /user/index/{id} - {id} = username, user_id or email
     *
     * @return json_encoded array
     */
    public function action_index()
    {
        // we retrieve the request_type from params
        $type = Arr::get(static::$params, 'request_type', false);

        // if we have a type
        if ($type)
        {
            // we check if it is valid and the method exists
            if (in_array($type, static::$valid_client_get_actions) && method_exists($this, $type))
            // run it
                return $this->{$type}();
            // ok, why do we have a type and not a method???
            else
            // yeap, you guessed it.
                $this->fail('user/generic', 'server_error');
        }

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_admin_actions) && method_exists($this, $type))
        {
            if (parent::has_role('admin'))
            {
                return $this->{$type}();
            }

            Debug::log('Access Denied invoked because no admin user or not client');
            return $this->error('access_denied');
        }

        // no type, let's try id
        $id = $this->request->param('id');

        if ($id)
            return $this->get($id);
        else
        // no id, this is a search
            return $this->search();
    }

    /**
     * Changes to user settings, notifications, profile info etc will use
     * this update.
     */
    public function action_update()
    {
        $id   = $this->request->param('id');
        // get the type of request
        $type = Arr::get(static::$params, 'request_type');

        // no type. we need a type
        if (!$type)
        {
            Debug::log('Invalid invoked because no request type');
            return $this->error('invalid_request');
        }


        // let's check if it's valid and run it
        if (in_array($type, static::$valid_user_actions) && method_exists($this, $type))
        {

            if (static::logged_in_is_id(static::$user_id))
            {
                return $this->{$type}();
            }

            return $this->error('access_denied');
        }

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_client_post_actions) && method_exists($this, $type))
        {
            if (parent::has_role('admin') || parent::is_client())
            {
                return $this->{$type}();
            }

            Debug::log('Access Denied invoked because no admin user or not client');
            return $this->error('access_denied');
        }

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_admin_actions) && method_exists($this, $type))
        {
            if (parent::has_role('admin'))
            {
                return $this->{$type}();
            }

            Debug::log('Access Denied invoked because no admin user or not client');
            return $this->error('access_denied');
        }

        // nope, return an error
        Debug::log('Invalid invoked request type is not valid');
        return $this->error('invalid_request');
    }

    /**
     * Logout a user from the sessions
     */
    public function action_logout()
    {
        $token = Arr::get(static::$params, 'access_token');
        if (!$token)
            return $this->error('invalid_request');

        $s = Document::factory('oauth2_session');

        return $s->destroy($token) ? $this->data(['success' => 1]) : $this->error('invalid_request');
    }

    /*     * ************************************************************************
     * Public Connections
     * *********************************************************************** */

    /**
     * Retrieves an array of [user_ids and user names] of people who like this user
     */
    public function action_likers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'user';

        $data = Document::factory('interaction')
                ->collection()
                ->likers(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves an array of [user_ids and user names] of people who like this user
     */
    public function action_subscribers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'user';

        $data = Document::factory('interaction')
                ->collection()
                ->subscribers(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves an array of subscription interactions for this user
     * Should at least have an object type
     */
    public function action_subscriptions()
    {
        static::$params['user_id'] = $this->request->param('id');

        $data = Document::factory('interaction')
                ->collection()
                ->subscriptions(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves an array of listings belonging to a user.
     */
    public function action_listings()
    {
        $id = $this->request->param('id');
        static::$params['user_id'] = $id;

        $posts = Collection::factory('post');

        /*
         * 1. if user is logged in and ids are the same (or admin or client)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed.
         */
        $status = Arr::get(static::$params, 'status', 'active');

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
                // requested user and logged in user must be the same or must be admin
                if (static::logged_in_is_id($id) || parent::has_role('admin'))
                    return $this->data(
                                    $posts->listings(static::$params, static::$user_id)
                    );

                // clients only see 'active' (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                {
                    static::$params['privacy'] = 'PUBLIC';
                    return $this->data(
                                    $posts->listings(static::$params, static::$user_id)
                    );
                }

                // oops. zip token.
                return $this->error('access_denied');
        }

        return $this->error('invalid_request');
    }

    /**
     * Returns inbox of the user.
     *
     * @return json data
     */
    public function action_inbox()
    {
        $id = $this->request->param('id');
        static::$params['user'] = $id;

        // no status checks here, statuses are in individual messages (comments)
        if (static::logged_in_is_id($id) || parent::has_role('admin'))
        {
            $posts = Mongo_Collection::factory('post');
            return $this->data($posts->inbox(static::$params, static::$user_id));
        }

        return $this->error('access_denied');
    }

    /**
     * Retrieves an array of posts belonging to a user.
     */
    public function action_feed()
    {
        $id = $this->request->param('id');

        static::$params['user_id'] = $id;

        static::$params['type'] = ['$ne' => 'conversation'];

        $posts = Mongo_Collection::factory('post');

        /*
         * 1. if user is logged in and ids are the same (or admin or client)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed.
         */

        $status = Arr::get(static::$params, 'status', 'active');

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
                // logged in user does not see other statuses if posts don't
                // belong to him
                if (static::logged_in_is_id($id))
                {
                    return $this->data(
                                    $posts->search(static::$params, static::$user_id)
                    );
                }

                // admin sees everything
                if (parent::has_role('admin'))
                {
                    static::$params['page_id'] = $id;
                    return $this->data(
                                    $posts->search(static::$params, static::$user_id)
                    );
                }
                // clients only see active (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                {
                    // only publically viewable posts
                    static::$params['privacy'] = 'PUBLIC';
                    // we include posts written on his page
                    static::$params['page_id'] = $id;
                    return $this->data(
                                    $posts->search(static::$params, static::$user_id)
                    );
                }
                // oops. zip token.
                return $this->error('access_denied');
        }

        return $this->error('invalid_request');
    }

    /**
     * Retrieves an array of testimonials written by or received for a user.
     */
    public function action_testimonials()
    {
        $type  = Arr::get(static::$params, 'request_type', 'received');
        $id    = $this->request->param('id');
        $posts = Mongo_Collection::factory('post');

        switch ($type)
        {
            case 'written' :
                static::$params['user_id'] = $id;
                break;
            case 'received' :
                static::$params['page_id'] = $id;
                break;
        }
        /*
         * 1. if user is logged in and ids are the same (or admin or client)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed (with a valid token).
         */

        $status = Arr::get(static::$params, 'status', 'active');

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
                if (static::logged_in_is_id($id) || parent::has_role('admin'))
                    return $this->data(
                                    $posts->testimonials(static::$params, static::$user_id)
                    );

                // clients only see 'active' (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                {
                    static::$params['privacy'] = 'PUBLIC';
                    return $this->data(
                                    $posts->testimonials(static::$params, static::$user_id)
                    );
                }


                // oops. zip token.
                return $this->error('access_denied');
        }

        return $this->error('invalid_request');
    }

    /**
     * This handles both retrieval and updating of a user's privacy settings
     */
    public function action_privacy()
    {
        $id = $this->request->param('id');
        if ($this->is_GET() && (static::logged_in_is_id($id) || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['settings' => 1]);
            return $this->data(
                            $user->stringify($user->as_array()['settings']['privacy'])
            );
        }
        else
        {
            if (static::logged_in_is_id($id) || parent::has_role('admin'))
            {
                // update user by his/her specific field
                if ($this->is_POST())
                {
                    $this->update_privacy_settings();
                }
                return true;
            }
        }
        return $this->error('access_denied');
    }

    /**
     * This handles both retrieval and updating of a user's privacy settings
     */
    public function action_billing()
    {
        $id = $this->request->param('id');
        if ($this->is_GET() && (static::logged_in_is_id($id) || parent::has_role('admin')))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['settings' => 1]);
            $this->data(
                    $user->stringify($user->as_array()['settings']['billing'])
            );
        }
        else
        {
            if (static::logged_in_is_id($id))
            {
                // update user by his/her specific field
                if ($this->is_POST())
                {
                    $this->update_billing_settings();
                }
                return true;
            }
        }
    }

    /**
     * This handles both retrieval and updating of a user's notifications settings
     */
    public function action_notifications()
    {
        $id = $this->request->param('id');
        if ($this->is_GET() && (static::logged_in_is_id($id) || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['settings' => 1]);
            $this->data(
                    $user->stringify($user->as_array()['settings']['notifications'])
            );
        }
        else
        {
            if (static::logged_in_is_id($id) || parent::has_role('admin'))
            {
                // update user by his/her specific field
                if ($this->is_POST())
                {
                    $this->update_notifications_settings();
                }
                return true;
            }
        }
    }

    /**
     * This handles both retrieval and updating of a user's info
     */
    public function action_info()
    {
        $id = $this->request->param('id');
        if ($this->is_GET() && (parent::logged_in() || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['info'     => 1, 'settings' => 1]);
            $this->data($user->stringify($user->as_array()['info']));
        }
        else
        {
            if (static::logged_in_is_id($id))
            {
                // update user by his/her specific field
                if ($this->is_POST())
                {
                    $this->update_info();
                }
                return true;
            }
        }
    }

    /**
     * This handles both retrieval and updating of a user's avatar
     */
    public function action_avatar()
    {

    }

    /**
     * Gets the user's media library
     */
    public function action_library()
    {
        $id = Arr::get($this->request->param(), 'id');
        if ($this->is_GET())
        {
            $page = Document::factory('page', ['user._id' => $id]);
            $page->load();
            return $page->stringify($page->as_array());
        }
    }

    /*     * ************************************************************************
     * Public Actions
     * *********************************************************************** */

    /**
     * Performs a like on the given user
     */
    public function action_like()
    {
        return parent::interact('like', 'user');
    }

    /**
     * Performs an unlike on the given user
     */
    public function action_unlike()
    {
        return parent::interact('unlike', 'user');
    }

    /**
     * Performs a subscribe on the given user
     */
    public function action_subscribe()
    {
        return parent::interact('subscribe', 'user');
    }

    /**
     * Performs an unsubscribe on the given user
     */
    public function action_unsubscribe()
    {
        return parent::interact('unsubscribe', 'user');
    }

    /*     * ************************************************************************
     * Protected Utility Actions - $_GETs
     * *********************************************************************** */

    /**
     * Checks for unique username or email or mobile.
     *
     * @param string static::$params['type'] decides whether it is username or email
     * @param string $request->param['id'] the username or email to check
     */
    protected function is_unique()
    {
        $type  = static::$params['type'];
        $value = $this->request->param('id');

        if (!$type || !$value)
            return $this->error('invalid_request');

        if ($type === 'mobile' && $value = SMS::valid($value))
        {
            if (static::$model->collection()->findOne(['info.telephone.mobile' => $value]))
            {
                return $this->fail(
                                'user/registration', $type . '_not_available'
                );
            }

            return $this->data(['success' => 1]);
        }

        if (static::$model->get($value))
            return $this->fail(
                            'user/registration', $type . '_not_available'
            );

        return $this->data(['success' => 1]);
    }

    /**
     * 1. Returns a user's public information if not logged in.
     * 2. Returns all user information if logged in & ids are the same.
     * 3. Retrieves a user & it's relation to logged in user.
     *
     * @param string|MongoID $id
     * @return object
     */
    protected function get($id)
    {
        if (parent::logged_in())
            return $this->data(static::$model->get($id));
        else
            return $this->data(static::$model->get_public($id));
    }

    /**
     * 1. Returns a user's public information if not logged in.
     * 2. Returns all user information if logged in & ids are the same.
     * 3. Retrieves a user & it's relation to logged in user.
     *
     * @param string|MongoID $id
     * @return object
     */
    protected function get_by_mobile()
    {
        $value = $this->request->param('id');

        $user = static::$model->collection()->findOne([
            'info.telephone.mobile' => SMS::valid($value)
                ]);
        if($user)
        {
            if((int) $user->interested === 1)
            {
                return $this->data(static::$model->stringify($user));
            }
            else
            {
                return $this->fail('user/registration', 'mobile_not_available');
            }
        }
        return $this->data([]);
    }

    /**
     * Proxy to retrieve a company
     */
    protected function get_company()
    {
        $r = [];
        $v = strtoupper(static::$params['term']);
        $p = Document::factory('page')->collection();
        $p->ensureIndex(['name' => 1]);
        $c = $p->find(['name' => new MongoRegex("/^$v/")])
                ->fields(['_id'  => 1, 'name' => 1, 'uri'  => 1])
                ->limit(5);

        while ($c->hasNext())
        {
            $row           = $c->getNext();
            $d             = $row->stringify($row->as_array());
            $data['label'] = $d["name"];
            $data['value'] = ['_id'  => $d["_id"], 'name' => $d['name'], 'uri' => $d['uri']];
            $r[]           = $data;
        }
        return $this->data($r);
    }

    /**
     * Checks if a user has an authenticated social login session and returns it.
     *
     * @param string $network the social login user has authenticated
     * @return array|NULL
     */
    protected function has_social_login($network)
    {
        return static::$model->load([
                    'logins.type' => $network, '_id'         => static::$user_id
                        ], ['logins' => 1]) ? static::$model->as_array()['logins'] : $this->data([]);
        ;
    }

    /**
     * Performs a search/sort of users.
     *
     * @return array of users
     */
    protected function search()
    {
        if (parent::logged_in())
            $users = Collection::factory('user')
                    ->search(static::$params, static::$user_id);
        else
            $users = Collection::factory('user')
                    ->search(static::$params);
        //die(Debug::vars(static::$params));
        return $this->data($users);
    }

    /*     * ************************************************************************
     * Protected Actions - CREATES
     * *********************************************************************** */

    /**
     * New user registration. The user is not active by default and can be
     * activated manually or by an automatic paypal redirect.
     */
    protected function registration()
    {
        $params     = static::extract_registration_values(static::$params);
        // validation rules for a regular user.
        $validation = Validation::factory($params)
                ->rule('first_name', 'alpha')
                ->rule('first_name', 'not_empty')
                ->rule('last_name', 'alpha')
                ->rule('last_name', 'not_empty')
                ->rule('username', 'not_empty')
                ->rule('info.gender', 'in_array', [':value', ['male', 'female']])
                ->rule('info.telephone.mobile', 'not_empty')
                ->rule('password', 'not_empty')
                ->rule('email', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain')
                ->rule('info.birthday', 'numeric');

        if (static::$params['user_type'] === 'agent')
        {
            // validation rules for a agents.
            $validation->rule('company', 'is_array')
                    ->rule('license', 'not_empty')
                    ->rule('designation', 'not_empty');
        }

        if ($validation->check())
        {
            static::$params['interested'] = 0;
            static::$params['roles'] = [static::$params['user_type']];
            static::$params['status'] = 'pending';
            static::$params['first_name'] = static::ucfirst(static::$params['first_name']);
            static::$params['last_name'] = static::ucfirst(static::$params['last_name']);
            static::$params['name'] = static::$params['first_name'] . ' ' . static::$params['last_name'];
            unset(static::$params['user_type']);
            if ($n = Arr::get($params, 'info.telephone.mobile'))
            {
                static::$params['info']['telephone']['mobile'] = SMS::valid($n);
            }
            $m = isset(static::$params['_id']) ? Document::factory('user', static::$params['_id']) : Document::factory('user');
            if ($u = $m->create(static::$params))
            {
                static::create_user_defaults($u);
                $r = static::$params;
                $this->admin_email(
                        'New User REGISTERED!! Double Woots! Woots!', print_r(static::$params, TRUE)
                );
                $this->user_email(
                        $r, 'We received your submission', Kohana::message('user/info', 'welcome_aboard')
                );

                $this->send_sms(
                        'Welcome to Realtasia!', $params['info.telephone.mobile']
                );

                $this->data($u->stringify($u->as_array()));
            }
        }
        else
        {
            $this->errors = $validation->errors('user/registration');
            $this->fail();
        }

        return true;
    }

    /**
     * New user interested in beta. We store this info in a seperate Mongo
     * Collection, not the user DB. But info here can aid in the registration
     * process.
     */
    protected function beta_interest()
    {
        $user       = Document::factory('user');
        $params     = static::extract_registration_values(static::$params);
        // validation rules for a regular user.
        $validation = Validation::factory($params)
                ->rule('first_name', 'alpha')
                ->rule('first_name', 'not_empty')
                ->rule('last_name', 'alpha')
                ->rule('last_name', 'not_empty')
                ->rule('username', 'not_empty')
                ->rule('info.telephone.mobile', 'not_empty')
                ->rule('message', 'not_empty')
                ->rule('email', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain');

        if ($validation->check())
        {
            static::$params['interested'] = 1;
            static::$params['first_name'] = static::ucfirst(static::$params['first_name']);
            static::$params['last_name'] = static::ucfirst(static::$params['last_name']);
            static::$params['name'] = static::$params['first_name'] . ' ' . static::$params['last_name'];
            if ($n = Arr::get($params, 'info.telephone.mobile'))
            {
                static::$params['info']['telephone']['mobile'] = SMS::valid($n);
            }
            if ($user->create(static::$params))
            {
                $r = static::$params;
                $this->admin_email(
                        'New User Joined Beta!! Woots', print_r(static::$params, TRUE)
                );
                $this->user_email(
                        $r, 'We received your submission', Kohana::message('user/info', 'thank_you_beta_email')
                );

                $this->send_sms(
                        'Thank you for you submission', $params['info.telephone.mobile']
                );

                $this->data(['message' => 'Thank you for you submission']);
            }
        }
        else
        {
            $this->errors = $validation->errors('user/registration');
            $this->fail();
        }

        return true;
    }

    /*     * ***************************************************"*********************
     * Protected Actions - UPDATES
     * *********************************************************************** */

    /**
     * Performs a password reset. Front-end app must authenticate user first
     * using a API sms call
     */
    protected function password_reset()
    {
        $id    = $this->request->param('id');
        $model = static::$model;

        if (!isset(static::$params['old_password']) || empty(static::$params['old_password']))
        {
            $old = static::$params['old_password'];
        }

        if (!isset(static::$params['new_password']) || empty(static::$params['new_password']))
            return $this->error('access_denied');

        if ($old)
        {
            if ($old === static::$params['new_password'])
            {
                return $this->fail('user/generic', 'same_password');
            }
            $model->load($id, ['password' => 1]);

            if ($model::hash($old) !== $model->password)
            {
                return $this->fail('user/generic', 'wrong_password');
            }
        }

        $user = $model->update_password(static::$params['new_password'], $id);

        return $user ? $this->data(['success' => 1]) : $this->fail('user/generic', 'server_error');
    }

    /**
     * Create a newly authenticated social login or update an existing one.
     */
    protected function create_social_login()
    {

    }

    /**
     * Update a user's info.
     *
     * @return boolean
     */
    protected function update_info()
    {
        $params = static::extract_info_values(static::$params);
        $possible_fields = array_fill_keys([
            'first_name', 'last_name', 'username', 'uri', 'email', 'name', 'designation', 'license'
        ], null);
        $possible_fields['company'] = [];
        $possible_fields['info'] = [];
        // a few checks, first the basic check.
        $validation = Validation::factory($params)
                ->rule('first_name', 'alpha')
                ->rule('first_name', 'not_empty')
                ->rule('last_name', 'alpha')
                ->rule('last_name', 'not_empty')
                ->rule('username', 'not_empty')
                ->rule('uri', 'not_empty')
                ->rule('info.gender', 'in_array', [':value', ['male', 'female']])
                ->rule('info.telephone.mobile', 'not_empty')
                ->rule('info.birthday', 'numeric')
                ->rule('info.languages', 'is_array')
                ->rule('info.achievements', 'is_array')
                ->rule('info.education', 'is_array')
                ->rule('info.social', 'is_array')
                ->rule('email', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain');


        // first round
        if (!$validation->check())
        {
            $this->errors = $validation->errors('user/info');
            return $this->fail();
        }

        $password = Arr::get($params, 'password');

        // if we have a password, check it.
        if($password)
        {
            if(Arr::get($params, 'confirmPassword') !== 'password')
            {
                $this->errors = ['passwords' => 'password do not match'];
                return $this->fail();
            }

            $possible_fields['password'] = $password;
        }
        // clean everything!
        foreach(static::$params as $key => $value)
        {
            switch($key)
            {
                case 'first_name':
                case 'last_name':
                case 'info':
                case 'designation':
                case 'license':
                case 'company':
                    if(!empty($value))
                    {
                        static::$params[$key] = Security::xss_clean($value);
                    }
                    else
                    {
                        // set to null so as to filter
                        static::$params[$key] = is_array($value) ? $value : null;
                    }
                break;
            }
        }

        $fields = Arr::overwrite($possible_fields, array_filter(static::$params));

        if ($info = static::$model->update_info($fields, static::$user_id))
        {
            return $this->data($info);
        }

        $this->errors = $validation->errors('user/info');
        return $this->fail();
    }

    /**
     * Update a user's privacuy settings.
     *
     * @return boolean
     */
    protected function update_privacy_settings()
    {
        $params     = static::extract_privacy_values(static::$params);
        $validation = Validation::factory($params)
                ->rule('is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('is', 'not_empty')
                ->rule('personal.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('personal.is', 'not_empty')
                ->rule('contacts.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('contacts.is', 'not_empty')
                ->rule('subscriptions.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('subscriptions.is', 'not_empty')
                ->rule('messages.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('messages.is', 'not_empty')
                ->rule('mentions.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('mentions.is', 'not_empty')
                ->rule('wallposts.others_posts.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('wallposts.others_posts.is', 'not_empty')
                ->rule('wallposts.others_posts_visibility.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('wallposts.others_posts_visibility.is', 'not_empty');


        if ($validation->check())
        {
            if ($privacy = static::$model->update_privacy(
                    static::$params, $this->request->param('id')
            ))
            {
                return $this->data($privacy);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('user/privacy');
        return $this->fail();
    }

    /**
     * Update a user's notification settings.
     *
     * @return boolean
     */
    protected function update_notifications_settings()
    {
        $params     = static::extract_notification_values(static::$params);
        $validation = Validation::factory($params)
                ->rule('email', 'email')
                ->rule('email', 'email_domain')
                ->rule('likers.own_post', 'not_empty')
                ->rule('likers.own_post', 'range', [':value', 0, 1])
                ->rule('likers.other_post', 'not_empty')
                ->rule('likers.other_post', 'range', [':value', 0, 1])
                ->rule('likers.profile', 'range', [':value', 0, 1])
                ->rule('likers.profile', 'not_empty')
                ->rule('subscribers.own_post', 'range', [':value', 0, 1])
                ->rule('subscribers.own_post', 'not_empty')
                ->rule('subscribers.other_post', 'range', [':value', 0, 1])
                ->rule('subscribers.other_post', 'not_empty')
                ->rule('subscribers.profile', 'range', [':value', 0, 1])
                ->rule('subscribers.profile', 'not_empty')
                ->rule('comments.own_post', 'range', [':value', 0, 1])
                ->rule('comments.own_post', 'not_empty')
                ->rule('comments.other_post', 'range', [':value', 0, 1])
                ->rule('comments.other_post', 'not_empty')
                ->rule('mentions', 'range', [':value', 0, 1])
                ->rule('mentions', 'not_empty')
                ->rule('testimonials', 'range', [':value', 0, 1])
                ->rule('testimonials', 'not_empty')
                ->rule('wallpost', 'range', [':value', 0, 1])
                ->rule('wallpost', 'not_empty')
                ->rule('messages', 'range', [':value', 0, 1])
                ->rule('messages', 'not_empty')
                ->rule('subscriptions', 'not_empty')
                ->rule('subscriptions', 'in_array', [':value', ['daily', 'weekly']]);

        //var_dump($validation->check());
        //die;
        if ($validation->check())
        {
            if ($notif = static::$model->update_notifications(
                    static::$params, $this->request->param('id')
            ))
            {
                return $this->data($notif);
            }
            return $this->error('server_error');
        }

        $this->errors = $validation->errors('user/notifications');
        return $this->fail();
    }

    /**
     * Update a user's billing settings.
     *
     * @return type
     */
    protected function update_billing_settings()
    {
        $validation = Validation::factory(static::$params)
                ->rule('mode', 'not_empty')
                ->rule('mode', 'in_array', [':value', ['cash', 'cheque', 'autopay']])
                ->rule('frequency', 'not_empty')
                ->rule('frequency', 'in_array', [':value', ['monthly', 'yearly']])
                ->rule('plan_id', 'not_empty');

        if ($validation->check())
        {
            if ($info = static::$model->update_billing(static::$params, static::$user_id))
            {
                return $this->data($info);
            }
            return $this->error('server_error');
        }

        $this->errors = $validation->errors('user/info');
        return $this->fail();
    }


    protected function update_avatar()
    {
        $uid       = $this->request->param('id');
        $picture   = Arr::get(static::$params, 'picture');

        $album = Document::factory('post');
        $query = ['user._id' => new MongoID($uid), 'name' => 'Avatars', 'type' => 'album'];
        $user = Document::factory('user', $uid);

        // either retrieve the avatar album or create one
        if(!$album->load($query))
        {

            if ($user->load(null, ['_id' => 1, 'library' => 1, 'counts' => 1]))
            {
                $avatars = [
                    'name'    => 'Avatars',
                    'message' => 'Your uploaded profile pictures',
                    'user'    => ['_id'  => $user->_id, 'name' => $user->name],
                    'page'    => Arr::merge(['type' => 'library'], $user->library),
                    'type'    => 'album',
                ];
                $album = $album->create_album($avatars);
            }
        }

        if(!$album)
            return $this->fail('user/generic', 'server_error');

        $attachment = [
            'name'        => Arr::get(static::$params, 'name'),
            'mediainfo'   => Arr::get(static::$params, 'mediainfo'),
            'picture'     => $picture,
            'user'        => ['_id'  => $user->_id, 'name' => $user->name],
            'page'        => ['_id'  => $album->_id, 'name' => $album->name, 'type' => 'album'],
            'page_parent' => Arr::merge(['type' => 'library'], $user->library)
        ];
        //Debug::log(print_r($attachment, true));

        if(Document::factory('post')->create_attachment($attachment))
        {
            $user->avatar = $picture;
            $user->save();
            return $this->data(['success' => 1]);
        }
    }

    /**
     * The official Client should be able to send SMSes too, although this really
     * shouldn't be publically known ... :-(
     *
     * @param string $message
     * @param string $number OPTIONAL
     * @return type
     */
    protected function send_sms($message = NULL, $to = NULL)
    {
        $id = $this->request->param('id');
        if (!$message)
        {
            $message = Arr::get(static::$params, 'message');
        }
        if (!$message)
        {
            Debug::log('no messages');
            return $this->error('invalid_request');
        }

        if (!$to)
        {
            $to = Arr::get(static::$params, 'to');
            if (!$to)
            {
                static::$model->load($id, ['info.telephone' => 1]);
                $result = static::$model->as_array();
                $to     = $result['mobile'];
            }
        }
        $sms    = SMS::factory($to, $message);
        return $this->data($sms->send());
    }

    /**
     * Sends a user an email. Normally for registrations purposes. The official
     * client can use this too.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    protected function send_email($subject = NULL, $message = NULL, $to = NULL)
    {
        $id = $this->request->param('id');

        if (!$message)
        {
            $message = Arr::get(static::$params, 'message');
        }

        if (!$subject)
        {
            $subject = Arr::get(static::$params, 'subject');
        }

        if (!$message || !$subject)
            return $this->error('invalid_request');

        if (!$to)
        {
            $to = Arr::get(static::$params, 'to');
            if (!$to)
            {
                static::$model->load($id);
                $result = static::$model->as_array();
                $to     = $result['email'];
            }
        }
        $name   = Arr::get(static::$params, 'name');


        $this->user_email(['email' => $to, 'name'  => $name], $subject, $message);
        return;
    }

    /*     * ************************************************************************
     * Public STATIC Utility Methods
     * *********************************************************************** */

    /**
     * A check to validate a user
     *
     * @param string|MongoID $id
     * @return boolean
     */
    public static function valid($id)
    {
        $user = Document::factory('user', $id);
        $user->load();
        return $user->loaded();
    }

    /*     * ************************************************************************
     * Protected STATIC Utility Methods
     * *********************************************************************** */

    protected static function create_user_defaults($user)
    {
        $library = [
            'type' => 'library',
            'uri'  => Router::user('library', (string) $user->_id),
            'user' => ['_id'  => $user->_id, 'name' => $user->name],
            'name' => "{$user->name}'s Media Library"
        ];
        $lib     = Document::factory('page')->create($library);
        if ($lib)
        {
            $uploads = [
                'name'    => 'Uploads',
                'message' => 'This is your default private album. All uploads come here unless otherwise specified',
                'privacy' => ['is' => 'PRIVATE'],
                'user'    => ['_id'  => $user->_id, 'name' => $user->name],
                'page'    => ['_id'  => $lib->_id, 'name' => "{$user->name}'s Media Library", 'type' => 'library']
            ];
            $album   = Document::factory('post')->create_album($uploads);
            if ($album)
            {
                $user->library = ['_id'  => $lib->_id, 'name' => $lib->name];
                $user->save();
            }
        }
    }


    /**
     * Sends a user an email. Normally for registrations purposes. The official
     * client can use this too.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    protected function user_email($recipient, $subject, $message)
    {
        $content = array(
            'header_plain'  => $subject,
            'message_plain' => $message,
            'message'       => View::factory('emails/content/standard')
                    ->set('content_header', "<h3>$subject</h3>")
                    ->set('content_message', $message)
        );

        $view = View::factory('emails/spam_mail')
                ->set('title', $content['header_plain'])
                ->set('teaser', strip_tags($content['header_plain']))
                ->set('content_view', $content['message']);

        $plainview = View::factory('emails/content/plaintext_generic')
                ->set('content_header', $content['header_plain'])
                ->set('content_message', $content['message_plain']);

        $email = Email::factory($content['header_plain'], $plainview)
                ->message($view, 'text/html')
                ->to($recipient['email'], $recipient['name'])
                ->from('[REDACTED]', 'Realtasia Beta Program');

        return $email->send();
    }

    /**
     * Utility to check if logged in user id is the same as given id
     *
     * @param string $id
     * @return type
     */
    protected static function logged_in_is_id($id)
    {
        // casting as string in case a MongoID was given
        return static::$user_id && (string) static::$user_id === (string) $id;
    }

    /*     * **********************************************************************************
     * ADMIN FUNCTIONS
     * *********************************************************************************** */

    /**
     * Sends an admin email.
     *
     * @param string $subject
     * @param string $message
     * @return type
     */
    protected static function admin_email($subject, $message)
    {
        $content = array(
            'header_plain'  => $subject,
            'message_plain' => $message,
            'message'       => View::factory('emails/content/standard')
                    ->set('content_header', "<h3>$subject</h3>")
                    ->set('content_message', "<pre>$message</pre>")
        );

        $view = View::factory('emails/admin')
                ->set('title', $content['header_plain'])
                ->set('teaser', strip_tags($content['header_plain']))
                ->set('content_view', $content['message']);

        $plainview = View::factory('emails/content/plaintext_generic')
                ->set('content_header', $content['header_plain'])
                ->set('content_message', $content['message_plain']);

        $email = Email::factory($content['header_plain'], $plainview)
                ->message($view, 'text/html')
                ->to('administrators@realtasia.com', 'RealtAsia Administrators')
                ->from('[REDACTED]', 'SiRyA');

        return $email->send();
    }

    //POSTS
    /**
     * changes the role of a user. Needs
     * [param : id, post : role :['admin', 'agent', 'user']]
     *
     * admin roles get extra count fields
     *
     * @return boolean|json data
     */
    protected function admin_change_role()
    {
        $id   = $this->request->param('id');
        $role = $this->request->post('role');

        if (!in_array($role, ['admin', 'agent', 'user']))
            return false;

        $user = Document::factory('user', $id);
        $user->load(null, ['counts' => 1]);

        $user->roles = [$role];
        if ($role === 'admin')
        {
            $counts       = $user->counts;
            $user->counts = Arr::merge($counts, ['contacted' => 0, 'activated' => 0]);
        }
        $user->save();
        $this->data(['status' => 1]);
    }

    /**
     * changes the status of a user. Needs
     * [param : id, post : status :['active', 'pending', 'suspended', 'terminated']]
     *
     * @return boolean|json data
     */
    protected function admin_change_status()
    {
        $id     = $this->request->param('id');
        $status = $this->request->post('status');

        if (!in_array($status, ['active', 'pending', 'suspended', 'terminated']))
            return false;

        $user = Document::factory('user', $id);

        $user->status = $status;
        $user->save();
        $this->data(['status' => 1]);
    }

    /**
     * Retrieve a session by token. This is a client method. Putting it here
     * because the next two methods are related
     */
    protected function get_session()
    {
        $token = $this->request->param('id');
        $query = [
            'session'       => ['$exists' => true],
            'session.token' => $token
        ];

        $admin = Document::factory('user');
        if ($admin->load($query, ['_id'   => 1, 'session' => 1, 'name'  => 1]))
        {
            return $this->data(Arr::merge(['success' => 1], $admin->stringify($admin->as_array())));
        }
        return $this->data([]);
    }

    /**
     * Creates a new session for an admin
     * 1. returns a 4 digit token to validate a user's registration
     * 2. user enters the token and api returns admin creds [_id, name]
     *    to be stored in user col.
     */
    protected function admin_create_session()
    {
        $id    = $this->request->param('id');
        $data  = ['success' => 1];
        $types = ['presentation', 'signup'];
        $type  = Arr::get(static::$params, 'type');
        if (!$type || !in_array($type, $types))
        {
            return $this->error('invalid_request');
        }

        $data['type'] = $type;
        $admin        = Document::factory('user');
        $fourD        = str_pad(rand(0, pow(10, 4) - 1), 4, '0', STR_PAD_LEFT);

        if ($admin->load([
                    '_id'   => $id,
                    'roles' => [ '$in' => ['admin']]], [
                    '_id'  => 1, 'name' => 1
                ]))
        {
            $admin->session = ['token' => $fourD, 'type' => $type];
            if ($admin->save())
            {
                return $this->data(Arr::merge(['success' => 1], $admin->stringify($admin->as_array())));
            }
        }

        return $this->error('invalid_request');
    }

    protected function admin_delete_session()
    {
        $token = $this->request->param('id');

        if (!$token)
            return $this->error('invalid_request');

        $query = [
            '_id'  => static::$user_id,
            '$and' => [['session' => ['$exists' => true], 'session.token' => $token]]
        ];

        $admin = Document::factory('user');
        if ($admin->load($query, ['_id'   => 1, 'session' => 1, 'name'  => 1]))
        {
            $admin->token = null;
            if ($admin->save())
            {
                return $this->data(Arr::merge(['success' => 1], $admin->stringify($admin->as_array())));
            }
        }
        return $this->error('invalid_request');
    }

    protected function admin_get_users()
    {
        $id     = new MongoID($this->request->param('id'));

        $return = [
            'all'          => Collection::factory('user')->find(),
            'presentation' => Collection::factory('user')->find(['rep.type' => 'presentation']),
            'signup'       => Collection::factory('user')->find(['rep.type' => 'signup']),
            'activity'     => Collection::factory('user')->find(['rep._id' => $id]),
            'pending'      => Collection::factory('user')->find(['status' => 'pending']),
            'activations'  => Collection::factory('user')->find(['status'  => 'active', 'rep._id' => $id]),
        ];

        $token  = Arr::get(static::$params, 'token');
        if ($token)
        {
            $return['token'] = Collection::factory('user')->find(['rep.token' => $token]);
        }

        $types = array_keys($return);
        $type = Arr::get(static::$params, 'type');

        if (!in_array( $type, $types))
        {
            $type = null;
        }

        $limit = (int) Arr::get(static::$params, 'limit', 10);
        $skip  = (int) Arr::get(static::$params, 'skip', 0);

        foreach ($types as $t)
        {
            $c  = $return[$t]->limit($limit)->skip($skip);
            unset($return[$t]);
            $return[$t]['count'] = $c->count(false);
            if ($type === $t)
            {
                while ($c->hasNext())
                {
                    $row = $c->getNext();
                    $return[$t]['cl'][] = $row->stringify($row->as_array());
                }
            }
        }
        return $this->data($return);
    }

    protected function admin_get_feedback()
    {

    }

    /*     * **********************************************************************************
     * Creates proper keys for validation. Because params (for Mongo) can include
     * sub arrays. But Kohana & Community decided that it should not validate
     * sub arrays. I see their point, but I still think they're full of shit.
     *
     * @see http://forum.kohanaframework.org/discussion/1338/validation-and-sub-arrays/p1
     *
     * @param array $array
     * @return array
     * *********************************************************************************** */

    protected static function extract_privacy_values($array)
    {
        $return = [];

        $return['contacts.is']                          = Arr::path($array, 'contacts.is');
        $return['personal.is']                          = Arr::path($array, 'contacts.is');
        $return['subscriptions.is']                     = Arr::path($array, 'subscriptions.is');
        $return['messages.is']                          = Arr::path($array, 'messages.is');
        $return['wallposts.others_posts.is']            = Arr::path($array, 'wallposts.others_posts.is');
        $return['wallposts.others_posts_visibility.is'] = Arr::path($array, 'wallposts.others_posts_visibility.is');
        $return['mentions.is']                          = Arr::path($array, 'mentions.is');

        return Arr::merge($return, $array);
    }

    protected static function extract_notification_values($array)
    {
        $return = [];

        $return['likers.own_post']        = Arr::path($array, 'likers.own_post');
        $return['likers.other_post']      = Arr::path($array, 'likers.other_post');
        $return['likers.profile']         = Arr::path($array, 'likers.profile');
        $return['subscribers.own_post']   = Arr::path($array, 'subscribers.own_post');
        $return['subscribers.other_post'] = Arr::path($array, 'subscribers.other_post');
        $return['subscribers.profile']    = Arr::path($array, 'subscribers.profile');
        $return['comments.own_post']      = Arr::path($array, 'comments.own_post');
        $return['comments.other_post']    = Arr::path($array, 'comments.other_post');

        return Arr::merge($return, $array);
    }

    protected static function extract_info_values($array)
    {
        $return = [];
        $return['info.gender']            = Arr::path($array, 'info.gender');
        $return['info.telephone.mobile']  = Arr::path($array, 'info.telephone.mobile');
        $return['info.birthday']          = Arr::path($array, 'info.birthday');
        $return['info.languages']         = Arr::path($array, 'info.languages');
        $return['info.achievements']      = Arr::path($array, 'info.achievements');
        $return['likers.education']       = Arr::path($array, 'likers.education');
        $return['likers.social']          = Arr::path($array, 'likers.social');

        return Arr::merge($return, $array);
    }


    protected static function extract_registration_values($array)
    {
        $return = [];

        $return['info.gender']           = Arr::path($array, 'info.gender');
        $return['info.telephone.mobile'] = Arr::path($array, 'info.telephone.mobile');
        $return['info.birthday']         = Arr::path($array, 'info.birthday');

        return Arr::merge($return, $array);
    }

    protected static function ucfirst($str)
    {
        return ucfirst(strtolower($str));
    }

}