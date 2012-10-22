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
    use Oauth2_Resource;
    // the request params
    public static $params = array();

    // the current session set upon authentication
    public static $current_session = null;
    // authenticated user_id, if the session is user authenticated
    public static $user_id = null;

    /**
     * This method extracts the token from the request params and sets global
     * variables for consumption by all child classes.
     *
     * @return void
     */
    public function before()
    {
        parent::before();
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
            return $this->error('invalid_request');

        // validate the token
        $current_session = $this->validate_token($token);

        // if validated, we get an array, so set the global properties
        if(is_array($current_session))
        {
            static::$current_session = $current_session;
            static::$user_id = static::$current_session['user_id'];
        }
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

        // check session
        if(!$user = Session::instance()->get(static::$current_session['access_token'] . static::$user_id))
        {
            // fetch user from db
            $user = (object) Model::factory('user')->get(static::$user_id);
            // unset some sensitive info
            unset($user->password);
            // make id available too
            $user->id = $user->_id;
            // set the session
            Session::instance()->set(static::$current_session['access_token'] . static::$user_id, $user);
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

        $return['data'] = static::string_ids($data);

        return $this->success((object)$return);
    }

    /**
     * Utility method to convert all MongoID ids to strings
     *
     * @param array|object $data
     * @return mixed
     */
    protected static function string_ids($data)
    {
        foreach($data as $k=>$v)
        {
            if($v instanceof MongoID)
            {
                if(is_object($data))
                    $data->{$k} = $v->__toString();
                else
                    $data[$k] = $v->__toString();
            }
        }

        return $data;
    }
}
// End Welcome
