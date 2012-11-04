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
class Controller_Post extends Controller_Resource {

    private static $valid_post_types = [
        'create_testimonial', 'create_listing', 'create_post', 'update_privacy'
    ];

    public function before()
    {
        parent::before();
        if(parent::logged_in())
        {
            static::$model = Mongo_Document::factory('post', null, static::$user_id);
        }
        else
        {
            static::$model = Mongo_Document::factory('post');
        }
    }

    /**************************************************************************
     * Default Actions
     *************************************************************************/

    /**
     * Fetch a post by id.
     * Check if there is a logged in user
     * Fetch requested post and check relation to logged_in user
     *
     * GET post
     * Usage: Issue a GET to /post/index/{id}
     *
     * @return json_encoded array
     */
    public function action_index()
    {
        // get the id
        $id = $this->request->param('id');

        if ($id)
            return $this->data(static::$model->get($id));

        return $this->error('invalid_request');
    }

    /**
     * Changes to post settings or content and post creation will use this update.
     */
    public function action_update()
    {
        // get the type of request
        $type = Arr::get(static::$params, 'request_type');

        // no type. we need a type
        if (!$type)
            $this->error('invalid_request');

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_post_types) && method_exists($this, $type))
            return $this->{$type}();

        // nope, return an error
        return $this->error('invalid_request');
    }

    /**************************************************************************
     * Public Connections
     *************************************************************************/

     /**
     * Retrieves an array of [user_ids and user names] of people who like this post
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
     * Retrieves an array of [user_ids and user names] of people who are subscribed to this post
     */
    public function action_subscribers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'post';

        $data = Mongo_Document::factory('interaction')
                ->collection()
                ->subscribers(static::$params);

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
        // an admin is logged in or this is a client_credentials grant_type
        Debug::log('is_admin :' . parent::has_role('admin'));
        Debug::log('is_client :' . parent::is_client());
        Debug::log('is_user :' . static::$user_id && static::$user_id === $id);
        if((static::$user_id && static::$user_id === $id) || parent::has_role('admin') || parent::is_client())
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

    /**************************************************************************
     * Public Actions
     *************************************************************************/

    /**
     * Performs a like on the given post
     */
    public function action_like()
    {
        return parent::interact('like', 'post');
    }

    /**
     * Performs an unlike on the given post
     */
    public function action_unlike()
    {
        return parent::interact('unlike', 'post');
    }

    /**
     * Performs a subscribe on the given post
     */
    public function action_subscribe()
    {
        return parent::interact('subscribe', 'post');
    }

    /**
     * Performs an unsubscribe on the given post
     */
    public function action_unsubscribe()
    {
        return parent::interact('unsubscribe', 'post');
    }


    /**************************************************************************
     * Protected Utility Actions - $_GETs
     *************************************************************************/
    /**
     * Performs a search/sort of users.
     *
     * @return array of users
     */
    protected function search()
    {
        if(parent::logged_in())
            $users = Mongo_Collection::factory('post')->search(static::$params, static::$user_id);
        else
            $users = Mongo_Collection::factory('post')->search(static::$params);

        return $this->data($users);
    }

    /**************************************************************************
     * Protected Actions - CREATES
     *************************************************************************/


    protected function create_testimonial()
    {

    }


    protected function create_listing()
    {

    }


    protected function create_post()
    {

    }

    /*****************************************************"*********************
     * Protected Actions - UPDATES
     *************************************************************************/

    /**
     * Update a listing
     */
    protected function update_listing()
    {
        $id = $this->request->params('id');
        static::$model->load_values(['_id' => new MongoID($id)]);
        static::$model->status = 'active';
        static::$model->save();
        $this->data(['status' => 1]);
    }

    protected function update_testimonial()
    {

    }

    protected function update_post()
    {
        
    }

    /**
     * Update a user's privacuy settings.
     *
     * @return boolean
     */
    protected function update_privacy()
    {
        $params = static::extract_privacy_values(static::$params);
        $validation = Validation::factory($params)
                ->rule('contacts.is', 'Controller_User::valid_privacy', [':value', ':field', ':validation'])
                ->rule('contacts.is', 'not_empty')
                ->rule('subscriptions.is', 'Controller_User::valid_privacy', [':value', ':field', ':validation'])
                ->rule('subscriptions.is', 'not_empty')
                ->rule('messages.is', 'Controller_User::valid_privacy', [':value', ':field', ':validation'])
                ->rule('messages.is', 'not_empty')
                ->rule('mentions.is', 'Controller_User::valid_privacy', [':value', ':field', ':validation'])
                ->rule('mentions.is', 'not_empty')
                ->rule('profile.others_posts.is', 'Controller_User::valid_privacy', [':value', ':field', ':validation'])
                ->rule('profile.others_posts.is', 'not_empty')
                ->rule('profile.others_posts_visibility.is', 'Controller_User::valid_privacy', [':value', ':field', ':validation'])
                ->rule('profile.others_posts_visibility.is', 'not_empty');

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

    /**************************************************************************
     * Protected STATIC Utility Methods
     *************************************************************************/

    /**
     * Small validation for possible privacy settings
     *
     * @param string $value
     * @return boolean
     */
    public static function valid_privacy($value, $field, $validation)
    {
        $privacy = ['PUBLIC', 'SUBSCRIBERS', 'SELF', 'CUSTOM'];

        return in_array($value, $privacy) ? TRUE : $validation->error($field, 'valid_privacy');

    }


    protected static function extract_creation_values($array)
    {
        $return = [];

        $return['info.gender'] = Arr::path($array, 'info.gender');
        $return['info.telephone.mobile'] = Arr::path($array, 'info.telephone.mobile');
        $return['info.birthday'] = Arr::path($array, 'info.birthday');


        return Arr::merge($return, $array);
    }
}