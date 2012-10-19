<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mongo Beta Model.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model/beta
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model_Beta extends Model{

    public static $salt = "a134Kt.aM9BaE";

    public function new_beta_user($fields)
    {
        $beta_key = md5($fields['email'] . static::$salt);
        $merge = Arr::merge(static::_beta_defaults($beta_key), $fields);

        try
        {
            $this->collection->update(array('email' => $fields['email']),$merge, array('safe' => 1, 'upsert' => 1));
            return $merge['beta_key'];
        }
        catch (MongoCursorException $e)
        {
            // log the error and return
            Debug::error(
                  "MongoCursorException while updating new beta key (email-:uid). \n"
                  . "Error Message- :message \n"
                  . "Error Code- :code", array(
               ':uid' => $email,
               ':message' => $e->getMessage(),
               ':code' => $e->getCode()
            ));
            return FALSE;
        }
        return FALSE;
    }

    public function bulk_insert_emails($emails)
    {
        $users = array();
        $existing = $this->collection->find(
              array('email'=> array('$in' => $emails)),
              array('email' => 1)
        );


        if($existing->hasNext())
            $emails = $this->remove_dups($emails, $existing);

        foreach($emails as $email)
        {
            $beta_key = md5($email . static::$salt);
            $users[] = array_merge(static::_beta_defaults($beta_key), array('email' => $email));
        }

        if(!empty($users))
        {
                try
                {
                    return $this->collection->batchInsert($users);
                }
                catch (MongoCursorException $e)
                {
                    // log the error and return
                    Debug::error(
                          "MongoCursorException while batch inserting emails (emails :- :uid). \n"
                          . "Error Message- :message \n"
                          . "Error Code- :code", array(
                       ':uid' => print_r($users, TRUE),
                       ':message' => $e->getMessage(),
                       ':code' => $e->getCode()
                    ));
                    return FALSE;
                }
        }

    }

    public function update_beta_status($_id)
    {

    }

    public function update_spam($email)
    {
        return $this->collection->update(array(
           'email' => $email
        ), array('$set' => array('spammed' => 1)));
    }

    public function fetch($args = array())
    {
        $this->cursor = $this->collection->find($args);
        return $this;
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
                $user = $this->cursor->getNext();

                //echo Debug::vars($page);

                // add this record to the return array
                $return[] = ($object) ? (object) $user : $user;
            }
        }

        return $return;
    }

    protected static function _beta_defaults($beta_key = NULL)
    {
        return array(
           'name' => '',
           'email'        => '',
           'beta_key'     => $beta_key,
           'user_type'    => '',
           'message'      => '',
           'spammed'      => 0,
           'interested'   => 0,
           'welcomed'     => 0
        );
    }

    protected static function remove_dups($emails, $cursor)
    {
        $emails = array_flip($emails);
        while($cursor->hasNext())
        {
            $email = $cursor->getNext();
            unset($emails[$email['email']]);
        }
        return array_values(array_flip($emails));
    }
}