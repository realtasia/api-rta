<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Post is one of RTA's core controllers. It handles all functionality for post types. Albums,
 * attachments, status updates, wallposts etc. also creates comments for a given post.
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Post
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Post extends Controller_Resource {

    private static $valid_get_types  = [
        'find_unique', 'retrieve_post_for_conversation'
    ];
    private static $valid_post_types = [
        'create_testimonial', 'create_listing', 'create_post', 'create_conversation',
        'update_privacy', 'update_testimonial', 'update_listing', 'update_post',
        'create_album', 'update_album', 'update_attachment', 'create_comment',
        'create_message', 'delete_comment', 'create_wallpost', 'create_attachment',
        'clear_unread'
    ];

    /**
     * Sets the static $model property to Document::post. Inits document params for logged in user
     * after log in checks.
     *
     * @return void
     */
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
     * Fetch a private GET action or fail if there is one and no method for it || Or fetch a post by id. Check if
     * there is a logged in user. Fetch requested post and check relation to  logged_in user. <code>
     * GET "/post/index/{id}" || "/post/index/{action}"</code>
     *
     * @return string JSON encoded array
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
        {
            static::$model->is_single = true;
            return $this->data(static::$model->get($id));
        }

        return $this->error('invalid_request');
    }

    /**
     * All private update methods go through this method. Authenticates whether a user is allowed to perform
     * and action before delegating to the respective handlers. <code>
     * POST "/post/update/{action}"</code>
     *
     * @return string JSON encoded array
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

    /*     * ***********************************************************************
     * Private Actions
     * *********************************************************************** */

    /**
     * Retrieves a post.
     *  - Checks if post owner is current user.
     *  - Checks if page itself is the current user.
     *  - Checks if page owner is current user
     *  If any thing checks out, we delete the post  <code>
     * POST "/post/delete/{id}"</code>
     *
     * @return string JSON encoded array
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
                    if ($attachment)
                    {
                        $this->delete_attachment($attachment);
                    }
                    if ($album)
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
                        if ($attachment)
                        {
                            $this->delete_attachment($attachment);
                        }
                        if ($album)
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
     * This hides a post from a user's ticker stream. The post is not actually
     * deleted. We can add on to the UI later to show hidden posts.<code>
     * POST "/post/hide/{id}"</code>
     * @return string JSON encoded array
     */
    public function action_hide()
    {
        $ticker = Collection::factory('ticker');
        $ticker->update([
            'document_id' => Document::check_id($this->request->param('id')),
            'user_id'     => Document::check_id(static::$user_id)
          ], [
            '$set' => ['visibility' => 'hidden']
        ]);

        return $this->data(['success' => 1]);
    }

    /**
     * This unhides a previously hidden post. This option in only available if the
     * user did not refresh the page after hiding an item.<code>
     * POST "/post/unhide/{id}"</code>
     * @return string JSON encoded array
     */
    public function action_unhide()
    {
        $ticker = Collection::factory('ticker');
        $ticker->update([
            'document_id' => Document::check_id($this->request->param('id')),
            'user_id'     => Document::check_id(static::$user_id)
          ], [
            '$set' => ['visibility' => 'visible']
        ]);

        return $this->data(['success' => 1]);
    }

    /**
     * Set or get the privacy of a post.<code>
     * POST [Setter] || GET [Getter]  "/post/privacy/{id}"</code>
     * @see update_privacy
     * @return string JSON encoded array
     */
    public function action_privacy()
    {
        $id   = $this->request->param('id');
        $post = Document::factory('post', $id);
        $post->load([], ['user'    => 1, 'privacy' => 1]);

        if ($this->is_GET() && (static::logged_in_is_id($post->user['_id']) || parent::has_role('admin')))
        {
            return $this->data($post->stringify($post->as_array()['privacy']));
        }
        elseif (static::logged_in_is_id($post->user['_id']) || parent::has_role('admin'))
        {
            // this is a setter
            if ($this->is_POST())
            {
                return $this->update_privacy();
            }
        }
        return $this->error('access_denied');
    }

    /**
     * Creates a comment for a given post
     *
     * @return string JSON encoded array
     */
    public function action_comment()
    {
        $params           = static::format(static::$params, 66, 100);
        $params['type']   = 'comment';
        $params['status'] = static::$params['status'];
        $params['user']   = static::$params['user'];
        $params['post']   = static::$params['post'];

        $c = Document::factory('comment', null, static::$user_id)->create($params);
        if ($c)
        {
            $pid = Document::check_id($params['post']['_id']);
            Job::add($pid, 'post', static::$user_id)->comment($c->_id);
            if (Ticker::factory($pid)->update_once([
                  'user_id' => Document::check_id(static::$user_id)
                ], 2))
            {
                Ticker::add($pid, 'update');
            }

            return $this->data($c->stringify($c->as_array()));
        }

        return $this->error('server_error');
    }

    /**
     * Creates a message for a given post
     * -- check if existing conversation exists (by user._id or to._id)
     *      -- create one if it doesn't exist.
     * -- retrieve post details and construct summary
     * -- clean and format message
     * -- create summary
     * -- add conversation._id to this message (needs to be an index)
     * -- add job for new private message
     * -- update the conversation's unread field to reflect the id of the receipient
     *
     * POST $params should be an array containing the following:
     * <ul>
     *  <li><strong>to_id</strong> : string the Document id of the post's creator</li>
     *  <li><strong>message</strong> : string the actual message</li>
     * </ul>
     *
     * @return string JSON encoded array
     */
    public function action_message()
    {
        // simple checks that return errors
        switch (true)
        {
            // if no logged in user
            case!parent::logged_in():
            // if no to id
            case!\Arr::get(static::$params, 'to_id'):
            // if both sender & recipient is the same person
            case (string) static::$user_id === (string) \Arr::get(static::$params, 'to_id', ''):
                return $this->error('invalid_request');
        }

        // set up all needed documents
        $post    = Document::factory('post');
        $summary = null;

        //if there is an post id.
        if ($this->request->param('id'))
        {
            $post->load($this->request->param('id'), [
                'page'    => 1,
                'listing' => 1,
                'type'    => 1,
                'user'    => 1,
                'uri'     => 1
            ]);
            // make the summary
            $summary = static::construct_message_summary($post);
        }

        $from = Document::factory('user', static::$user_id);
        $to   = Document::factory('user', static::$params['to_id']);
        $in   = [
            '$in' => [
                Document::check_id($from->_id),
                Document::check_id($to->_id),
            ]
        ];
        // query to find conversation
        $conv = Collection::factory('post')->find([
            'to._id'   => $in, 'user._id' => $in
        ]);

        $fields = ['name' => 1, 'uri'  => 1, '_id'  => 1];
        // load both users in question
        $from->load(null, $fields);
        $to->load(null, $fields);

        // check if we have a conversation, if not create one
        if (!$conv->hasNext())
        {
            $conv = $this->create_conversation([
                'to'   => $to->as_array(),
                'user' => $from->as_array(),
            ]);
        }
        else
        {
            $conv = $conv->getNext();
        }

        $params = static::format(static::$params);

        foreach ([$to, $from, $post] as $v)
        {
            if (is_array($v) && isset($v['_id']))
                $v['_id'] = Document::check_id($v['_id']);
        }

        $message = [
            'to'           => $to->as_array(),
            'user'         => $from->as_array(),
            'post'         => $post->as_array(),
            'status'       => 'active',
            'conversation' => [ '_id' => Document::check_id($conv->_id)],
            'message'      => \Arr::get($params, 'message'),
            'excerpt'      => \Arr::get($params, 'excerpt'),
            'summary'      => $summary
        ];

        $c = Document::factory('comment', null, static::$user_id)
          ->create_message($message);

        if ($c)
        {
            $to->inc('counts.newMessages');
            $to->save();
            $from->save();

            $conv->set('unread', $to->_id);
            $conv->set('lastMessage', $from->as_array());
            $conv->set('message', $c->message);
            $conv->set('excerpt', $c->excerpt);
            $conv->inc('counts.messages');
            $conv->save();

            Debug::log(Job::add($conv->_id, 'post', static::$user_id)
                ->private_message($c->_id));

            return $this->data($c);
        }

        return $this->error('server_error');
    }

    /**
     * Creates a feedback type post.
     *
     * @return string JSON encoded array
     */
    public function action_feedback()
    {
        if (parent::is_POST())
        {
            $params['message']     = nl2br(Security::xss_clean(static::$params['message']));
            $params['type']        = 'feedback';
            $params['user']        = static::$params['user'];
            $params['environment'] = @json_decode(static::$params['environment'], true);

            $c = Document::factory('comment', null, static::$user_id)->create_feedback($params);
            if ($c)
            {
                $pid = Document::check_id($params['post']['_id']);
                Job::add($pid, 'comment', static::$user_id)->feedback($c->_id);
                // admin user....
                // Job::add($pid, 'comment', static::$user_id)->feedback($c->_id);
                return $this->data($c->stringify($c->as_array()));
            }

            return $this->error('server_error');
        }
    }

    /* *************************************************************************
     * Public Connections
     * ************************************************************************/

    /**
     * Retrieves users who like this post. <code>
     * GET "/post/likers/{id}"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'limit' => 11, 'skip' => 0, 'object_id' => NULL,  'object_type' => NULL, 'interaction' => 'like',
     *      'user_id' => NULL
     * ];
     * </code>
     * Returns a JSON string
     * <code>
     * {
     *      ['_id': 'user_id', 'name':'username'] , [...]
     * }
     * </code>
     *
     * @return string JSON encoded array
     */
    public function action_likers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'post';

        $data = Document::factory('interaction')
          ->collection()
          ->likers(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves users who are subscribed to this post. <code>
     * GET "/post/subscribers/{id}"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'limit' => 11, 'skip' => 0, 'object_id' => NULL,  'object_type' => NULL, 'interaction' => 'subscribe',
     *      'user_id' => NULL
     * ];
     * </code>
     * Returns a JSON string
     * <code>
     * {
     *      ['_id': 'user_id', 'name':'username'] , [...]
     * }
     * </code>
     *
     * @return string JSON encoded array
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

    /**
     * Retrieves attachments (subposts) of a given post (normally albums or listings). <code>
     * GET "/post/attachments/{id}"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'status' => null, 'skip' => 0, 'limit' => 11, 'sort' => 'active',  's' => '', 'include_ids' => [],
     *      'page_id' => null, 'parent_id' => null,  'user_id' => null, 'type' => null, 'listing' => [], 'to_id' => null,
     *      'privacy' => NULL, 'company_id' => null
     * ];
     * </code>
     *
     * @return string JSON encoded array
     */
    public function action_attachments()
    {
        $id = $this->request->param('id');

        $q = Arr::merge(static::$params, ['type' => 'attachment', 'page_id' => $id, 'sort' => 'newest']);

        return $this->data(Collection::factory('post')->search($q, static::$user_id));
    }

    /**
     * Retrieves an array of posts belonging to a user.
     */
    public function action_listings()
    {
        $id = $this->request->param('id');

        static::$params['type'] = 'listing';

        $posts = Mongo_Collection::factory('post');

        /*
         * 1. if user is logged in and ids are the same (or admin)
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
                    $p = Arr::merge(static::$params, ['user_id' => static::$user_id]);
                    return $this->data($posts->search($p, static::$user_id));
                }

                // admin sees everything
                if (parent::has_role('admin'))
                    return $this->data($posts->search(static::$params, static::$user_id));

                // clients only see active (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                {
                    // only publically viewable posts
                    static::$params['privacy'] = static::logged_in_is_id($id) ? null : 'PUBLIC';
                    // we include posts written on his page
                    return $this->data($posts->search(static::$params, static::$user_id));
                }
        }

        return $this->error('invalid_request');
    }

    /**
     * Retrieves the full message content from a given post id
     */
    public function action_fullContent()
    {
        static::$model->load($this->request->param('id'), ['message' => !0]);
        $this->data(static::$model->message);
    }

    /*     * ************************************************************************
     * Public Actions
     * *********************************************************************** */

    /**
     * Performs a like on the given post. <code>
     * POST "/post/like/{id}"</code>
     *
     * @return string JSON encoded array
     */
    public function action_like()
    {
        return parent::interact('like', 'post');
    }

    /**
     * Performs an unlike on the given post. <code>
     * POST "/post/unlike/{id}"</code>
     *
     * @return string JSON encoded array
     */
    public function action_unlike()
    {
        return parent::interact('unlike', 'post');
    }

    /**
     * Performs a subscribe on the given post. <code>
     * POST "/post/subscribe/{id}"</code>
     *
     * @return string JSON encoded array
     */
    public function action_subscribe()
    {
        return parent::interact('subscribe', 'post');
    }

    /**
     * Performs an unsubscribe on the given post. <code>
     * POST "/post/unsubscribe/{id}"</code>
     *
     * @return string JSON encoded array
     */
    public function action_unsubscribe()
    {
        return parent::interact('unsubscribe', 'post');
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
     * @return string JSON encoded array
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
     * Clears a conversation's unread status
     */
    protected function clear_unread()
    {
        $conv = Document::factory('post', $this->request->param('id'));
        $conv->set('unread', null);
        $conv->save();
    }

    /**
     * A conversation is a special post type with no message. It
     * has to contain a user and maybe a list of to[ids]. It can also be one
     * to[_id]. Either way, this post object has very little information and
     * normally depends on it's containing messages (comments) for content.
     *
     * @return \Document the instantated saved conversation
     */
    protected function create_conversation($params)
    {
        if (!parent::logged_in())
            return $this->error('invalid_request');

        $defaults = [
            'type'    => 'conversation', 'status'  => 'active',
            'privacy' => ['is' => 'PRIVATE']
        ];

        return static::$model->create_conversation(Arr::merge($defaults, $params));
    }

    /**
     * Testimonials are very similar to regular posts accept that this method sets the type to testimonial and
     * ensures that the default status is pending  unless this is an update. Upon successful creation,
     *   - increases the receipient count for testimonials received.
     *   - increases the writer's count for testimonials written.<code>
     * POST "/post/update/create_testimonial"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'user'         => [], // '_id', 'name' of the user who created this
     *      'page'         => [], // '_id', 'name', 'type', 'uri' the page it was written on (location, user, album etc...)
     *      'page_parent'  => [], // '_id', 'name', 'type', 'uri' the page's parent (estate or library)
     *      'company'      => [], // '_id', 'name', 'type' the user's company if available
     *      'message'      => '', // the content
     *      'picture'      => null, // if this post has an image, this will be the url
     *      'mediainfo'    => [], // if this post has an image, this will be the url
     *      'link'         => [], // 'name', 'description', 'source' the original link if there is one.
     *      'type'         => 'testimonial',
     *      'subtype'      => 'post',
     *      'status'       => 'pending', // note that this defaults to pending.
     *      'privacy'      => ['is' => 'PUBLIC'],
     * ];
     * </code>
     * @uses Controller_Page
     * @return string JSON encoded array
     */
    protected function create_testimonial($update = FALSE)
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['privacy.is'] = Arr::path(static::$params, 'privacy.is');
        if ($update)
        {
            $validation = Validation::factory(static::$params)
              ->rule('status', 'not_empty');
        }
        else
        {
            $validation = Validation::factory(static::$params)
              ->rule('message', 'not_empty')
              ->rule('privacy.is', 'Privacy::valid', [
                  ':value', ':field', ':validation'
              ])
              ->rule('page', 'not_empty')
              ->rule('page', 'Controller_User::valid', [':value', ':field', ':validation'])
              ->rule('page_parent', 'Controller_Page::valid', [':value', ':field', ':validation']);
        }

        $defaults = [
            'type'   => 'testimonial', 'status' => 'pending'
        ];
        if ($validation->check())
        {
            if ($update && isset(static::$params['status']))
            {
                $post = Document::factory('post', $this->request->param('id'), static::$user_id);
                $post->set('status', static::$params['status']);
                if ($post->save())
                {
                    if ($post->status === 'active')
                    {
                        $t = Ticker::factory($post);
                        Job::add($post->_id, 'post', null)->approve_testimonial($post->user['_id']);

                        $t->update(['user_id' => $post->page['_id']]);

                        if ($t->update_once(['user_id' => Document::check_id(static::$user_id)]))
                        {
                            Ticker::add($post->_id, 'update');
                        }
                    }
                    $post = $post->stringify($post->as_array());
                    return $this->data($post);
                }


                return $this->error('server_error');
            }
            else
            {
                static::$params = static::format(static::$params);

                $params = Arr::merge(static::$params, $defaults);
                if ($post   = static::$model->create($params))
                {
                    $t = Ticker::factory($post);
                    Job::add($post->_id, 'post', static::$user_id)->testimonial($post->page['_id']);
                    $t->create_once(static::$user_id, 2);
                    $t->create($post->page['_id'], 1);
                    if (static::$params['privacy.is'] === 'PUBLIC')
                    {
                        Ticker::add($post->_id, 'new');
                    }

                    $post = $post->stringify($post->as_array());
                    return $this->data($post);
                }
            }

            return $this->error('server_error');
        }

        $this->errors = $validation->errors('post/post');
        return $this->fail();
    }

    /**
     * Listing has a lot more validation rules.
     *
     * @return string JSON encoded array
     */
    protected function create_listing($update = FALSE)
    {
        $id = $this->request->param('id');

        if (!$id && !parent::logged_in())
            return $this->error('invalid_request');

        static::$params['user']['_id'] = $id ? $id : static::$user_id;

        $validation = Validation::factory(static::$params)
          ->rule('type', 'not_empty')
          ->rule('type', 'in_array', [':value', ['Rent', 'Sale']])
          ->rule('price', 'not_empty')
          ->rule('price', 'numeric')
          ->rule('rooms', 'not_empty')
          ->rule('rooms', 'numeric')
          ->rule('built_size', 'not_empty')
          ->rule('built_size', 'numeric')
          ->rule('property_type', 'not_empty')
          ->rule('land_size', 'numeric')
          ->rule('address', 'not_empty')
          ->rule('page', 'not_empty')
          ->rule('page', 'Controller_Page::valid', [':value', ':field', ':validation'])
          ->rule('page_parent', 'Controller_Page::valid', [':value', ':field', ':validation']);

        if ($validation->check())
        {
            static::$params = static::format(static::$params);
            $listing       = \Arr::extract(static::$params, [
                  'type', 'price', 'rooms', 'built_size', 'land_size', 'cov', 'valuation', 'leaseSpace', 'cobroke', 'unit', 'property_type', 'baths'
            ]);
            $page          = \Arr::get(static::$params, 'page');
            $unitIsAddress = \Arr::get(static::$params, 'unitIsAddress');
            if ('Private Landed' === $page['subtype'] && $unitIsAddress)
            {
                $listing['unit'] = array_shift(explode(' ', static::$params['address']));
            }
            else
            {
                $listing['unit'] = $listing['unit']['floor'] . '-' . $listing['unit']['stack'];
            }

            $params = \Arr::merge([
                  'type'    => 'listing', 'status'  => 'active', 'privacy' => ['is' => 'PUBLIC']
                ], \Arr::extract(static::$params, [
                    'message', 'address', 'page', 'page_parent', 'excerpt', 'showAddress', 'picture'
                ]), [
                  'listing'    => $listing,
                  'addedMedia' => \Arr::map("Document::check_id", \Arr::get(static::$params, 'addedMedia', [])),
            ]);

            $params['counts']['attachments'] = count($params['addedMedia']);

            if ($params['status'] === 'active')
            {
                $params['expiry'] = time();
            }

            $u                  = (array) $this->get_user();
            $params['user']     = [
                '_id'  => Document::check_id($u['_id']),
                'name' => $u['name']
            ];
            $params['company']  = $u['company'];
            $params['keywords'] = static::listing_keywords($params);
            if ($update)
            {
                $post = Document::factory('post', $update);
            }
            else
                $post = static::$model;

            $post->current_user = $u;

            $post->create($params);

            if ($post)
            {
                $t = Ticker::factory($post);
                if ($update)
                {
                    if ($t->update_once(['user_id' => Document::check_id(static::$user_id)], 1))
                    {
                        Ticker::add($post->_id, 'update');
                    }
                }
                else
                {
                    $t->create_once(static::$user_id, 2);
                    Ticker::add($post->_id, 'new');
                }
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
     * Creates a wallpost. Uses the user controller to validate a page if present. A page parent is optional,
     * but a page is needed so we know where this post was created - in this case it is a user. <code>
     * POST to "/post/update/create_wallpost"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'user'         => [], // '_id', 'name' of the user who created this
     *      'page'         => [], // '_id', 'name', 'type', 'uri' the page it was written on (location, user, album etc...)
     *      'page_parent'  => [], // '_id', 'name', 'type', 'uri' the page's parent (estate or library)
     *      'company'      => [], // '_id', 'name', 'type' the user's company if available
     *      'message'      => '', // the content
     *      'picture'      => null, // if this post has an image, this will be the url
     *      'mediainfo'    => [], // if this post has an image, this will be the url
     *      'link'         => [], // 'name', 'description', 'source' the original link if there is one.
     *      'type'         => 'wallpost',
     *      'subtype'      => 'post',
     *      'status'       => 'active', // pending, active, expired,
     *      'privacy'      => ['is' => 'PUBLIC'],
     * ];
     * </code>
     * @uses Controller_Page
     * @return string JSON encoded array
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
                $t = Ticker::factory($post);
                $t->create_once(static::$user_id, 2);
                if ($post->page['type'] === 'user' && (string) $post->page['_id'] !== static::$user_id)
                {
                    $t->create($post->page['_id'], 2);
                }
                if (static::$params['privacy.is'] === 'PUBLIC')
                {
                    Ticker::add($post->_id, 'new');
                }

                static::_do_interactions($post);
                Job::add($post->_id, 'post', static::$user_id)->wallpost($post->page['_id']);
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
     * Creates a regular post. Uses the page controller to validate a page if present. A page parent is optional,
     * but a page is needed so we know where this post was created.  <code>
     * POST to "/post/update/create_post"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'user'         => [], // '_id', 'name' of the user who created this
     *      'page'         => [], // '_id', 'name', 'type', 'uri' the page it was written on (location, user, album etc...)
     *      'page_parent'  => [], // '_id', 'name', 'type', 'uri' the page's parent (estate or library)
     *      'company'      => [], // '_id', 'name', 'type' the user's company if available
     *      'message'      => '', // the content
     *      'picture'      => null, // if this post has an image, this will be the url
     *      'mediainfo'    => [], // if this post has an image, this will be the url
     *      'link'         => [], // 'name', 'description', 'source' the original link if there is one.
     *      'type'         => 'post',
     *      'subtype'      => 'post',
     *      'status'       => 'active', // pending, active, expired,
     *      'privacy'      => ['is' => 'PUBLIC'],
     * ];
     * </code>
     * @uses Controller_Page
     * @return string JSON encoded array
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
            'type'   => 'post', 'status' => 'active'
        ];
        if ($validation->check())
        {
            static::$params = static::format(static::$params);

            if ($post = static::$model->create(Arr::merge($defaults, static::$params)))
            {
                $t = Ticker::factory($post);
                $t->create_once(static::$user_id, 2);
                if ($post->page['type'] === 'user' && (string) $post->page['_id'] !== static::$user_id)
                {
                    $t->create($post->page['_id'], 1);
                }
                if (static::$params['privacy.is'] === 'PUBLIC')
                {
                    Ticker::add($post->_id, 'new');
                }
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
     * Creates an attachment. <code>
     * POST "/post/update/create_attachment"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'name'  => '', 'mediainfo' => [], 'picture' => '', 'privacy' => ['is' => 'PUBLIC'],  'user' => [],
     *      'message' => '', 'excerpt' => '', 'page' => [], 'subtype'   => null, 'type'  => 'attachment',
     * ];
     * </code>
     * @return string JSON encoded array
     */
    protected function create_attachment()
    {
        $data     = \Arr::get(static::$params, 'data');
        $user     = (object) $this->get_user();
        $album_id = \Arr::get(static::$params, 'album_id');
        $debug    = ['album_id' => $album_id];

        if ($album_id)
        {
            $a               = Document::factory('post', $album_id);
            $a->current_user = $user;
            $a->load();
            $debug['loaded'] = $a->as_array();
        }
        elseif (Arr::get(static::$params, 'create_album'))
        {
            $debug['creating'] = 'yes';
            $a                 = $this->create_album(true);
            if (!$a)
            {
                return $this->error('invalid_request');
            }
        }
        // can be json encoded.
        if (!is_array($data))
        {
            $data = @json_decode($data, true);
        }

        if (empty($data))
            return $this->error('invalid_request');

        $data['name'] = trim(Security::xss_clean($data['name']));
        $data['page'] = ['_id'  => Document::check_id($a->_id), 'name' => $a->name, 'type' => $a->type, 'uri'  => $a->uri];
        $data['user'] = ['_id'  => Document::check_id($user->_id), 'name' => $user->name];
        $lib          = $user->library;
        $lib['_id']   = Document::check_id($lib['_id']);

        $data['page_parent'] = $lib;

        $args                     = \Arr::merge([
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
        $debug['attachment']      = $args;
        $fh                       = fopen('debug.html', 'a');
        fwrite($fh, "<pre>" . print_r($debug, true) . "</pre>");
        fclose($fh);
        $attachment               = Document::factory('post');
        $attachment->current_user = $user;
        if ($attachment->create_attachment($args))
        {
            $a->set('picture', $attachment->picture);
            $a->save();
            static::creator_interactions($attachment->_id, 'post');
            return $this->data($attachment->stringify($attachment->as_array()));
        }

        return $this->error('invalid_request');
    }

    /**
     * Creates an album. <code>
     * POST "/post/update/create_album"</code>
     * with the following params
     * <code>
     * $defaults = [
     *      'name'  => '', 'mediainfo' => [], 'picture' => '', 'privacy' => ['is' => 'PUBLIC'],  'user' => [],
     *      'message' => '', 'excerpt' => '', 'page' => [], 'subtype'   => null, 'type'  => 'album',
     * ];
     * </code>
     * @param boolean $model whether to return the model data instead. Helpful when creating an attachment.
     * @return mixed false if creation failed, a model or JSON encoded array
     */
    protected function create_album($model = false)
    {
        $p = Arr::get(static::$params, 'create_album', []);
        if (!is_array($p))
        {
            $p = json_decode($p, true);
        }
        $params = static::format($p);
        if (!is_array($params))
        {
            $params = @json_decode($params, TRUE);
        }
        $params['name']      = trim(Security::xss_clean($params['name']));
        $album               = Document::factory('post');
        $user                = (object) $this->get_user();
        $album->current_user = $user;
        $lib                 = $user->library;
        $lib['_id']          = Document::check_id($lib['_id']);

        $args = \Arr::overwrite([
              'name'    => '',
              'excerpt' => '',
              'message' => '',
              'user'    => ['_id'  => Document::check_id($user->_id), 'name' => $user->name],
              'page'    => $lib,
              'type'    => 'album',
              'privacy' => ['is' => 'PUBLIC'],
            ], $params);

        if ($album->create_album($args))
        {
            Ticker::factory($album)->create_once(static::$user_id, 2);
            if (\Arr::get($album->privacy, 'is') === 'PUBLIC')
            {
                Ticker::add($album->_id, 'new');
            }
            static::creator_interactions($album->_id, 'post');
            return $model ? $album : $this->data($album->stringify($album->as_array()));
        }

        return $model ? false : $this->error('invalid_request');
    }

    /*     * ***************************************************"*********************
     * Protected Actions - UPDATES
     * ************************************************************************* */

    /**
     * Update a listing. Checks if there is an id and calls the create_listing
     * method. <code>
     * POST "/post/update/update_listing/{id}"</code>
     * @see self::create_listing
     * @return string JSON encoded array
     */
    protected function update_listing()
    {
        $post_id = Arr::get(static::$params, '_id');

        if (!$post_id)
            return $this->error('invalid_request');

        // the create post method in the model checks to see if this is an update.
        return $this->create_listing($post_id);
    }

    /**
     * Update a testimonial. Checks if there is an id and calls the create_testimonial
     * method. <code>
     * POST "/post/update/update_testimonial/{id}"</code>
     * @see self::create_testimonial
     * @return string JSON encoded array
     */
    protected function update_testimonial()
    {
        $post_id = $this->request->param('id');
        //return $this->data([$post_id]);
        if (!$post_id)
            return $this->error('invalid_request');

        // the create post method in the model checks to see if this is an update.
        return $this->create_testimonial(TRUE);
    }

    /**
     * Updates an album and all it's attachments. <code>
     * POST "/post/update/update_album/{id}"</code>
     * @see self::create_album
     * @return string JSON encoded array
     */
    protected function update_album()
    {
        $id             = $this->request->param('id');
        $params         = static::format(static::$params);
        $params['name'] = trim(Security::xss_clean($params['name']));
        $user           = (object) $this->get_user();
        $lib            = $user->library;
        $lib['_id']     = Document::check_id($lib['_id']);
        $params['user'] = ['_id'  => Document::check_id($user->_id), 'name' => $user->name];
        $params['page'] = $lib;
        if (!Privacy::valid(\Arr::path($params, 'privacy.is')))
        {
            $params['privacy']['is'] = 'PUBLIC';
        }
        $args = \Arr::overwrite([
              'name'    => '',
              'excerpt' => '',
              'message' => '',
              'user'    => [],
              'privacy' => [],
              'page'    => [],
              'type'    => 'album'
            ], $params);

        $a               = Document::factory('post', $id);
        $a->current_user = $user;
        $a->load_values($args);
        if ($a->save())
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
     * updates an attachment.<code>
     * POST "/post/update/update_attachment/{id}"</code>
     * @see self::create_attachment
     * @return string JSON encoded array
     */
    protected function update_attachment()
    {
        $id     = $this->request->param('id');
        $params = static::format(static::$params);
        if (!empty($params['name']))
        {
            $params['name'] = trim(Security::xss_clean($params['name']));
        }
        $user                  = (object) $this->get_user();
        $page                  = Arr::get($_REQUEST, 'album_id');
        $album                 = Document::factory('post', $page);
        $album->load(null, ['type' => 1, 'name' => 1, 'uri'  => 1]);
        $lib                   = $user->library;
        $lib['_id']            = Document::check_id($lib['_id']);
        $params['page']        = $album->as_array();
        $params['user']        = ['_id'  => Document::check_id($user->_id), 'name' => $user->name];
        $params['page_parent'] = $lib;
        if (!Privacy::valid(\Arr::path($params, 'privacy.is')))
        {
            $params['privacy']['is'] = 'PUBLIC';
        }

        $args            = \Arr::merge([
              'excerpt'     => null,
              'message'     => null,
              'user'        => [],
              'privacy'     => [],
              'page'        => [],
              'page_parent' => [],
              'type'        => 'attachment',
            ], $params);
        //die(json_encode($args));
        $a               = Document::factory('post', $id);
        $a->current_user = $user;
        $a->load_values($args);
        if ($a->save())
        {
            $a->load();
            return $this->data($a->stringify($a->as_array()));
        }

        return $this->error('invalid_request');
    }

    /**
     * Update a post's privacy settings.
     *
     * @return string JSON encoded array
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
     * Sent about the {type link} on {page link} [IF LISTING, for {sale}] when;
     *
     * @param Document $post
     * @return string A sentence describing the context of this message.
     */
    public static function construct_message_summary(Document $post)
    {
        $page_link = HTML::anchor(\Arr::get($post->page, 'uri'), \Arr::get($post->page, 'name'));
        $post_link = HTML::anchor($post->uri, $post->type);
        $created   = Date::fuzzy_span($post->_id->getTimestamp());
        if ($post->type === 'listing')
        {
            $addon = "for {$post->listing['type']}";
        }

        return "Sent about the $post_link $addon written on $page_link $created";
    }

    /*     * ************************************************************************
     * Protected STATIC Utility Methods
     * *********************************************************************** */

    /**
     * Constructs a request object to delete S3 resources
     *
     * @param array $post
     * @param boolean $array
     * @return mixed An array if flag is set or issues a request to s3 directly.
     */
    protected static function delete_attachment(array $post, $array = false)
    {
        $picture = Arr::get($post, 'picture');
        $source  = Arr::path($post, 'link.src');
        $uid     = (string) Arr::get($post['user'], '_id');
        $objects = [];
        // not sure if basename() can do relative
        if ($picture)
        {
            $file  = basename($picture);
            $sizes = [110, 90, 40, 16, 150];
            foreach ($sizes as $size)
            {
                $objects[] = ['Key' => "$uid/$size/$file"];
            }
        }
        elseif ($source)
        {
            $file = basename($source);
        }


        $objects[] = $file ? ['Key' => $uid . '/' . $file] : [];

        return $array ? array_filter($objects) : ASDK::s3()->deleteObjects([
              'Bucket'  => RT::BUCKET,
              'Objects' => array_filter($objects),
        ]);
    }

    /**
     * This is similar to the above except it issues a batch request to S3 for multiple attachments.
     *
     * @param array $album
     * @return boolean
     */
    protected static function delete_attachments(array $album)
    {
        $objects  = $final    = [];
        $attaches = Collection::factory('post')
          ->find(['page._id' => Document::check_id($album['_id'])])
          ->fields(['user', 'link', 'picture']);

        while ($attaches->hasNext())
        {
            $child     = $attaches->getNext();
            $objects[] = static::delete_attachment($child->as_array(), true);
            $child->delete();
        }

        if (!empty($objects))
        {
            $objs = \Arr::flatten_recursive($objects);

            foreach ($objs as $v)
            {
                $final[] = ["Key" => $v];
            }

            return ASDK::s3()->deleteObjects([
                  'Bucket'  => RT::BUCKET,
                  'Objects' => array_filter($final),
            ]);
        }

        return true;
    }

    /**
     * Creates proper keys for validation because params (for Mongo) include
     * sub arrays. But Kohana & Community decided that it should not validate
     * sub arrays. I see their point, but I still think they're full of shit.
     *
     * @see http://forum.kohanaframework.org/discussion/1338/validation-and-sub-arrays/p1
     *
     * @param array $array
     * @return array
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
     *  @author: Søren Løvborg
     *
     *  To the extent possible under law, Søren Løvborg has waived all copyright
     *  and related or neighboring rights to UrlLinker.
     *  http://creativecommons.org/publicdomain/zero/1.0/

     *  Regular expression bits used by htmlEscapeAndLinkUrls() to match URLs.
     * @param string $text
     * @return string a html string with linked urls
     */
    protected static function extract_links($text)
    {
        /**
         * @update 22 Aug 2013, I think this line might be the cuplrit for
         * causing havoc with '&amp;' in text.
         */
        //$text = urldecode($text);

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
                    $completeUrl = "/external?uri=";
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
     * Cleans out text fields and auto-links urls
     *
     * @param array $params the post params received for this request
     * @param int $len
     * @param int $max
     * @return array
     */
    protected static function format(array $params, $len = 65, $max = 200)
    {
        $text = trim(Security::xss_clean(Arr::get($params, 'message')));
        if ($text)
        {
            // remove extra line breaks
            $text     = preg_replace("/(\n){3,}/", "\n\n", trim($text));
            // forgot the difference, just doing it again. Carriage return in windows?
            $text     = preg_replace("/(\r\n){3,}/", "\r\n\r\n", trim($text));

            // in either case above, this will still match
            $original = $chunks   = explode("\n", $text);

            // if there's a link, this wouldn't be empty.
            $link     = Arr::get($params['link'], $type);

            foreach ($chunks as $key => $t)
            {
                if (mb_strlen($t) > 0 || !empty($t))
                {
                    $chunks[$key] = trim(Text::wrap($t, $len));
                }
            }

            // after all the wrapping, more line breaks occur. join & split again
            // for accuracy in counting lines
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

    /**
     * Performs default subscription for a user. Page owners and Poster needs to be subscribed to the post
     * upon creation.
     *
     * @param Document $post
     * @uses Controller_Resource::creator_interactions()
     *
     * @return boolean
     */
    protected static function _do_interactions(Document $post)
    {
        if ((string) $post->user['_id'] !== (string) $post->page['_id'])
        {
            $i = Document::factory('interaction', null, $post->page['_id']);
            $i->subscribe($post->_id, 'post');
        }
        return static::creator_interactions($post->_id, 'post');
    }

    /**
     * Updates all attachments of a listing and sets a new field, listing_id.
     *
     * @param array $attachments
     * @param MongoID $post_id
     * @return int

    protected static function _attachments_for_listings(array $attachments)
    {
        $ids     = [];
        $picture = null;
        if (!empty($attachments))
        {
            foreach ($attachments as $a)
            {
                $ids[]   = Document::check_id($a['_id']);
                if (!empty($a['picture']))
                    $picture = $a['picture'];
            }
        }

        if ($picture)
        {
            $post->set('picture', $attachment->picture);
            $post->save();
        }

        $query = [
            '_id' => ['$in' => $ids]
        ];

        return Collection::factory('post')->update($query, [
              '$set' => ['listing_id' => $post->_id]
            ], ['multiple' => true]);
    }
     */
    protected static function listing_keywords($params)
    {
        if (\Arr::get($params, 'showAddress') === false)
        {
            $a = @explode(' ', \Arr::get($params, 'address'));
            if (!empty($a))
            {
                unset($a[0]);
                $params['address'] = implode(' ', $a);
            }
        }

        $params['subtype'] = \Arr::path($params, 'page.subtype');
        $params['name']    = \Arr::path($params, 'page.name');
        $params['parent']  = \Arr::path($params, 'page_parent.name');

        return \Arr::map('strtolower', \Arr::extract($params, [
                'address', 'subtype', 'name', 'parent'
        ]));
    }

}