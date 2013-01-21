<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of comment
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Controller/User
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Comment extends Controller_Resource {


    /*************************************************************************
     * Private Actions
     ************************************************************************ */
    /**
     * Retrieves a comment.
     *  - Checks if the comment author is current user.
     *  - Checks if post owner is current user.
     *  - Checks if page owner is current user.
     *  - Checks if admin user.
     *
     *  If any thing checks out, we delete the comment
     */
    public function action_delete()
    {
        $id = $this->request->param('id');

        $comment = Document::factory('comment', $id);

        // one by one. First we check the comment user
        if ($comment->load(null, ['_id'    => 1, 'user'   => 1, 'post'   => 1, 'counts' => 1]))
        {
            $is_owner = static::logged_in_is_id((string) $comment->user['_id']);

            if ($is_owner || parent::has_role('admin'))
            {
                $comment->delete();
                return $this->data(['success' => 1]);
            }

            $post = Document::factory('post', $comment->post['_id']);

            // now we check the post user
            if ($post->load(null, ['user' => 1, 'page' => 1]))
            {
                $is_post_owner = static::logged_in_is_id((string) $post->user['_id']);
                $page_is_user  = static::logged_in_is_id((string) $post->page['_id']);
                if ($is_post_owner || $page_is_user)
                {
                    $comment->delete();
                    return $this->data(['success' => 1]);
                }
                // still here
                $page = Document::factory('page', $post->page['_id']);

                // now we check the page user
                if ($page->load(null, ['user' => 1]))
                {
                    if (static::logged_in_is_id((string)$page->user['_id']))
                    {
                        $comment->delete();
                        return $this->data(['success' => 1]);
                    }
                }
            }
        }

        return $this->error('access_denied');
    }


    /**************************************************************************
     * Public Connections
     * ************************************************************************/

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

    /**************************************************************************
     * Public Actions
     ************************************************************************ */

    /**
     * Performs a like on the given post
     */
    public function action_like()
    {
        return parent::interact('like', 'comment');
    }

    /**
     * Performs an unlike on the given post
     */
    public function action_unlike()
    {
        return parent::interact('unlike', 'comment');
    }

    public function action_get()
    {
        $defaults = [
           'limit' => 2, 'skip' => 0, 'type' => 'comment', 'sort' => -1,
           'post_id' => $this->request->param('id'), 'include_ids' => NULL,
            'status' => 'active'
        ];

        $params = Arr::merge($defaults, static::$params);
        $params = Arr::overwrite($defaults, $params);

        $c = Collection::factory('comment')->fetch($params, $this->get_user());

        return $this->data($c);

    }


}