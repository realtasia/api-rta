<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  A simple log notifier
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Stats
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
 */
class Controller_Stats extends Controller{

    public function action_check()
    {
        $logs = Model::factory('log')->collection->findOne();
        $t = new DateTime($logs['time']);
        echo exec("DISPLAY=:0 notify-send " . escapeshellarg($t->format("g:i:s a")) . " " . escapeshellarg($logs['body']. PHP_EOL));
        exit;
    }

    public function action_index()
    {
        return exec("php /home/jason/www/rta.casa.net/index.php --uri=stats/check");
        exit;
    }
}