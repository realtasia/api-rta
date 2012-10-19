<?php

defined('SYSPATH') or die('No direct access allowed.');

class oauth2 {

    /**
     * '_id'             => $this->_id,
     * 'client_id'       => $this->_data['client_id'],
     * 'redirect_uri'    => $this->_data['redirect_uri'],
     * 'user_id'         => $this->_data['user_id'],
     * 'grant_type'      => $this->_data['grant_type'],
     * 'code'            => $this->_data['code'],
     * 'first_requested' => $this->_data['first_requested'],
     * 'last_active'     => $this->_data['last_updated'],
     * 'access_token'    => $this->_data['access_token'],
     * 'code'            => $this->_data['code']
     * 
     */
    // oAuth instances
    protected static $_instance;

    public function instance()
    {
        if (!isset(static::$_instance))
        {
            $config = Kohana::$config->load('oauth2');
            static::$_instance = new self($config);
        }

        return static::$_instance;
    }

    // the current session instance. Default is Mongo
    protected $_session;
    protected $_model;
    protected $_config;

    /**
     * Loads Session and configuration options.
     *
     * @return  void
     */
    public function __construct($config = array())
    {
        // Save the config in the object
        $this->_config = $config;

        // Instantiate a session.
        $this->_session = Session::instance('mongo', 'oauth2');

        // The oauth Model
        $this->_model = Model::factory('oauth2');
    }

    public function code()
    {
        
    }

    public function token()
    {
        
    }

    /**
     * Resource Owner Password Flow or for validating a user
     * Returns client credentials including a user id if found & valid.
     * 
     * @param type $client_id
     * @param type $username
     * @param type $password
     * @param type $auto_approve
     * @return boolean
     */
    public function password($client_id, $username, $password, $auto_approve = false)
    {
        $client = $this->_model->check_client(array(
            'client_id' => $client_id), $auto_approve
        );

        if ($client)
        {
            $user_id = $this->_model->check_password($username, $password);

            if ($user_id)
            {
                return $this->set_token($client['client_id'], $user_id);
            }

            return false;
        }

        return false;
    }

    /**
     * Client Side Application Flow or retrieve Client Credentials. 
     * Returns client if found & valid.
     * 
     * @param mixed $client_id
     * @param mixed $client_secret
     * @param string $redirect_uri
     * 
     * @return object|boolean
     */
    public function credentials($client_id, $client_secret = NULL, $redirect_uri = NULL)
    {
        $params = [
            'client_id' => $client_id
        ];

        if ($client_secret)
        {
            $params['client_secret'] = $client_secret;
        }

        $client = $this->_model->check_client($params);

        if ($client)
        {
            // just to confirm the redirect uri's are the same
            $given_domain  = parse_url($redirect_uri, PHP_URL_HOST);
            $stored_domain = parse_url($client->redirect_uri, PHP_URL_HOST);
            
            if($given_domain === $stored_domain)
                return $this->set_token($client['client_id']);

            return false;
        }

        return false;
    }

    public function get_token($client_id)
    {
        $check = $this->get_session();

        if (array_key_exists($check, 'client_id') AND $check['client_id'] == $client_id)
        {
            return $check['token'];
        }
    }

    public function set_token($client_id, $user_id = NULL, $grant_type = 'resource')
    {
        $access_token = hash_hmac('sha256', new MongoID, 'my-baby-curly');
        
        $params = [
            'user_id'      => $user_id,
            'grant_type'   => $grant_type,
            'client_id'    => $client_id,
            'code'         => null,
            'access_token' => $access_token,
            'stage'        => 'granted'
        ];
        
        return $this->set_session($params);
    }

    protected function get_session($key = null)
    {
        return $key ? $this->_session->get($key) : $this->_session;
    }

    protected function set_session(array $values)
    {
        $this->_session->set('oauth2', $values);

        return $this->_session;
    }

}