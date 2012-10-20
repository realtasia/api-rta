<?php defined('SYSPATH') or die('No direct access allowed.');


class Model_Oauth2_Session extends Model {

    /**
     * @var integer time to live
     */
    protected static $ttl = 3600; // 1 hour

    /**
     * Gets a stored session for the given token.
     * 
     * @param string $token
     * @return boolean|array
     */
    public function get_session_by_token($token)
    {
        return $this->get_session(['access_token' => $token]);
    }

    /**
     * Retrieves a session by an array of params. Normal cases should include
     * user_id, client_id or a given token.
     * 
     * @param array $params
     * @return boolean|array
     */
    public function get_session(array $params)
    {
        // try to get a session with the given params.
        $session = $this->collection->findOne($params);
        
        // return it. Auth class should check for expiry
        return $session ? $session : false;
    }
    
    /**
     * Destroys a session by it's access token.
     * 
     * @param string $token
     * @return type
     */
    public function destroy($token)
    {
        return $this->collection->remove(['access_token' => $token]);
    }
    
    /**
     * Either updates or creates a new session with given params. The params 
     * array should look like this. 
     * [ 
     *      _id        => the MongoID of the existing session or null for new.
     *      client_id  => REQUIRED, 
     *      user_id    => OPTIONAL,
     *      code       => OPTIONAL,
     *      grant_type => REQUIRED. Currently serving "password" and "credentials",
     *      stage      => the stage of the auth flow, defaults to "granted"
     * ]
     * 
     * @param array $params
     * @param boolean $session
     * 
     * @return string|array
     */
    public function set_token($params, $session = true)
    {
        // the access token
        $access_token = sha1(time() . uniqid());

        $params = [
            'client_id'    => $params['client_id'],
            'user_id'      => isset($params['user_id']) ? $params['user_id'] : NULL,
            'code'         => isset($params['code']) ? $params['code'] : NULL,
            'access_token' => $access_token,
            'expires_in'   => time() + static::$ttl,
            'grant_type'   => $params['grant_type'],
            'stage'        => isset($params['stage']) ? $params['stage'] : 'granted',
        ];
        
        $result = $this->db->command([
            'findandmodify' => Inflector::plural($this->collname),
            'query'         => ['_id' => isset($params['_id']) ? $params['_id'] : NULL],
            'update'    => $params,
            'upsert'    => true
        ]);


        return $session ? $result['value'] : $access_token;
    }
    
    /**
     * Utility to check if a token expired.
     * 
     * @param integer $ttl the current expiry timestamp.
     * @return boolean
     */
    public static function expired($ttl)
    {
        return time() - $ttl > static::$ttl;
    }

}