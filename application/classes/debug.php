<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of debug
 *
 * @author jason
 */
class Debug extends Kohana_Debug
{
    public static function log($message, $values = array())
    {
        return Kohana::$log->add(Log::DEBUG, $message, $values)->write();
    }

    public static function oauth_error($message, $values = array())
    {
        return Kohana::$log->add(Log::ERROR, $message, $values)->write();
    }

}

?>
