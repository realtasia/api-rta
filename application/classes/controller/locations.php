<?php

defined('SYSPATH') or die('No direct script access.');
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2000M');

/**
 * Description of locations
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Locations
 * @version 1.0
 * @copyright Copyright (c) 2012 Realtasia Pte Ltd
 */
//class Controller_Locations extends Controller_Resource {

class Controller_Locations extends Controller_Resource {

    public static $stray_statuses = [
        'SUCCESS', 'INVALID', 'REVIEW', 'NEW'
    ];

    /**
     * Gets an unprocessed entry in straylocations. Retrieval is related to the
     * 'skip' query given...
     *
     *
     * @return /Response with JSON data
     */
    public function action_getstray()
    {
        $skip   = $this->request->query('skip');
        $status = $this->request->query('status');

        $st = Collection::factory('strayproject')
          ->find(['status' => $status ? $status : 'NEW'])
          ->skip($skip ? $skip : 0)
          ->sort_asc('_id');


        if ($st->hasNext())
        {
            $d = $st->getNext();
            return $this->data($d->stringify($d->as_array()));
        }
    }

    /**
     * Returns the total number of unprocessed 'would-be' locations.
     *
     *
     * @return /Response with JSON data
     */
    public function action_countstrays()
    {
        $return = array();

        foreach(static::$stray_statuses as $filter)
        {
            $return[$filter] = Collection::factory('strayproject')->count(['status' => $filter]);
        }

        return $this->data($return);

        //if($st->hasNext())
        $this->response->headers("Content-Type", "application/json");
        $this->response->body(json_encode(['total' => $st]));
    }

    /**
     * A method for updating the status of a stray location. Added the $doc
     * param so this method can be used by other methods.Updates status of stray
     * and adds possible comments.
     *
     * @param Boolean $doc return Mongo_Document or Response
     * @return type
     */
    public function action_updatestray($doc = false)
    {
        $p      = $this->request->post();
        $id     = \Arr::get($p, 'id');
        $status = \Arr::get($p, 'status');

        // has to be valid status
        if (!in_array($status, static::$stray_statuses))
            return $this->error('invalid_request');

        $comments = \Arr::get($p, 'message');
        $stray    = Document::factory('strayproject', $id);

        $stray->status  = $status;
        $stray->message = $comments;
        $stray->save();

        return $doc ? $stray : $this->data([
              'status' => 'success',
              'data'   => $stray->as_array()
        ]);
    }

    /**
     * Similar to another method. More robust???
     *
     * @see self::action_search()
     * @return /Response with JSON data
     */
    public function action_searchsingpost()
    {
        $s      = Collection::factory('singpost');
        $name   = $this->request->query('name');
        $text   = static::format($name);
        ;
        $street = $this->request->query('street');
        $postal = $this->request->query('postal');
        $or     = [];
        $return = [
            'namefound'   => false,
            'streetfound' => false,
            'postalfound' => false,
            'total'       => 0,
            'data'        => null,
        ];

        if (is_array($text))
        {
            $text = array_map('strtolower', $text);
            $or[] = ['keywords' => ['$in' => $text]];
        }

        if ($name)
            $or[] = ['keywords' => strtolower($name)];


        if ($street)
            $or[] = static::parse_street($street);

        if ($postal)
            $or[]   = ['keywords' => strtolower($postal)];
        //return $this->data($or);
        $cursor = $s->find(['$or' => array_filter($or)]);

        $return['data']      = (object) [];
        $return['debugData'] = ['$or' => array_filter($or)];

        if ($cursor->hasNext())
        {
            $return['total'] = count($cursor);
            $row             = $cursor->getNext();
            $return['data']  = $row->stringify($row->as_array());
            $return['ids'][] = (string) $row->_id;

            if (strtolower($row->building) === strtolower($name))
                $return['namefound'] = true;

            if (strtolower($row->number) . ' ' . strtolower($row->street) === strtolower($street))
                $return['streetfound'] = true;

            if (strtolower($row->postal) === strtolower($postal))
                $return['postalfound'] = true;
        }

        while ($cursor->hasNext())
        {
            $return['ids'][] = (string) $cursor->getNext()->_id;
        }

        return $this->data($return);
    }

