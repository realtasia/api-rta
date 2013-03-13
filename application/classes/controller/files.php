<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of files
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_
 * @subpackage Auth
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Files extends Controller {

    public function action_index()
    {
        $path = $this->request->query('id');

        static::get_folders($path ? $path : '/media/jason/Store');
    }

    public static function get_folders($path)
    {
        $return = [];
        $folders = [];
        $files = [];
        $folder = new DirectoryIterator(realpath($path));
        while($folder->valid())
        {
            if($folder->isDir())
            {
                $folders[] = HTML::anchor('/files?id=' . urlencode($folder->getRealPath()), $folder->getFilename()) . '<br>';
            }
            if($folder->isFile())
            {
                $files[] = $folder->getFilename() . '<br>';
            }
            $folder->next();
        }

        sort($folders);
        sort($files);
        foreach($folders as $f)
        {
            echo $f;
        }
        foreach($files as $f)
        {
            echo $f;
        }
    }
}
