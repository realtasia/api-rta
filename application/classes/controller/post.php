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
        'find_unique'
    ];

    private static $valid_post_types = [
        'create_testimonial', 'create_listing', 'create_post', 'create_conversation',
        'update_privacy', 'update_testimonial', 'update_listing', 'update_post',
        'create_album', 'create_comment', 'create_message',
        'delete_comment', 'create_wallpost'
    ];

    public function before()
    {
        parent::before();
        if (parent::logged_in())
        {
            static::$model = Document::factory('post', null, static::$user_id);
        }
        else
        {
            static::$model = Document::factory('post');
        }
    }

    /**************************************************************************
     * Default Actions
     ************************************************************************ */

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
        if ($type)
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

    /*     * ************************************************************************
     * Private Actions
     * *********************************************************************** */
    /**
     * Retrieves a post.
     *  - Checks if post owner is current user.
     *  - Checks if page itself is the current user.
     *  - Checks if page owner is current user
     *  - Checks if admin user.
     *
     *  If any thing checks out, we delete the post
     */
    public function action_delete()
    {
        $post   = Document::factory('post', $this->request->param('id'));

        // We check the post user
        if ($post->load(null, array_fill_keys(['user','counts','page','page_parent','to','type'], 1)))
        {
            $post_user  = (string) $post->user['_id'];
            $page       = (string) $post->page['_id'];
            $is_owner   = static::logged_in_is_id($post_user);
            $page_is_cu = static::logged_in_is_id($page);

            if ($is_owner || $page_is_cu || parent::has_role('admin'))
            {
                if($post->delete())
                {
                    return $this->data(['success' => 1]);
                }
             }

            $page = Document::factory('page', $page);
            $page->load(null, ['user' => 1]);

            // now we check if the owner of the page is the current user
            if ($page->loaded())
            {
                $page_user = (string) $page->user['_id'];
                if (static::$user_id === $page_user)
                {
                    if($post->delete())
                    {
                        return $this->data(['success' => 1]);
                    }
                }
            }
        }

        return $this->error('access_denied');
    }

    /**
     * Set or get the privacy of a post
     */
    public function action_privacy()
    {
        $id = $this->request->param('id');
        $post = Document::factory('post', $id);
        $post->load([], ['user' => 1, 'privacy' => 1]);

        if ($this->is_GET() && (static::logged_in_is_id($post->user['_id']) || parent::has_role('admin')))
        {
            return $this->data(
                    $post->stringify($post->as_array()['privacy'])
            );
        }
        else
        {
            if(static::logged_in_is_id($post->user['_id']) || parent::has_role('admin'))
            {
                // update user by his/her specific field
                if($this->is_POST())
                {
                    $this->update_privacy();
                }
                return true;
            }
        }
        return $this->error('access_denied');
    }

    /**
     * Creates a comment for a given post
     *
     * @return json data
     */
    public function action_comment()
    {
        $params['message'] = nl2br(Security::xss_clean(static::$params['message']));
        $params['type']    = 'comment';
        $params['status']  = static::$params['status'];
        $params['user']    = static::$params['user'];
        $params['post']    = static::$params['post'];

        $c = Document::factory('comment', null, static::$user_id)
                ->create($params);
        if ($c)
        {
            Job::add($params['post']['_id'], 'post', static::$user_id)
                    ->comment($c->_id);
            $ret = $c->as_array();
            $ret['created'] = $ret['_id']->getTimestamp();

            return $this->data($c->stringify($ret));
        }

        return $this->error('server_error');
    }


    /*     * ************************************************************************
     * Public Connections
     * *********************************************************************** */

    /**
     * Retrieves an array of [user_ids and user names] of people who like this post
     */
    public function action_likers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'comment';

        $data = Document::factory('interaction')
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

        $data = Document::factory('interaction')
                ->collection()
                ->subscribers(static::$params);

        return $this->data($data);
    }

    /**************************************************************************
     * Public Actions
     ************************************************************************ */

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
     ************************************************************************ */

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
            if ($user_id = Arr::get(static::$params, 'user_id'))
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
     ************************************************************************ */

    /**
     * A conversation is a special post type with no message. It
     * has to contain a user and maybe a list of to[ids]. It can also be one
     * to[_id]. Either way, this post object has very little information and
     * normally depends on it's containing messages (comments) for content.
     *
     */
    protected function create_conversation()
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['user']['_id'] = $id ? $id : static::$user_id;

        $validation = Validation::factory(static::$params)
                ->rule('to', 'not_empty')
                ->rule('to', 'is_array')
                ->rule('message', 'not_empty');

        $defaults = [
            'type'   => 'conversation', 'status' => 'active'
        ];
        if ($validation->check())
        {
            static::$params['message'] = nl2br(HTML::chars(static::$params['message']));
            if ($comment = static::$model->create_conversation(Arr::merge($defaults, static::$params)))
            {
                Job::add($comment->post['_id'], 'post', static::$user_id)
                        ->private_message($comment->_id);

                return $this->data($comment->stringify($comment->as_array()));
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/conversation');
        return $this->fail();
    }

    /**
     * Testimonials are very similar to regular posts accept that this method
     * sets the type to testimonial and ensures that the default status is pending
     * unless this is an update.
     *
     * Upon successful creation,
     *   - increase the receipient count for testimonials received.
     *   - increase the writer's count for testimonials written.
     *
     * @return json array
     */
    protected function create_testimonial($update = FALSE)
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['privacy.is'] = Arr::path(static::$params, 'privacy.is');
        $validation = Validation::factory(static::$params)
                ->rule('message', 'not_empty')
                ->rule('privacy.is', 'Privacy::valid', [
                    ':value', ':field', ':validation'
                ])
                ->rule('page', 'not_empty')
                ->rule('page', 'Controller_User::valid', [':value', ':field', ':validation'])
                ->rule('page_parent', 'Controller_Page::valid', [':value', ':field', ':validation']);

        $defaults = [
            'type'   => 'testimonial', 'status' => 'pending'
        ];
        if ($validation->check())
        {
            static::$params['message'] = nl2br(Security::xss_clean(static::$params['message']));

            $params = Arr::merge(static::$params, $defaults);

            if ($update && isset(static::$params['status']))
            {
                $params['status'] = static::$params['status'];
            }

            // this is a little different. we merge params with defaults to force defaults
            if ($post = static::$model->create($params))
            {
                if ($update && $params['status'] === 'active')
                {
                    Job::add($post->_id, 'post')
                            ->approve_testimonial($post->user['_id']);
                }
                else
                {
                    Job::add($post->_id, 'post', static::$user_id)
                            ->testimonial($post->page['_id']);
                }
                $post = $post->stringify($post->as_array());
                return $this->data($post);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/post');
        return $this->fail();
    }

    /**
     * Listing has a lot more validation rules.
     *
     * @return json array
     */
    protected function create_listing($update = FALSE)
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['user']['_id'] = $id ? $id : static::$user_id;

        $params               = static::extract_listing_values(static::$params);
        $params['privacy.is'] = Arr::path($params, 'privacy.is');

        $validation = Validation::factory($params)
                ->rule('privacy.is', 'Privacy::valid', [
                    ':value', ':field', ':validation'
                ])
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
                ->rule('page', 'not_empty')
                ->rule('page', 'Controller_Page::valid_page', [':value', ':field', ':validation'])
                ->rule('page_parent', 'Controller_Page::valid_parent', [':value', ':field', ':validation']);

        $defaults = [
            'type'   => 'listing', 'status' => 'active'
        ];
        if ($validation->check())
        {
            if ($post = static::$model->create(Arr::merge($defaults, static::$params)))
            {
                static::creator_interactions($post->_id, 'post');
                $post = $post->stringify($post->as_array());
                $post['counts']['subscribers'] = 1;
                return $this->data($post);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/listing');
        return $this->fail();
    }

    /**
     * Creates a wallpost. Uses the user controller to validate a page if
     * present. A page parent is optional, but a page is needed so we know
     * where this post was created - in this case it is a user.
     *
     * @uses Controller_Page
     *
     * @return json array
     */
    protected function create_wallpost()
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['privacy.is'] = Arr::path(static::$params, 'privacy.is');

        $validation = Validation::factory(static::$params)
                ->rule('message', 'not_empty')
                ->rule('privacy.is', 'Privacy::valid', [
                    ':value', ':field', ':validation'
                ])
                ->rule('page', 'not_empty')
                ->rule('page', 'Controller_User::valid', [':value', ':field', ':validation'])
                ->rule('page_parent', 'Controller_Page::valid', [':value', ':field', ':validation']);

        $defaults = [
            'type'   => 'wallpost', 'status' => 'active'
        ];
        if ($validation->check())
        {
            static::$params['message'] = nl2br(Security::xss_clean(static::$params['message']));
            // this is a little different. we merge params with defaults to force defaults
            if ($post = static::$model->create(Arr::merge(static::$params, $defaults)))
            {
                Job::add($post->_id, 'post', static::$user_id)
                        ->wallpost($post->page['_id']);

                static::creator_interactions($post->_id, 'post');
                $post = $post->stringify($post->as_array());
                $post['counts']['subscribers'] = 1;
                return $this->data($post);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/post');
        return $this->fail();
    }

    /**
     * Creates a regular post. Uses the page controller to validate a page if
     * present. A page parent is optional, but a page is needed so we know
     * where this post was created.
     *
     * @uses Controller_Page
     *
     * @return json array
     */
    protected function create_post()
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['user']['_id'] = $id ? $id : static::$user_id;

        static::$params['privacy.is'] = Arr::path(static::$params, 'privacy.is');

        $validation = Validation::factory(static::$params)
                ->rule('message', 'not_empty')
                ->rule('privacy.is', 'Privacy::valid', [
                    ':value', ':field', ':validation'
                ])
                ->rule('page', 'not_empty')
                ->rule('page', 'Controller_Page::valid', [':value', ':field', ':validation'])
                ->rule('page_parent', 'Controller_Page::valid', [':value', ':field', ':validation']);

        $defaults = [
            'type'   => 'wallpost', 'status' => 'active'
        ];
        if ($validation->check())
        {
            static::$params['message'] = nl2br(Security::xss_clean(static::$params['message']));

            if ($post = static::$model->create(Arr::merge($defaults, static::$params)))
            {
                static::creator_interactions($post->_id, 'post');
                $post = $post->stringify($post->as_array());
                $post['counts']['subscribers'] = 1;
                return $this->data($post);
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/post');
        return $this->fail();
    }

    /**
     * Creates a message for a given conversation
     *
     * @return json data
     */
    protected function create_message()
    {
        $params['post']['_id']  = $this->request->param('id');
        $params['post']['type'] = static::$params['post_type'];

        $params['message'] = nl2br(HTML::chars(static::$params['message']));

        $c = Document::factory('comment', null, static::$user_id)
                ->create_message($params);
        if ($c)
        {
            Job::add($params['post']['_id'], 'post', static::$user_id)
                    ->private_message($c['_id']);

            return $this->data($c);
        }

        return $this->error('server_error');
    }

    /*****************************************************"*********************
     * Protected Actions - UPDATES
     ************************************************************************ */

    /**
     * Update a listing. Checks if there is an id and calls the create_listing
     * method
     */
    protected function update_listing()
    {
        $post_id = Arr::get(static::$params, '_id');

        if (!$post_id)
            return $this->error('invalid_request');

        // the create post method in the model checks to see if this is an update.
        return $this->create_listing(TRUE);
    }

    /**
     * Update a testimonial. Checks if there is an id and calls the create_testimonial
     * method
     */
    protected function update_testimonial()
    {
        $post_id = Arr::get(static::$params, '_id');

        if (!$post_id)
            return $this->error('invalid_request');

        // the create post method in the model checks to see if this is an update.
        return $this->create_testimonial(TRUE);
    }

    /**
     * Update a post. Checks if there is an id and calls the create_post
     * method
     */
    protected function update_post()
    {
        $post_id = Arr::get(static::$params, '_id');

        if (!$post_id)
            return $this->error('invalid_request');

        // the create post method in the model checks to see if this is an update.
        return $this->create_post(TRUE);
    }

    /**
     * Update a post's privacuy settings.
     *
     * @return boolean
     */
    protected function update_privacy()
    {
        $privacy = Arr::path(static::$params, 'privacy.is');
        if(!in_array($privacy, ['PUBLIC', 'PRIVATE']))
        {
            $this->errors = ['privacy' => "We do not recognise this value"];
            return $this->fail();
        }

        $d = Document::factory('post', $this->request->param('id'));
        $d->set('privacy.is', $privacy);
        if($d->save())
        {
            return $this->data(['success' => 1]);
        }

        return $this->error('invalid_request');
    }

    /**************************************************************************
     * Protected STATIC Utility Methods
     ************************************************************************ */

    /**
     * Small validation for property types
     *
     * @param string $value
     * @return boolean
     */
    public static function valid_property_type($value, $field, $validation)
    {
        if (!is_array($value))
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

    protected static function creator_interactions($id, $objtype)
    {
        $i = Document::factory('interaction', null, static::$user_id);
        $i->subscribe($id, $objtype);
    }

}