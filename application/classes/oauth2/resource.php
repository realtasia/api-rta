<?php

defined('SYSPATH') or die('No direct access allowed.');

trait Oauth2_Resource {
    // oAuth instances
    protected static $_instance;

    public static function instance()
    {
        if (!isset(static::$_instance))
        {
            static::$_instance = new self();
        }

        return static::$_instance;
    }

    // the client mongo model
    protected static $mClient;
    // the session mongo model
    protected static $mSession;
    // the success object formatter
    protected static $success;
    // the error object formatter
    protected static $error;

    /**
     * Loads Session and configuration options.
     *
     * @return  void
     */
    public function init()
    {
        static::$mClient  = Model::factory('oauth2_client');
        static::$mSession = Model::factory('oauth2_session');
        static::$success = new Oauth2_Success;
        static::$error = new Oauth2_Error;
    }

    /**
     * Validates an access token and returns session
     *
     * @param string $token
     *
     * @return boolean
     */
    public function validate_token($token, $auto_approve = false)
    {
        $this->init();
        $session = static::$mSession->get_session_by_token($token);

        if($session)
        {
            // check if the token expired.
            if(static::$mSession->expired($session['expires_in']))
            {
                // token expired. generate a new one and return the token.
                $update_session = static::$mSession->set_token($session);
                return $update_session;
            }

            // not expired, return the session.
            return $session;
        }

        return null;
    }

}