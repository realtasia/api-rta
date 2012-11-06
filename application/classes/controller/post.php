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

    private static $valid_get_types = [
        'find_unique', 'search_listings', 'search_testimonials'
    ];

    private static $valid_post_types = [
        'create_testimonial', 'create_listing', 'create_post', 'update_privacy',
        'update_testimonial', 'update_listing', 'update_post'
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
     * Fetch a private GET action or fail if there is one and no method for it
     * Or fetch a post by id.
     * Check if there is a logged in user
     * Fetch requested post and check relation to logged_in user
     *
     * GET post
     * Usage: Issue a GET to /post/index/{id}
     *
     * @return json data
     */
    public function action_index()
    {
        // we retrieve the request_type from params
        $type = Arr::get(static::$params, 'request_type', false);

        // if we have a type
        if($type)
        {
            // we check if it is valid and the method exists
            if (in_array($type, static::$valid_get_types) && method_exists($this, $type))
                // run it
                return $this->{$type}();
            // ok, why do we have a type and not a method???
            else
                // yeap, you guessed it.
                return $this->fail('user/generic', 'server_error');
        }

        // try id
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
     * Performs a search/sort of posts.
     *
     * @return array of posts
     */
    protected function search()
    {
        if(parent::logged_in())
            $users = Mongo_Collection::factory('post')->search(static::$params, static::$user_id);
        else
            $users = Mongo_Collection::factory('post')->search(static::$params);

        return $this->data($users);
    }

    /**
     * This method searches the DB for a duplicate listing. If it finds one,
     * issues a fail with a message and the listing id. If the required params
     * are not available, issues a fail with message.
     * If none is found, issues a success.
     *
     * Required GET params [unit, not_empty, listing_type, page_id]
     *
     * @todo Only logged in user is checked. What about admins & client credentials?
     *
     * @return json data
     */
    protected function find_unique()
    {
        $validation = Validation::factory(static::$params)
                      ->rule('unit', 'not_empty')
                      ->rule('listing_type', 'not_empty')
                      ->rule('page_id', 'not_empty');

        if ($validation->check())
        {
            if($user_id = Arr::get(static::$params, 'user_id'))
            {
                static::$params['user_id'] = $user_id;
            }
            else
            {
                static::$params['user_id'] = static::$user_id;
            }
            if ($id = static::$model->find_unique(static::$params))
            {
                $this->errors = [
                    'message' => Kohana::message('post/listings', 'listing_exists'),
                    'id'      => $id
                ];
                return $this->fail();
            }

            return $this->data(['success' => 1]);
        }

        $this->errors = $validation->errors('post/listing');
        return $this->fail();
    }


    /**************************************************************************
     * Protected Actions - CREATES
     *************************************************************************/


    protected function create_testimonial()
    {
        $validation = Validation::factory(static::$params)
                ->rule('message', 'not_empty')
                ->rule('page', 'Controller_Post::valid_page', [':value', ':field', ':validation'])
                ->rule('page_parent', 'Controller_Post::valid_page', [':value', ':field', ':validation']);

        $defaults = [
            'type'   => 'testimonial', 'status' => 'pending'
        ];
        if ($validation->check())
        {
            // this is a little different. we merge params with defaults to force defaults
            if ($post = static::$model->create(Arr::merge(static::$params, $defaults)))
            {
                return $this->data($post);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('user/privacy');
        return $this->fail();
    }


    protected function create_listing()
    {
        $params = static::extract_listing_values(static::$params);
        $validation = Validation::factory($params)
                ->rule('listing.type', 'not_empty')
                ->rule('listing.type', 'in_array', [':value', ['rent', 'sale']])
                ->rule('listing.price', 'not_empty')
                ->rule('listing.price', 'numeric')
                ->rule('listing.rooms', 'not_empty')
                ->rule('listing.rooms', 'numeric')
                ->rule('listing.built_size', 'not_empty')
                ->rule('listing.built_size', 'numeric')
                ->rule('listing.built_size', 'not_empty')
                ->rule('listing.property_type', 'not_empty')
                ->rule('listing.property_type', 'Controller_Post::valid_property_type', [':value', ':field', ':validation'])
                ->rule('listing.land_size', 'numeric')
                ->rule('listing.unit', 'not_empty')
                ->rule('status', 'not_empty')
                ->rule('page', 'Controller_Page::valid_page', [':value', ':field', ':validation'])
                ->rule('page_parent', 'Controller_Page::valid_parent', [':value', ':field', ':validation']);

        $defaults = [
            'type'   => 'listing', 'status' => 'active'
        ];
        if ($validation->check())
        {
            if ($post = static::$model->create(Arr::merge($defaults, static::$params)))
            {
                return $this->data($post);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/listing');
        return $this->fail();
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

    }

    protected function update_testimonial()
    {

    }

    protected function update_post()
    {

    }

    /**
     * Update a post's privacuy settings.
     *
     * @return boolean
     */
    protected function update_privacy()
    {
        $params = static::extract_privacy_values(static::$params);
        $validation = Validation::factory($params)
                ->rule('is', 'Controller_User::valid_privacy', [':value', ':field', ':validation']);

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
     * Small validation for property types
     *
     * @param string $value
     * @return boolean
     */
    public static function valid_property_type($value, $field, $validation)
    {
        if(!is_array($value))
        {
            $validation->error($field, 'prop_type.not_array');
            return false;
        }

        foreach ($value as $location => $type)
        {
            switch ($location)
            {
                case 'HDB':
                    $types = [
                        '2I', '2S (Standard)', '3A', '3A Modified', '3NG', '3I',
                        '3I Modified', '3S (Simplified)', '3STD (Standard)',
                        '4A', '4NG', '4S (Simplified)', '4I', '4STD (Standard)',
                        '5A', '5I', '5S', 'EA', 'EM', 'Other'
                    ];

                return in_array($type, $types) ? TRUE : $validation->error($field, 'prop_type.hdb_not_valid');

                case 'Private Residential':
                case 'Serviced Residential':
                    $types = [ 'Condominium', 'Apartment', 'Walkup', 'Townhouse'];
                return in_array($type, $types) ? TRUE : $validation->error($field, 'prop_type.apt_not_valid');

                case 'Private Landed':
                    $types = [ 'Bungalow', 'Bungalow (Good Class)', 'Detached', 'Semi-Detached', 'Terraced', 'Corner-Terrace'];
                return in_array($type, $types) ? TRUE : $validation->error($field, 'prop_type.landed_not_valid');
            }

        }
    }


    /**
     * Creates proper keys for validation. Because params (for Mongo) include
     * sub arrays. But Kohana & Community decided that it should not validate
     * sub arrays. I see their point, but I still think they're full of shit.
     *
     * @see http://forum.kohanaframework.org/discussion/1338/validation-and-sub-arrays/p1
     *
     * @param array $array
     * @return type
     */
    protected static function extract_listing_values(array $array)
    {
        $return = [];

        $return['listing.type']          = Arr::path($array, 'listing.type');
        $return['listing.price']         = Arr::path($array, 'listing.price');
        $return['listing.rooms']         = Arr::path($array, 'listing.rooms');
        $return['listing.built_size']    = Arr::path($array, 'listing.price');
        $return['listing.property_type'] = Arr::path($array, 'listing.property_type');
        $return['listing.land_size']     = Arr::path($array, 'listing.land_size');
        $return['listing.unit']          = Arr::path($array, 'listing.unit');


        return Arr::merge($return, $array);
    }
}