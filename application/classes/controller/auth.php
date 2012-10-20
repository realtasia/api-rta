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
class Controller_Auth extends Controller {

    public $return;
    public static $auth;

    public function before()
    {
        parent::before();
        $this->grant_type = $this->request->post('grant_type');
        static::$auth = Oauth2_Authentication::instance();
    }

    public function action_index()
    {
//        return Model::factory('oauth2_client')->set_client([
//            'name' => 'testApp',
//            'client_id' => '123456789',
//            'client_secret' => 'secret',
//            'status' => 'approved',
//            'redirect_uri' => null,
//            'auto_approve' => true,
//        ]);
        //return $this->return = time();
        $this->return = static::$auth->login('123456789', 'one.user', 'one');
        //return Model::factory('oauth2')->insert();
//        switch($this->grant_type)
//        {
//            case 'code': return $this->action_code(); break;
//            case 'token': return $this->action_token(); break;
//            case 'password': return $this->action_password(); break;
//            case 'credentials': return $this->action_credentials(); break;
//            default: return $this->error(); break;
//        }
    }

    public function action_code()
    {
        if(!$this->grant_type == 'code')
        {
            return $this->error();
        }
    }

    public function after()
    {
        $this->response->headers('Content-Type', 'application/javascript');
        $body = json_encode($this->return, JSON_PRETTY_PRINT);
        //$body .= Cookie::get('oauth2');
        $this->response->body($body);
    }

}