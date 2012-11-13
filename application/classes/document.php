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
//    public function before_save($action)
//    {
//        $time           = time();
//        $this->modified = $time;
//        if (isset($this->active))
//        {
//            $this->active = $time;
//        }
//    }

    public function modify($key, $value)
    {
        $object = Arr::delim_to_multi([$key => $value]);
        $this->_object = Arr::merge($this->_object, $object);
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

    public function atomic($id)
    {
        if ($id !== NULL)
        {
            if (is_array($id))
            {
                foreach ($id as $key => $value)
                {
                    $this->_object[$this->get_field_name($key)] = $value;
                }
            }
            else
            {
                $this->_object['_id'] = $this->_cast('_id', $id);
            }
        }
    }

    public function save($safe = TRUE)
    {
        try
        {
            parent::save($safe);
        }
        catch (MongoException $e)
        {
            Debug::error('Error: :e with values :v', [
                ':e' => $e->getMessage(),
                ':v' => print_r($this->as_array(), TRUE),
            ]);

            return false;
        }

        return true;
    }


    /**
     * Testing revealed that can't query the DB with string IDs if it isn't an
     * index. Utility to ensure all _ids are in fact instances of the MongoID object.
     *
     * @todo Consider DBRefs??
     *
     *
     * @param string|MongoID $id
     * @return type
     */
    public static function check_id($id)
    {
        return ($id instanceof MongoID) ? $id : new MongoID($id);
    }

    /**
     * Just to ensure that the plural versions of an object tallies with their
     * respective count fields and subarrays
     *
     * @param string $type
     */
    protected static function plural($type)
    {
        switch($type)
        {
            case 'wallpost': return 'posts';
            case 'testimonial': return 'testimonials';
            case 'message': return 'messages';
            case 'checkin': return 'checkins';
            case 'event': return 'events';
            case 'user': return 'users';
            case 'page': return 'pages';
            case 'location': return 'locations';
            case 'agency': return 'agencies';
            case 'comment': return 'comments';
            case 'attachment': return 'attachments';
            case 'like': return 'likes';
            case 'liker': return 'likers';
            case 'subscribe': return 'subscribes';
            case 'subscriber': return 'subscribers';
        }
    }

}
