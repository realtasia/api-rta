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
    public function interacting($object_id)
    {
        if ($this->current_user == NULL)
            return false;

        $results = $this->collection(true)->find([
                    'user._id'  => $this->current_user->_id,
                    'object_id' => $object_id
                ])->as_array();

        $return = [];

        if (!empty($results))
        {
            foreach ($results as $k => $v)
            {
                if ($k === 'interaction')
                {
                    $return[$v] = true;
                }
            }
        }

        return empty($results) ? false : $return;
    }

    /**
     * Sets the user field to the current_user
     *
     * @return void
     */
    public function before_save($action)
    {
        if (isset($this->interaction))
            return false;

        $this->user = $this->current_user;
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

        // fetch the concerned object
        $object = Mongo_Document::factory($this->object_type, $this->object__id);

        // update the latest interactions in the object
        if (static::$inc === 1)
        {
            // sadly can't be atomic
            $obj_interact_counts = count($object->{static::$count_field});

            // we are only saving the latest five interactions
            if ($obj_interact_counts === 5)
            {
                // if 5, then we remove the last entry
                $object->pop(static::$count_field);
            }
            // add it to the set
            $object->addToSet(static::$count_field, $this->as_array());
        }
        else
        {
            // we just remove the interaction.
            $object->pull(static::$count_field . '._id', $this->_id);
        }

        // increment the count for this interaction
        $object->inc('counts.' . $this->{static::$count_field}, static::$inc);

        // objects save method automatically updates timestamps
        $object->save();

        // update the user's last active
        $this->current_user->save();

        return $this;
    }

    /**
     * Performs an interaction. Currently not very optimised.
     *
     * @param string $interaction
     * @param string|MongoID $object_id
     * @param string $object_type
     * @return boolean|\Model_Interaction
     */
    protected function interact($interaction, $object_id, $object_type)
    {

        if ($this->current_user == NULL)
            return false;

        // set the query
        $query = [
            'interaction' => $interaction,
            'object_id'   => $object_id,
            'user._id'    => $this->current_user->_id,
        ];

        $this->verify_fields($interaction, $object_id, $object_type);

        switch ($this->interaction)
        {
            case 'unlike':
            case 'unsubscribe':
                $this->collection(true)->remove_safe($query);
                $this->after_save(null);
                break;
            case 'like':
                $interacting = $this->interacting($object_id);
                if (!$interacting['like'])
                {
                    $this->save();
                }
                if (!$interacting['subscribe'])
                {
                    $this->clear();
                    $this->interact('subscribe', $object_id, $object_type);
                }
                break;
            case 'subscribe':
                $interacting = $this->interacting($object_id);
                if (!$interacting['subscribe'])
                {
                    $this->save();
                }
                break;
        }
        return $this;
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
            case 'unlike':
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
                return false;
        }

        $this->interaction = $interaction;
        $this->object_id   = $object_id;
        $this->object_type = $object_type;

        return $this;
    }
}