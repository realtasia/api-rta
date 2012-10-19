<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller {
    
    public $return;
    
    public function before()
    {
        parent::before();
        $this->grant_type = $this->request->post('grant_type');
        $this->oauth2 = oauth2::instance();
    }
    
    public function action_index()
    {
        //Model::factory('oauth2')->insert();
        $this->return = $this->oauth2->password('123456789', 'one.user', 'one');
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