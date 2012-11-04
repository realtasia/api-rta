<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of abstract
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Controller/Abstract
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Abstract extends Controller {

    public static $params = array();
    /**
     * @var array REST types
     */
    protected $_action_map = [
        HTTP_Request::GET    => 'index',
        HTTP_Request::POST   => 'update',
    ];

    // the model each respective child controller uses
    protected static $model;


    // the global validation errors array. returns it's errors if available.
    public $errors = null;
    /**
     * @var string requested action
     */
    protected static $_action_requested = '';

    // the current session set upon authentication
    public static $current_session = null;

    /**
     * Checks the requested method against the available methods. If the method
     * is supported, sets the request action from the map. If not supported,
     * the "invalid" action will be called.
     */
    public function before()
    {
        $_action_requested = $this->request->action();

        $method = $this->_method();
        // Map to default actions unless it's a connection
        if (method_exists($this, 'action_' . $_action_requested) && !in_array($_action_requested, $this->_action_map))
        {
            $this->request->action($_action_requested);
        }
        else
        {
            $this->request->action($this->_action_map[$method]);
        }


        return parent::before();
    }

    public $return = array();

    public function action_continue()
    {
        Debug::log('Continue Invoked');
        return $this->error('access_denied');
    }

    /**
     * Returns a formatted response containing current session and data
     *
     * @uses Oauth2_Resource->success
     * @param array|object $data
     * @return boolean
     */
    public function data($data)
    {
        $return = ['status' => 'success'];

        if(static::$current_session)
        {
            $return['session'] = static::$current_session;
        }

        $this->response->status(200);
        $this->return = (object) $return;
        $this->return->data = $data;
    }

    /**
     * Utility method that checks if a response array is an error object
     *
     * @param array $return
     * @return boolean
     */
    protected static function is_error(array $return)
    {
        return isset($return['status']) AND $return['status'] !== 'success';
    }

    /**
     * Utility method that sets the response status and the response body for
     * errors
     *
     * @param string $key
     * @param int $status
     * @return boolean
     */
    protected function error($key, $status = 200)
    {
        $this->response->headers([
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => date('c', time()),
        ]);
        $this->response->status(200);
        $this->return = is_array($key)
                ? (object) $key : (object) [
            'status' => 'error',
            'data'   => [
                'title'   => $key,
                'message' => Kohana::message('oauth2', 'message.' . $key)
            ]];

        if(static::$current_session)
        {
            $this->return->session = [
                'access_token' => static::$current_session['access_token']
            ];
            if(isset(static::$current_session['state']))
            {
                $this->return->session['state'] = static::$current_session['state'];
            }
        }

//        $header   = http_build_query($this->return);

        $this->response->status(200);
//        if(!$this->request->is_ajax())
//            $this->response->headers('WWW-Authenticate', $header);
        return $this->after();
    }

    /**
     * Utility method that sets the response status and the response body for
     * errors
     *
     * @param string $file for Kohana::Message to parse
     * @param string $key the array key in dot(.) notation
     * @param int $status
     * @return boolean
     */
    protected function fail($file = NULL, $key = NULL, $status = 200)
    {
        $this->response->headers([
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => date('c', time()),
        ]);
        $this->response->status($status);

        if(!$file && !$key)
        {

            if(is_array($this->errors) && !empty($this->errors))
            {
                $error = (object) [
                    'status' => 'fail',
                    'data'   => $this->errors
                ];
            }
        }
        else
        {
            $error = (object) [
                'status' => 'fail',
                'data'   => [
                    'title'   => Inflector::humanize($key),
                    'message' => Kohana::message($file, $key)
                ]
            ];
        }


        $this->return = $error;
        //var_dump($this->return);

        if(static::$current_session)
        {
            $this->return->session = static::$current_session;
        }

//        $header   = http_build_query($this->return);
//        $this->response->headers('WWW-Authenticate', $header);
    }

    /**
     * Sets content type and converts response body to JSON
     *
     */
    public function after()
    {
        $p = Arr::get($_SERVER, 'HTTP_ORIGIN');
        if($p)
        {
            switch ($p) {
                case 'http://test.jjpc.net':
                case 'http://test.casa.net':
                case 'http://beta.realtasia.com':
                case 'http://beta.realtasia.com.sg':
                case 'http://realtasia.com.sg':
                case 'http://www.realtasia.com.sg':
                case 'http://www.realtasia.com':
                    $this->response->headers([
                        'Access-Control-Allow-Origin' => $p,
                        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                        'Access-Control-Allow-Credentials' => 'true',
                        'Access-Control-Max-Age' => '3600',
                        'Access-Control-Allow-Headers' => 'Content-Type, *',
                        'p3p' => 'CP=IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT',
                    ]);
                    //Debug::log('Made it to CORS');
                    break;
            }
        }
        $this->response->headers('Content-Type', 'application/javascript');
        $data = json_encode($this->return, JSON_PRETTY_PRINT);
        if(isset(static::$params['callback']))
            $data = static::$params['callback'] . '(' . $data . ')';
        //Debug::log('Made it to body');
        $this->response->body($data);
    }

    /**
     * retrieves current request method
     *
     * @return string
     */
    protected function _method()
    {
        return Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method());
    }

    /**
     * Returns true if current request method is a GET
     *
     * @return boolean
     */
    protected function is_GET()
    {
        return $this->_method() === HTTP_Request::GET;
    }

    /**
     * Returns true if current request method is a PUT
     *
     * @return boolean
     */
    protected function is_PUT()
    {
        return $this->_method() === HTTP_Request::PUT;
    }

    /**
     * Returns true if current request method is a POST
     *
     * @return boolean
     */
    protected function is_POST()
    {
        return $this->_method() === HTTP_Request::POST;
    }

    /**
     * Returns true if current request method is a DELETE
     *
     * @return boolean
     */
    protected function is_DELETE()
    {
        return $this->_method() === HTTP_Request::DELETE;
    }

    /**
     * Returns true if current session is a client_credential grant type
     *
     * @return boolean
     */
    protected static function is_client()
    {
        return isset(static::$current_session)
                ? static::$current_session['grant_type'] === 'client_credentials'
                : false;
    }
}
