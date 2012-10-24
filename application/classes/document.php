<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Description of document
 *
 * @author jj
 * @package realtasia-beta-1
 * @subpackage
 * @copyright 2012 Realtasia Pte Ltd
 */
class Document extends Mongo_Document {

    /**
     *
     * @var mixed MongoID || string. Used if a record(s) is retrieved for an
     * authenticated user. Some methods will check for interactions.
     */
    protected $_user_id;

    /**
     *
     * @var array This field is used in most document schemas
     */
    protected static $counts = array(
        'attachments'  => 0,
        'comments'     => 0,
        'events'       => 0,
        'likers'       => 0,
        'listings'     => 0,
        'posts'        => 0,
        'subscribers'  => 0,
        'testimonials' => 0,
    );

    /**
     * const SAVE_INSERT = 'insert';
     * const SAVE_UPDATE = 'update';
     * const SAVE_UPSERT = 'upsert';
     */
    public function before_save($action)
    {
        $time           = time();
        $this->modified = $time;
        if (isset($this->active))
        {
            $this->active = $time;
        }

        unset($this->created);

        return $this;
    }

    public function after_save($action)
    {

    }

    public function before_load()
    {

    }

    public function after_load()
    {
        $this->created  = date('c', $this->_id->getTimestamp());
        $this->modified = date('c', $this->modified);

        if (isset($this->active))
        {
            $this->active = date('c', $this->active);
        }

        return $this;
    }

    public function before_delete()
    {

    }

    public function after_delete()
    {

    }

}

?>
