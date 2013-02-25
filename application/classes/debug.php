<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of Debug
 *
 * @author Jason Nathan
 * @category RealtAsia API
 * @package Helpers
 * @subpackage Debug
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Debug extends Kohana_Debug
{
    public static function log($message, $values = array())
    {
        return Kohana::$log->add(Log::DEBUG, $message, $values)->write();
    }

    public static function error($message, $values = array())
    {
        return Kohana::$log->add(Log::ERROR, $message, $values)->write();
    }

}

?>