    /**
     * Retrieves a list of unique estates served by the current request query
     *
     *
     * @return /Response with JSON data
     */
    public function action_estates()
    {
        $id = ucwords(strtolower($this->request->query('term')));
        $e  = Document::factory('singpost')->collection()->distinct('estate', [
            'estate' => new MongoRegex("/^$id/")
        ]);
        return $this->data($e);
    }

    /**
     * Retrieves a list of unique building names served by the current request query
     *
     *
     * @return /Response with JSON data
     */
    public function action_building()
    {
        $term = strtoupper($this->request->query('term'));
        $e    = Document::factory('singpost')->collection()->distinct('building', [
            'building' => new MongoRegex("/^" . $term . "/")
        ]);
        return $this->data($e);
    }

    /**
     * returns json string of results retrieved from the request query params
     * given
     *
     * @uses \Controller_locations::query()
     * @return /Response with JSON data
     */
    public function action_search()
    {
        $query = $this->query();
        $row   = [];
        $e     = Document::factory('singpost');
        $c     = $e->collection()
          ->collection()
          ->find($query);
        while ($c->hasNext())
        {
            $rows[] = $e->stringify($c->getNext());
        }
        return $this->data($rows);
    }

    /**
     * 3 step process that updates or creates new estates by sector
     *  1. updates singpost documents with an estate name in the given sector.
     *  2. Fetches or creates the actual estate location in the 'pages' collection
     *  3. Retrieves any existing child location ids from those
     *     present in the updated singpost entries and then updates the
     *     respective 'parent' fields to reflect the estate in 2.
     *
     * needs a $_POST with the following:[
     *   **estate  => 'name', ** // name of estate,
     *   **sector  => 'sector', ** // two digit sctor code (first 23 digits of postal code)
     * ]
     *
     *
     * @return /Response with JSON data
     */
    public function action_modifyestate()
    {
        $u        = (object) $this->get_user();
        $children = null;
        $estate   = trim($this->request->post('estate'));
        $sector   = trim($this->request->post('sector'));

        Collection::factory('singpost')->update_safe(
          ['postal' => new MongoRegex("/^$sector/")], ['$set' => ['estate' => $estate]], ['multiple' => true]
        );

        $new  = Document::factory('page');
        $load = $new->load(['name'    => $estate, 'type'    => 'location', 'subtype' => 'Estate']);

        if (!$load)
        {
            $new = $new->create([
                'subtype' => 'Estate',
                'type'    => 'location',
                'user'    => ['_id'  => Document::check_id($u->_id), 'name' => $u->name],
                'name'    => $estate,
                'sector'  => $sector,
            ]);

            $new->load();
        }

        if ($new->loaded())
        {
            $uids       = [];
            $updatekids = Collection::factory('singpost')->find(['postal' => new MongoRegex("/^$sector/")]);
            while ($updatekids->hasNext())
            {
                $c   = $updatekids->getNext();
                $cid = Arr::get($c->child, '_id');
                if ($cid)
                {
                    $uids[] = $cid;
                }
            }

            $children = Collection::factory('page')->update_safe(['_id' => ['$in' => $uids]], [
                '$unset' => ['oldestate' => 1],
                '$set'   => [
                    'sector' => $sector,
                    'parent' => [
                        '_id'  => $new->_id,
                        'name' => $new->name,
                        'uri'  => $new->uri,
                        'type' => 'location',
                    ]]], ['multiple' => true]);

            if ($children)
            {
                $old_count = Arr::get($new->counts, 'locations', 0);
                $new_count = (int) $children + (int) $old_count;
                $new->set('counts.locations', $new_count);
                $new->save();
            }
        }

        return $this->data([
              'children'  => $children,
              'newcount'  => $new_count,
              'newestate' => $new->as_array()
        ]);
    }

