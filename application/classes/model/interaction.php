<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Interaction model. MongoDB awesomeness
 * Schema:
 *      object_type => post|page|user|comment,
 *      object_id   => MongoID
 *      user        =>
 *          _id         => MongoID (retrieved from public static variable user_id),
 *          name        => The name of the user, for ease.
 *
 * @author Jason Nathan
 * @package realtasia-beta-1.0
 * @subpackage Model/Interaction
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Interaction extends Document {

    private static $inc = 1;
    private static $count_field;
    private static $valid_interactions = ['like', 'unlike', 'subscribe', 'unsubscribe'];
    private static $valid_objects = ['post', 'page', 'comment', 'user'];

    /**
     * Proxy method to self::interact
     *
     * Performs a like on an object.
     *
     * @param id|MongoID $object_id
     * @param string $object_type
     *
     * @return boolean|\Model_Interaction
     */
    public function like($object_id, $object_type)
    {
        return $this->interact('like', $object_id, $object_type);
    }

    /**
     * Proxy method to self::interact
     *
     * Performs a subscribe.
     *
     * @param id|MongoID $object_id
     * @param string $object_type
     *
     * @return boolean|\Model_Interaction
     */
    public function subscribe($object_id, $object_type)
    {
        return $this->interact('subscribe', $object_id, $object_type);
    }

    /**
     * Proxy method to self::interact
     *
     * Performs a like on an object.
     *
     * @param id|MongoID $object_id
     * @param string $object_type
     *
     * @return boolean|\Model_Interaction
     */
    public function unlike($object_id, $object_type)
    {
        return $this->interact('unlike', $object_id, $object_type);
    }

    /**
     * Proxy method to self::interact
     *
     * Performs a subscribe.
     *
     * @param id|MongoID $object_id
     * @param string $object_type
     *
     * @return boolean|\Model_Interaction
     */
    public function unsubscribe($object_id, $object_type)
    {
        return $this->interact('unsubscribe', $object_id, $object_type);
    }

    /**
     * Checks if current user is interacting with object
     *
     * @param id|MongoID $object_id
     *
     * @return boolean|array of interactions
     */
    public function interacting($object_id, $object_type)
    {
        if ($this->current_user == NULL)
            return false;

        $results = $this->collection(true)->find([
                    'user._id'  => $this->current_user->_id,
                    'object_id' => $object_id,
                    'object_type' => $object_type
                ]);
        $return = ['like' => false, 'subscribe' => false];
        while($results->hasNext())
        {
            foreach ($results->getNext()->as_array() as $k => $v)
            {
                if ($k == 'interaction')
                {
                    $return[$v] = true;
                }
            }
        }
        return $return;
    }

    /**
     * Sets the user field to the current_user
     *
     * @return void
     */
    public function before_save($action)
    {
        $user = $this->current_user->as_array();
        unset($user['created']);
        if(isset($user['modified']))
            unset($user['modified']);

        $this->user = $user;
    }

    /**
     * 1. Updated the counts in the object.
     *      Upon saving, the object timestamps are updated automatically
     * 2. Saves the current user
     *      Upon saving, timestamps are updated automatically
     *
     * @return \Model_Interaction
     */
    public function after_save($action)
    {
        // guess the update for this interaction.
        $this->guess_update();

        $count_field = static::$count_field;

        $current_user = $this->get_current_user_fields();

        // fetch the concerned object
        $object = Mongo_Document::factory($this->object_type, $this->object_id);

        // update the latest interactions in the object
        if (static::$inc === 1)
        {
            // sadly can't be atomic
            $obj_interact_counts = count($object->{$count_field});

            // we are only saving the latest five interactions
            if ($obj_interact_counts === 5)
            {
                // if 5, then we remove the last entry
                $object->pop($count_field);
            }
            // add it to the set
            $object->addToSet($count_field, $current_user);
        }
        else
        {
            // we just remove the interaction.
            $object->pull($count_field, ['_id' => $current_user['_id']]);
        }

        // increment the count for this interaction
        $object->inc('counts.' . $count_field, static::$inc);
        //print_r($object);
        //die();
        // objects save method automatically updates timestamps
        $object->save();

        // update the user's last active
        $this->current_user->save();

        return $this;
    }


    /**
     * Runs the after_save method as it needs to do the same thing
     *
     */
    public function after_delete()
    {
        $this->after_save();
    }

    /**
     * Performs an interaction. Currently not very optimised.
     * 1. unlike|unsubscribe
     *      delete entry from db.
     *      call the after_save function to update the user and interacted object
     * 2. like
     *      check current interactions and add a like if not available
     *      check if this is a comment and fetch the parent object to run a subscribe
     *      if not a comment, run a subscribe anyway.
     *      call the after_save function to update the user and interacted object
     * 3. subscribe
     *      check current interactions and add a subscribe if not available
     *      call the after_save function to update the user and interacted object
     *
     * @param string $interaction
     * @param string|MongoID $object_id
     * @param string $object_type
     * @return boolean|\Model_Interaction
     */
    protected function interact($interaction, $object_id, $object_type)
    {
        if (!$this->current_user->loaded())
            return false;

        $user = (object) $this->get_current_user_fields();

        // set the query
        $query = [
            'interaction' => $interaction,
            'object_id'   => $object_id,
            'user._id'    => $user->_id,
        ];

        $this->verify_fields($interaction, $object_id, $object_type);

        try
        {
            switch ($this->interaction)
            {
                case 'unlike':
                case 'unsubscribe':
                    // remove the 'un'
                    $query['interaction'] = substr($this->interaction, 2);

                    // delete the subscription
                    $this->collection(true)->remove_safe($query, ['justOne'    => TRUE]);
                    $this->after_delete();
                    break;
                case 'like':

                    // check current interactions with this object
                    $interacting = $this->interacting($object_id, $object_type);

                    // there were no likes by this user on this object
                    if (!$interacting['like'])
                    {
                        $this->save();
                    }
                    /*
                     * Comments are special. If someone likes a comment, we
                     * have to subscribe that person to the object the comment
                     * was created for. Cos we can't subscribe to comments.
                     */
                    if($this->object_type == 'comment')
                    {
                        // get the comment's parent object. variables are
                        // confusing. $object_id refers to this comment _id.
                        // retrieving 'object_id' for it's parent object
                        $p = Mongo_Document::factory('comment')
                                ->get($object_id, ['object_id', 'object_type']);

                        // get the parent object interactions with the current user
                        $p_interacting = $this->interacting($p->object_id, $p->object_type);

                        // same as below, we create a subscribe if none exist.
                        if (!$p_interacting['subscribe'])
                        {
                            $this->clear();
                            $this->interact('subscribe', $p->object_id, $p->object_type);
                        }
                    }
                    else
                    {
                        // this isn't a like on a comment, we proceed with user
                        // subscription of this object
                        if (!$interacting['subscribe'])
                        {
                            $this->clear();
                            $this->interact('subscribe', $object_id, $object_type);
                        }
                    }

                    break;
                case 'subscribe':
                    $interacting = $this->interacting($object_id, $object_type);
                    if (!$interacting['subscribe'])
                    {
                        $this->save();
                    }
                    break;
            }
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoCursorException while user :user tried to :interact an object: :object_type (:oid) \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", [
                ':user'        => $user->name,
                ':interact'    => $interaction,
                ':iod'         => $object_id,
                ':object_type' => $object_type,
                ':message'     => $e->getMessage(),
                ':code'        => $e->getCode()
            ]);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Decides whether it is a positive or negative action based on the interaction
     *
     * @return \Model_Interaction
     */
    protected function guess_update()
    {
        switch ($this->interaction)
        {
            case 'like':
                static::$inc = 1;
                static::$count_field = 'likers';
                break;
            case 'unlike':
                static::$inc = -1;
                static::$count_field = 'likers';
                break;
            case 'subscribe':
                static::$inc = 1;
                static::$count_field = 'subscribers';
                break;
            case 'unsubscribe':
                static::$inc = -1;
                static::$count_field = 'subscribers';
                break;
        }

        return $this;
    }

    /**
     * Verifies the fields needed for an insertion
     *
     * @param string $interaction
     * @param string|MongoID $object_id
     * @param string $object_type
     *
     * @return boolean|\Model_Interaction
     */
    protected function verify_fields($interaction, $object_id, $object_type)
    {
        switch (true)
        {
            case!isset($interaction):
            case!in_array($interaction, self::$valid_interactions):
            case!isset($object_type):
            case!in_array($object_type, self::$valid_objects):
            case!isset($this->current_user):
            case!isset($object_id):
                return false;
        }

        $this->interaction = $interaction;
        $this->object_id   = $object_id;
        $this->object_type = $object_type;

        return $this;
    }
}