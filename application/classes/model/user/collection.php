<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of collection
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Model/User/Collection
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Model_User_Collection extends Mongo_Collection {
    protected $name = 'users';

    /**
     * Does a search, sort and limit. $args is an array that defaults to:
     *
     *  array(
     *          'limit' => 11, 'offset' => 0, 'type' => '',
     *          'subtypes' => array(), 'sort' => 'alpha',
     *          's' => '', 'include_ids' => FALSE,
     *          'object_id' => FALSE, 'user_id' => false, 'children' => FALSE
     *  );
     *
     * @param array $args
     * @return self Sets the $cursor property and returns itself
     */
    public function search($args = array(), $user_id = NULL)
    {
        $defaults = [
           'limit' => 11, 'skip' => 0, 'roles' => [], 'sort' => 'alpha',
           's' => '', 'include_ids' => [], 'children' => []
        ];

        // set some empty arrays
        $query = $ids = $return = [];

        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        // looking for a specific role
        if (!empty($roles))
            $query['roles'] = array('$in' => $roles);

        // if specific records are needed add to the ids array
        // here's where we set the $id array
        if (!empty($include_ids))
            $ids = Arr::merge($ids, $include_ids);

        // keyword searches
        if ($s)
        {
            // this time it's a regex search.
            $s = new MongoRegex("/^$s/i");
            $query['$or'] = [
                    ['username' => $s],
                    ['email' => $s],
                    ['telephone.mobile' => $s],
                    ['company.name' => $s]
                ];
        }

        // iterate through the $ids and make sure they're all MongoID objects
        if (!empty($ids))
            $query['_id']['$in'] = $ids;

        switch($sort)
        {
            case 'alpha': $sort = ['name' => 1]; break;
            case 'popular': $sort = ['counts.total' => -1]; break;
            default: $sort = ['_id' => -1]; break;
        }

        $results = $this->find($query)
                ->fields(static::$protected_fields, false)
                ->limit($limit)
                ->sort($sort)
                ->skip($skip)
                ->load();

        while($results->hasNext())
        {
            $row = $results->getNext();
            if($user_id && $usr = $this->get_user($user_id))
            {
                $row->current_user = $usr;
                $row->after_load();
            }
            $ret = $row->stringify($row->as_array());

            $return[] = $ret;
        }

        return $return;
    }

    private static $protected_fields = ['password', 'settings', 'logins'];

}
?>
