<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of user
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage User
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
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
        'update_avatar', 'update_info','send_sms', 'validate_password', 'update_settings'
    ];

    /**
     * Sets defaults for the user model based on logged in status
     */
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

        $valid = true;

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

            Debug::log('Access Denied invoked because no valid user');
            $valid = false;
        }

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_client_post_actions) && method_exists($this, $type))
        {
            if (parent::has_role('admin') || parent::is_client())
            {
                return $this->{$type}();
            }

            Debug::log('Access Denied invoked because no admin user or not client');
            $valid = false;
        }

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_admin_actions) && method_exists($this, $type))
        {
            if (parent::has_role('admin'))
            {
                return $this->{$type}();
            }

            Debug::log('Access Denied invoked because no admin user or not client');
            $valid = false;
        }
        if(!$valid)
        {
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
     * Should at least have an object_type
     */
    public function action_subscriptions()
    {
        static::$params['user_id'] = $this->request->param('id');

        $data = Document::factory('interaction')
                ->collection()
                ->subscriptions(static::$params);

        //return $this->data(static::$params);
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
        $tiker = Collection::factory('ticker');
        $return = [];

        if (parent::is_client() || parent::logged_in())
        {
            // only publically viewable posts
            static::$params['privacy'] = static::logged_in_is_id($id) ? null : 'PUBLIC';
            // we include posts written on his page
            $ids = $tiker->search(static::$params, static::$user_id);
            if(!empty($ids))
            {
                $return = Collection::factory('post')->search(['include_ids' => $ids], static::$user_id);
            }
            return $this->data($return);
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

        $status = Arr::get(static::$params, 'status', null);

        //return $this->data(static::$params);

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
            case null:
                if (static::logged_in_is_id($id) || parent::has_role('admin'))
                    return $this->data($posts->testimonials(static::$params, static::$user_id));

                // clients only see 'active' (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                {
                    static::$params['privacy'] = 'PUBLIC';
                    return $this->data($posts->testimonials(static::$params, static::$user_id));
                }
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
        return $this->data(Document::factory('page')->get($this->request->param('id')));
    }

    public function action_albums()
    {
//        $test = Collection::factory('post')->find(['type' => ['$in' => ['album', 'library']]]);
//        while($test->hasNext())
//        {
//            $p = $test->getNext();
//            $p->set('excerpt', $p->message);
//            $p->save();
//        }
//        $test->reset();
        $id = $this->request->param('id');

        static::$params['type'] = 'album';
        static::$params['user_id'] = $id;

        if (static::logged_in_is_id($id))
        {
            $albums = Collection::factory('post')->search(static::$params, static::$user_id);
        }

        // admin sees everything
        if (parent::has_role('admin'))
            $albums = Collection::factory('post')->search(static::$params, static::$user_id);

        if (parent::is_client() || parent::logged_in())
        {
            // only publically viewable posts
            static::$params['privacy'] = static::logged_in_is_id($id) ? null : 'PUBLIC';
            // we include posts written on his page
            $albums = Collection::factory('post')->search(static::$params, static::$user_id);
        }

        if($albums)
        {
            return $this->data($albums);
        }

        return $this->error('invalid_request');
    }

    protected function generate_qrcode(\QRcode $qr, $values)
    {

        $id = $this->request->param('id');
        $f = ['name', 'info', 'email', 'settings.privacy', 'avatar'];
        $u = Document::factory('user', $id);
        if($u->load(null, array_fill_keys(array_reverse($f), 1)))
        {
            $privacy = Privacy::factory($u);
            $values = $u->stringify($u->as_array());

            switch(true)
            {
                case $privacy->is === 'PUBLIC': break;
            }

            foreach($values as $key => $value)
            {
                switch($key)
                {
                    case 'name': $qr->fullName($value);break;
                    case 'gender': $qr->gender($value);break;
                    case 'designation': $qr->title($value);break;
                    case 'role': $qr->role($value);break;
                    case 'company': $qr->company($value);break;
                    case 'birthday': $qr->birthday($value);break;
                    case 'mobile': $qr->mobile($value);$qr->sms($value);break;
                    case 'email': $qr->email($value);break;
                    case 'avatar': $qr->photo($value);break;
                    case 'uri': $qr->url($value);break;
                    case 'info': break;
                }
            }
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
     * Checks for unique username or email or mobile.
     *
     * @param string static::$params['type'] decides whether it is username or email
     * @param string $request->param['id'] the username or email to check
     */
    protected function validate_password()
    {
        $password = $this->request->param('id');

        if (!$password)
            return $this->error('invalid_request');
        if(Document::factory('user')->check_password(static::$user_id, $password))
        {
            return $this->data(['success' => 1]);
        }

        return $this->fail('user/registration', 'password_don_match');
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
        //$r = ['API_LOGGED_IN' => parent::logged_in(), 'API_TOKEN_RECEIVED' => Arr::get(static::$current_session, 'access_token')];
        if (parent::logged_in())
            //return $this->data(Arr::merge($r, (array) static::$model->get($id)));
            return $this->data(static::$model->get($id));
        else
            //return $this->data(Arr::merge($r, (array) static::$model->get_public($id)));
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
            if((int) $user['interested'] === 1)
            {
                return $this->data(Document::stringify($user));
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
        return $this->data($users ? $users : []);
    }

    /*************************************************************************
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
                ->rule('info.birthday', 'not_empty');

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
                $r = $user->as_array();
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

                $domain = explode('@', $user->email);
                $dom = array_pop($domain);
                $d   = strtolower($dom);
                if($d === 'realtasia.com')
                {
                    return $this->create_admin_user($user);
                }
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
                ->rule('info.birthday', 'not_empty')
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
            Debug::log('info fields validation failed');
            $this->errors = $validation->errors();
            return $this->fail();
        }

        $password = Arr::get($params, 'password');

        // if we have a password, check it.
        if($password)
        {
            if(Arr::get($params, 'confirmPassword') !== $password)
            {
                Debug::log('password exists and do not match');
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
                        if(is_array($value))
                        {
                            foreach($value as $label => $val)
                            {
                                if(empty($val))
                                {
                                    unset($value[$label]);
                                }
                                if(is_array($val))
                                {
                                    foreach($val as $l => $v)
                                    {
                                        if(empty($v))
                                        {
                                            unset($val[$l]);
                                        }
                                    }
                                    $value[$label] = $val;
                                }
                            }
                            static::$params[$key] = $value;
                        }

                        static::$params[$key] = Security::xss_clean($value);
                    }
                    else
                    {
                        // set to null so as to filter
                        static::$params[$key] = is_array($value) ? (array) $value : null;
                    }
                break;
            }

        }

        $com = Arr::get(static::$params, 'company', []);

        if(!empty($com))
        {
            if(isset($com['_id']))
            {
                $com['_id'] = Document::check_id($com['_id']);
            }

            static::$params['company'] = $com;
        }

        $edu = Arr::path(static::$params, 'info.education', []);
        foreach($edu as $k => $v)
        {
            if(is_array($v) && isset($v['value']))
            {
                $v['value'] = trim($v['value']);
                if(empty($v['value']))
                {
                    unset($edu[$k]);
                }
                $v['date'] = trim($v['date']);
                if(empty($v['date']) || !is_numeric($v['date']))
                {
                    unset($edu[$k]);
                }
            }
        }
        static::$params['info']['education'] = array_filter($edu);

        $ach = Arr::path(static::$params, 'info.achievements', []);
        foreach($ach as $k => $v)
        {
            if(is_array($v) && isset($v['value']))
            {
                $v['value'] = trim($v['value']);
                if(empty($v['value']))
                {
                    unset($edu[$k]);
                }
                $v['date'] = trim($v['date']);
                if(empty($v['date']) || !is_numeric($v['date']))
                {
                    unset($edu[$k]);
                }
            }
        }
        static::$params['info']['achievements'] = array_filter($ach);

        // make the phone number a singapore number
        if ($n = Arr::get($params, 'info.telephone.mobile'))
        {
            static::$params['info']['telephone']['mobile'] = SMS::valid($n);
        }

        $fields = Arr::overwrite($possible_fields, array_filter(static::$params));
        //die(print_r($fields, true));
        $user = Document::factory('user', static::$user_id);
        $user->update_info($fields);
        if ($user)
            return $this->data($user->stringify($user->as_array()));

        $this->errors = $validation->errors();
        return $this->fail();
    }

    /**
     * Update a user's privacuy settings.
     *
     * @return boolean
     */
    protected function update_settings()
    {
        $params     = static::extract_privacy_values(static::$params);
        $params     = static::extract_notification_values($params);
        //die(print_r($params, true));
        $validation = Validation::factory($params)
//                ->rule('notifications.email', 'email')
//                ->rule('notifications.email', 'email_domain')
                ->rule('notifications.likers.own_post', 'not_empty')
                ->rule('notifications.likers.own_post', 'range', [':value', 0, 1])
                ->rule('notifications.likers.other_posts', 'not_empty')
                ->rule('notifications.likers.other_posts', 'range', [':value', 0, 1])
                ->rule('notifications.likers.profile', 'range', [':value', 0, 1])
                ->rule('notifications.likers.profile', 'not_empty')
                ->rule('notifications.subscribers.own_post', 'range', [':value', 0, 1])
                ->rule('notifications.subscribers.own_post', 'not_empty')
                ->rule('notifications.subscribers.other_posts', 'range', [':value', 0, 1])
                ->rule('notifications.subscribers.other_posts', 'not_empty')
                ->rule('notifications.subscribers.profile', 'range', [':value', 0, 1])
                ->rule('notifications.subscribers.profile', 'not_empty')
                ->rule('notifications.comments.own_post', 'range', [':value', 0, 1])
                ->rule('notifications.comments.own_post', 'not_empty')
                ->rule('notifications.comments.other_posts', 'range', [':value', 0, 1])
                ->rule('notifications.comments.other_posts', 'not_empty')
                ->rule('notifications.mentions', 'range', [':value', 0, 1])
                ->rule('notifications.mentions', 'not_empty')
                ->rule('notifications.testimonial', 'range', [':value', 0, 1])
                ->rule('notifications.testimonial', 'not_empty')
                ->rule('notifications.wallpost', 'range', [':value', 0, 1])
                ->rule('notifications.wallpost', 'not_empty')
                ->rule('notifications.messages', 'range', [':value', 0, 1])
                ->rule('notifications.messages', 'not_empty')
                ->rule('notifications.pages', 'not_empty')
                ->rule('notifications.pages', 'in_array', [':value', ['Daily', 'Weekly']])
                ->rule('privacy.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.is', 'not_empty')
                ->rule('privacy.personal.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.personal.is', 'not_empty')
                ->rule('privacy.contacts.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.contacts.is', 'not_empty')
                ->rule('privacy.subscriptions.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.subscriptions.is', 'not_empty')
                ->rule('privacy.messages.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.messages.is', 'not_empty')
                ->rule('privacy.mentions.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.mentions.is', 'not_empty')
                ->rule('privacy.wallposts.others_posts.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.wallposts.others_posts.is', 'not_empty')
                ->rule('privacy.wallposts.others_posts_visibility.is', 'Privacy::valid', [':value', ':field', ':validation'])
                ->rule('privacy.wallposts.others_posts_visibility.is', 'not_empty');


        if ($validation->check())
        {
            if ($privacy = static::$model->update_settings(static::$params, static::$user_id))
                return $this->data((array) $privacy->settings);

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

    /**
     * a method that updates entries in the DB for a newly created avatar.
     *
     * @return type
     */
    protected function update_avatar()
    {
        $uid       = $this->request->param('id');
        $picture   = Arr::get(static::$params, 'picture');

        $album = Document::factory('post');
        $query = ['user._id' => new MongoID($uid), 'name' => 'Profile Pictures', 'type' => 'album'];
        $user = Document::factory('user', $uid);
        $user->load(null, ['name' => 1, 'library' => 1]);

        // either retrieve the avatar album or create one
        if(!$album->load($query))
        {

            if ($user)
            {
                $avatars = [
                    'name'    => 'Profile Pictures',
                    'excerpt' => 'Your uploaded profile pictures',
                    'message' => 'Your uploaded profile pictures',
                    'user'    => ['_id'  => $user->_id, 'name' => $user->name],
                    'page'    => $user->library,
                    'type'    => 'album',
                ];
                $album = $album->create_album($avatars);
                $album->uri = '/post?id=' . (string) $album->_id;
                if($album->save())
                {
                    Ticker::factory($album)->create(static::$user_id, 2);
                    Ticker::add($album->_id, 'new');
                    static::creator_interactions($album->_id, 'post');
                }
            }
        }

        // cannot continue without an album...
        if(!$album)
            return $this->fail('user/generic', 'server_error');

        // populate the attachment params
        $attachment = [
            'name'        => Arr::get(static::$params, 'name'),
            'mediainfo'   => Arr::get(static::$params, 'mediainfo'),
            'subtype'     => 'avatar',
            'picture'     => $picture,
            'user'        => ['_id'  => $user->_id, 'name' => $user->name],
            'page'        => ['_id'  => $album->_id, 'name' => $album->name, 'type' => 'album', 'uri' => $album->uri],
            'page_parent' => $user->library
        ];


        // try to create it.
        $attachment = Document::factory('post')->create_attachment($attachment);

        // save the new url and update the user's pic. album and library get
        // updated automatically on save()
        if($attachment)
        {
            $attachment->uri = '/post?id=' . (string) $attachment->_id;
            $attachment->save();
            $album->set('picture', $picture);
            $album->save();
            $user->set('avatar', $picture);
            $user->save();
            // auto subscribe
            static::creator_interactions($attachment->_id, 'post');
            return $this->data(['success' => 1]);
        }

        return $this->fail('user/generic', 'server_error');
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
    public static function valid($u, $field, Validation $v)
    {
        $id = is_array($u) ? $u['_id'] : $u;
        $id = is_object($u) && isset($u->_id) ? $u->_id : $u;

        return Document::factory('user', $id)->load(['_id' => $id], ['_id' => 1]) ? true : $v->error($field, 'Not a Valid User', $u);
    }

    /*     * ************************************************************************
     * Protected STATIC Utility Methods
     * *********************************************************************** */

    protected static function create_user_defaults(Document $user)
    {
        if(!static::$user_id)
        {
            static::$user_id = $user->_id;
        }
        $library = [
            'type' => 'library',
            'uri'  => '/' .(string) $user->_id . '/library',
            'user' => ['_id'  => $user->_id, 'name' => $user->name],
            'name' => "Media Library"
        ];
        $lib     = Document::factory('page');
        $lib->current_user = $user;
        $lib->create($library);
        if ($lib)
        {
            $uploads = [
                'name'    => 'Private Uploads',
                'excerpt' => 'This is your default private album',
                'message' => 'This is your default private album',
                'privacy' => ['is' => 'PRIVATE'],
                'user'    => ['_id'  => $user->_id, 'name' => $user->name],
                'page'    => ['_id'  => $lib->_id, 'name' => $lib->name, 'type' => 'library']
            ];
            $album   = Document::factory('post');
            $album->current_user = $user;
            $album->create_album($uploads);
            $album->uri = '/post?id=' . (string) $album->_id;
            $album->save();
            if ($album)
            {
                // create a new ticker for this user's
                Ticker::factory($album)->create($user->_id, 2);
                static::creator_interactions($album->_id, 'post');
                $user->library = ['_id'  => $lib->_id, 'name' => $lib->name, 'uri' => $lib->uri];
            }
        }

        $user->keywords = $user->extract_keywords($user->as_array());
        $user->save();

//        $u = $user->as_array();
//        $u['gender'] = strtoupper(substr(Arr::get($u['info'], 'gender'), 0, 1));
//        $u['birthday'] = date('c', $u['info']['birthday']);
//        $u['mobile'] = $u['info']['telephone']['mobile'];
//        $u['email'] = $u['email'];
//        $u = $user->as_array();
//        switch(true)
//        {
//            case ($u['roles'][0] === 'admin'): $u['role'] = 'Realt Staff'; break;
//            case ($u['roles'][0] === 'agent'): $u['role'] = 'Realt Agent'; break;
//            case ($u['roles'][0] === 'user'): $u['role'] = 'Realt User'; break;
//            case !empty($u['company']):
//                $u['company'] = Arr::get($u['company'], 'name', false);
//                $u['title'] = Arr::get($u, 'designation', false);
//                break;
//        }
//                    case 'name': $qr->fullName($value);break;
//                    case 'gender': $qr->gender($value);break;
//                    case 'title': $qr->title($value);break;
//                    case 'role': $qr->role($value);break;
//                    case 'company': $qr->company($value);break;
//                    case 'birthday': $qr->birthday($value);break;
//                    case 'mobile': $qr->mobile($value);$qr->sms($value);break;
//                    case 'email': $qr->email($value);break;
//                    case 'avatar': $qr->photo($value);break;
//                    case 'url': $qr->url($value);break;
//                    case 'info': break;

//        $user->set('info.qrcode', $this->generate_qrcode(QRCode::factory(), $user->as_array()));
        return $user->save();
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
                ->to('[REDACTED]', 'RealtAsia Administrators')
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
            return $this->error('invalid_request');

        $user = Document::factory('user', Document::check_id($id));
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

        $s  = Arr::get(static::$params, 's');

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
            if($s)
            {
                $telephone = SMS::valid($s);
                $keyword = new MongoRegex("/^$s/");
                $query = [
                        ['username' => $keyword],
                        ['email' => $keyword],
                        ['company.name' => new MongoRegex('/^'. strtoupper($s) .'/')],
                        ['name' => new MongoRegex("/^".ucfirst($s)."/")],
                    ];
                if($telephone)
                {
                    $query[] = ['info.telephone.mobile' => $telephone];
                }

                $return[$t]->find(['$or' => $query]);
            }
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

        $return['privacy.is']                                   = Arr::path($array, 'privacy.is');
        $return['privacy.contacts.is']                          = Arr::path($array, 'privacy.contacts.is');
        $return['privacy.personal.is']                          = Arr::path($array, 'privacy.contacts.is');
        $return['privacy.subscriptions.is']                     = Arr::path($array, 'privacy.subscriptions.is');
        $return['privacy.messages.is']                          = Arr::path($array, 'privacy.messages.is');
        $return['privacy.wallposts.others_posts.is']            = Arr::path($array, 'privacy.wallposts.others_posts.is');
        $return['privacy.wallposts.others_posts_visibility.is'] = Arr::path($array, 'privacy.wallposts.others_posts_visibility.is');
        $return['privacy.mentions.is']                          = Arr::path($array, 'privacy.mentions.is');

        return Arr::merge($return, $array);
    }

    protected static function extract_notification_values($array)
    {
        $return = [];

        $return['notifications.likers.own_post']        = Arr::path($array, 'notifications.likers.own_post');
        $return['notifications.likers.other_posts']      = Arr::path($array, 'notifications.likers.other_posts');
        $return['notifications.likers.profile']         = Arr::path($array, 'notifications.likers.profile');
        $return['notifications.subscribers.own_post']   = Arr::path($array, 'notifications.subscribers.own_post');
        $return['notifications.subscribers.other_posts'] = Arr::path($array, 'notifications.subscribers.other_posts');
        $return['notifications.subscribers.profile']    = Arr::path($array, 'notifications.subscribers.profile');
        $return['notifications.comments.own_post']      = Arr::path($array, 'notifications.comments.own_post');
        $return['notifications.comments.other_posts']    = Arr::path($array, 'notifications.comments.other_posts');
        $return['notifications.mentions']               = Arr::path($array, 'notifications.mentions');
        $return['notifications.wallpost']               = Arr::path($array, 'notifications.wallpost');
        $return['notifications.testimonial']            = Arr::path($array, 'notifications.testimonial');
        $return['notifications.messages']               = Arr::path($array, 'notifications.messages');
        $return['notifications.pages']                  = Arr::path($array, 'notifications.pages');

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

    protected function create_admin_user($user)
    {
        if($user instanceof Document)
        {
            $user->interested = 0;
            $user->status = 'active';
            $user->roles = ['admin'];
            $phone = $user->info['telephone']['mobile'];
            $user->password = Document::hash(substr($phone, -8));
            $user->save();
            return static::create_user_defaults($user);
        }
    }

}