    /**
     * This method creates a new location and updates singpost collection with
     * the newly created location id.
     *
     * needs a $_POST with the following:[
     *   'ids   => [], //of singpost document ids to update
     *   'data' => [
     *      **estate  => 'name', ** // name of estate
     *      **name    => 'name', ** // the name of the new location
     *      **subtype => 'name', ** // the location subtype 'private-apartments' etc
     *   ]
     * ]
     *
     * @params $_POST array()
     * @return /Response with JSON data
     */
    public function action_createlocation()
    {
        $new     = [];
        $u       = (object) $this->get_user();
        $ids     = $this->request->post('ids');
        $data    = $this->request->post('data');
        $data    = array_map("trim", array_map("strip_tags", array_filter((array)$data)));
        $pt      = Arr::get($data, 'property_type');
        if(is_array($pt))
        {
            if(!empty($pt) && !empty($pt[0]))
                $pt = $pt[0];
            else
                $pt = null;
        }
        $estate  = Arr::get($data, 'estate');
        $name    = Arr::get($data, 'name');
        $subtype = Arr::get($data, 'subtype');
        // we need these. not there or not valid? throw error
        if (empty($ids) || !$estate || !$name || !$subtype || !in_array($subtype, ['Private Apartments', 'Serviced Apartments', 'Private Landed', 'HDB']))
            return $this->error('invalid_request');

        // retrieve the given estate from the db or throw error if not found
        $e = Document::factory('page');

        if (!$e->load(['type' => 'location', 'name' => $estate]))
            return $this->error('invalid_request');

        // extract the parent subdocument.
        $parent = \Arr::extract($e->as_array(), ['_id', 'name', 'uri', 'type']);

        // a new location model
        $l = Document::factory('page');

        // we check if an existing location exists, safety measure. Ideally I
        // would check against 'keywords', but there is no telling how many false
        // positives there might be. Text entry just cannot be predicted.
        if (!$l->load(['type' => 'location', 'name' => static::ucwords($name)]))
        {
            // populate location data
            $new['parent']        = $parent;
            $new['subtype']       = $subtype;
            $new['name']          = $name;
            $new['property_type'] = Arr::get($data, 'property_type');
            $new['content']       = Arr::get($data, 'content');
            $new['type']          = 'location';
            $new['user']          = ['_id'  => Document::check_id($u->_id), 'name' => $u->name];

            //return $this->data($new);
            // save
            if ($l->create($new))
            {
                // estate will need an up-count
                $e->inc('counts.locations');
                $e->save();
            }
        }
        else
        {
            $l->name          = $name;
            $l->subtype       = $subtype;
            if($pt)
            {
                $l->property_type = $pt;
            }
            $l->content       = Arr::get($data, 'content');
            $l->parent        = $parent;
            //return $this->data($l->as_array());
            $l->save();
        }
        !$l->loaded() && $l->load();
        // update singpost table with the newly created child location and add
        // the child location name to the keywords sub-document.
        if ($l->loaded())
        {
            Collection::factory('singpost')->update_safe(
              ['_id' => ['$in' => array_map('Document::check_id', $ids)]], [
                '$set'      => [
                    'type'  => $subtype,
                    'child' => [
                        '_id'  => $l->_id,
                        'name' => $l->name,
                        'uri'  => $l->uri,
                    ]
                ],
                '$addToSet' => [
                    'keywords' => strtolower($l->name)
                ]], ['multiple' => true]
            );

            return $this->data([
                  'location'       => $l->as_array(),
                  'postal_updated' => count($ids)
            ]);
        }
        //return false;
        return $this->error('invalid_request');
    }

    /**
     * Simple method to get a location by name
     *
     * @return Response
     */
    public function action_fetchlocation()
    {
        $name = $this->request->query('name');
        if($name)
        {
            $d = Document::factory('page', ['name' => static::ucwords($name)]);
            if($d->load())
                return $this->data($d->stringify($d->as_array()));
        }
        return $this->data([]);
    }

