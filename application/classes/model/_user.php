<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mongo User Model.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model_User extends Model {

    /**
     *
     * This method is mostly identical to the latter. Only the record retrieved
     * does not include the lists of subscribers, subcriptions etc. Contains only
     * relevant information about the user including his various counts
     *
     * @param string $username can be username or email
     * @param boolean $object defaults to true, decides how to return record
     * @uses self::_get_one()
     * @return mixed user object or array if found. NULL if not found.
     */
    public function get($username, $object = true)
    {
        if (empty($username))
            return false;

// remove uneeded fields from query
        $user = $this->collection->findOne(self::_get_one($username));

// if no record found, return NULL
        if (empty($user))
            return NULL;

        $user['avatar'] = $this->avatar($user, array(32, 60, 150));

        return ($object) ? (object) $user : $user;
    }

    public function get_public($username, $object = true)
    {
        if (empty($username))
            return false;

// remove uneeded fields from query
        $user = $this->collection->findOne(self::_get_one($username), [
            'password'         => 0,
            'email'            => 0,
            'telephone'        => 0,
            'roles'            => 0,
            'social'           => 0,
            'notifications'    => 0,
            'avatar.uncropped' => 0,
         ]);

// if no record found, return NULL
        if (empty($user))
            return NULL;

        $user['avatar'] = $this->avatar($user, array(32, 60, 150));

        return ($object) ? (object) $user : $user;
    }

    /**
     * This method is mostly identical to the former except that it fetches the
     * entire user object. Also if $user_id is set, it checks for subscriptions
     *
     * @param string $username can be username or email
     * @param boolean defaults to true, decides how to return record
     * @uses self::_get_one()
     * @return mixed user object or array if found. NULL if not found
     */
    public function get_full($username, $user_id = false, $object = true)
    {
        if (empty($username))
            return FALSE;

        // just do a regular findOne
        $user = $this->collection->findOne(self::_get_one($username));

        if (empty($user))
            return NULL;

        // If a user_id is given and it doesn't belong to this user object
        if ($user_id AND parent::string_id($user_id) !== $user['_id']->__toString())
        {
            //set the model $_user_id property.
            $this->_user_id = parent::check_id($user_id);

            //check if logged in user is subscribing to this user object
            $user['actions'] = $this->interacting($user, $this->_user_id);
        }

        $user['avatar'] = $this->avatar($user, array(32, 60, 150));

        return ($object) ? (object) $user : $user;
    }

    /**
     * Does a search, sort and limit. $args is an array of arguments to use for a member search.
     * Defaults to:
     *       array(
     *         'limit' => 11, 'offset' => 0, 'roles' => array(),
     *         'sort' => 'alpha', 's' => '', 'include_ids' => false,
     *         'object_id' => false, 'user_id' => false, 'children' => false
     *       )
     *
     * @param array $args
     * @uses self::_get_one()
     * @return self Sets the parent::$cursor property and returns itself
     */
    public function search($args = array())
    {
        $defaults = array(
            'limit'  => 11, 'offset' => 0, 'roles'  => array(), 'sort'        => 'alpha',
            's'           => '', 'include_ids' => false, 'object_id'   => false,
            'user_id'     => false, 'children'    => false
        );

        extract(Arr::merge($defaults, $args), EXTR_SKIP);

// set the vars to an empty array. $ids is used for an $in query
        $query = $ids   = array();

//set the model $_user_id property for formatting during recursal
        if ($user_id)
            $this->_user_id = parent::check_id($user_id);

// looking for a specific role
        if (!empty($roles))
            $query['roles'] = array('$in' => $roles);

// here's where we set the $id array
        if (!empty($include_ids))
            $ids = array_merge_recursive($ids, $include_ids);

// this is ambiguous. This is set to remove the parent object id
// used primarily for child searches within a parent. Used in this
// case to see a subscriber's friend subscriptions & not himself
        if ($object_id)
            $query['_id']['$ne'] = new MongoID($object_id);

// keyword searches
        if ($s)
// this time it's a regex search -> not good, no indexing...
            $query = Arr::merge($query, self::_get_one($s, TRUE));

// iterate through the $ids and make sure they're all MongoID objects
        if (!empty($ids))
            $query['_id']['$in'] = array_map(array($this, 'check_id'), $ids);

// assign the parent $cursor property for recursal at a later stage.
        $this->cursor = $this->collection->find($query, array(
                    'social'        => 0, 'subscriptions' => 0,
                    'subscribers'   => 0, 'likers'        => 0,
                    'email'         => 0, 'password'      => 0
                        )
                )->limit($limit);

// which page are we on?
        if ($offset > 0)
            $this->cursor->skip($offset);

// @TODO: set sort options properly
        $this->cursor->sort(array('_id' => 1));
        return $this;
    }

    /**
     * Deletes user
     *
     * @param string username or email
     * @return boolean
     */
    public function delete($user)
    {
        return empty($user) ? FALSE : $this->collection->remove(self::_get_one($user));
    }

    /**
     * Updates timestamps and counts of a user, normally after he interacted on
     * an object.
     *
     * @param string|MongoID $user_id
     * @param string|MongoID $_id the current object id;
     * @param Interact $i an instantated Interact object
     * @return Model
     */
    public function auto_update_user($_id, $user_id, Interact $i)
    {
        $_id     = parent::check_id($_id);
        $user_id = parent::check_id($user_id);

        // comments are special. We don't remove user subscriptions just because his
        // comment is deleted. He might have other comments. check if there are
        // existing comments by this user if this user has an existing comment,
        // we don't touch the subscriptions. The query is created by Interactions
        if (($i::object() == 'comments' AND $i::action() == 'delete') AND self::factory('comment')->get_by_post($_id, $user_id))
            $update = $i::get_user_update_query($_id);
        else
            $update = $i::get_user_update_query($_id, TRUE);

        // this adds another criteria to ensure there are no duplicated
        // interactions
        $usr_criteria = $i::get_user_criteria_query($_id);

        // the default query
        $usr_criteria['_id'] = $user_id;

        // then we update the user (the interactor)
        try
        {
            // since this user did something, we update his "active" field
            $u = Model::factory('user')->collection->update(
                    $usr_criteria, $update, array('safe' => TRUE)
            );
        } catch (MongoCursorException $e)
        {
            // log the error and return
            Debug::error(
                    "MongoCursorException while trying to update user's (id-:uid) ':interaction' to document (id-:id). \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", array(
                ':id'          => $_id, ':uid'         => $user_id,
                ':interaction' => $i::action(), ':message'     => $e->getMessage(),
                ':code'        => $e->getCode()
            ));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * A utility method that loops through all user objects and removes a document
     * form their list of subscriptions, likes etc. Also decreases the counts.
     *
     * Used when a document, normally a post, is deleted.
     *
     * @param MongoDocument Object $post the post object to be deleted
     * @return bool
     */
    public function clean_user_documents($post)
    {
        $type    = Inflector::plural($post->type);
        $post_id = parent::check_id($post->_id);
        //Debug::log(print_r($post, TRUE));

        if (!empty($post->likers))
        {
            $remove['$inc'] = array('counts.likes'   => -1);
            $remove['$pull'] = array('likes.' . $type => $post_id);
            $try             = $this->_update_multiple(array(
                '_id' => array('$in' => $post->likers)
                    ), $remove);
            //Debug::log(print_r($remove, TRUE));
        }

        if (!empty($post->subscribers))
        {
            $remove['$inc'] = array('counts.subscriptions' => -1, 'counts.' . $type      => -1);
            $remove['$pull']       = array('subscriptions.' . $type => $post_id);
            $try                     = $this->_update_multiple(array(
                '_id' => array('$in' => $post->subscribers)
                    ), $remove);
            //Debug::log(print_r($remove, TRUE));
        }

        if ($post->counts['comments'] > 0)
            parent::factory('comment')->delete($post->_id);

        return TRUE;
    }

    public function register($fields)
    {
        $new_user = [];

        list($month, $day, $year) = explode('/', $fields['birthday']);

        $new_user['first_name']          = ucfirst(strtolower($fields['first_name']));
        $new_user['middle_name']         = ucfirst(strtolower($fields['last_name']));
        $new_user['last_name']           = ucfirst(strtolower($fields['last_name']));
        $new_user['name']                = $new_user['first_name'] . ' ' . $new_user['last_name'];
        $new_user['username']            = $fields['nicename'];
        $new_user['uri']                 = '/' . $fields['nicename'];
        //$new_user['email'][] = $fields['email'];
        $new_user['telephone']['mobile'] = '+65 ' . $fields['mobile'];
        $new_user['birthday']            = mktime(0, 0, 0, $month, $day, $year);
        $new_user['gender']              = $fields['gender'];
        $new_user['password']            = Auth::instance()->hash($fields['password']);

        if (isset($fields['oauth_token']))
        {
            $new_user['social']['facebook'] = [
                'expires'     => $fields['expires'],
                'issued_at'   => $fields['issued_at'],
                'oauth_token' => $fields['oauth_token'],
                'id'          => $fields['id'],
                'link'        => $fields['link']
            ];

            $new_user['name'] = $fields['name'];
        }

        $user = $this->get($fields['email'], FALSE);

        if ($user)
        {
            $user['email'] = Arr::merge($user['email'], [$fields['email']]);
            $merge = Arr::merge(self::_user_defaults(), $user, $new_user);
        }
        else
        {
            $defaults          = self::_user_defaults();
            $defaults['email'] = Arr::merge($defaults['email'], [$fields['email']]);
            $merge = Arr::merge($defaults, $new_user);
        }

        $merge = self::_make_unique_fields($merge);

        try
        {
            $this->collection->save($merge, ['safe' => TRUE]);
            return $merge;
        } catch (MongoCursorException $e)
        {
// log the error and return
            Debug::error(
                    "MongoCursorException while trying to register user (email-:uid). \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", [
                ':uid'     => $fields['email'],
                ':message' => $e->getMessage(),
                ':code'    => $e->getCode()
            ]);
            return FALSE;
        }
    }

    /**
     * Retrieve user avatar or default avatar by size
     *
     * @param MongoID $_id
     * @param int $size
     * @return type
     */
    public function avatar($_id, $size)
    {
        if ((is_object($_id) AND !$_id instanceof MongoID) OR is_array($_id))
        {
            $avatar = Arr::get((array) $_id, 'avatar', FALSE);
        }
        else
        {
            $result = $this->collection->findOne(
                    array('_id' => parent::check_id($_id)), array('avatar' => 1)
            );
            $avatar  = $result['avatar'];
        }
//die(debug::vars($avatar));
        if (!is_array($size))
            $size    = (array) $size;

        $return = array();

        foreach ($size as $k => $s)
        {
            if (isset($avatar[$s]) AND !empty($avatar[$s]))
            {
                if (strpos($avatar[$s], 'http://') === FALSE)
                    $return[$s] = 'http://' . $avatar[$s];
                else
                    $return[$s] = $avatar[$s];
            }
            else
            {
                $return[$s] = URL::site("/assets/images/avatar-{$s}X{$s}.jpg", 'http');
            }
        }

        return count($return) === 1 ? implode('', $return) : $return;
    }

    /**
     * Loops through the result set and sets default params. Also checks for
     * subscription against the current logged in user.
     * Recursal ideally shouldn't be done here especially when db size grows,
     * should reconsider this decision at a later stage
     *
     * @param boolean defaults to true to return an array of user objects
     * @return array
     */
    protected function _recurse($object = true)
    {
        //die(Debug::vars($this->cursor->explain()));
        // the final array
        $return = array();
        if (is_object($this->cursor))
        {
            while ($this->cursor->hasNext())
            {
                $person = $this->cursor->getNext();
                // check if property $_user_id is set to verify subscriptions
                if ($this->_user_id)
                {
                    $person['actions'] = $this->interacting($person, $this->_user_id);
                }

                $person['avatar'] = $this->avatar($person, array(150, 60, 32));

                // changing dates to something human readable
                $person['joined'] = date('c', $person['_id']->getTimestamp());
                $person['active'] = date('c', $person['active']);

                // assigining to string for javascript to be able to read the id
                $person['id'] = $person['_id']->__toString();

                // check user roles. Assumption is Staff takes prority, must
                // ensure roles are removed for paying users who default payment
                if (in_array('staff', $person['roles']))
                    $person['subtype'] = 'Realtasia Staff';
                elseif (in_array('agent', $person['roles']))
                    $person['subtype'] = 'Realtasia Agent';
                else
                    $person['subtype'] = 'User';

                $return[] = ($object) ? (object) $person : $person;
            }
        }

        return $return;
    }

    protected static function _user_defaults()
    {

        $counts = array(
            'attachments'   => '0',
            'events'        => '0',
            'likes'         => '0',
            'listings'      => '0',
            'locations'     => '0',
            'subscriptions' => '0',
            'testimonials'  => '0',
        );

//        $subs = array(
//              'attachments'	=> array(),
//              'events'        => array(),
//              'listings'	=> array(),
//              'locations'	=> array(),
//              'posts'         => array(),
//              'testimonials'	=> array(),
//              'users'         => array(),
//        );

        return array(
            'active' => time(),
            'avatar' => array(
                32        => 'beta.realtasia.com.sg/assets/images/avatar-32X32.jpg',
                60        => 'beta.realtasia.com.sg/assets/images/avatar-60X60.jpg',
                150       => 'beta.realtasia.com.sg/assets/images/avatar.jpg',
                'cropped' => array(),
                'uncropped' => array(),
            ),
            'birthday'    => '',
            'counts'      => $counts,
            'designation' => '',
            'name'        => '',
            'email'       => array(),
            'first_name' => '',
            'last_name'  => '',
            'likers'     => array(),
            'middle_name' => '',
//           'notifications'  => array(
//                  'comments'      => array(
//                     'other_posts'  => 1,
//                     'own_post'     => 1,
//                  ),
//                  'likers'        => array(
//                     'other_posts'  => 1,
//                     'own_post'     => 1,
//                     'profile'      => 1
//                  ),
//                  'profile_posts' => 1,
//                  'subscribers'   => array(
//                     'own_post'     => 1,
//                     'profile'      => 1
//                  ),
//                  'subscriptions' => array(
//                     'locations'    => 'weekly',
//                     'profiles'     => 'weekly'
//                  ),
//                  'testimonials'  => 1,
//           ),
            'password'    => '',
            'profile_id'  => '',
            'roles'       => array('login'),
            'social' => array(),
            'status'   => '',
            'username' => '',
        );
    }

    protected static function _make_unique_fields($user)
    {
        $user = (array) $user;
        foreach ($user as $k => $v)
        {
            if (is_array($v) AND array_keys($v) === range(0, count($v) - 1))
            {
                $user[$k] = array_values(array_filter($v));
            }
        }
        ksort($user);
        return $user;
    }

    protected static function _is_post_interaction($action)
    {
        $post_types = array(
            'post', 'testimonial', 'listing', 'event'
        );

        foreach ($post_types as $check)
        {
            if (strpos($action, $check) !== FALSE)
            {
                return Inflector::plural($check);
            }
        }

        return FALSE;
    }

    protected function _update_multiple($criteria, $update)
    {
        try
        {
            return $this->collection
                            ->update($criteria, $update, array(
                                'multiple' => TRUE, 'safe'     => TRUE
                            ));
        } catch (MongoException $e)
        {
            Debug::error(
                    "MongoCursorException while trying to update multiple user documents with criteria: :criteria \n
                   with update query: :update \n"
                    . "Error Message- :message \n"
                    . "Error Code- :code", array(
                ':criteria' => print_r($criteria, TRUE), ':update'   => print_r($update, TRUE),
                ':message'  => $e->getMessage(), ':code'     => $e->getCode()
            ));
            return FALSE;
        }
    }

}