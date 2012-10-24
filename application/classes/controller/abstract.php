<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of abstract
 *
 * @author jj
 */
class Controller_Abstract extends Controller {

    /**
     * @var array REST types
     */
    protected $_action_map = array
        (
        HTTP_Request::GET    => 'index',
        HTTP_Request::PUT    => 'update',
        HTTP_Request::POST   => 'create',
        HTTP_Request::DELETE => 'delete',
    );

    /**
     * @var string requested action
     */
    protected static $_action_requested = '';

    /**
     * Checks the requested method against the available methods. If the method
     * is supported, sets the request action from the map. If not supported,
     * the "invalid" action will be called.
     */
    public function before()
    {
        static::$_action_requested = $this->request->action();

        $method = Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method());
        // Map to default actions unless it's a connection
        if(method_exists($this, 'action_' . static::$_action_requested))
        {
            $this->request->action(static::$_action_requested);
        }
        else
        {
            $this->request->action($this->_action_map[$method]);
        }


        return parent::before();
    }


    public $return = array();

    /**
     * Utility method that sets the response status and the response body for
     * successful requests.
     *
     * @param type $session
     * @return void
     */
    protected function success($session)
    {
        $this->response->status(200);
        $this->return = $session;
        return;
    }

    /**
     * Utility method that checks if a response array is an error object
     *
     * @param array $return
     * @return boolean
     */
    protected static function is_error(array $return)
    {
        return array_key_exists('error', $return);
    }

    /**
     * Utility method that sets the response status and the response body for
     * errors
     *
     * @param string $key
     * @param int $status
     * @return boolean
     */
    protected function error($key, $status = 401)
    {
        $this->response->status($status);
        $this->return = is_array($key) ? $key : static::$error->create($key);
        $header       = '';

        foreach ($this->return as $k => $v)
        {
            $header .= "$k=$v&";
        }
        $this->response->headers('WWW-Authenticate', $header);
        return false;
    }

    /**
     * Sets content type and converts response body to JSON
     *
     */
    public function after()
    {
        $this->response->headers('Content-Type', 'application/javascript');
        $body = json_encode($this->return, JSON_PRETTY_PRINT);
        $this->response->body($body);
    }

}
