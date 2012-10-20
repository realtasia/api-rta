<?php defined('SYSPATH') or die('No direct access allowed.');
/*
 *   db.oauth_clients.insert({name :, client_id :, client_secret :, redirect_uri :, status :, auto_approve :});
 */

class Model_Oauth2_Resource extends Model {

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

        $user_id = Model::factory('user')->collection->findOne($params, array('_id' => 1));

        return $user_id ? $user_id : false;

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