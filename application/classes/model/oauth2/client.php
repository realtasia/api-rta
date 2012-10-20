<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 *   db.oauth_clients.insert({name :, client_id :, client_secret :, redirect_uri :, status :, auto_approve :});
 */

class Model_Oauth2_Client extends Model {

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
            'password' => $this->hash($password));

        $user_id = Model::factory('user')->collection->findOne($params, ['_id' => 1]);

        return $user_id ? $user_id : false;

    }

    /**
     * An implementation to validate client credentials. Since this can be used
     * in an all oAuth2 authentication flows, we add the auto-approve flag 
     * to it for extensibility,
     * 
     * @param array $params query fields, usually (client_id, client_secret, redirect_uri)
     * @param boolean $auto_approve
     * 
     * @return boolean
     */
    public function get_client($params, $auto_approve = false)
    {
        if(is_string($params)) $params = ['client_id' => $params];
        
        $client = $this->collection->findOne($params);

        if ($client)
        {
            if ($auto_approve)
                return $client['auto_approve'] ? $client : false;

            return $client;
        }

        return false;
    }

    public function set_client($params)
    {
        // typing it out to remind myself of the fields
        return $this->collection->insert(array(
                    'name'          => $params['name'],
                    'client_id'     => $params['client_id'],
                    'client_secret' => $params['client_secret'],
                    'redirect_uri'  => $params['redirect_uri'],
                    'status'        => $params['status'],
                    'auto_approve'  => $params['auto_approve'],
                ));
    }

    /**
     * Perform a hmac hash, using the configured method.
     *
     * @param   string  string to hash
     * @return  string
     */
    public function hash($str)
    {
        return hash_hmac('sha256', $str, 'pS3*Z2f9Ow<}^H6L,{<dS<><ld; 6|xAkwg3MgakYd}>t:KZgjjDx4U@/}#XO+]*');
    }

}