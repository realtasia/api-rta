<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of user
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Model/Post/
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Post extends Document {

    protected $after_delete_fields;

    /**
     * Retrieve a single post
     *
     * @param MongoID|string|array $id or an array of query params
     * @return array|null
     */
    public function get($id, array $fields = [], $object = TRUE)
    {
        if ($this->load($id, $fields))
        {
            $ret = $this->stringify($this->as_array());
            return $object ? (object) $ret : $ret;
        }
        return false;
    }

    /**
     * Create a default post with new values.
     *
     * @param array $params
     * @return boolean
     */
    public function create($params)
    {
        $this->collection()->ensureIndex([
            'status'     => 1, 'user._id'   => 1, 'privacy.is' => 1, 'modified'   => -1
                ], ['name' => 'post_id_fields', 'safe' => true]);
        $this->collection()->ensureIndex([
            'page_parent._id' => 1
                ], ['sparse' => true, 'safe'   => true]);
        $this->collection()->ensureIndex([
            'page._id' => 1
                ], ['sparse' => true, 'safe'   => true]);
        $this->collection()->ensureIndex([
            'to._id' => 1
                ], ['sparse' => true, 'safe'   => true]);
        $this->collection()->ensureIndex([
            'type' => 1,
        ]);
        $this->collection()->ensureIndex([
            'listing.unit' => 1,
        ]);
        $this->collection()->ensureIndex([
            'listing.type'          => 1, 'listing.price'         => 1, 'listing.rooms'         => 1,
            'listing.built_size'    => 1, 'listing.property_type' => 1
        ]);
        // overwrite defaults
        $data                   = Arr::overwrite(static::$post_fields, $params);
        // merge with defaults
        $data                   = Arr::merge(static::$post_fields, $data);

        // sort - for some sanity in debugging.. :-)
        ksort($data);

        // if an id is present, this is an update.
        if (isset($data['_id']))
        {
            $this->atomic($data['_id']);
            unset($params['_id']);
        }

        // load the document
        $this->load_values($data);

        try
        {
            // save
            $this->save();
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoException while trying to create post: :post \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", [
                ':post'    => print_r($data, TRUE),
                ':message' => $e->getMessage(),
                ':code'    => $e->getCode()
            ]);
            return false;
        }

        return $this;
    }

    /**
     * A conversation is special. It is a post with no message. Messages are
     * added via comments. This method automatically creates a comment.
     *
     * @param array $params
     * @return array|boolean
     */
    public function create_conversation($params)
    {
        $message = $params['message'];
        unset($params['message']);

        if ($this->create($params))
        {
            $msg = parent::factory('comment');

            $params['post'] = [
                '_id'  => $this->_id,
                'type' => $this->type
            ];

            $params['message'] = $message;

            if ($msg->create_message($params))
            {
                return $msg;
            }
        }

        return false;
    }

    /**
     * An album is special. An album is a post because it has comments, privacy
     * and counts (of attachments)
     */
    public function create_album($params)
    {
        $defaults = [
            'name' => '',
            'message' => '',
            'privacy' => ['is' => 'PUBLIC'],
            'user' => [],
            'page' => [],
            'type' => 'album',
        ];

        return $this->create(Arr::merge($defaults, $params));
    }

    /**
     *
     */

    /**
     * Searches for a document that has unique values. Returns false if none is
     * found and the id of the document if found.
     *
     * @param array $params
     * @return boolean|array
     */
    public function find_unique(array $params)
    {
        $query = [
            'listing.unit' => $params['unit'],
            'listing.type' => $params['listing_type'],
            'page.id'      => parent::check_id($params['page_id']),
            'user.id'      => parent::check_id($params['user_id'])
        ];

        return $this->get($query, ['_id' => 1], false);
    }

    /**
     * Calls the parent pages and user to update counts and timestamps.
     */
    public function after_save($action)
    {
        if ($action === parent::SAVE_INSERT || $action === parent::SAVE_UPSERT)
        {
            $pages = [];

            if (isset($this->page))
                $pages[] = $this->page;

            if (isset($this->to))
                $pages[] = $this->to;

            if (isset($this->page_parent))
                $pages[] = $this->page_parent;

            return $this->process_counts(['pages' => $pages, 'privacy' => $this->privacy], $this->type, $this->user);
        }
    }

    /**
     * Unsets the 'created' field which was extracted from the MongoID
     * sets the modified timestamp, among other things.
     */
    public function before_save($action)
    {
        $this->modified = time();
        // Updates
        if ($action === parent::SAVE_UPDATE)
        {

            // during testing a whole lot of counts went into negative. Just a
            // small sanity check.
            if (!isset($this->_changed['counts']) && isset($this->counts))
            {
                foreach ($this->counts as $k => $v)
                {
                    if ($v < 0)
                    {
                        $this->set('counts.' . $k, 0);
                    }
                }
            }
            foreach (['interactions', 'private_actions', 'connections', 'actions', 'created'] as $v)
            {
                if (!isset($this->_changed[$v]) && isset($this->{$v}))
                {
                    unset($this->{$v});
                }
            }
        }

        // Creates
        if ($action === parent::SAVE_INSERT || $action === parent::SAVE_UPSERT)
        {
            // auto-posts have no subscriptions, likes or comments for now.
            if (in_array($this->type, ['subscription', 'like']))
            {
                foreach (['comments', 'subscribers', 'likers'] as $value)
                {
                    unset($this->{$value}, $this->counts[$value]);
                }
            }

            // testimonials have no comments.
            if ($this->type === 'testimonial')
            {
                unset($this->comments, $this->counts['comments']);
            }

            // make sure all ids are mongo ids.
            $this->set('user._id', parent::check_id($this->user['_id']));
            $this->set('page._id', parent::check_id($this->page['_id']));
            // we check if there is a parent too.
            if (isset($this->page_parent))
            {
                $this->set('page_parent._id', parent::check_id($this->page_parent['_id']));
            }
        }

        return $this;
    }

    /**
     * Converts timestamps to dates.
     * Checks and sets current user attributes. (if user returned is the user logged in)
     * Otherwise, checks current user's interaction with the returned user
     */
    public function after_load()
    {
        $id = parent::check_id($this->_id);

        $this->created = $id->getTimestamp();
        // if there is no current user, no further formatting required.
        if ($this->current_user === NULL)
            return $this;

        $current_user = (object) $this->get_default_user_fields();

        // check if it's the same user
        if ((string) $this->user['_id'] === (string) $current_user->_id)
        {
            $this->private_actions = ['can_edit'         => true];
            $this->connections = static::get_all_connection_routes($this->_id);
        }
        else
        {
            $interactions       = parent::factory('interaction', NULL, $current_user->_id);
            $this->interactions = $interactions->interacting($this->_id, 'post');
            $this->actions      = static::get_action_routes($this->_id);
            $this->connections  = static::get_public_connection_routes($this->_id);
        }

        // we should trigger the comment model's formatting so each comment in
        // this post is properly formatted.
        if (!empty($this->comments))
        {
            foreach ($this->comments as $key => $value)
            {
                $comment = parent::factory('comment');
                if ($current_user)
                {
                    // check if there is a current user
                    $comment->current_user = $current_user;
                }
                // now each comment in the post is properly formatted.
                $this->set("comments.$key", $comment->stringify($comment->to_array()));
            }
        }

        return $this;
    }

    /**
     * Extract the user, post_id and pages.
     *
     */
    public function before_delete()
    {
        $this->after_delete_fields = [
            '_id'         => $this->_id,
            'user'        => $this->user,
            'type'        => $this->type,
            'counts'      => $this->counts,
            'page'        => $this->page,
            'page_parent' => $this->page_parent,
            'to'          => $this->to,
            'privacy'     => $this->privacy,
        ];
    }

    /**
     * Process counts, remove comments and interactions.
     *
     * @return type
     */
    public function after_delete()
    {
        $id   = $this->after_delete_fields['_id'];
        $user = $this->after_delete_fields['user'];
        $type = $this->after_delete_fields['type'];
        $counts = $this->after_delete_fields['counts'];
        $privacy = $this->after_delete_fields['privacy'];

        unset(
                $this->after_delete_fields['_id'],
                $this->after_delete_fields['user'],
                $this->after_delete_fields['type'],
                $this->after_delete_fields['counts'],
                $this->after_delete_fields['privacy']
        );
        $pages = [];

        foreach ($this->after_delete_fields as $key => $array)
        {
            if (!empty($array))
                $pages[] = $array;
        }

        $this->process_counts(['pages' => $pages, 'privacy' => $privacy], $type, $user, -1);

        // remove all embedded documents
        if(isset($counts))
        {
            if($counts['subscribers'] > 0 || $counts['likers'] > 0)
            {
                parent::factory('interaction')->collection()
                        ->remove_safe([
                            'object_id' => $id,
                            'object_type' => 'post',
                ], ['multiple' => true]);
            }

            if($counts['comments'] > 0)
            {
                parent::factory('comment')->collection()
                        ->remove_safe([
                            'post._id' => $id
                ], ['multiple' => true]);
            }
        }

    }

    /**
     * This updates all respective counts of a post's user and a post's pages
     * depending the type of post and the increment.
     *
     * @param array $pages all possible pages [page, page_parent, to]
     * @param string $type this post typer
     * @param array $user this post's creator
     * @param int $inc increase or decrease
     * @return \Model_Post
     */
    public function process_counts($params, $type, $user, $inc = 1)
    {
        $pages = $params['pages'];
        $privacy = $params['privacy'];
        if (!empty($pages))
        {
            foreach ($pages as $page)
            {
                switch ($type)
                {
                    case 'testimonial' :
                        // get the page type
                        $page_type = $page['type'] === 'user' ? 'user' : 'page';

                        parent::factory($page_type, $page['_id'])
                                ->inc('counts.testimonials.received', $inc)
                                ->save();
                        break;

                    case 'listing' :
                    case 'wallpost' :
                    case 'checkin' :
                    case 'attachment' :
                    case 'event' :
                    case 'post' :
                        $type = $type === 'wallpost' ? 'posts' : Inflector::plural($type);
                        // for other kinds of posts, we don't update the
                        // user whose page was written on.
                        if ($page['type'] !== 'user')
                        {
                            parent::factory('page', $page['_id'])
                                    ->inc('counts.' . $type, $inc)
                                    ->save();
                            break;
                        }
                    case 'conversation' :
                        // conversations, we update both. Because we need
                        // the number of total conversations in each user's
                        // inbox.
                        parent::factory('user', $page['_id'])
                                ->inc('counts.conversations', $inc)
                                ->save();
                        break;
                    case 'album' :
                        // albums are organised by private and public
                        $field = $privacy['is'] === 'PUBLIC' ? 'albums.public' : 'albums.private';
                        parent::factory('page', $page['_id'])
                                ->inc('counts.' .$field, $inc)
                                ->save();
                        break;
                }
            }
        } // pages
        // now we update the user that created the post
        switch ($type)
        {
            case 'testimonial' :
                // update the user's count
                parent::factory('user', $user['_id'])
                        ->inc('counts.testimonials.written', $inc)
                        ->save();
                break;

            default :
                $type = $type === 'wallpost' ? 'posts' : Inflector::plural($type);

                parent::factory('user', $user['_id'])
                        ->inc('counts.' . $type, $inc)
                        ->save();
                break;
        }

        return $this;
    }

    /**
     * Updates privacy settings of a post.
     *
     * @param array $params
     * @param string|MongoID $user_id
     * @return type
     */
    public function update_privacy($params, $_id)
    {
        $field  = static::$post_fields['privacy'];
        $values = Arr::overwrite($field, $params);

        $this->load_values([
            '_id'     => new MongoID($_id),
            'privacy' => $values,
        ]);
        $this->save();

        return $values;
    }

    /**
     * returns a document with fields specific to what the Job::factory needs.
     *
     * @return \Document
     */
    public function job_fields()
    {
        return $this->load([], [
                    'user'         => 1, // '_id', 'name' of the user who created this
                    'to'           => 1, // '_id', 'name' of the user this message was sent to (for messages)
                    'page'         => 1, // '_id', 'name', 'type' the page it was written on (location, user profile etc...)
                    'page_parent'  => 1, // '_id', 'name', 'type' the page's parent (company or estate)
                    'listing'      => 1, // '_id'
                    'message'      => 1, // the content
                    'message_tags' => 1, // names of existing people within the content are parsed
                    'type'         => 1, // wallpost, testimonial, conversation, listing, checkin, event, album, image
                    'status'       => 1, // pending, active, expired,
                    'uri'          => 1,
                    'modified'     => 1,
                    'privacy'      => 1,
                    'counts'       => 1
                ]);
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

        foreach (['like', 'unlike', 'subscribe', 'unsubscribe', 'comment'] as $k => $v)
        {
            $actions[$v] = Router::post($v, $user_id);
        }

        return $actions;
    }

    /**
     * Gets all connection links, including private ones that a logged in user
     * can perform on this post
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_all_connection_routes($_id)
    {
        $actions = ['privacy' => Router::post('privacy', $_id)];

        return Arr::merge($actions, static::get_public_connection_routes($_id));
    }

    /**
     * Gets all publically available connection links for a given post.
     *
     * @param string|MongoID $user_id
     * @return array
     */
    protected static function get_public_connection_routes($_id)
    {
        $actions = [];

        foreach (['likers', 'comments', 'subscribers'] as $k => $v)
        {
            $actions[$v] = Router::post($v, $_id);
        }

        return $actions;
    }

    /**
     * The standard post document.
     *
     * @var array
     */
    private static $post_fields = [
        'user' => [], // '_id', 'name' of the user who created this
        'to' => [], // '_id', 'name' of the user this message was sent to (for messages)
        'page' => [], // '_id', 'name', 'type' the page it was written on (location, user profile etc...)
        'page_parent' => [], // '_id', 'name', 'type' the page's parent (company or estate)
        'listing' => [
            'type'          => '',
            'price'         => 0,
            'rooms'         => 1,
            'built_size'    => 0,
            'property_type' => '',
            'tenure'        => 0,
            'land_size'     => 0,
            'value'         => 0,
            'cobroke'       => 0,
            'unit'          => '',
        ], // '_id'
        'name'          => '', // the content
        'message'       => '', // the content
        'message_tags'  => [], // names of existing people within the content are parsed
        'picture' => '', // if this post has an image, this will be the url
        'link'    => [], // 'name', 'caption', 'description', 'source' the original link if there is one
        'video' => [], // 'name', 'caption', 'description', 'source', 'properties' if this post has a video
        'type'     => 'post', // wallpost, testimonial, conversation, listing, checkin, event, album
        'status'   => 'active', // pending, active, expired,
        'uri'      => '',
        'modified' => 0,
        'privacy'  => ['is'     => 'PUBLIC'],
        'counts' => [
            'attachments' => 0,
            'comments'    => 0,
            'events'      => 0,
            'likers'      => 0,
            'subscribers' => 0
        ],
        'comments'    => [],
        'subscribers' => [],
        'likers' => []
    ];

}