<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Mongo Post Model.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model_Post extends Model {

    /**
     * Retrieves a single document from the database. If $user_id is set
     * (i.e. Logged in user) then does further checks to see if that user is
     * subscribed. Implemented caching previously. This differs from most other
     * get functions because there are no keyword searches.
     *
     * @todo A more robust higher level caching is required.
     *
     * @param string|MongoID $_id Post ID.
     * @param string|MongoID $user_id
     * @param bool $object to return document as object
     * @return mixed False if not found, user object if set above or array
     */
    public function get($_id, $user_id = FALSE, $object = TRUE)
    {
        $params = array('_id' => parent::check_id($_id));

        $post = $this->collection->findOne($params);
        if (empty($post))
            return NULL;

        // check for subscriptions
        if ($user_id)
        {
            $user   = Model::factory('user')->get_selective($user_id, $fields = array(
                'subscriptions', 'counts', 'likes'
                    ));

            $post['is_owner'] = parent::_is_owner($post, $user);
            $post             = parent::interacting($post, $user, TRUE);
        }

        if (isset($post['counts']) AND $post['counts']['comments'] > 0)
        {
            //$post['comments'] = self::format_comment($post['comments'], $post['_id'], $user_id);
            $post['comments'] = parent::factory('comment')->browse_comments($post['_id'], 0, 50, $user_id);
        }

        if (empty($post))
            return NULL;
        else
            $post = $this->format($post);

        return $object ? (object) $post : $post;
    }

    /**
     * Does a search, sort and limit. $args is an array that defaults to:
     *
     *  array(
     *     'limit' => 11, 'offset' => 0, 'type' => FALSE,
     *     'subtypes' => array(), 'sort' => 'newest',
     *     's' => '', 'include_ids' => false, 'profile_id' => false,
     *     'object_id' => false, 'user_id' => false, 'children' => false
     *  );
     *
     * @param array $args
     * @return self Sets the $cursor property and returns itself
     */
    public function search($args)
    {
        $defaults = array(
            'limit'    => 11, 'offset'   => 0, 'type'     => FALSE,
            'subtypes' => array(), 'sort'        => 'newest',
            's'           => '', 'include_ids' => FALSE, 'profile_id'  => FALSE,
            'object_id'   => FALSE, 'user_id'     => FALSE, 'children'    => FALSE
        );
        //echo Debug::vars($args);
        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        // Now we build the query
        // if there is a user id, set the class property for interaction
        // retrieval later (subscripts & likes)
        if ($user_id)
            $this->_user_id = parent::check_id($user_id);

        // set some empty arrays
        $query = $ids   = array();

        /**
         * subtypes =>
         *      Locations (estate, hdb, private-apartment, private-landed,
         *                 serviced-residences, cluster-housing)
         */
        if (!empty($subtypes))
            $query['subtype'] = array('$in' => $subtypes);

        // for example, listing or testimonial
        if ($type)
        {
            if (is_array($type))
                $query['post_type'] = array('$in'               => $type);
            else
                $query['post_type'] = $type;
        }

        // if specific records are needed add to the ids array
        if (!empty($include_ids))
            $ids = array_merge_recursive($ids, $include_ids);

        // this is the search term typed.
        if (strlen($s) >= 3)
        {
            $regexObj     = new MongoRegex("/$s/i");
            $query['$or'] = array(
                array('slug' => $regexObj)
            );
        }

        // this is different from include_ids as we are looking in the
        // parent_id property to find children. @TODO dbrefs instead?
        if ($children)
        {
            if (!is_array($children))
                $children = array($children);

            $query['parent_id']['$in'] = array_map(array($this, 'check_id'), $children);
        }

        // similar to the above, we don't return the parent object in results
        if ($object_id)
            $query['page_id'] = parent::check_id($object_id);

        // this is a profile page, return all posts in his page
        if ($profile_id)
            $query['$or'] = array(
                array('page_id' => parent::check_id($profile_id)),
                array('user_id' => parent::check_id($profile_id))
            );


        // now we validate the consodilated ids and continue building
        if (!empty($ids))
            $query['_id']['$in'] = array_map(array($this, 'check_id'), $ids);

        // last bit of criteria, we only fetch posts that have the publish status
        $query['status'] = 'publish';

        //echo Debug::vars($query);
        // we query the db and set it to the cursor property for global access
        // we also add 2 comments to each post
        //die(Debug::vars($query));
        $this->cursor = $this->collection
                ->find($query)
                ->limit($limit);

        // set the sort options
        $sort = $sort == 'newest' ? array('_id' => -1) : array('comments' => 1);

        $this->cursor->sort($sort);
        // the last bit, only done if offset is set
        if ($offset > 0)
            $this->cursor->skip($offset);

        return $this;
    }

    /**
     * The core method that creates a new (status) post. All other post types
     * are created by their respective Models. Since there are alot more things
     * needed for those. Sets a pretty standard schema. 
     * Updates the created post with a new URI and updates the parent
     * pages and the logged in user with timestamps and counts.
     *
     *
     * @param array $post_array
     * @param object $user
     * @param object $page
     * @param string $post_type
     * @return bool
     */
    public function create($post_array, $user, $page, $post_type)
    {
        /**
         * Clean Post
         * Parse Post
         * Insert Post
         * Update Parents -- moved out to events
         * Update User -- moved out to events
         * Send Notification -- moved out to events
         */
        $content = nl2br(HTML::chars(Arr::get($post_array, 'textareaContent')));
        if (isset($post_array['provider_url']))
        {
            $content .= View::factory('opengraph')->set('post', $post_array);
        }
        $title   = Text::limit_words($content, 10, '&hellip;');
        $current = time();
        $counts  = array_merge(parent::$counts, array('subscribers' => 1));
        $pg_type      = (isset($page->type)) ? $page->type : 'user';

        $insert = array(
            'content'        => $content,
            'title'          => $title,
            'slug'           => URL::title($title, '-'),
            'page_id'        => $page->_id,
            'page_uri'       => $page->uri,
            'page_title'     => $page->name,
            'page_type'      => $pg_type,
            'user_id'        => $user->_id,
            'user_uri'       => $user->uri,
            'user_full_name' => $user->name,
            'modified'       => $current,
            'type'           => $post_type,
            'status'         => $post_type == 'testimonial' ? 'pending' : 'publish',
            'uri'            => '',
            'counts'         => $counts,
            'subscribers'    => array($user->_id),
            'likers' => array()
        );

        if (isset($page->parent_id))
            $insert['page_parent_id'] = $page->parent_id;

        try
        {
            $post = $this->collection->insert($insert, array('safe' => TRUE));
            $uri   = '/post/' . $insert['_id'];

            $post = $this->db->command(
                    array(
                        'findandmodify' => 'posts',
                        'query'         => array('_id'    => $insert['_id']),
                        'update' => array('$set' => array('uri' => $uri))
                    )
            );
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoCursorException while trying to create a :post_type post on page :page for user :uid. \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", array(
                ':page'    => $page->_id, ':uid'     => $user->_id,
                ':type'    => $post_type, ':message' => $e->getMessage(),
                ':code'    => $e->getCode()
            ));
            return FALSE;
        }

        return $post['value']['_id'];
    }

    /**
     * Deletes a post and other objects that depend on it.
     *
     * @todo move user functions to a queue
     * @param string|MongoID $_id
     * @return bool
     */
    public function delete($_id)
    {
        $post = $this->get($_id);
        if (!empty($post->likers) OR !empty($post->subscribers))
        {
            parent::factory('user')->clean_user_documents($post);
        }
        try
        {
            $this->collection
                    ->remove(array('_id' => parent::check_id($_id)), array('safe'    => TRUE, 'justOne' => TRUE));

            parent::factory('comment')->delete($_id);
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoCursorException while trying to delete a :post_type post. \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", array(
                ':page'      => $page->_id, ':post_type' => $post->type,
                ':message'   => $e->getMessage(), ':code'      => $e->getCode()
            ));
            return FALSE;
        }
        // complete the interaction process for a new post.

        return TRUE;
    }

    /**
     * This method is called to create a machine generated post based on a user's
     * activity. Normally, the activity type is trivial, so the post will display
     * mostly as a single line in the user's profile page.
     *
     * For Subscription type activites, this method tries to see if another such
     * subscription was created for the same type of object within the last hour.
     * If it finds one, it adds the id of the newly subscribed object to the list
     * instead.
     * $post_types = subscription, like, comment.
     * $page_types = location, profile, user
     *
     * @param MongoID|string $_id The document's id
     * @param string $action The action the user did, subscribe, unsubscribe etc
     * @param MongoID|string $user_id User_id is given anyway, to modularise
     * @param Interact $i an instantated Interact object
     * @return bool
     */
    public function auto_create($_id, $user_id, Interact $i)
    {
        $user = Model::factory('user')->get($user_id);

        $document = parent::_fetch_document($_id, $i::model());
        //Debug::log($i::model());

        if ($i::model() == 'post' AND $document->user_id == $user->_id)
            return TRUE;

        $title    = property_exists($document, 'title') ? $document->title : $document->first_name . ' ' . $document->last_name;
        $type     = property_exists($document, 'type') ? $document->type : Inflector::singular($i::object());
        $time     = time();
        $new_page = array(
            '_id'  => Model::check_id($document->_id),
            'name' => $title,
            'uri'  => $document->uri,
        );
        $post  = array(
            'subscriptions' => array($new_page),
            'page_type'      => $type,
            'page_uri'       => $document->uri,
            'page_name'      => $title,
            'user_id'        => $user->_id,
            'user_uri'       => $user->uri,
            'user_full_name' => $user->first_name . ' ' . $user->last_name,
            'modified'       => $time,
            'type'           => '',
            'status'         => 'publish',
                //'avatar'         => $user->avatar
        );
        switch ($i::action())
        {
            // subscription, then retrieve last subscription within last hour
            // that pertains to a similar document type; i.e. user or location
            // then add to that list and modify timestamp. If not, create new.
            case 'subscribe':

                $criteria = array(
                    'page_type' => $type, // id to remove
                    'modified'  => array('$gte'      => $time - 3600), // last hour
                    'post_type' => 'subscription', // obviously
                    'user_id'   => $user->_id, // the user id.
                );

                // in case we're creating a new post, set the post type.
                $post['type'] = 'subscription';

                // weird thing to do in an update, have to give the whole doc
                $update_set = $post;

                // remove the subscriptions so the other modifier can work on it.
                unset($update_set['subscriptions']);

                // finally, the magical findAndModify command.
                $exists = $this->db->command(
                        array(
                            'findandmodify' => 'posts',
                            // the criteria to find
                            'query'         => $criteria,
                            // the updating if found
                            'update'        =>
                            array(
                                '$addToSet' => array('subscriptions' => $new_page),
                                '$set'          => $update_set,
                            ),
                            // the new post to create if not found
                            'upsert'        => $post
                        )
                );
                break;

            // unsubscribe, check if a recent post has the id and remove it.
            // only done of course if exists
            case 'unsubscribe':
                $criteria = array(
                    'subscriptions.$._id' => $document->_id, // id to remove
                    'page_type'           => $type, // type of subscription
                    'modified'            => array('$gte'    => $time - 3600), // last hour
                    'type'    => 'subscription', // obviously
                    'user_id' => $user->_id, // the user id.
                );

                $update = array(
                    '$pull' => array('subscriptions' => $new_page),
                    '$set'          => array('modified' => $time)
                );

                try
                {
                    $this->collection->update($criteria, $update, array('safe' => TRUE));
                } catch (MongoCursorException $e)
                {
                    Debug::error(
                            "MongoCursorException while trying to remove a :interaction on page :id for user :uid. \n"
                            . "Error Message- :message \n"
                            . "Error Code- :code", array(
                        ':id'              => $_id, ':uid'             => $user->_id,
                        ':interaction'     => $action, ':message'         => $e->getMessage(),
                        ':code'            => $e->getCode()
                    ));
                    return FALSE;
                }
                break;
            // like, just insert new post
            case 'like':
                // in case we're creating a new post, set the post type.
                $post['type']      = 'like';
                $post['post_type'] = 'like';

                // weird thing to do in an update, have to give the whole doc
                $update_set = $post;

                // finally, the magical findAndModify command.
                $this->collection->save($post);
                break;

            // unlike, just delete
            case 'unlike':
                try
                {
                    $this->collection->remove(array('_id' => $_id));
                } catch (MongoCursorException $e)
                {
                    Debug::error(
                            "MongoCursorException while trying to remove a :interaction on page :id for user :uid. \n"
                            . "Error Message- :message \n"
                            . "Error Code- :code", array(
                        ':id'          => $_id, ':uid'         => $user->_id,
                        ':interaction' => $action, ':message'     => $e->getMessage(),
                        ':code'        => $e->getCode()
                    ));
                    return FALSE;
                }
                break;
        }
    }

    /**
     * Small utility to update post counts and timestamps
     *
     * @param MongoID|string $_id the post id
     * @param Interact $i instantated Interact object
     * @return null
     */
    public function update_post($_id, $user_id, Interact $i)
    {
        $test = array('id' => $_id) + $i::get_update_query(parent::check_id($user_id), TRUE);
        //debug::log(print_r($test, TRUE));
        return $this->collection->update(
                        array('_id' => parent::check_id($_id)), $i::get_update_query(parent::check_id($user_id), TRUE), array('safe' => TRUE)
        );
    }

    public function testimonial_approval($_id, $approve)
    {
        $status = $approve ? 'publish' : 'pending';

        try
        {
            $doc = $this->db->command(
                    array(
                        'findandmodify' => 'posts',
                        'query'         => array('_id'    => parent::check_id($_id)),
                        'update' => array('$set' => array('status'   => $status, 'modified' => time())),
                        'upsert'   => FALSE,
                    ));
            if (isset($doc['value']))
                return $doc['value'];
        } catch (MongoCursorException $e)
        {
            Debug::error('Error while updating testimonial status :id -> :e', array(
                ':id' => $_id,
                ':e'  => $e->getMessage()
            ));
        }
    }

    public function testimonial_deletion($_id, $deletion)
    {
        $status = $deletion ? 'trash' : 'pending';

        try
        {
            $doc = $this->db->command(
                    array(
                        'findandmodify' => 'posts',
                        'query'         => array('_id'    => parent::check_id($_id)),
                        'update' => array('$set' => array('status'   => $status, 'modified' => time())),
                        'upsert'   => FALSE,
                    ));
            if (isset($doc['value']))
                return $doc['value'];
        } catch (MongoCursorException $e)
        {
            Debug::error('Error while updating testimonial status :id -> :e', array(
                ':id' => $_id,
                ':e'  => $e->getMessage()
            ));
        }
    }

    /**
     * Because of possible ajax calls (json data), this recursal method is
     * prevalant in all models. Does all needed formatting and checks for
     * current user interactions relative to each record if user is logged in
     *
     * @param bool $object Defaults to true to return array of document objects
     * @return array of MongoDocuments
     */
    protected function _recurse($object = TRUE)
    {
        $return = array();

        // we check if the cursor is valid
        if (is_object($this->cursor))
        {
            // internal MongoDB recursal methods
            while ($this->cursor->hasNext())
            {
                $post = $this->cursor->getNext();
                //echo Debug::vars($page);

                $post = $this->format($post);

                // add this record to the return array
                $return[] = ($object) ? (object) $post : $post;
            }
        }

        return $return;
    }

    /**
     * Utility method to create a HTML title for machine generated posts. This
     * title includes all relevant links to other objects referenced.
     *
     *
     * @param array $post
     * @return string HTML for the title
     */
    protected static function _create_info_title($post)
    {
        $named_location = '<span class="post_in">the&nbsp;' . $post['page_type'] . '&nbsp;';
        $named_location .= html::anchor($post['subscriptions'][0]['uri'], $post['subscriptions'][0]['name']);
        $named_location .= '</span>';
        unset($post['subscriptions'][0]);
        $count          = count($post['subscriptions']);
        $action         = $post['post_type'] == 'subscription' ? 'subscribed to' : 'likes';
        $object         = $post['page_type'] == 'location' ? 'locations' : 'people';
        $browse_lnk     = '/' . Inflector::plural($post['post_type']) . '.php/?' .
                http_build_query(
                        array('type' => $post['page_type'], 'id'   => $post['_id']
                ));

        switch ($count)
        {
            case 0:
                return "$action $named_location";
            case 1:
                return "$action $named_location and " . html::anchor($post['subscriptions'][1]['uri'], $post['subscriptions'][1]['name']);
            default:
                foreach ($post['subscriptions'] as $page)
                {
                    $ids[] = $page['_id'];
                }
                return "$action $named_location and " . html::anchor($browse_lnk, $count . ' other ' . $object);
        }
    }

    protected static function _create_interaction_title($post)
    {
        $own_wall   = $other_wall = FALSE;
        $span       = '<span class="post_in">';

        $page_title = "Go to {$post['page_title']} {$post['page_type']} page";
        if ($post['page_type'] == 'user')
        {
            $page_title = "Go to {$post['page_title']}'s profile page";
            if ($post['page_id'] === $post['user_id'])
                $own_wall   = TRUE;
            else
                $other_wall = TRUE;
        }

        $page_link = html::anchor($post['page_uri'], $post['page_title'], array('title'     => $page_title, 'class'     => 'twip'));
        $close_span = '</span>';

        switch ($post['type'])
        {
            case "testimonial": return "$span wrote a testimonial about $page_link $close_span";
            case "listing": return "$span created a listing in $page_link $close_span";
            case "event": return "$span created an event in $page_link $close_span";
            case "attachment": return "$span uploaded some media in $page_link $close_span";
            case "post" AND $own_wall: return "$span shared a post on his profile page $close_span";
            case "post" AND $other_wall: return "$span shared a post on $page_link's profile $close_span";
            default: return "$span shared a post in $page_link $close_span";
        }
    }

    /**
     * Utility method that formats a post
     *
     * @param MongoDocument $post
     * @return MongoDocument formatted post
     */
    protected function format($post)
    {

        if ($this->_user_id instanceof MongoID)
        {
            $user   = Model::factory('user')->get_selective($this->_user_id, $fields = array(
                'subscriptions', 'counts', 'likes'
                    ));
        }

        // checking for user interactions
        if ($this->_user_id)
        {
            $post['is_owner'] = parent::_is_owner($post, $user);
            $post             = parent::interacting($post, $user, TRUE);
        }

        // check if post type allows comments and retrieve if any exist
        if (isset($post['counts']) AND $post['counts']['comments'] > 0)
        {
            $count            = $post['counts']['comments'] - 2;
            $count            = $count < 0 ? 0 : $count;
            $post['comments'] = parent::factory('comment')
                    ->browse_comments($post['_id'], $count, 2, $this->_user_id);
        }

        // some posts have an array of documents
        if ($post['type'] == 'subscription' OR $post['type'] == 'like')
        {
            foreach ($post['subscriptions'] as $k => $v)
            {
                $post['subscriptions'][$k]['_id'] = $v['_id']->__toString();
            }
            $post['info']                     = self::_create_info_title($post);
        }

        // extract unix timestamp from mongoID and convert to string
        $post['created'] = date('c', $post['_id']->getTimestamp());
        // extract avatar
        $post['avatar']  = Model::factory('user')->avatar($post['user_id'], 60);

        // convert all mongoIDs to string for use in JSON data
        foreach ($post as $k => $v)
        {
            if ($v instanceof MongoID)
                $post[$k]                  = $v->__toString();
        }
        // create an interaction title for the various post types
        if ($post['type'] !== 'subscription' AND $post['type'] !== 'like')
            $post['interaction_title'] = static::_create_interaction_title($post);

        // while we're at it, dates can be formatted too
        $post['modified'] = date('c', $post['modified']);

        return $post;
    }

}