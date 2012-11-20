<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Description of comment
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Model/Comment/Document
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Comment extends Document {

    protected $after_delete_fields = [];

    /**
     * Creates a comment.
     *
     * @param array $params
     */
    public function create($params)
    {
        $this->collection()->ensureIndex([
            'type'     => 1, 'status'   => 1, 'modified' => -1
                ], ['safe' => true]);
        $this->collection()->ensureIndex([
            'user._id' => 1
                ], ['safe' => true]);
        $this->collection()->ensureIndex([
            'post._id'  => 1, 'post.type' => 1
                ], [ 'safe' => true]);

        $params = $this->check_params($params);

        if (isset($params['_id']))
        {
            $this->atomic($params['_id']);
            unset($params['_id']);
        }

        $this->load_values($params);

        if ($this->save())
            return $this;

        return false;
    }

    /**
     * A message is only created for the conversation post type. Slightly different
     * because it doesn't have counts or is likeable.
     *
     * @param type $params
     * @return \Model_Comment|boolean
     */
    public function create_message($params)
    {
        $args = [
            'post' => [
                '_id'     => $params['post']['_id'],
                'type'    => $params['post']['type'],
            ],
            'message' => $params['message'],
            'user'    => $params['user'],
            'type'    => 'message'
        ];

        if (isset($params['_id']))
        {
            $this->atomic($params['_id']);
            unset($params['_id']);
        }

        $this->load_values($params);

        if ($this->save())
            return $this;

        return false;
    }

    /**
     * Checks & validates the comment
     *
     * @param array $params
     * @return boolean
     */
    protected static function check_params(array $params)
    {
        if (!$this->current_user->loaded())
            return false;

        $pars                = $params;
        $pars['post._id']  = Arr::path($params, 'post._id');
        $pars['post.type'] = Arr::path($params, 'post.type');

        $comment = Validation::factory($pars)
                ->rule('post._id', 'not_empty')
                ->rule('post.type', 'not_empty')
                ->rule('message', 'not_empty')
                ->rule('type', 'not_empty');

        $user = (object) $this->get_current_user_fields();

        if ($comment->check())
        {
            $params['user']   = isset($params['user']) ? $params['user'] : $user;
            $params['counts'] = ['likers'          => 0];
            $params['likers'] = [];
            $params['status'] = isset($params['status']) ? $params['status'] : 'active';
            return $params;
        }
    }

    /**
     * Extract the post and user id of this comment. Possibly, retrieve the post
     * object.
     */
    public function before_delete()
    {
        $this->after_delete_fields = $this->as_array();
    }

    /**
     * Job should have a delete function with given params. Updates counts and
     * deletes potential likers to this comment.
     */
    public function after_delete()
    {
        $id = Arr::get($this->after_delete_fields, '_id');
        $post = Arr::get($this->after_delete_fields, 'post');
        $user = Arr::get($this->after_delete_fields, 'user');
        $counts = Arr::get($this->after_delete_fields, 'counts');

        $object = parent::factory('post', $post['_id']);

        $object->inc('counts.comments', -1);
        $object->pull('comments', $this->after_delete_fields);
        $object->save();

        if($counts && $counts['likers'] > 1)
        {
            parent::factory('interaction')->collection()
                    ->remove_safe([
                        'object_id' => $id,
                        'object_type' => 'comment',
            ], ['multiple' => true]);
        }

        parent::factory('user', $user['_id'])->save();
    }

    /**
     * Add this user as a subscriber to the object that he commented on.
     *
     * @param type $action
     */
    public function after_save($action)
    {
        if ($action === parent::SAVE_INSERT || $action === parent::SAVE_UPSERT)
        {
            $i = parent::factory('interaction', null, $this->user['_id']);
            $i->interact('subscribe', $this->post['_id'], 'post');

            $object = parent::factory($this->post['type'], $this->post['_id']);

            $object->inc('counts.comments');
            $object->addToSet('comments', $this->as_array());
            $object->save();
            /*
             * Small hack to ensure a capped interaction list.
             * @see https://github.com/colinmollenhour/mongodb-php-odm/issues/27#issuecomment-9950515
             */
            if (static::$inc === 1)
            {
                $object->collection()->update_safe(
                        ['_id'      => $this->_id, 'comments' => ['$size' => 6]], ['$pop' => ['comments' => 1]]
                );
            }
        }

        // update the user's last active
        $this->current_user->save();

        return $this;
    }

    /**
     * Checks if the current_user is interacting with this object.
     * Also checks if the current_user can delete this object
     */
    public function after_load()
    {
        if ($this->current_user == NULL)
            return;

        $current_user = (object) $this->get_current_user_fields();

        // fetch only the required field
        $post = Model_Document::factory($this->object_type)
                ->get($this->object_id, ['user']);

        // check if it's the same user
        if ($post->user->_id === $current_user->_id)
        {
            $this->can_delete = true;
        }

        // check if it's the same user
        if ($this->user->_id === $current_user->_id)
        {
            $this->can_delete = true;
        }

        $interactions       = Model_Document::factory('interaction', NULL, $current_user->_id);
        $this->interactions = $interactions->interacting($this->_id, 'comment');
    }

}

?>
