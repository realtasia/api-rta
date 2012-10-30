<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of document
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Model/Document
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Document extends Mongo_Document {
    use Trait_Oauth2_Utils;
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
     * shorthand for findOne
     *
     * @param array|string $params
     * @param boolean $object
     * @return MongoDocument|object
     */
    public function get($params, array $fields = [], $object = TRUE)
    {
        if($this->load($params, $fields))
        {
            $ret = $this->stringify($this->as_array());
            return $object ? (object) $ret : $ret;
        }

        return $object ? (object) [] : [];
    }

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
    }

    public function before_delete()
    {

    }

    public function after_delete()
    {

    }
}

?>
