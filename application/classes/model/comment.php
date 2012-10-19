<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mongo Comment Model.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model_Comment extends Model
{

    /**
     * Does sorting and limiting. $args is an array that defaults to:
     *
     *  array(
     *      'limit' => 2, 'offset' => 0, 'type' => 'comment', 'sort' => 'ASC',
     *      'comment_author_id' => FALSE, 'post_id' => FALSE, 'include_ids' => FALSE,
     *      'user_id' => FALSE, 'status' => 'publish'
     *  );
     *
     * @param array $args
     * @return self Sets the $cursor property and returns itself
     */
    public function fetch($args)
    {
        $defaults = array(
           'limit' => 2, 'offset' => 0, 'type' => 'comment', 'sort' => 'ASC',
           'author_id' => FALSE, 'post_id' => FALSE, 'include_ids' => FALSE,
           'user_id' => FALSE, 'status' => 'publish'
        );

        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        //we need a criteria
        if (!$author_id AND !$post_id AND !$include_ids)
            return array();

        if ($user_id)
            $this->_user_id = parent::check_id($user_id);

        $query = $ids = array();

        if ($post_id)
            $query['post_id'] = parent::check_id($post_id);

        if ($author_id)
        {
            if (is_array($author_id))
                $query['user_id'] = array(
                   '$in' => Arr::map('Model::check_id', $author_id)
                );
            else
                $query['user_id'] = $author_id;
        }

        if ($include_ids)
        {
            if (is_array($include_ids))
                $query['_id'] = array(
                   '$in' => Arr::map('Model::check_id', $include_ids)
                );
            else
                $query['_id'] = $include_ids;
        }

        $query['status'] = $status;

        $sort = $sort == 'ASC' ? array('_id' => 1) : array('_id' => -1);

        $this->cursor = $this->collection
              ->find($query)
              ->limit($limit)
              ->skip($offset)
              ->sort($sort);

        return $this;
    }

    public function get($_id, $object = TRUE)
    {
        $comment = $this->collection
                        ->findOne(array('_id' => parent::check_id($_id)));
        if(empty($comment)) return NULL;

        return $object ? (object) $comment : $comment;
    }

    public function get_by_post($post_id, $user_id)
    {
        $comment = $this->collection
                        ->findOne(array(
                           'post_id' => parent::check_id($post_id),
                           'user_id' => parent::check_id($user_id)
                        ));
        return (empty($comment)) ? NULL : $comment;
    }

    /**
     * Comment validation and insertion
     *
     * @param string|MongoID $post_id the post this comment belongs to
     * @param MongoDocument $user the user object
     * @param array $comment_array the comment content
     * @return bool
     */
    public function new_comment($post_id, $user, $comment_array)
    {
        $comment = array(
           '_id' => new MongoID($user->username),
           'likers' => array(),
           'user_id' => parent::check_id($user->_id),
           'post_id' => parent::check_id($post_id),
           'author' => $user->first_name . ' ' . $user->last_name,
           'author_url' => Route::get('browse')
                 ->uri(array('action' => 'profile', 'format' => 'php')) . '?id=' . $user->_id->__toString(),
           'content' => nl2br(HTML::chars($comment_array['content'])),
           'status' => isset($comment_array['status']) ? $comment_array['status'] : 'publish'
        );

        try
        {
            $this->collection->save($comment, array('safe' => TRUE));
        }
        catch (MongoCursorException $e)
        {
            Debug::error(
                  "MongoCursorException while create comment ':interaction' for post :id for user :uid. \n"
                  . "Error Message- :message \n"
                  . "Error Code- :code", array(
               ':id' => $_id, ':uid' => $user->_id,
               ':interaction' => $comment['comment_content'], ':message' => $e->getMessage(),
               ':code' => $e->getCode()
            ));
            return FALSE;
        }

        return self::format($comment, $user->_id);
    }

    /**
     * Deletes a comment by post id or by comment id.
     *
     * @todo Delete comment likers as well!
     * @param string|MongoID $post_id
     * @param string|MongoID $comment_id
     * @return type
     */
    public function delete($post_id, $comment_id = NULL)
    {
        $query = array('post_id' => parent::check_id($post_id));

        if ($comment_id)
        {
            $query['_id'] = parent::check_id($comment_id);
        }

        try
        {
            $this->collection
                 ->remove($query, array('safe' => TRUE, 'justOne' => TRUE));

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
     * Since comments only have the like interaction, this is a simplified version
     * of the parent Interact function
     *
     * @param string|MongoID $_id the comment id
     * @param string|MongoID $user_id
     * @param Interact $i instantated Interact object
     * @return bool
     */
    public function process_liking($_id, $user_id, Interact $i)
    {
        // verify the user_id and _id to ensure they are MongoIDs
        $_id = self::check_id($_id);
        $user_id = self::check_id($user_id);

        // this adds criteria to ensure there are no duplicated
        // interactions
        if ($i::is_positive())
        {
            // the extra criterias, user_id is not in the list of this interaction
            $criteria = array(
               '_id' => $_id,
               'likers' => array('$nin' => array($user_id))
            );
        }
        else
        {
            // the extra criterias, user_id is in the list of this interaction
            $criteria = array(
               '_id' => $_id,
               'likers' => array('$in' => array($user_id))
            );
        }

        try
        {
            $this->collection
                  ->update($criteria, array(
                     $i::get_modifier() => array('likers' => $user_id),
                     '$set' => array('modified' => time())
                        ), array('safe' => TRUE)
            );
        }
        catch (MongoCursorException $e)
        {
            // log the error and return
            Debug::error(
                  "MongoCursorException while trying to update comment's (id-:id) ':interaction' by user (id-:uid). \n"
                  . "Error Message- :message \n"
                  . "Error Code- :code", array(
               ':id' => $_id, ':uid' => $user_id,
               ':interaction' => $i->action, ':message' => $e->getMessage(),
               ':code' => $e->getCode()
            ));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * shorthand method for fetch
     *
     *
     * @param string|MongoID $post_id the post id
     * @param int $skip The offset to retrieve records from
     * @param int $limit The number of records to retrieve
     * @param string|MongoID $user_id
     * @return array of comments
     */
    public function browse_comments($post_id, $skip = 0, $limit = 50, $user_id = NULL)
    {
        $args = array('post_id' => $post_id, 'offset' => $skip, 'limit' => $limit, 'user_id' => $user_id);

        $this->fetch($args);

        return $this->as_array();
    }

    /**
     * Because of possible ajax calls (json data), this recursal method is
     * prevalant in all models. Does all needed formatting and checks for
     * current user interactions relative to each record if user is logged in
     *
     * @param bool $object Defaults to true to return array of document objects
     * @uses Model_Comment::format()
     * @return array of formatted MongoDocuments
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
                //$comment = $this->cursor->getNext();
                //echo Debug::vars($page);

                $comment = self::format($this->cursor->getNext(), $this->_user_id);

                // add this record to the return array
                $return[] = ($object) ? (object) $comment : $comment;
            }
        }

        return $return;
    }

    /**
     * This method is used internally to format comments
     *
     *
     * @param MongoDocument $comment
     * @param MongoID $user_id
     * @uses self::_comment_liking()
     * @return MongoDocument $comment
     */
    protected static function format($comment, $user_id = NULL)
    {
        if (!isset($comment['_id']) AND is_array($comment))
        {
            // if comment is an array of comments, we apply this formatting recursively
            foreach ($comment as $k => $v)
            {
                $comment[$k] = self::format($v, $user_id);
            }

            return $comment;
        }

        // format the date
        $comment['created'] = date('c', $comment['_id']->getTimestamp());
        // create a [pst_id key
        $comment['avatar'] = Model::factory('user')->avatar($comment['user_id'], 32);

        // set to NULL
        $comment['liking'] = NULL;

        // check if current user likes this
        if ($user_id)
        {
            $comment = self::_comment_liking($comment, $user_id);
            $comment['is_owner'] = parent::_is_owner($comment, $user_id);
        }

        return $comment;
    }

    /**
     *
     * This method differs slightly from the original because we check a user id
     * against the comment likers array as opposed to checking a document id in
     * a user's likes array so that we don't have to retrieve user likes for
     * every comment.
     *
     * @param MongoDocument $document raw document array from the DB
     * @return MongoDocument $document formatted document array with HTML links
     */
    protected static function _comment_liking($comment, $user_id)
    {

        // the number of likers in this document
        $counts = count($comment['likers']);
        // the post id and id
        $id = $comment['post_id'] . '/' . $comment['_id'];

        // the controller that processes comment likes
        $controller = 'comment';

        // check if user has liked this document
        if (in_array($user_id, $comment['likers']))
        {
            // set the unlike link + middot + the opening span HTML
            $comment['liking'] = button::comment_unlike($controller, $id, $counts, array('liking'));

            return $comment;
        }

        $comment['liking'] = button::comment_like($controller, $id, $counts);

        return $comment;
    }

}