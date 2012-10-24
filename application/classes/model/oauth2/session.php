<?php defined('SYSPATH') or die('No direct access allowed.');


class Model_Oauth2_Session extends Model {

    /**
     * @var integer time to live
     */
    public static $ttl = 3600; // 1 hour

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

        if ($session)
        {
            $session['id'] = $session['_id']->__toString();
            unset($session['_id']);
            if (is_object($session['user_id']))
            {
                $session['user_id'] = $session['user_id']->__toString();
            }
            return $session;
        }

        // return it. Auth class should check for expiry
        return false;
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
     *      grant_type => REQUIRED. Currently serving "password" and "client_credentials",
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

        // query array can safely contain these
        $query = [
            'client_id'    => $params['client_id'],
            'grant_type'   => $params['grant_type']
        ];

        // only add the next 3 params if they exist
        if(isset($params['user_id']))
        {
            $query['user_id'] = $params['user_id'];
        }

        if(isset($params['_id']))
        {
            $query['_id'] = $params['_id'];
        }

        if(isset($params['access_token']))
        {
            $query['access_token'] = $params['access_token'];
        }

        // for the new or the update object.
        $params = [
            'client_id'    => $params['client_id'],
            'user_id'      => isset($params['user_id']) ? $params['user_id'] : NULL,
            'code'         => isset($params['code']) ? $params['code'] : NULL,
            'access_token' => $access_token,
            'expires_in'   => time() + static::$ttl,
            'grant_type'   => $params['grant_type'],
            'stage'        => isset($params['stage']) ? $params['stage'] : 'granted',
        ];

        $this->db->command([
            'findandmodify' => Inflector::plural($this->collname),
            'query'         => $query,
            'update'        => $params,
            'upsert'        => true
        ]);

        foreach($params as $k => $v)
        {
            if($v instanceof MongoID)
                $params[$k] = $v->__toString();
        }

        // keeping this here for future
//        $this->collection->ensureIndex(['user_id' => 1]);
//        $this->collection->ensureIndex(['client_id' => 1]);
//        $this->collection->ensureIndex(['access_token' => 1], ['unique' => 1]);

        return $session ? $params : $access_token;
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