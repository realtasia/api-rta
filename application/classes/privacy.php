<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Description of privacy
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Privacy {

    public $document;
    public $model     = null;
    public $read_only = false;

    /**
     * $load can be a string or mongoid object. The model is needed because we
     * instantiate the Document. Can be an array, then it becomes readonly. Can
     * also be an instance of Document directly.
     *
     *
     * @param mixed $load
     * @param string $model the mongo_document to load
     */
    public static function factory($load, $model = null)
    {
        return new Privacy($load, $model);
    }

    /**
     * $load can be a string or mongoid object. The model is needed because we
     * instantiate the Document. Can be an array, then it becomes readonly. Can
     * also be an instance of Document directly.
     *
     *
     * @param mixed $load
     * @param string $model the mongo_document to load
     */
    public function __construct($load, $model = null)
    {
        if ((is_string($load) || $load instanceof MongoID) && $model)
        {
            $this->document = Mongo_Document::factory(Inflector::singular($model), $load);
        }

        if (is_object($load) && $load instanceof Mongo_Document)
        {
            $this->document = $load;
        }

        if (is_array($load) && isset($load['settings']))
        {
            $this->document = (object) $load;
            $this->read_only = true;
        }

        if ($model)
        {
            $this->model = $model;
        }

        //print_r($this->document);
    }

    /**
     * Returns a boolean if the document is $type
     *
     * @param string $type
     * @return boolean
     */
    public function doc_is($type)
    {
        if ($this->model)
            return Inflector::singular($this->model) === $type;

        if (!$this->read_only)
            return (string) $this->document->collection() === $type;

        if ($type === 'user')
        {
            if ($this->read_only)
                return isset($this->document->settings['privacy']['personal']);
        }

        return FALSE;
    }

    public function __get($name)
    {

        if (isset($this->{$name}))
        {
            return $this->{$name};
        }

        if (!$this->read_only)
        {
            $this->document->load([], ['settings.privacy']);
            $privacy = $this->document->settings['privacy'];
        }

        $privacy = $this->document->settings['privacy'];


        if ($name === 'is')
        {
            return $privacy['is'];
        }

        if (isset($privacy[$name]))
        {
            return $privacy[$name]['is'];
        }

        return null;
    }

    public function __toString()
    {
        //$doc = is_array($this->document) ? $this->document : $this->document->as_array();

        //return print_r($this->document, true);
    }

    /**
     * Small validation for possible privacy settings
     *
     * @param string $value
     * @return boolean
     */
    public static function valid($value, $field = null, $validation = null)
    {
        if (in_array($value, ['PUBLIC', 'SUBSCRIBERS', 'SELF', 'CUSTOM']))
        {
            return true;
        }

        if ($validation instanceof Validation)
        {
            $validation->error($field, 'valid_privacy');
        }

        return false;
    }

}
