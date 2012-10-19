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
            'password' => $this->hash($password));

        $user_id = Model::factory('user')->collection->findOne($params, array('_id' => 1));

        if ($user_id)
        {
            return $user_id;
        }

        return false;
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

    public function insert()
    {
        return $this->collection->insert(
                        array(
                            'name'          => 'main_app',
                            'client_id'     => '123456789',
                            'client_secret' => 'secret',
                            'redirect_uri'  => 'http://rta.jjpc.net/rta/callback',
                            'status'        => 'approved',
                            'auto_approve'  => true,
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