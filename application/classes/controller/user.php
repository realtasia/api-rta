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

    private static $valid_put_types = [
        'password_reset', 'update_social_login', 'activate', 'change_avatar'
    ];

    private static $valid_get_types = [
        'has_social_login', 'is_unique', 'get', 'search'
    ];

    private static $valid_post_types = [
        'registration', 'beta_interest', 'create_social_login'
    ];

    public function before()
    {
        parent::before();
        if(parent::logged_in())
        {
            static::$model = Mongo_Document::factory('user', null, static::$user_id);
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
        $id = $this->request->param('id');

        $type = Arr::get(static::$params, 'request_type');

        if (in_array($type, static::$valid_get_types) && method_exists($this, $type))
            return $this->{$type}();

        if ($id)
            return $this->get($id);
        else
            return $this->search();
    }

    /**
     * Changes to user settings, notifications, profile info etc will use
     * this update.
     */
    public function action_update()
    {
        // get the type of update
        $type = Arr::get(static::$params, 'request_type');

        if (!$type)
            return $this->error('invalid_request');

        if (in_array($type, static::$valid_put_types) && method_exists($this, $type))
            return $this->{$type}();

        return $this->error('invalid_request');
    }

    /**
     * Creates a new user with the given params.
     */
    public function action_create()
    {
        // get the type of create
        $type = Arr::get(static::$params, 'request_type');

        if (!$type)
            return $this->error('invalid_request');

        if (in_array($type, static::$valid_post_types) && method_exists($this, $type))
            return $this->{$type}();

        return $this->error('invalid_request');
    }

    /**
     * Changes the state of a user to terminated. No real delete in the api.
     * Also checks if logged in user has authority to do so.
     */
    public function action_delete()
    {

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

        $data = Mongo_Collection('interaction')->subscribers(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves an array of [user_ids and user names] of people who like this user
     */
    public function action_subscribers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'user';

        $data = Mongo_Collection('interaction')->likers(static::$params);

        return $this->data($data);
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
        // either an admin is logged in or this is a client_credentials grant_type
        if((static::$user_id && static::$user_id === $id) || parent::has_role('admin') || parent::is_client())
        {
            // retrieve user privacy
            if(static::is_GET())
            {
                $user = static::$model;
                $user->load($id, [$retrieve_field => 1]);
                $this->data($user->stringify($user->as_array()));
            }
            // update user privacy
            if(static::is_PUT())
            {
                $this->data($this->{$update_method}());
            }
        }

        // no way to validate this request.
        $this->error('access_denied');
        return true;
    }

    /**
     * This handles both retrieval and updating of a user's privacy settings
     */
    public function action_privacy()
    {
        return $this->validate_current_request('update_privacy', 'settings.privacy');
    }

    /**
     * This handles both retrieval and updating of a user's notifications settings
     */
    public function action_notifications()
    {
        return $this->validate_current_request('update_notifications', 'settings.notifications');
    }

    /**
     * This handles both retrieval and updating of a user's info
     */
    public function action_info()
    {
        return $this->validate_current_request('update_info', 'info');
    }

    /**
     * This handles both retrieval and updating of a user's avatar
     */
    public function avatar()
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
            return $this->fail('register/user', 'username.' . $type . '_not_available');

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
        return static::$model->load(['logins.type' => $network, '_id' => static::$user_id], ['logins'])
                ? static::$model->as_array()
                : false;
    }

    /**
     * Performs a search/sort of users.
     *
     * @return array of users
     */
    protected function search()
    {
        $users = Mongo_Collection::factory('user')->search(static::$params);

        return $this->data($users);
    }

    /**************************************************************************
     * Protected Utility Actions - $_POSTs
     *************************************************************************/

    /**
     * New user registration. The user is not active by default and can be
     * activated manually or by an automatic paypal redirect.
     */
    protected function registration()
    {
        // validation rules for a regular user.
        $validation = Validation::factory(static::$params)
                ->rule('first_name', 'not_empty')
                ->rule('last_name', 'alpha')
                ->rule('number.mobile', 'not_empty')
                ->rule('gender', 'alpha')
                ->rule('password', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain')
                ->rule('birthday', 'numeric');

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
            static::$params['roles'] = ['agent'];
            static::$params['status'] = 'pending';
            unset(static::$params['user_type']);

            if ($u = static::$model->create(static::$params))
            {
                unset($u['password']);
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
                ->rule('number.mobile', 'not_empty')
                ->rule('user_type', 'not_empty')
                ->rule('display_name', 'not_empty')
                ->rule('email', 'email')
                ->rule('email', 'email_domain');

        if($validation->check())
        {
            static::$params['interested'] = 1;
            if ($user->new_beta_user(static::$params))
            {
                $r = array('email' => static::$params['email'], 'name' => static::$params['display_name'], 'number.mobile' => static::$params['number.mobile']);
                $this->admin_email('New User Joined Beta!! Woots', print_r(static::$params, TRUE));
                $this->user_email($r, 'We received your submission', Kohana::message('register/info', 'thank_you_beta_email'));

                $this->send_sms('Thank you for you submission', static::$params['number.mobile']);

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

    /**
     * The official Client should be able to send SMSes too, although this really
     * shouldn't be publically known ... :-(
     *
     * @param string $message
     * @param string $number
     * @return type
     */
    protected function send_sms($message, $to = NULL)
    {
        $id = $this->request->params('id');
        if(!$to)
        {
            if(!$to = Arr::get(static::$params['to']))
            {
                static::$model->load($id, ['info.telephone' => 1]);
                $result = static::$model->as_array();
                $to = $result['mobile'];
            }
        }
        $this->return = SMS::factory($to, $message);
        return;

    }

    /**************************************************************************
     * Protected Utility Actions - $_PUTs
     *************************************************************************/

    /**
     * Performs a password reset. Front-end app must authenticate user first
     * using a API sms call
     */
    protected function password_reset()
    {
        if(!parent::logged_in())
            return $this->error('access_denied');

        if(!isset(static::$params['password']) || empty(static::$params['password']))
            return $this->error('access_denied');

        $id = $this->param->user('id');
        $user = static::$model->update_password(static::$params['password'], $id);

        return $user ? $this->data(['success' => 1]) : $this->error('access_denied');
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
        $id = $this->request->params('id');
        static::$model->load_values(['_id' => new MongoID($id)]);
        static::$model->status = 'active';
        static::$model->save();
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

        if($validation->check())
        {
            $this->data(static::$model->update_info(static::$params, static::$user_id));
        }
        else
        {
            $this->errors = $validation->errors('user/info');
            $this->fail();
        }

        return true;
    }

    /**
     * Update a user's privacuy settings.
     *
     * @return boolean
     */
    protected function update_privacy()
    {
        $validation = Validation::factory(static::$params)
                ->rule('contact', 'Controller_User::valid_privacy')
                ->rule('subscriptions', 'Controller_User::valid_privacy')
                ->rule('messages', 'Controller_User::valid_privacy')
                ->rule('mentions', 'Controller_User::valid_privacy')
                ->rule('profile.others_posts', 'Controller_User::valid_privacy')
                ->rule('profile.others_posts_visibility', 'Controller_User::valid_privacy');

        if($validation->check())
        {
            $this->data(static::$model->update_privacy(static::$params, static::$user_id));
        }
        else
        {
            $this->errors = $validation->errors('user/privacy');
            $this->fail();
        }

        return true;
    }

    /**
     * Update a user's notification settings.
     *
     * @return boolean
     */
    protected function update_notifications()
    {
        $validation = Validation::factory(static::$params)
                ->rule('email', 'email')
                ->rule('email', 'email_domain')
                ->rule('likers.own_post', 'range', [':value', 0, 1])
                ->rule('likers.other_post', 'range', [':value', 0, 1])
                ->rule('likers.profile', 'range', [':value', 0, 1])
                ->rule('subscribers.own_post', 'range', [':value', 0, 1])
                ->rule('subscribers.other_post', 'range', [':value', 0, 1])
                ->rule('subscribers.profile', 'range', [':value', 0, 1])
                ->rule('comments.own_post', 'range', [':value', 0, 1])
                ->rule('comments.other_post', 'range', [':value', 0, 1])
                ->rule('mentions', 'range', [':value', 0, 1])
                ->rule('testimonials', 'range', [':value', 0, 1])
                ->rule('wallpost', 'range', [':value', 0, 1])
                ->rule('messages', 'range', [':value', 0, 1]);

        if($validation->check())
        {
            $this->data(static::$model->update_notifications(static::$params, static::$user_id));
        }
        else
        {
            $this->errors = $validation->errors('user/notifications');
            $this->fail();
        }

        return true;

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
     * Sends a user an email. Normally for registrations purposes
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    protected static function user_email($recipient, $subject, $message)
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

    /**
     * Small validation for possible privacy settings
     *
     * @param string $value
     * @return boolean
     */
    protected static function valid_privacy($value)
    {
        return in_array($value, ['public', 'subscribers', 'private']);
    }

}