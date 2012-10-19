<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mongo Comment Model.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Model
 * @copyright 2011 Realtasia Pte Ltd
 */
class Model_Attachment extends Model{

    public function new_media($array, $settings = array('safe' => TRUE))
    {
        $this->collection->save($array, $settings);
        return $array;
    }
}