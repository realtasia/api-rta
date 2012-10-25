<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of comment
 *
 * @author Jason Nathan
 * @package realtasia-beta-1
 * @subpackage /Model/Comment
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Comment extends Document {


    /**
     * Creates a comment.
     *
     * @param array $params
     */
    public function create($params)
    {
        $this->check_params($params);

        $this->load_values($params);

        $this->save();
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
        $comment = Validation::factory($params);

        $comment
            ->rule('object_id', 'not_empty')
            ->rule('object_type', 'not_empty')
            ->rule('content', 'not_empty');

        $user = (object) $this->get_current_user_fields();

        if ($comment->check())
        {
            $params['user'] = $user;
            $params['counts'] = ['likers' => 0];
            $params['likers'] = [];
            $params['status'] = isset($params['status']) ? isset($params['status']) : 'publish';
            $params['content'] = nl2br(HTML::chars($params['content']));

            return $params;
        }
    }

    /**
     * Deletes a comment by comment id. An object id will help.
     *
     * @todo Delete comment likers as well!
     * @param string|MongoID $object_id
     * @param string|MongoID $comment_id
     * @return type
     */
    public function delete($comment_id, $object_id = NULL)
    {
        $query = array('_id' => $comment_id);

        if ($object_id)
        {
            $query['object_id'] = $object_id;
        }

        try
        {
            $this->collection(true)->remove_safe($query, ['justOne' => TRUE]);

        }
        catch (MongoException $e)
        {
            Debug::error(
                  "MongoCursorException while trying to delete comments id: :comment in :post_type post: :post. \n"
                  . "Error Message- :message \n"
                  . "Error Code- :code", array(
               ':page' => $page->_id, ':post_type' => $post->type,
               ':comment' => $comment_id, ':post' => $post_id,
               ':message' => $e->getMessage(), ':code' => $e->getCode()
            ));
            return FALSE;
        }
    }

    /**
     * Add this user as a subscriber to the object that he commented on.
     *
     * @param type $action
     */
    public function after_save($action)
    {
        $i = Mongo_Document::factory('interaction', null, $user->_id->__toString());
        $i->interact('subscribe', $this->object_id, $this->object_type);
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
        if($post->user->_id === $current_user->_id)
        {
            $this->can_delete = true;
        }

        // check if it's the same user
        if($this->user->_id === $current_user->_id)
        {
            $this->can_delete = true;
        }

        $interactions = Model_Document::factory('interaction', NULL, $current_user->_id);
        $this->interactions = $interactions->interacting($this->_id, 'comment');
    }

}
?>