    /**
     * This is a scenario where admin edits or changes a location while viewing
     * a single property.
     *
     * 1. Grab the location by id and update.
     *      - set a job that updates all posts that have this id (cron)
     * 2. Update all Singpost entries with contains this location id (child._id)
     * 3. Check if estate has changed. If true:
     *      - fetch estate location
     *      - update singpost to rename all estates with this name.
     *      - update all locations with this fetched location id
     *
     * needs a $_POST with the following:[
     *     estate  => 'name', ** // name of estate
     *     name    => 'name', ** // the name of the new location
     *     subtype => 'subtype', ** // the location subtype 'private-apartments' etc
     *     property_type => 'property_type', ** // the property_type, if available
     * ]
     *
     *
     * @return /Response with JSON data
     */
    public function action_editlocationinline()
    {
        $u        = (object) $this->get_user();
        $children = null;
        $data = json_decode(\Arr::get(static::$params, 'data'), true);
        $id = \Arr::get($data, '_id');
        $parent = \Arr::get($data, 'parent');
        $singpost = Collection::factory('singpost');
        $children = Collection::factory('page');
        $response = [];

        // parent found, means estate name is changed
        if($parent)
        {
            $p_doc = Document::factory('page', \Arr::get($parent, '_id'));
            $p_doc->load();
            $oldname = $p_doc->name;

            $name = trim(strip_tags(\Arr::get($parent, 'name')));
            $slug = URL::title($name);
            $uri = "/location/estate/" . $slug;

            $p_doc->name = $name;
            $p_doc->slug = $slug;
            $p_doc->uri = $uri;
            $p_doc->addToSet('keywords', $slug);
            $p_doc->save();
            $response['parent'] = \Arr::overwrite($parent, array_merge(["uri" => $uri], $p_doc->as_array()));

            // update singapost results;
            $singpost->update([
                'estate' => $oldname
            ], [
                '$set' => ['estate' => $name],
                '$addToSet' => ['keywords' => strtolower($name)]
            ],[
                'multiple' => true
            ]);

            $singpost->reset();

            // update all children to reflect new parent name
            $children->update([
                'parent._id' => $p_doc->_id
            ], [
                '$set' => [
                    'parent.name' => $name,
                    'parent.uri' => $uri
                ],
                '$addToSet' => ['keywords' => strtolower($p_doc->name)]
            ],[
                'multiple' => true
            ]);
        }

        // id found update the location and all singpost results
        if($id)
        {
            $c_doc = Document::factory('page', $id);
            $new_vals = $singpost_vals = [];

            $subtype = \Arr::get($data, 'subtype');

            if(!$subtype || !in_array($subtype, ['Private Apartments', 'Serviced Apartments', 'Private Landed', 'HDB']))
                return $this->error('invalid_request');

            foreach($data as $k => $v)
            {
                switch(true)
                {
                    case $k === 'name':
                        $singpost_vals['name'] = trim(strip_tags($v));
                        $singpost_vals["slug"] = URL::title($singpost_vals['name']);
                        $singpost_vals["uri"] = "/location/" . URL::title($subtype) . '/' . $singpost_vals["slug"];
                        break;
                    case $k === "subtype":
                        $new_vals['subtype'] = $subtype;
                        break;
                    case $k === "property_type":
                        $new_vals['property_type'] = $v;
                        break;
                }
            }
            $c_update = $new_vals + $singpost_vals;
            foreach($c_update as $p => $v)
            {
                $c_doc->{$p} = $v;
            }

            $c_doc->addToSet('keywords', $singpost_vals["slug"]);
            //$c_doc->a
            $c_doc->save();
            $response += $c_update;

            // update singapost results;
            $singpost->update([
                // criteria is _id
                'child._id' => Document::check_id($id)
            ], [
                // new child subdoc
                '$set' => ['child' => $singpost_vals + ['_id' => Document::check_id($id)]],
                // add the child name to the keywords
                '$addToSet' => ['keywords' => strtolower($singpost_vals["name"])]
            ],[
                'multiple' => true
            ]);

        }

        return !empty($response) ? $this->data($data) : $this->error('invalid_request');
    }


