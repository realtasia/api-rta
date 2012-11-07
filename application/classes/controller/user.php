<?php defined('SYSPATH') or die('No direct script access.');
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

    private static $valid_get_types = [
        'has_social_login', 'is_unique', 'get', 'search'
    ];

    private static $valid_adminPost_types = [
        'registration', 'beta_interest', 'activate', 'send_sms', 'send_email',
        'is_unique', 'admin_email'
    ];

    private static $valid_userPost_types = [
        'create_social_login', 'password_reset', 'update_social_login'
    ];

    public function before()
    {
        parent::before();
        if(parent::logged_in())
        {
            static::$model =
                    Mongo_Document::factory('user', null, static::$user_id);
        }
        else
        {
            static::$model = Mongo_Document::factory('user');
        }
    }

    /**************************************************************************
     * Default Actions
     *************************************************************************/

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
        if($type)
        {
            // we check if it is valid and the method exists
            if (in_array($type, static::$valid_get_types)
                && method_exists($this, $type))
                // run it
                return $this->{$type}();
            // ok, why do we have a type and not a method???
            else
                // yeap, you guessed it.
                $this->fail('user/generic', 'server_error');
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
        // get the type of request
        $type = Arr::get(static::$params, 'request_type');

        // no type. we need a type
        if (!$type)
            return $this->error('invalid_request');

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_adminPost_types)
            && method_exists($this, $type))
        {
            if(parent::has_role('admin') || parent::is_client())
            {
                return $this->{$type}();
            }

            Debug::log('Access Denied invoked because no admin user or not client');
            return $this->error('access_denied');
        }

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_userPost_types)
            && method_exists($this, $type))
        {

            if(static::logged_in_is_id($id))
            {
                return $this->{$type}();
            }

            return $this->error('access_denied');
        }

        // nope, return an error
        return $this->error('invalid_request');
    }

    /**************************************************************************
     * Public Connections
     *************************************************************************/

     /**
     * Retrieves an array of [user_ids and user names] of people who like this user
     */
    public function action_likers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'user';

        $data = Mongo_Document::factory('interaction')
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

        $data = Mongo_Document::factory('interaction')
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

        $data = Mongo_Document::factory('interaction')
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

        $posts = Mongo_Collection::factory('post');

        /*
         * 1. if user is logged in and ids are the same (or admin or client)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed.
         */

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
                    return $this->data(
                            $posts->listings(static::$params, static::$user_id)
                    );

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

        return $this->error('invalid_request');
    }

    /**
     * Retrieves an array of posts belonging to a user.
     */
    public function action_feed()
    {
        $id = $this->request->param('id');

        static::$params['user_id'] = $id;

        static::$params['type'] = ['$ne' => 'messages'];

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
                    return $this->data(
                            $posts->testimonials(static::$params, static::$user_id)
                    );

                // oops. zip token.
                return $this->error('access_denied');
        }

        return $this->error('invalid_request');
    }


    /**
     * Utility method that validates this request and either runs an update
     * method (for PUT requests) or retrieves a DB field (GET REQUEST)
     *
     * @param string $update_method
     * @param string $retrieve_field
     * @return boolean
     */
    public function validate_current_request($update_method, $retrieve_field)
    {
        $id = $this->request->param('id');

        // current user is the user we need to update/fetch or
        // an admin is logged in or this is a client_credentials grant_type
        if(static::logged_in_is_id($id) || parent::has_role('admin'))
        {
            // retrieve user by his/her specific field requested.
            if($this->is_GET())
            {
                $user = static::$model;
                if($user->load($id, [$retrieve_field => 1]))
                {
                    $this->data($user->stringify($user->as_array()));
                }
            }
            // update user by his/her specific field
            if($this->is_POST())
            {
                $this->{$update_method}();
            }
            return true;
        }

        // no way to validate this request.
        Debug::log('In Validate Request, no way to validate request');
        $this->fail('user/generic', 'access_denied');
        return true;
    }

    /**
     * This handles both retrieval and updating of a user's privacy settings
     */
    public function action_privacy()
    {
        if ($this->is_GET() && (parent::logged_in() || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['settings' => 1]);
            $this->data(
                    $user->stringify($user->as_array()['settings']['privacy'])
            );
        }
        else
            return $this->validate_current_request(
                    'update_privacy', 'settings.privacy'
                   );

    }

    /**
     * This handles both retrieval and updating of a user's notifications settings
     */
    public function action_notifications()
    {
        return $this->validate_current_request(
                'update_notifications', 'settings.notifications'
               );
    }

    /**
     * This handles both retrieval and updating of a user's info
     */
    public function action_info()
    {
        if ($this->is_GET() && (parent::logged_in() || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['info' => 1]);
            $this->data($user->stringify($user->as_array()['info']));
        }
        else
            return $this->validate_current_request('update_info', 'info');
    }

    /**
     * This handles both retrieval and updating of a user's avatar
     */
    public function action_avatar()
    {

    }


    /**************************************************************************
     * Public Actions
     *************************************************************************/

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


    /**************************************************************************
     * Protected Utility Actions - $_GETs
     *************************************************************************/

    /**
     * Checks for unique username or email.
     *
     * @param string static::$params['type'] decides whether it is username or email
     * @param string $request->param['id'] the username or email to check
     */
    protected function is_unique()
    {
        $type = static::$params['type'];
        $value = $this->request->param('id');

        if(!$type || !$value)
            return $this->error('invalid_request');

        if(static::$model->get([$type => $value]))
            return $this->fail(
                    'register/user', 'username.' . $type . '_not_available'
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
        if(parent::logged_in())
            return $this->data(static::$model->get($id));
        else
            return $this->data(static::$model->get_public($id));
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
            'logins.type' => $network, '_id' => static::$user_id
        ], ['logins'])
                ? static::$model->as_array()['logins']
                : $this->data('status', 1);;
    }

    /**
     * Performs a search/sort of users.
     *
     * @return array of users
     */
    protected function search()
    {
        if(parent::logged_in())
            $users = Mongo_Collection::factory('user')
                                    ->search(static::$params, static::$user_id);
        else
            $users = Mongo_Collection::factory('user')
                                    ->search(static::$params);

        return $this->data($users);
    }

    /**************************************************************************
     * Protected Actions - CREATES
     *************************************************************************/

    /**
     * New user registration. The user is not active by default and can be
     * activated manually or by an automatic paypal redirect.
     */
    protected function registration()
    {
        $params = static::extract_registration_values(static::$params);
        // validation rules for a regular user.
        $validation = Validation::factory($params)
                ->rule('first_name', 'alpha')
                ->rule('first_name', 'not_empty')
                ->rule('last_name', 'alpha')
                ->rule('last_name', 'not_empty')
                ->rule('info.gender', 'alpha')
                //->rule('info.telephone.mobile', 'numeric')
                ->rule('password', 'not_empty')
                ->rule('email', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain')
                ->rule('info.birthday', 'numeric');

        if (static::$params['user_type'] == 'agent')
        {
            // validation rules for a agents.
            $validation->rule('company', 'is_array')
                       ->rule('license', 'not_empty')
                       ->rule('designation', 'alpha');
        }

        if($validation->check())
        {
            static::$params['roles'] = [static::$params['user_type']];
            static::$params['status'] = 'pending';
            unset(static::$params['user_type']);

            if ($u = static::$model->create(static::$params))
            {
                unset($u['password']);
                $r = static::$params;
                $this->admin_email(
                        'New User REGISTERED!! Woots',
                        print_r(static::$params, TRUE)
                );
                $this->user_email(
                        $r,
                        'We received your submission',
                        Kohana::message('register/info', 'thank_you_beta_email')
                );

                $this->send_sms(
                        'Thank you for you submission',
                        static::$params['mobile']
                );

                $this->data($u);
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
        $user = Mongo_Document::factory('beta');

        $validation = Validation::factory(static::$params)
                ->rule('message', 'not_empty')
                ->rule('mobile', 'not_empty')
                ->rule('user_type', 'not_empty')
                ->rule('name', 'not_empty')
                ->rule('name', 'alpha')
                ->rule('email', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain');

        if($validation->check())
        {
            static::$params['interested'] = 1;
            if ($user->create(static::$params))
            {
                $r = static::$params;
                $this->admin_email(
                        'New User Joined Beta!! Woots',
                        print_r(static::$params, TRUE)
                );
                $this->user_email(
                        $r,
                        'We received your submission',
                        Kohana::message('register/info', 'thank_you_beta_email')
                );

                $this->send_sms(
                        'Thank you for you submission',
                        static::$params['mobile']
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

    /*****************************************************"*********************
     * Protected Actions - UPDATES
     *************************************************************************/

    /**
     * Performs a password reset. Front-end app must authenticate user first
     * using a API sms call
     */
    protected function password_reset()
    {
        if(!isset(static::$params['password'])
           || empty(static::$params['password']))
            return $this->error('invalid_request');

        if(!isset(static::$params['new_password'])
           || empty(static::$params['new_password']))
            return $this->error('invalid_request');

        $id = $this->param->user('id');
        $user = static::$model->update_password(
                static::$params['new_password'], $id
        );

        return $user
        ? $this->data(['success' => 1])
        : $this->fail('user/generic', 'server_error');
    }

    /**
     * Create a newly authenticated social login or update an existing one.
     */
    protected function create_social_login()
    {

    }

    /**
     * Activate a user. Used by paypal return method or called directly by an admin.
     */
    protected function activate()
    {
        $id = $this->request->param('id');
        $user = Mongo_Document::factory('user', $id);

        //static::$model->load_values(['_id' => new MongoID($id)]);
        $user->status = 'active';
        $user->save();
        $this->data(['status' => 1]);
    }

    /**
     * Update a user's info.
     *
     * @return boolean
     */
    protected function update_info()
    {
        $validation = Validation::factory(static::$params)
                ->rule('telephone', 'is_array')
                ->rule('birthday', 'numeric')
                ->rule('gender', 'not_empty');

        if ($validation->check())
        {
            if ($info = static::$model->update_info(
                    static::$params, static::$user_id)
            )
            {
                return $this->data($info);
            }
            return $this->error('server_error');
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
        $params = static::extract_privacy_values(static::$params);
        $validation = Validation::factory($params)
                ->rule('contacts.is', 'Controller_User::valid_privacy', [
                    ':value', ':field', ':validation'
                ])
                ->rule('contacts.is', 'not_empty')
                ->rule('subscriptions.is', 'Controller_User::valid_privacy', [
                    ':value', ':field', ':validation'
                ])
                ->rule('subscriptions.is', 'not_empty')
                ->rule('messages.is', 'Controller_User::valid_privacy', [
                    ':value', ':field', ':validation'
                ])
                ->rule('messages.is', 'not_empty')
                ->rule('mentions.is', 'Controller_User::valid_privacy', [
                    ':value', ':field', ':validation'
                ])
                ->rule('mentions.is', 'not_empty')
                ->rule('profile.others_posts.is',
                       'Controller_User::valid_privacy', [
                            ':value', ':field', ':validation'
                ])
                ->rule('profile.others_posts.is', 'not_empty')
                ->rule('profile.others_posts_visibility.is',
                       'Controller_User::valid_privacy', [
                            ':value', ':field', ':validation'
                ])
                ->rule('profile.others_posts_visibility.is', 'not_empty');


        if ($validation->check())
        {
            if ($privacy = static::$model->update_privacy(
                    static::$params, static::$user_id
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
        $params = static::extract_notification_values(static::$params);
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
                ->rule('messages', 'not_empty');

        //var_dump($validation->check());
        //die;
        if ($validation->check())
        {
            if ($notif = static::$model->update_notifications(
                    static::$params, static::$user_id
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
        if(!$message)
        {
            $message = Arr::get(static::$params, 'message');
        }
        if(!$message)
        {
            Debug::log('no messages');
            return $this->error('invalid_request');
        }

        if(!$to)
        {
            $to = Arr::get(static::$params, 'to');
            if(!$to)
            {
                static::$model->load($id, ['info.telephone' => 1]);
                $result = static::$model->as_array();
                $to = $result['mobile'];
            }
        }
        $sms = SMS::factory($to, $message);
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

        if(!$message)
        {
            $message = Arr::get(static::$params, 'message');
        }

        if(!$subject)
        {
            $subject = Arr::get(static::$params, 'subject');
        }

        if(!$message || !$subject)
            return $this->error('invalid_request');

        if(!$to)
        {
            $to = Arr::get(static::$params, 'to');
            if(!$to)
            {
                static::$model->load($id);
                $result = static::$model->as_array();
                $to = $result['email'];
            }
        }
        $name = Arr::get(static::$params, 'name');


        $this->user_email(['email' => $to, 'name' => $name], $subject, $message);
        return;
    }

    /**************************************************************************
     * Protected STATIC Utility Methods
     *************************************************************************/

    /**
     * Sends an admin email.
     *
     * @param string $subject
     * @param string $message
     * @return type
     */
    protected static function admin_email($subject, $message)
    {
        $content  = array(
           'header_plain' => $subject,
           'message_plain' => $message,
           'message' => View::factory('emails/content/standard')
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
        $content  = array(
           'header_plain' => $subject,
           'message_plain' => $message,
           'message' => View::factory('emails/content/standard')
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
              ->bcc('administrators@realtasia.com', 'RealtAsia Administrators')
              ->from('[REDACTED]', 'Realtasia Beta Program');

        return $email->send();
    }

    protected static function logged_in_is_id($id)
    {
        return static::$user_id && static::$user_id === $id;
    }

    /**
     * Small validation for possible privacy settings
     *
     * @param string $value
     * @return boolean
     */
    public static function valid_privacy($value, $field, $validation)
    {
        if(in_array($value, ['PUBLIC', 'SUBSCRIBERS', 'SELF', 'CUSTOM']))
        {
            return true;
        }

        $validation->error($field, 'valid_privacy');
    }

    protected static function extract_privacy_values($array)
    {
        $return = [];

        $return['contacts.is'] =
                        Arr::path($array, 'contacts.is');
        $return['subscriptions.is'] =
                        Arr::path($array, 'subscriptions.is');
        $return['messages.is'] =
                        Arr::path($array, 'messages.is');
        $return['profile.others_posts.is'] =
                        Arr::path($array, 'profile.others_posts.is');
        $return['profile.others_posts_visibility.is'] =
                        Arr::path($array, 'profile.others_posts_visibility.is');
        $return['mentions.is'] = Arr::path($array, 'mentions.is');

        return Arr::merge($return, $array);
    }

    protected static function extract_notification_values($array)
    {
        $return = [];

        $return['likers.own_post'] =
                        Arr::path($array, 'likers.own_post');
        $return['likers.other_post'] =
                        Arr::path($array, 'likers.other_post');
        $return['likers.profile'] =
                        Arr::path($array, 'likers.profile');
        $return['subscribers.own_post'] =
                        Arr::path($array, 'subscribers.own_post');
        $return['subscribers.other_post'] =
                        Arr::path($array, 'subscribers.other_post');
        $return['subscribers.profile'] =
                        Arr::path($array, 'subscribers.profile');
        $return['comments.own_post'] =
                        Arr::path($array, 'comments.own_post');
        $return['comments.other_post'] =
                        Arr::path($array, 'comments.other_post');

        return Arr::merge($return, $array);
    }

    protected static function extract_registration_values($array)
    {
        $return = [];

        $return['info.gender'] =
                        Arr::path($array, 'info.gender');
        $return['info.telephone.mobile'] =
                        Arr::path($array, 'info.telephone.mobile');
        $return['info.birthday'] =
                        Arr::path($array, 'info.birthday');

        return Arr::merge($return, $array);
    }
}