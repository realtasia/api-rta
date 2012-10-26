<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  1. Authenticate a token request
 *      a. Set a global $session variable for consumption by child controllers.
 *  2. Method to check if it is a user authenticated request.
 *  3. Get error utility from trait.
 *  4. Get success utility from trait.
 *
 * @uses Oauth2_Resource
 *
 */
abstract class Controller_Resource extends Controller_Abstract{
    use Trait_Oauth2_Resource;

    // the request params
    public static $params = array();
    //the model used in each controller
    protected static $model;
    // the current session set upon authentication
    public static $current_session = null;
    // authenticated user_id, if the session is user authenticated
    public static $user_id         = null;

    /**
     * This method extracts the token from the request params and sets global
     * variables for consumption by all child classes.
     *
     * @return void
     */
    public function before()
    {
        // check if available in $_GET
        static::$params = $this->request->query();
        $token = $this->request->query('access_token');
        if(!$token)
        {
            // Check $_POST
            static::$params = $this->request->post();
            $token = $this->request->post('access_token');
        }

        // Still nothing, return an error
        if(!$token)
            return $this->oauth_error('invalid_request');

        // validate the token
        $current_session = $this->validate_token($token);

        // if validated, we get an array, so set the global properties
        if(is_array($current_session))
        {
            static::$current_session = $current_session;
            static::$user_id = static::$current_session['user_id'];
            if(isset(static::$params['state']))
            {
                static::$current_session['state'] = static::$params['state'];
            }

        }

        return parent::before();
    }

    /**
     * Gets a user from session if available. If not, gets one from the user db
     * Only does this if there is a user authenticated session.
     *
     * @return mixed
     */
    public function get_user()
    {
        // no user, so we return
        if(!$this->logged_in())
            return null;

        // the session key
        $key = static::$current_session['access_token'] . static::$user_id;
        // check session
        if(!$user = Session::instance()->get($key))
        {
            // fetch user from db
            $user = (object) Mongo_Document::factory('user');
            $user->load(static::$user_id);
            // unset some sensitive info
            unset($user->password);
            $user = $user->as_array();
            // set the session
            Session::instance()->set($key, $user);
        }
        // return the user
        return $user;
    }

    /**
     * checks if this is a user authenticated session
     *
     * @return boolean
     */
    public function logged_in()
    {
        return (bool) static::$user_id;
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
        $return = [];

        if(static::$current_session)
        {
            $return = static::$current_session;
        }

        $return['data'] = $data;

        return $this->success((object)$return);
    }

    /**
     * Utility method to perform the interaction
     *
     * @todo add email Job.
     * @param string $interaction like|unlike|subscribe|unsubscribe
     */
    protected function interact($interaction, $object_type)
    {
        $interact = Mongo_Document::factory('interaction', null, static::$user_id);
        if($interact->{$interaction}($this->request->param('id'), $object_type))
                return $this->data(['success' => 1]);

        return $this->oauth_error('invalid_request', 400);
    }

    /**
     * Utility method for Resource Errors
     */

    protected function error($file, $key, $status = 401)
    {
        $this->response->status($status);

        $error = [
            'error' => $key,
            'error_description' => Kohana::message($file, $key)
        ];

        $this->return = $error;
        $header       = '';

        foreach ($this->return as $k => $v)
        {
            $header .= "$k=$v&";
        }
        $this->response->headers('WWW-Authenticate', $header);
        return false;
    }
}
// End Welcome