    /**
     * Splits a keyword into as many forms as queriable to return better results
     *
     * ObjectId("50ed2599b90fb8c81700003f")
     * ObjectId("50ed2578b90fb8c81700003e")
     *
     * @param string $text
     * @param boolean $result
     * @return mixed \Mongo_Cursor of an executed query or a query array
     */
    public static function findBuilding($text, $result = false)
    {
        $text     = static::format($text);
        $singpost = Collection::factory('singpost');

        if (is_array($text))
        {
            $r1 = new MongoRegex("/^" . ucwords(strtolower($text[0])) . "/");
            $r2 = new MongoRegex("/^" . ucwords(strtolower($text[1])) . "/");
            $q  = [
                '$or' => [
                    ['keywords' => $r1],
                    ['keywords' => $r2]
                ]
            ];
            return $result ? $singpost->collection()->findOne($q) : $q;
        }

        $r1 = new MongoRegex("/^" . $text . "/");
        $r2 = new MongoRegex("/^" . ucwords(strtolower($text)) . "/");
        $q  = [
            '$or' => [
                ['keywords' => $r1],
                ['keywords' => $r2],
            ]
        ];
        return $result ? $singpost->collection()->findOne($q) : $q;
    }

    /**
     * A simple utility that
     *  1. converts "project@place" to "project @ place"
     *  2. converts "project, the" to an array for use as an '$in' mongo query
     *     ['the', 'project'].
     *     (the keywords subdoc has "the" as one of it's values)
     *
     * @param string $text
     * @return mixed array | string
     */
    public static function format($text)
    {

        $parts = explode('@', $text);
        if (!empty($parts))
        {
            $parts = array_map('trim', $parts);
            $parts = array_map('strtolower', $parts);
            $text  = implode(" ", $parts);
        }

        if (strpos($text, 'THE ') === 0)
        {
            $text = [
                trim(substr($text, 4, strlen($text))), $text
            ];
        }
        //echo Debug::vars($text);
        return $text;
    }

    /**
     * This method parses through query parameters and returns an organised
     * query array for use with mongodb. It ensures an index order on multikey
     * indexes created on the Singpost collection - where possible.
     * index is {estate, child, keywords}
     *
     * @return array a MongoDB compatible query
     */
    public function query()
    {
        // the return array
        $r = array();
        // remove all empty values recursively
        $q = array_map('array_filter', $this->request->query());

        foreach ($q as $k => $v)
        {
            if (in_array($k, ['estate']))
            {
                $r[$k] = static::_parse_string_search($v, 'self::ucwords');
            }

            if ($k === 'child')
            {
                $child = self::_parse_null_string($v);

                if (is_string($child))
                    $r['$or']       = [
                        ['child.name' => new MongoRegex("/^" . strtoupper($v) . "/")],
                        ['child' => new MongoRegex("/^" . $v . "/")]
                    ];
                else
                    $r['child._id'] = $child;
            }

            if (in_array($k, ['property_type']))
            {
                $r['type'] = static::_parse_string_search($v, 'self::ucwords');
            }

            if ($k === 'building')
            {
                $r[$k] = static::_parse_string_search($v, 'strtoupper');
            }
        }

        if (isset($q['sector']) && !empty($q['sector']))
        {
            $r['postal'] = new MongoRegex("/^{$q['sector']}/");
        }

        if (isset($q['singpostType']))
        {
            $r['address'] = ['$in' => $q['singpostType']];
        }

        if (isset($q['s']))
        {
            $keywords = [
                ['building' => new MongoRegex("/^" . strtoupper($q['s']) . "/")],
                ['child.name' => new MongoRegex("/^" . ucwords(strtolower($q['s'])) . "/")],
                ['child' => new MongoRegex("/^" . ucwords(strtolower($q['s'])) . "/")]
            ];


            if (isset($r['$or']) && is_array($r['or']))
            {
                $r['$or'] = \Arr::merge($r['$or'], $keywords);
            }
            else
            {
                $r['$or'] = $keywords;
            }

            if (strpos($q['s'], ','))
            {
                $r['$or'][] = ['postal' => ['$in' => array_map("trim", explode(',', $q['s']))]];
            }
            else
            {
                $r['$or'][] = ['postal' => new MongoRegex("/^" . ucwords(strtolower($q['s'])) . "/")];
            }
        }

        return array_map('array_filter', $r);
    }

