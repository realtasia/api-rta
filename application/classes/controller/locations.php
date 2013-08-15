<?php defined('SYSPATH') or die('No direct script access.');
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
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Locations extends Controller_Resource {

//class Controller_Locations extends Controller {

    public function action_estates()
    {
        $id = ucwords(strtolower($this->request->query('term')));
        $e  = Document::factory('singpost')->collection()->distinct('estate', [
            'estate' => new MongoRegex("/^$id/")
                ]);
        return $this->data($e);
    }

    public function action_child()
    {
        $id = ucfirst($this->request->query('term'));
        $e  = Document::factory('singpost')->collection()->distinct('child', [
            '$or' => [
                ['child.name' => new MongoRegex("/^" . $id . "/")],
                ['child' => new MongoRegex("/^" . $id . "/")]
            ]
                ]);
        return $this->data($e);
    }

    public function action_building()
    {
        $id = strtoupper($this->request->query('term'));
        $e  = Document::factory('singpost')->collection()->distinct('building', [
            'building' => new MongoRegex("/^$id/")
                ]);
        return $this->data($e);
    }

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

    public function action_createestates()
    {
        $r      = [];
        $id     = ucwords(strtolower($this->request->query('term')));
        $e      = array_filter(Document::factory('singpost')->collection()->distinct('estate'));
        $p      = Document::factory('page');
        $u      = (object) $this->get_user();
        $params = ['subtype' => 'estate', 'type'    => 'location', 'user'    => ['_id'  => $u->_id, 'name' => $u->name]];

        foreach ($e as $estate)
        {
            $params['name'] = $estate;
            $p->clear();
            $r[]            = $p->create($params)->as_array();
        }
        return $this->data($r);
    }

    public function action_modifyestate()
    {
        $u         = (object) $this->get_user();
        $children  = null;
        $estate    = trim($this->request->post('estate'));
        $sector    = trim($this->request->post('sector'));

        Collection::factory('singpost')->update_safe(
                ['postal' => new MongoRegex("/^$sector/")], ['$set' => ['estate' => $estate]], ['multiple' => true]
        );

        $new = Document::factory('page');
        $load = $new->load(['name' => $estate, 'type' => 'location', 'subtype' => 'Estate']);

        if(!$load)
        {
            $new          = $new->create([
                'subtype' => 'Estate',
                'type'    => 'location',
                'user'    => ['_id'  => Document::check_id($u->_id), 'name' => $u->name],
                'name'    => $estate,
                'sector'  => $sector,
            ]);

            $new->load();
        }

        if($new->loaded())
        {
            $uids = [];
            $updatekids = Collection::factory('singpost')->find(['postal' => new MongoRegex("/^$sector/")]);
            while($updatekids->hasNext())
            {
                $c = $updatekids->getNext();
                $cid = Arr::get($c->child, '_id');
                if($cid)
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

            if($children)
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

    public function action_createlocation()
    {
        $new     = [];
        $u       = (object) $this->get_user();
        $ids     = $this->request->post('ids');
        $data    = $this->request->post('data');
        $data    = array_filter($data);
        $estate  = Arr::get($data, 'estate');
        $name    = Arr::get($data, 'name');
        $subtype = Arr::get($data, 'subtype');
        // we need these. not there or not valid? throw error
        if (empty($ids) || !$estate || !$name || !$subtype || !in_array($subtype, ['Private Apartments', 'Serviced Apartments', 'Private Landed', 'HDB']))
            return $this->error('invalid_request');

        // retrieve the given estate from the db or throw error if not found
        $e = Document::factory('page');

        if (!$e->load(['name' => $estate, 'type' => 'location']))
            return $this->error('invalid_request');

        $l = Document::factory('page');
        if (!$l->load(['name' => $name]))
        {
            // populate location data
            $new['parent']        = [
                '_id'  => $e->_id,
                'name' => $e->name,
                'uri'  => $e->uri,
                'type' => 'location',
            ];
            $new['subtype']       = $subtype;
            $new['name']          = $name;
            $new['property_type'] = Arr::get($data, 'property_type', []);
            $new['content']       = Arr::get($data, 'content');
            $new['type']          = 'location';
            $new['user']          = ['_id'  => Document::check_id($u->_id), 'name' => $u->name];

            // load new values
            $location = Document::factory('page');

            // save
            if ($location->create($new))
            {
                $e->inc('counts.locations');
                $e->save();
                // update singpost table
                Collection::factory('singpost')->update_safe(
                        ['_id' => ['$in' => Arr::map('Document::check_id', $ids)]], ['$set' => [
                        'type'  => Arr::get($data, 'property_type'),
                        'child' => [
                            '_id'  => $location->_id,
                            'name' => $location->name,
                            'uri'  => $location->uri,
                    ]]], ['multiple' => true]
                );
                //return $location;
            }
        }
        else
        {
            $l->name          = $name;
            $l->subtype       = $subtype;
            $l->property_type = Arr::get($data, 'property_type', []);
            $l->content       = Arr::get($data, 'content');
            $l->parent        = [
                '_id'  => $e->_id,
                'name' => $e->name,
                'uri'  => $e->uri,
                'type' => 'location',
            ];

            $l->save();

            Collection::factory('singpost')->update_safe(
                    ['_id' => ['$in' => Arr::map('Document::check_id', $ids)]], ['$set' => [
                    'child' => [
                        '_id'  => $l->_id,
                        'name' => $l->name,
                        'uri'  => $l->uri,
                ]]], ['multiple' => true]
            );
        }
        //return false;
        //return $this->data(['success' => true]);
        return $this->data(Arr::merge($ids, ['estate' => $estate]));
    }

    public function action_csv()
    {
        $e        = Document::factory('page');
        $location = Document::factory('page');
        $json     = fopen("notfound.json", "w");
        $nf       = [];
        // load new values

        if (($handle = fopen("condos.csv", "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                $name = mb_substr($data[0], 0, mb_strlen($data[0]) - 1);
                $type = $data[2];
                $f    = static::findBuilding($name, true);
                if ($f)
                {
                    // check if child exists
                    if (!(Arr::get((array) $f['child'], '_id') instanceof MongoID))
                    {
                        // no child, get estate or return error
                        if (!$e->load(['name' => $f['estate'], 'type' => 'location']))
                        {
                            echo Debug::vars(['Estate Not Found' => $f['child']]);
                            return;
                        }



                        $property_type        = $f['address'] === 'Walkup' ? $f['address'] . ' ' . $type : $type;
                        // populate location data
                        $new['parent']        = [
                            '_id'  => $e->_id,
                            'name' => $e->name,
                            'uri'  => $e->uri,
                            'type' => 'location',
                        ];
                        $new['subtype']       = 'Private Apartments';
                        $new['name']          = ucwords(strtolower($name));
                        $new['sector']        = substr($f['postal'], 0, 2);
                        $new['property_type'] = $property_type;
                        $new['content']       = Arr::get($data, 'content');
                        $new['type']          = 'location';
                        $new['user']          = ['_id'  => new MongoID('50b7620ab90fb8e884000009'), 'name' => 'Jason Nathan'];
                        if ($location->create($new))
                        {
                            $e->inc('counts.locations');
                            $e->save();
                            // update singpost table
                            $update = Collection::factory('singpost')->update_safe(
                                    static::findBuilding($name), ['$set' => [
                                    'child' => [
                                        '_id'  => $location->_id,
                                        'name' => $location->name,
                                        'uri'  => $location->uri,
                                ]]], ['multiple' => true]
                            );
                            echo Debug::vars(Arr::merge($location->as_array(), (array) $update));
                        }
                        $e->clear();
                        $location->clear();
                    }
                    else
                    {
                        echo Debug::vars(['Child Exists' => $name]);
                    }
                }
                else
                {
                    $nf[] = $name;
                }
            }
            fwrite($json, json_encode($nf));
            fclose($handle);
        }
    }

    public function action_json()
    {
        $found    = $total    = 0;
        $e        = Document::factory('page');
        $location = Document::factory('page');
        $file     = json_decode(file_get_contents("notfound.json"));
        $json     = fopen("notfound.json", "w");
        $nf       = [];
        // load new values
        foreach ($file as $name)
        {
            //echo Debug::vars(static::findBuilding($name));
            $total++;
            $type = $data[2];
            $f    = static::findBuilding($name, true);
            if ($f)
            {
                $found++;
                // check if child exists
                if (!(Arr::get((array) $f['child'], '_id') instanceof MongoID))
                {
                    // no child, get estate or return error
                    if (!$e->load(['name' => $f['estate'], 'type' => 'location']))
                    {
                        echo Debug::vars(['Estate Not Found' => $f['child']]);
                        return;
                    }

                    $property_type        = $f['address'] === 'Walkup' ? $f['address'] . ' ' . $type : $type;
                    // populate location data
                    $new['parent']        = [
                        '_id'  => $e->_id,
                        'name' => $e->name,
                        'uri'  => $e->uri,
                        'type' => 'location',
                    ];
                    $new['subtype']       = 'Private Apartments';
                    $new['name']          = ucwords(strtolower($name));
                    $new['sector']        = substr($f['postal'], 0, 2);
                    $new['property_type'] = $property_type;
                    $new['content']       = Arr::get($data, 'content');
                    $new['type']          = 'location';
                    $new['user']          = ['_id'  => new MongoID('50b7620ab90fb8e884000009'), 'name' => 'Jason Nathan'];
                    if ($location->create($new))
                    {
                        $e->inc('counts.locations');
                        $e->save();
                        // update singpost table
                        $update = Collection::factory('singpost')->update_safe(
                                static::findBuilding($name), ['$set' => [
                                'child' => [
                                    '_id'  => $location->_id,
                                    'name' => $location->name,
                                    'uri'  => $location->uri,
                            ]]], ['multiple' => true]
                        );
                        echo Debug::vars(Arr::merge($location->as_array(), (array) $update));
                    }
                    $e->clear();
                    $location->clear();
                }
                else
                {
                    echo Debug::vars(['Child Exists' => $name]);
                    unset($name);
                }
            }
            else
            {
                $nf[] = $name;
            }
        }
        fwrite($json, json_encode(array_filter($nf)));
        echo Debug::vars([
            'total' => $total,
            'found' => $found,
        ]);
    }

    public function action_lostandfound()
    {
        $found  = $total  = 0;
        $mysqli = new mysqli('127.0.0.1', 'root', '', 'test');
        $json   = json_decode(file_get_contents("notfound.json"));

        foreach ($json as $name)
        {
            $total++;
            $query  = "SELECT * FROM `condominiums` WHERE `Project` RLIKE '" . $mysqli->real_escape_string($name) . "' ";
            $result = $mysqli->query($query) or die($mysqli->error . __LINE__);
            if ($result->num_rows > 0)
            {
                $found++;
                while ($row = $result->fetch_assoc())
                {
                    echo Debug::vars(Arr::merge($row, ['NAME' => $name]));
                }
            }
        }
        echo Debug::vars([
            'total' => $total,
            'found' => $found,
        ]);
    }

    public static function findBuilding($text, $result = false)
    {
        $text     = static::format($text);
        $singpost = Collection::factory('singpost');

        if (is_array($text))
        {
            $r1 = new MongoRegex("/^" . $text[0] . "/");
            $r2 = new MongoRegex("/^" . $text[1] . "/");
            $r3 = new MongoRegex("/^" . ucwords(strtolower($text[0])) . "/");
            $r4 = new MongoRegex("/^" . ucwords(strtolower($text[1])) . "/");
            $q  = [
                '$or' => [
                    ['building' => $r1],
                    ['building' => $r2],
                    ['other_name' => $r3],
                    ['other_name' => $r4],
                ]
            ];
            return $result ? $singpost->collection()->findOne($q) : $q;
        }

        $r1 = new MongoRegex("/^" . $text . "/");
        $r2 = new MongoRegex("/^" . ucwords(strtolower($text)) . "/");
        $q  = [
            '$or' => [
                ['building' => $r1],
                ['other_name' => $r2],
            ]
        ];
        return $result ? $singpost->collection()->findOne($q) : $q;
    }

    public static function format($text)
    {

//        $parts = explode(' ', $text);
//
//        foreach ($parts as $k => $part)
//        {
////            if ($part === 'AT')
////            {
////                $parts[$k] = '@';
////            }
//
//            if (is_numeric($part))
//            {
//                unset($parts[$k]);
//            }
//        }
//        $text = trim(implode(' ', $parts));

        if (strpos($text, 'THE ') === 0)
        {
            $text = [
                trim(substr($text, 4, strlen($text))), $text
            ];
        }
        echo Debug::vars($text);
        return $text;
    }

    public function query()
    {
        $r = [];
        $q = array_filter($this->request->query());

        foreach ($q as $k => $v)
        {
            if (empty($v))
                unset($q[$k]);

            if (in_array($k, ['estate']))
            {
                switch ($v)
                {
                    case 'NULL':
                        $r[$k] = NULL;
                        break;
                    case 'NOT NULL':
                        $r[$k] = ['$ne' => NULL];
                        break;
                    default:
                        $r[$k] = new MongoRegex("/^" . ucwords(strtolower($v)) . "/");
                        break;
                }
            }

            if (in_array($k, ['property_type']))
            {
                switch ($v)
                {
                    case 'NULL':
                        $r['type'] = NULL;
                        break;
                    case 'NOT NULL':
                        $r['type'] = ['$ne' => NULL];
                        break;
                    default:
                        $r['type'] = new MongoRegex("/^" . ucwords(strtolower($v)) . "/");
                        break;
                }
            }

            if ($k === 'building')
            {
                switch ($v)
                {
                    case 'NULL':
                        $r[$k] = NULL;
                        break;
                    case 'NOT NULL':
                        $r[$k] = ['$ne' => NULL];
                        break;
                    default:
                        $r[$k] = new MongoRegex("/^" . strtoupper($v) . "/");
                        break;
                }
            }

            if ($k === 'child')
            {
                switch ($v)
                {
                    case 'NULL':
                        $r['child._id'] = NULL;
                        $r['keywords'] = 'nolocation';
                        break;
                    case 'NOT NULL':
                        $r['child._id'] = ['$ne' => NULL];
                        break;
                    default:
                        $r['$or']       = [
                            ['child.name' => new MongoRegex("/^" . strtoupper($v) . "/")],
                            ['child' => new MongoRegex("/^" . $v . "/")]
                        ];
                        break;
                }
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

            $r['$or'] = [
                ['building' => new MongoRegex("/^" . strtoupper($q['s']) . "/")],
                ['child.name' => new MongoRegex("/^" . ucwords(strtolower($q['s'])) . "/")],
                ['child' => new MongoRegex("/^" . ucwords(strtolower($q['s'])) . "/")]
            ];
            if (strpos($q['s'], ','))
            {
                $r['$or'][] = ['postal' => ['$in' => explode(',', $q['s'])]];
            }
            else
            {
                $r['$or'][] = ['postal' => new MongoRegex("/^" . ucwords(strtolower($q['s'])) . "/")];
            }
        }

        return $r;
    }

    public function action_correct()
    {
        return $this->data(Collection::factory('page')->correct3());
    }

    public function action_orphans()
    {
        $ids = [];
        echo "<pre>";
        $estates = Collection::factory('page')->find([
            'type' => 'location', 'subtype' => 'Estate'
        ], ['_id' => 1]);

        while($estates->hasNext())
        {
            $e = $estates->getNext();
            $ids[] = Document::check_id($e->_id);
        }

        $ids = array_filter($ids);

        $children = Collection::factory('page')->remove_safe([
            'type' => 'location', 'parent._id' => ['$nin' => $ids], 'subtype' => ['$ne' => 'Estate']
        ], ['multiple' => true]);
        echo Debug::vars($children);

//        while($children->hasNext())
//        {
//            $c= $children->getNext()->as_array();
//            var_dump(['name' => $c['name'], 'parent' => $c['parent']]);
//        }
    }
}