<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Controller {
    
    public $fields = array(
        'grant_type',
        'client_id',
        'client_secret',
        'redirect_uri',
        'username',
        'password',
        'scope',
        'state',
        'code',
        
    );
    
    public function before()
    {
        parent::before();
        $this->session = Session::instance();
        $this->grant_type = $this->response->post('grant_type');
    }
    
    public function action_index()
    {
        switch($this->grant_type)
        {
            case 'code': return $this->action_code(); break;    
            case 'token': return $this->action_token(); break;    
            case 'password': return $this->action_password(); break;    
            case 'credentials': return $this->action_credentials(); break;
            default: return $this->error(); break;
        }
    }
    
    public function action_code()
    {
        if(!$this->grant_type == 'code')
        {
            return $this->error();
        }
    }
    
}