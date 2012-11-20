<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 *   db.oauth_clients.insert({name :, client_id :, client_secret :, redirect_uri :, status :, auto_approve :});
 */

class Model_Oauth2_Resource extends Document {

    protected $name = 'oauth2_resources';

    /**
     * An implementation to validate a Resource Owner Flow by user credentials
     *
     * @param array $params query fields, usually (client_id, client_secret, redirect_uri)
     * @param boolean $auto_approve
     * @uses Model_User
     *
     * @return boolean
     */
    public function check_password($username, $password)
    {
        $params = array(
            '$or' => array(
                array('username' => $username),
                array('email'    => $username),
            ),
            'password' => static::hash($password));

        $user = Document::factory('user');
        if($user->load($params, ['_id']));

        return $user->loaded() ? $this->stringify($user->as_array()) : false;
    }


}