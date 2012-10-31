<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of testpad
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Testpad extends controller {

    public function action_index()
    {
        $route = Router::user('like', new MongoID);



        $this->response->body($route);
    }
    //put your code here
}
