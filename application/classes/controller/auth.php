<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This controller :
 *      1. Authenticates a request
 *          a. username / password
 *          b. client credentials (because of other oauth_providers - but should port it here the soonest!)
 *              i. client_id, oauth_token
 *      2. Process given access_tokens
 *          a. Issue a token
 *          b. validate a token
 *      3. Needs to return a session with the following
 *          a. the new/existing token
 *          b. the token expiry (ttl)
 *          c. a user_id to make api requests with
 *      4. Needs to provide valid error messages
 */
class Controller_Auth extends Controller_Abstract {
    public static $auth;
    public static $error;
    public static $success;

    /**
     * Instantate the needed libraries.
     */
    public function before()
    {
        parent::before();
        static::$auth = Oauth2_Authentication::instance();
        static::$error = new Oauth2_Error;
        static::$success = new Oauth2_Success;
    }

    /**
     * Utility landing Auth Page. Checks the grant type and delegates the request
     * to the proper actions.
     *
     * @return void
     */
    public function action_index()
    {
        if (!$params = $this->check_headers())
            return;

        $grant_type = $params['grant_type'];

        switch ($grant_type)
        {
            case 'password': return $this->action_login($params);
            case 'oauth': return $this->action_oauth($params);
            case 'client_credentials': return $this->action_credentials($params);
        }
    }

    /**
     * In case this is a post request
     *
     * @return type
     */
    public function action_update()
    {
        return $this->action_index();
    }

    /**
     * Performs a Resource Owner Password Flow authentication. Sets error
     * object if not valid or the the success object with access_token if not.
     *
     * @return boolean
     */
    public function action_login($params = null)
    {
        if (!$params)
        {
            if (!$params = $this->check_headers())
                return;
        }

        $check = static::$auth->login($params, true);

        // return state if available
        if(isset($params['state']))
        {
            $check['state'] = $params['state'];
        }

        return static::is_error($check) ? $this->error($check) : $this->data($check);
    }

    /**
     * Performs a Client Credentials Flow authentication. Sets error
     * object if not valid or the the success object with access_token if not.
     *
     * @return boolean
     */
    public function action_credentials($params = null)
    {
        if (!$params)
        {
            if (!$params = $this->check_headers())
                return;
        }

        $check = static::$auth->credentials($params, true);

        // state parameter.
        if(isset($params['state']))
        {
            $check['state'] = $params['state'];
        }

        return static::is_error($check) ? $this->error($check) : $this->data($check);
    }

    /**
     * A little misleading. Doesn't check headers for now. Just checks $_GETs &
     * $_POSTs. Returns the parsed params
     *
     * @return boolean | array
     */
    protected function check_headers()
    {
        $post = $this->request->post();
        $get  = $this->request->query();
        if (empty($post) && empty($get))
        {
            Debug::log(print_r($_GET, true));
            return $this->error('invalid_request');
        }

        if (empty($post))
        {
            static::$params = $get;
            Debug::log(print_r($get, true));
        }
        else
        {
            static::$params = $post;
            Debug::log(print_r($post, true));
        }

        switch (true)
        {
            case!isset(static::$params['grant_type']) || empty(static::$params['grant_type']):
            case!isset(static::$params['redirect_uri']) || empty(static::$params['redirect_uri']):
            case!isset(static::$params['client_id']) || empty(static::$params['client_id']):
            case static::$params['grant_type'] === 'password' && (!isset(static::$params['username']) || empty(static::$params['username'])):
            case static::$params['grant_type'] === 'password' && (!isset(static::$params['password']) || empty(static::$params['password'])):
                Debug::log('invalid request invoked in check headers [params not checking out]');
                return $this->error('invalid_request [params not checking out]');
        }

        return static::$params;
    }

}