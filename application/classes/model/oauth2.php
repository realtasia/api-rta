<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 *   db.oauth_clients.insert({name :, client_id :, client_secret :, redirect_uri :, status :, auto_approve :});
 */

class Model_Oauth2 extends Model {

    public function check_password($username, $password)
    {
        $params = array(
            '$or' => array(
                array('username' => $username),
                array('email'    => $username),
            ),
            'password' => $password);
        
        $user_id = Model::factory('user')->findOne($params, array('_id' => 1));
        
        return $user_id ? $user_id : false;
    }

    /**
     * An implementation to validate client credentials. Since this can be used
     * in an oAuth2 Client Side Application Flow, a Resource Owner Password Flow
     * and for simply checking if a client exists, we add the auto-approve flag 
     * to it for extensibility,
     * 
     * @param array $params query fields, usually (client_id, client_secret, redirect_uri)
     * @param boolean $auto_approve
     * 
     * @return boolean
     */
    public function check_client($params, $auto_approve = false)
    {
        $client = $this->collection->findOne($params);

        if ($client)
        {
            if ($auto_approve)
            {
                return $client['auto_approve'] ? $client : false;
            }

            return $client;
        }

        return false;
    }

}