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

    private static $valid_get_types  = [
        'find_unique'
    ];
    private static $valid_post_types = [
        'create_testimonial', 'create_listing', 'create_post', 'create_conversation',
        'update_privacy', 'update_testimonial', 'update_listing', 'update_post', 
        'create_album', 'update_album', 'create_comment', 'create_message',
        'delete_comment', 'create_wallpost', 'create_attachment'
    ];
    
    protected static $bucket = 'static2.realtasia.com.sg';

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

    /*     * ************************************************************************
     * Default Actions
     * *********************************************************************** */

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
        $post = Document::factory('post', $this->request->param('id'));

        // We check the post user
        if ($post->load(null, array_fill_keys(['user', 'counts', 'page', 'page_parent', 'to', 'type', 'picture', 'link'], 1)))
        {
            $post_user  = (string) $post->user['_id'];
            $page       = (string) $post->page['_id'];
            $is_owner   = static::logged_in_is_id($post_user);
            $page_is_cu = static::logged_in_is_id($page);
            
            if ($post->type === 'attachment')
            {
                $attachment = $post->as_array();
            }
            
            if ($post->type === 'album')
            {
                $album = $post->as_array();
            }
            
            if ($is_owner || $page_is_cu || parent::has_role('admin'))
            {
                if ($post->delete())
                {
                    if($attachment)
                    {
                        $this->delete_attachment($attachment);
                    }
                    if($album)
                    {
                        $this->delete_attachments($album);
                    }     
                    
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
                    if ($post->delete())
                    {
                        if($attachment)
                        {
                            $this->delete_attachment($attachment);
                        }
                        if($album)
                        {
                            $this->delete_attachments($album);
                        }  
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
        $id   = $this->request->param('id');
        $post = Document::factory('post', $id);
        $post->load([], ['user'    => 1, 'privacy' => 1]);

        if ($this->is_GET() && (static::logged_in_is_id($post->user['_id']) || parent::has_role('admin')))
        {
            return $this->data($post->stringify($post->as_array()['privacy'])
            );
        }
        else
        {
            if (static::logged_in_is_id($post->user['_id']) || parent::has_role('admin'))
            {
                // update user by his/her specific field
                if ($this->is_POST())
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
        $params           = static::format(static::$params, 66, 100);
        $params['type']   = 'comment';
        $params['status'] = static::$params['status'];
        $params['user']   = static::$params['user'];
        $params['post']   = static::$params['post'];

        $c = Document::factory('comment', null, static::$user_id)
                ->create($params);
        if ($c)
        {
            Job::add($params['post']['_id'], 'post', static::$user_id)
                    ->comment($c->_id);
            $ret            = $c->as_array();
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

    /*     * ************************************************************************
     * Public Actions
     * *********************************************************************** */

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


    public function action_attachments()
    {
        $id = $this->request->param('id');

        $q = Arr::merge(static::$params, ['type' => 'attachment', 'page_id' => $id]);

        return $this->data(Collection::factory('post')->search($q, static::$user_id));
    }

    /*     * ************************************************************************
     * Protected Utility Actions - $_GETs
     * *********************************************************************** */

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

    /*     * ************************************************************************
     * Protected Actions - CREATES
     * *********************************************************************** */

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
            static::$params = static::format(static::$params);

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
                $post                          = $post->stringify($post->as_array());
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

        static::$params['page']['type'] = 'user';

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
            static::$params = static::format(static::$params);
            // this is a little different. we merge params with defaults to force defaults
            if ($post = static::$model->create(Arr::merge(static::$params, $defaults)))
            {
                Job::add($post->_id, 'post', static::$user_id)
                        ->wallpost($post->page['_id']);

                static::creator_interactions($post->_id, 'post');
                $post                          = $post->stringify($post->as_array());
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
            static::$params = static::format(static::$params);

            if ($post = static::$model->create(Arr::merge($defaults, static::$params)))
            {
                static::creator_interactions($post->_id, 'post');
                $post                          = $post->stringify($post->as_array());
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
    
    /**
     * extracts an album from the given params or creates a new album.
     * then creates an attachment post
     * 
     * @return JSON data
     */
    protected function create_attachment()
    {
        $data     = \Arr::get(static::$params, 'data');
        $user     = (object) $this->get_user();
        $album_id = \Arr::get(static::$params, 'album_id');
        
        if($album_id)
        {
            $a = Document::factory('post', $album_id);
            $a->current_user = $user;
            $a->load();
            $page = ['_id' => $a->_id, 'name' => $a->name, 'type' => $a->type, 'uri' => $a->uri];
        }        
        elseif(Arr::get(static::$params, 'create_album'))
        {
            $a = $this->create_album(true);
            if(!$a)
            {
                return $this->error('invalid_request');
            }
            
            $page = ['_id' => $a->_id, 'name' => $a->name, 'type' => $a->type, 'uri' => $a->uri];
        }
        
        // can be json encoded.
        if(!is_array($data))
        {
            $data = @json_decode($data, true);
        }
        
        if(empty($data))
            return $this->error ('invalid_request');
        
        $data['name']   = trim(Security::xss_clean($data['name']));       
        $data['page'] = $page;
        $data['user'] = ['_id'  => Document::check_id($user->_id),'name' => $user->name];
        $lib = $user->library;
        $lib['_id'] = Document::check_id($lib['_id']);

        $data['page_parent'] = $lib;
        
        $args = \Arr::merge([
            'name'        => '',
            'excerpt'     => '',
            'message'     => '',
            'picture'     => '',
            'page'        => [],
            'user'        => [],
            'page_parent' => [],
            'mediainfo'   => [],
            'subtype'     => '',
            'type'        => 'attachment',
        ], $data);
        
        $attachment     = Document::factory('post');
        $attachment->current_user = $user;
        if($attachment->create_attachment($args))
        {
            $a->set('picture', $attachment->picture);
            $a->save();
            static::creator_interactions($attachment->_id, 'post');
            return $this->data($attachment->stringify($attachment->as_array()));
        }
        
        return $this->error ('invalid_request');
    }

    /**
     * 
     * @param boolean $model whether to return the model data instead. Helpful when creating an attachment.
     * @return mixed false if creation failed, a model or JSON data
     */
    protected function create_album($model = false)
    {
        $params = static::format(Arr::get(static::$params, 'create_album'));
        if(!is_array($params))
        {
            $params = @json_decode($params, TRUE);
        }
        $params['name'] = trim(Security::xss_clean($params['name']));
        $album = Document::factory('post');
        $user  = (object) $this->get_user();
        $album->current_user = $user;
        $lib   = $user->library;
        $lib['_id'] = Document::check_id($lib['_id']);

        $args = \Arr::merge([
                    'name' => '',
                    'excerpt' => '',
                    'message' => '',
                    'user' => ['_id' => Document::check_id($user->_id), 'name' => $user->name],
                    'page' => $lib,
                    'type' => 'album',
        ], $params);

        if ($album->create_album($args))
        {
            static::creator_interactions($album->_id, 'post');
            return $model ? $album : $this->data($album->stringify($album->as_array()));
        }

        return $model ? false : $this->error('invalid_request');
    }
   

    /*     * ***************************************************"*********************
     * Protected Actions - UPDATES
     * *********************************************************************** */

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
     * updates an album and all it's attachments.
     * 
     * @return JSON data
     */
    protected function update_album()
    {
        $id = $this->request->param('id');
        $params = static::format(static::$params);
        $params['name'] = trim(Security::xss_clean($params['name']));
        $user  = (object) $this->get_user();      
        $lib   = $user->library;
        $lib['_id'] = Document::check_id($lib['_id']);        
     
        $args = \Arr::overwrite([
                    'name' => '',
                    'excerpt' => '',
                    'message' => '',
                    'user' => ['_id' => Document::check_id($user->_id), 'name' => $user->name],
                    'privacy' => [],
                    'page' => $lib,
                    'type' => 'album',
        ], $params);
        
        $a = Document::factory('post', $id);
        $a->current_user = $user;          
        $a->load_values($args);
        if($a->save())
        {
            // update all the attachments
            Collection::factory('post')->update(['page._id' => $a->_id], [
                '$set' => ['page.name' => $a->name]
            ]);
            
            return $this->data($a->stringify($a->as_array()));
        }
        
        return $this->error('invalid_request');
    }    
    
    /**
     * Update a post's privacuy settings.
     *
     * @return boolean
     */
    protected function update_privacy()
    {
        $privacy = Arr::path(static::$params, 'privacy.is');
        if (!in_array($privacy, ['PUBLIC', 'PRIVATE']))
        {
            $this->errors = ['message' => "We do not recognise this value"];
            return $this->fail();
        }

        $d = Document::factory('post', $this->request->param('id'));
        $d->set('privacy.is', $privacy);
        if ($d->save())
        {
            return $this->data(['success' => 1]);
        }

        return $this->error('invalid_request');
    }

    /*     * ************************************************************************
     * Protected STATIC Utility Methods
     * *********************************************************************** */

    protected static function delete_attachment($post, $array = false)
    {
        $picture = Arr::get($post, 'picture');
        $source  = Arr::path($post, 'link.source');
        $uid = (string) Arr::get($post['user'], '_id');  
        $objects = [];
        // not sure if basename() can do relative
        if ($picture)
        {
            $file = basename($picture);
            $sizes = [110, 90, 40, 16, 150];     
            foreach($sizes as $size)
            {
                $objects[] = ['Key' => "$uid/$size/$file"];
            }            
        } 
        elseif($source)
        {
            $file = basename($source);
        }

   
        $objects[] = $file ? ['Key' => $uid . '/' . $file] : [];
                
        return $array ? array_filter($objects) : AmazonSDK::s3()->deleteObjects([
            'Bucket' => static::$bucket,
            'Objects' => array_filter($objects),
        ]);
    }

    protected static function delete_attachments($album)
    {
        $objects = $final = [];
        $attaches = Collection::factory('post')
                    ->find(['page._id' => Document::check_id($album['_id'])])
                    ->fields(['user', 'link', 'picture']);
        
        while($attaches->hasNext())
        {
            $objects[] = static::delete_attachment($attaches->getNext()->as_array(), true);
        }
        
        if(!empty($objects))
        {
            $objs = \Arr::flatten_recursive($objects);

            foreach($objs as $v)
            {
                $final[] = ["Key" => $v];
            }

            return AmazonSDK::s3()->deleteObjects([
                'Bucket' => static::$bucket,
                'Objects' => array_filter($final),
            ]);            
        }
        
        return true;
    }
    
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
     * Creates proper keys for validation because params (for Mongo) include
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

    /**
     *  UrlLinker - facilitates turning plain text URLs into HTML links.
     *
     *  Author: Søren Løvborg
     *
     *  To the extent possible under law, Søren Løvborg has waived all copyright
     *  and related or neighboring rights to UrlLinker.
     *  http://creativecommons.org/publicdomain/zero/1.0/
     */
    /*
     *  Regular expression bits used by htmlEscapeAndLinkUrls() to match URLs.
     */
    protected static function extract_links($text)
    {
        /**
         *  $validTlds is an associative array mapping valid TLDs to the value true.
         *  Since the set of valid TLDs is not static, this array should be updated
         *  from time to time.
         *
         *  List source:  http://data.iana.org/TLD/tlds-alpha-by-domain.txt
         *  Last updated: 2012-09-06
         */
        $validTlds = array_fill_keys(explode(" ", ".ac .ad .ae .aero .af .ag .ai .al .am .an .ao .aq .ar .arpa .as .asia .at .au .aw .ax .az .ba .bb .bd .be .bf .bg .bh .bi .biz .bj .bm .bn .bo .br .bs .bt .bv .bw .by .bz .ca .cat .cc .cd .cf .cg .ch .ci .ck .cl .cm .cn .co .com .coop .cr .cu .cv .cw .cx .cy .cz .de .dj .dk .dm .do .dz .ec .edu .ee .eg .er .es .et .eu .fi .fj .fk .fm .fo .fr .ga .gb .gd .ge .gf .gg .gh .gi .gl .gm .gn .gov .gp .gq .gr .gs .gt .gu .gw .gy .hk .hm .hn .hr .ht .hu .id .ie .il .im .in .info .int .io .iq .ir .is .it .je .jm .jo .jobs .jp .ke .kg .kh .ki .km .kn .kp .kr .kw .ky .kz .la .lb .lc .li .lk .lr .ls .lt .lu .lv .ly .ma .mc .md .me .mg .mh .mil .mk .ml .mm .mn .mo .mobi .mp .mq .mr .ms .mt .mu .museum .mv .mw .mx .my .mz .na .name .nc .ne .net .nf .ng .ni .nl .no .np .nr .nu .nz .om .org .pa .pe .pf .pg .ph .pk .pl .pm .pn .post .pr .pro .ps .pt .pw .py .qa .re .ro .rs .ru .rw .sa .sb .sc .sd .se .sg .sh .si .sj .sk .sl .sm .sn .so .sr .st .su .sv .sx .sy .sz .tc .td .tel .tf .tg .th .tj .tk .tl .tm .tn .to .tp .tr .travel .tt .tv .tw .tz .ua .ug .uk .us .uy .uz .va .vc .ve .vg .vi .vn .vu .wf .ws .xn--0zwm56d .xn--11b5bs3a9aj6g .xn--3e0b707e .xn--45brj9c .xn--80akhbyknj4f .xn--80ao21a .xn--90a3ac .xn--9t4b11yi5a .xn--clchc0ea0b2g2a9gcd .xn--deba0ad .xn--fiqs8s .xn--fiqz9s .xn--fpcrj9c3d .xn--fzc2c9e2c .xn--g6w251d .xn--gecrj9c .xn--h2brj9c .xn--hgbk6aj7f53bba .xn--hlcj6aya9esc7a .xn--j6w193g .xn--jxalpdlp .xn--kgbechtv .xn--kprw13d .xn--kpry57d .xn--lgbbat1ad8j .xn--mgb9awbf .xn--mgbaam7a8h .xn--mgbayh7gpa .xn--mgbbh1a71e .xn--mgbc0a9azcg .xn--mgberp4a5d4ar .xn--o3cw4h .xn--ogbpf8fl .xn--p1ai .xn--pgbs0dh .xn--s9brj9c .xn--wgbh1c .xn--wgbl6a .xn--xkc2al3hye2a .xn--xkc2dl3a5ee0h .xn--yfro4i67o .xn--ygbi2ammx .xn--zckzah .xxx .ye .yt .za .zm .zw"), true);

        /*
         *  Regular expression bits used by htmlEscapeAndLinkUrls() to match URLs.
         */
        $rexScheme     = 'https?://';
        // $rexScheme    = "$rexScheme|ftp://"; // Uncomment this line to allow FTP addresses.
        $rexDomain     = '(?:[-a-zA-Z0-9]{1,63}\.)+[a-zA-Z][-a-zA-Z0-9]{1,62}';
        $rexIp         = '(?:[1-9][0-9]{0,2}\.|0\.){3}(?:[1-9][0-9]{0,2}|0)';
        $rexPort       = '(:[0-9]{1,5})?';
        $rexPath       = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
        $rexQuery      = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexFragment   = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexUsername   = '[^]\\\\\x00-\x20\"(),:-<>[\x7f-\xff]{1,64}';
        $rexPassword   = $rexUsername; // allow the same characters as in the username
        $rexUrl        = "($rexScheme)?(?:($rexUsername)(:$rexPassword)?@)?($rexDomain|$rexIp)($rexPort$rexPath$rexQuery$rexFragment)";
        $rexTrailPunct = "[)'?.!,;:]"; // valid URL characters which are not part of the URL if they appear at the very end
        $rexNonUrl     = "[^-_$+.!*'(),;/?:@=&a-zA-Z0-9]"; // characters that should never appear in a URL
        $rexUrlLinker  = "{\\b$rexUrl(?=$rexTrailPunct*($rexNonUrl|$))}";


        $html = '';

        $position = 0;
        while (preg_match($rexUrlLinker, $text, $match, PREG_OFFSET_CAPTURE, $position))
        {
            list($url, $urlPosition) = $match[0];

            // Add the text leading up to the URL.
            $html .= htmlspecialchars(substr($text, $position, $urlPosition - $position));

            $scheme      = $match[1][0];
            $username    = $match[2][0];
            $password    = $match[3][0];
            $domain      = $match[4][0];
            $afterDomain = $match[5][0]; // everything following the domain
            $port        = $match[6][0];
            $path        = $match[7][0];

            // Check that the TLD is valid or that $domain is an IP address.
            $tld = strtolower(strrchr($domain, '.'));
            if (preg_match('{^\.[0-9]{1,3}$}', $tld) || isset($validTlds[$tld]))
            {
                // Do not permit implicit scheme if a password is specified, as
                // this causes too many errors (e.g. "my email:foo@example.org").
                if (!$scheme && $password)
                {
                    $html .= htmlspecialchars($username);

                    // Continue text parsing at the ':' following the "username".
                    $position = $urlPosition + strlen($username);
                    continue;
                }

                if (!$scheme && $username && !$password && !$afterDomain)
                {
                    // Looks like an email address.
                    $completeUrl = "mailto:$url";
                    $linkText    = $url;
                }
                else
                {
                    // Prepend http:// if no scheme is specified
                    $completeUrl = "/external.php?uri=";
                    $completeUrl .= $scheme ? $url : "http://$url";
                    $linkText    = "$domain$port$path";
                }

                $linkHtml = html::anchor($completeUrl, htmlspecialchars($linkText), ['title'  => 'Visit link in new tab', 'target' => '_blank']);

                // Cheap e-mail obfuscation to trick the dumbest mail harvesters.
                $linkHtml = str_replace('@', '&#64;', $linkHtml);

                // Add the hyperlink.
                $html .= $linkHtml;
            }
            else
            {
                // Not a valid URL.
                $html .= htmlspecialchars($url);
            }

            // Continue text parsing from after the URL.
            $position = $urlPosition + strlen($url);
        }

        // Add the remainder of the text.
        $html .= htmlspecialchars(substr($text, $position));
        return $html;
    }

    /**
     * 
     * @param array $params the POSTed vals array
     * @param type $len
     * @param type $max
     * @return type
     */
    protected static function format($params, $len = 76, $max = 200)
    {
        $text     = trim(Security::xss_clean(Arr::get($params, 'message')));
        if($text)
        {
            $original = $chunks   = explode("\n", $text);
            $link     = Arr::get($params['link'], $type);

            foreach ($chunks as $key => $t)
            {
                if (mb_strlen($t) >= 1)
                {
                    $chunks[$key] = trim(Text::wrap($t, $len));
                }
            }

            // after all the wrapping, more line breaks occurs. join & split again
            // for accuracy
            $chunks = explode("\n", implode("\n", $chunks));

            // if this is a link-type post, the excerpt is shorter
            if ($link)
            {
                $excerpt = implode("\n", array_slice($chunks, 0, 5));
            }
            else
            {
                // special post types have shorter excerpts
                switch (Arr::get($params, 'type'))
                {
                    case 'attachment':
                    case 'listing':
                    case 'album':
                        $excerpt = implode("\n", array_slice($chunks, 0, 5));
                        break;
                    default:
                        $excerpt = implode("\n", array_slice($chunks, 0, 10));
                        break;
                }
            }

            // we default to the original text
            $message = implode("\n", $original);

            // unless the original post was too long
            if (count($chunks) >= $max)
            {
                $message = implode("\n", array_slice($chunks, 0, $max));
            }

            $params['message'] = nl2br(static::extract_links(trim($message)));
            $params['excerpt'] = nl2br(static::extract_links(trim($excerpt)));            
        }

        return $params;
    }

}