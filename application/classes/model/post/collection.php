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
class Model_Post_Collection extends Mongo_Collection {
    protected $name = 'posts';

    /**
     * Does a search, sort and limit. $args is an array that defaults to:
     *
        $this->collection()->ensureIndex([
            'status' => 1, 'user._id' => 1, 'page._id' => 1, 'privacy.is' => 1
        ], ['name' => 'post_id_fields', 'safe' => true]);
        $this->collection()->ensureIndex([
            'page_parent._id' => 1
        ], ['sparse' => true, 'safe' => true]);
        $this->collection()->ensureIndex([
            'to._id' => 1
        ], ['sparse' => true, 'safe' => true]);
        $this->collection()->ensureIndex([
            'type' => 1,
        ]);
        $this->collection()->ensureIndex([
            'listing' => 1
        ]);
     * 'price' => 0, 'rooms' => 1, 'built_size' => 0, 'property_type' => 0, 'tenure' => 0,
     * 'land_size' => 0, 'value' => 0, 'description' => 0, 'cobroke' => 0,
     *
     * @param array $args
     * @return self Sets the $cursor property and returns itself
     */
    public function search($args = array(), $user_id = NULL)
    {
        $defaults = [
           'status' => 'active', 'skip' => 0, 'limit' => 11, 'sort' => 'active',
           's' => '', 'include_ids' => [], 'page' => null, 'parent' => null,
           'user' => null, 'type' => null, 'listing' => [], 'to' => null
        ];

        // set some empty arrays
        $query = $ids = $return = [];

        extract(Arr::merge($defaults, $args), EXTR_SKIP);

        $query['status'] = $status;
        // looking for a specific role
        if ($type)
        {
            $query['type'] = $type;
            if($type === 'listing')
            {
                $query = Arr::merge($query, $this->format_listing_params($listing));
            }
        }

        // if specific records are needed add to the ids array
        // here's where we set the $id array
        if (!empty($include_ids))
            $ids = Arr::merge($ids, $include_ids);

        // keyword searches
        if ($s)
        {
            // this time it's a regex search.
            $query['message'] = new MongoRegex("/$s/i");
        }

        // iterate through the $ids and make sure they're all MongoID objects
        if (!empty($ids))
            $query['_id']['$in'] = $ids;

        if($page)
            $query['page._id'] = $page;

        if($parent)
            $query['parent._id'] = $parent;

        if($user)
            $query['user._id'] = $user;

        if($to)
            $query['to._id'] = $to;

        switch($sort)
        {
            case 'active': $sort = ['modified' => -1]; break;
            case 'newest': $sort = ['_id' => -1]; break;
            default: $sort = ['_id' => -1]; break;
        }

        $results = $this->find($query)
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

    public function testimonials($args = array(), $user_id = NULL)
    {
        $defaults = ['status' => 'active', 'type' => 'testimonial'];

        return $this->search(Arr::merge($defaults, $args));
    }

    public function listings($args = array(), $user_id = NULL)
    {
        $defaults = ['status' => 'active', 'type' => 'listing'];

        return $this->search(Arr::merge($defaults, $args));
    }

    /**
     * Formats (hopefully) the listings query in the correct order such that it
     * utilises an index.
     *
     * @param array $listings
     * @return array
     */
    protected function format_listing_params(array $listings)
    {
        $format = [
            'listing.price' => 0, 'listing.rooms' => 1, 'listing.built_size' => 0,
            'listing.property_type' => '', 'listing.tenure' => 0, 'listing.land_size' => 0,
            'listing.value' => 0, 'listing.description' => 0, 'listing.cobroke' => 0
        ];

        return Arr::merge($format, $listings);
    }
}
?>
