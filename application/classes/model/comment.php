<?php defined('SYSPATH') or die('No direct script access.');
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


    /**
     * Creates a comment.
     *
     * @param array $params
     */
    public function create($params)
    {
        $this->collection()->ensureIndex([
            'type' => 1, 'status' => 1, 'modified' => -1
        ], ['safe' => true]);
        $this->collection()->ensureIndex([
            'user._id' => 1
        ], ['safe' => true]);
        $this->collection()->ensureIndex([
            'object._id' => 1, 'object.type' => 1
        ], [ 'safe' => true]);

        $params = $this->check_params($params);

        if(isset($params['_id']))
        {
            $this->atomic($params['_id']);
            unset($params['_id']);
        }

        $this->load_values($params);

        if($this->save())
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
            'object' => [
                '_id' => $params['post']['_id'],
                'type' => $params['post']['type'],
            ],
            'message' => $params['message'],
            'user'    => $params['user'],
            'type'    => 'message'
        ];

        if(isset($params['_id']))
        {
            $this->atomic($params['_id']);
        }

        $this->load_values($params);

        if($this->save())
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

        $pars = $params;
        $pars['object._id'] = Arr::path($params, 'object._id');
        $pars['object.type'] = Arr::path($params, 'object.type');

        $comment = Validation::factory($pars)
                ->rule('object._id', 'not_empty')
                ->rule('object.type', 'not_empty')
                ->rule('message', 'not_empty')
                ->rule('type', 'not_empty');

        $user = (object) $this->get_current_user_fields();

        if ($comment->check())
        {
            $params['user']  = isset($params['user']) ? $params['user'] : $user;
            $params['counts'] = ['likers' => 0];
            $params['likers'] = [];
            $params['status'] = isset($params['status']) ? $params['status'] : 'active';
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
            $query['object._id'] = $object_id;
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
        $i = Mongo_Document::factory('interaction', null, (string) $this->current_user->_id);
        $i->interact('subscribe', $this->object['_id'], $this->object['type']);
        // guess the update for this interaction.
        $this->guess_update();

        $count_field = static::$count_field;

        $interactor = $this->get_default_user_fields();

        // fetch the concerned object
        $object = Mongo_Document::factory($this->object['type'], $this->object['_id']);


        // update the latest interactions in the object
        if (static::$inc === 1)
        {
            // add this subscriber to the list
            $object->addToSet('comments', $interactor);
        }
        else
        {
            // we just remove the interaction.
            $object->pull('comments', ['_id' => $interactor['_id']]);
        }

        // increment the count for this interaction
        $object->inc('counts.comments', static::$inc);
        $object->save();

        /*
         * Small hack to ensure a capped interaction list.
         * @see https://github.com/colinmollenhour/mongodb-php-odm/issues/27#issuecomment-9950515
         */
        if (static::$inc === 1)
        {
            $object->collection()->update_safe(
              ['_id' => $this->object['_id'], 'comments' => ['$size' => 6]],
              ['$pop' => ['comments' => 1]]
            );
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
