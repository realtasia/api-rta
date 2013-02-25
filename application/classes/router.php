<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of Router
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Helpers
 * @subpackage Router
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
