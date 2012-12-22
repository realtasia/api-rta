<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of route
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Helper/Router
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Router {
    public static function __callStatic($name, $arguments)
    {
        $action = $arguments[0];
        $id     = $arguments[1] ? $arguments[1] : '';
        //return print_r($arguments, true);
        return Route::url($name, [
            'controller' => $name,
            'action'=> $action,
            'id' => $id
        ], 'http');
    }
}
