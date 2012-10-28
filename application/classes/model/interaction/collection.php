<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of collection
 *
 * @author jj
 * @package RealtAsia API
 * @subpackage /Model/Interaction
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Interaction_Collection extends Mongo_Collection {
    protected $name = 'interactions';

    /**
     * Method that retrieves an object's subscribers in an array containing a
     * user id and user name
     *
     * @param array $args
     * @return array
     */
    public function subscribers(array $args)
    {
        $args['interaction'] = 'subscribe';
        return $this->fetch($args);
    }

    /**
     * Method that retrieves an object's likers in an array containing a
     * user id and user name
     *
     * @param array $args
     * @return array
     */
    public function likers(array $args)
    {
        $args['interaction'] = 'like';
        return $this->fetch($args);
    }

    /**
     * Utility method that aids in the above to retrievals
     *
     * @param array $args
     * @return array
     */
    protected function fetch(array $args)
    {
        $defaults = [
            'limit' => 15, 'skip' => 0, 'object_id' => NULL,
            'object_type' => NULL, 'interaction' => NULL
        ];
        $return = [];

        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        $query = [
            'object_id'   => $object_id,
            'object_type' => $object_type,
            'interaction' => $interaction
        ];

        $results = $this->find($query, ['user' => 1])
                ->limit($limit)
                ->skip($skip);

        while($results->hasNext())
        {
            $row = $results->getNext();
            $return[] = $row->stringify($row->as_array());
        }

        return $return;
    }
}