    /** A simple utility that parses & processes "NULL" & "NOT NULL" string values
     * Also applies a callback filter to the text when supplied with one. Finally
     * returns a valid MongoDB query based on the supplied text. Also handles
     * regular text.
     *
     * @param string $value
     * @param Callable $filter the callback function to apply to the text
     * @return \MongoRegex
     */
    protected static function _parse_string_search($value, $filter = null)
    {
        $v = self::_parse_null_string(strtoupper($value));

        //if null or an array, this was a "NULL"/"NOT NULL" type string. nothing
        // left to do.
        if (is_null($v) || is_array($v))
            return $v;

        // sometimes, specific query fields need special processing like ucwords
        if (is_callable($filter))
            $v = $filter($v);

        return new MongoRegex("/^" . $v . "/");
    }

    /**
     * Does most of the heavy lifting for the method above.
     *
     * @param String $value
     * @return mixed null|Array|String $value
     */
    protected static function _parse_null_string($value)
    {
        $v = strtoupper($value);
        if ($v === "NULL")
            return null;

        if ($v === "NOT NULL")
            return ['$ne' => NULL];

        return $value;
    }

    /**
     * Another utility. To cut typing and to allow use as callable function.
     *
     * @param String $v
     * @return String
     */
    public static function ucwords($v)
    {
        return ucwords(strtolower($v));
    }

    /**
     * Some address sources for properties have addresses ranges. e.g.
     * "1 - 50 example road". Since this is not ideal for a query in SingPost db
     * (singpost is one address per document), this method returns an array
     * for use in and '$in' operator, splitting the above addresses into individual
     * items if such an address was provided.
     *
     * @param String $text
     * @return Array Mongo Query
     */
    protected function parse_street($text)
    {
        // check if this address has the deciding '-'
        if (strpos($text, '-') !== false)
        {
            // we have a match. first explode
            $explode1 = array_map("trim", explode("-", $text));

            // check if second block starts with a number
            if (is_numeric(substr($explode1[1], 0, 1)))
            {
                //explode again, whitespace this time.
                $explode2 = array_map("trim", explode(" ", $explode1[1]));

                //start a range
                $range = range((int) $explode1[0], (int) $explode2[0]);

                // remove the number from the second part
                unset($explode2[0]);

                // combine the remaining words to form the street name.
                $street = strtolower(trim(implode(" ", $explode2)));

                foreach ($range as $k => $r)
                {
                    // build the query "1 example road", "2 example road" etc
                    $in[] = "$k $street";
                }
                // return the $in query
                return ['keywords' => ['$in' => $in]];
            }
        }
        // regular address, just return.
        return ['keywords' => strtolower($text)];
    }


	public function action_fixestates()
	{
		$estates = Collection::factory('page')
		->find([
			'type' => 'location',
			'subtype' => 'Estate',
			
		]);
		
		while($estates->hasNext())
		{
			$e = $estates->getNext();
			$children = Collection::factory('page')
					->find([
						'$or' => [
							['parent._id' => $e->_id],
							['parent.name' => $e->name]
						]				
					])->count();
					
					$e->set('counts.locations', $children);
					$e->save();
					echo "{$e->name}: " . $e->counts['locations'] . " <br>";
					echo "$children <br>";
		
		}
		
		
	}


    public function action_updatekeywords()
    {
        $c = Collection::factory('page')->find();

        while ($c->hasNext())
        {
            $d = $c->getNext();
            $d->addToSet("keywords", strtolower($d->slug));
            $d->save();
            $ret [] = $d->keywords;
        }

        return $this->response->body(Debug::vars($ret));
    }
}