<?php

class Controller_Stats extends Controller{
    
    public function action_check()
    {
        //print_r("<pre>");
        $logs = Model::factory('log')->collection->findOne();
        $t = new DateTime($logs['time']);
        //print_r($logs);
        echo exec("DISPLAY=:0 notify-send " . escapeshellarg($t->format("g:i:s a")) . " " . escapeshellarg($logs['body']. PHP_EOL));
        //print_r(get_class_methods($logs));
//                ->find();
//        while($logs->hasNext())
//        {
//            $l = $logs->getNext();
//            $d = new DateTime($l['time']);
//            return exec("notify-send " . escapeshellarg($d->format("g:i:s a")) . " " . escapeshellarg($l['body']. PHP_EOL));
//            //echo $d->format("g:i:sa") . " : " . $l['body'] . "\n";
//        }
        exit;
    }
    
    public function action_index()
    {
        return exec("php /home/jason/www/rta.casa.net/index.php --uri=stats/check");
        exit;
    }
}