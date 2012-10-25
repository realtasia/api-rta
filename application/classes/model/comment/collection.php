<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of collection
 *
 * @author jj
 * @package realtasia-beta-1
 * @subpackage
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_Comment_Collection extends Mongo_Collection {
    protected $name = 'comments';

    public function fetch($args)
    {
        $defaults = array(
           'limit' => 2, 'skip' => 0, 'type' => 'comment', 'sort' => 1,
           'object_id' => NULL, 'include_ids' => NULL,'status' => 'publish'
        );

        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        $query = $return = [];

        if ($include_ids)
        {
            if (is_array($include_ids))
                $query['_id'] = ['$in'         => $include_ids];
            else
                $query['_id'] = $include_ids;
        }

        $query['status'] = $status;
        $query['type']   = $type;

        $results = $this->find($query)
                ->limit($limit)
                ->sort('_id', $sort)
                ->skip($skip);

        if($results->hasNext())
        {
            $return[] = $results->getNext()->as_array();
        }

        return $return;
    }
}
?>
