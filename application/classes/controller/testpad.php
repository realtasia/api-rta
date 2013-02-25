<?php defined('SYSPATH') or die('No direct script access.');
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2000M');

/**
 *  Testing Controller for some tests
 *
 * @author Jason Nathan
 * @category RealtAsia API
 * @package Controller
 * @subpackage Testpad
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
 */
class Controller_Testpad extends Controller {

    use Trait_Oauth2_Utils;

    public static $updates = [
        ['q' => ['postal' => '539060'], 'u' => ['type' => 'Private Apartments']],
        ['q' => ['postal' => '387990'], 'u' => ['type' => 'Private Apartments']],
        ['q' => ['postal' => '259428'], 'u' => ['type' => 'Private Apartments']],
        ['q' => ['postal' => '259421'], 'u' => ['type' => 'Private Apartments']],
        ['q' => ['postal' => '059521'], 'u' => ['type' => 'Private Apartments']],
        ['q' => ['postal' => '239856'], 'u' => ['type' => 'Private Apartments']],
        ['q' => ['postal' => '218307'], 'u' => ['type' => 'Private Apartments', 'property_type' => 'walkup']],
        ['q' => ['postal' => '118895'], 'u' => ['type' => 'Private Apartments', 'child' => 'Le Hill Condominium']],
        ['q' => ['postal' => '118896'], 'u' => ['type' => 'Private Apartments', 'child' => 'Le Hill Condominium']],
        ['q' => ['postal' => '207555'], 'u' => ['type' => 'Private Apartments', 'child' => 'Tyrwhitt 139']],
        ['q' => ['postal' => '219757'], 'u' => ['type' => 'Private Apartments', 'child' => "D' CAMBRIDGE"]],
        ['q' => ['postal' => '219758'], 'u' => ['type' => 'Private Apartments', 'child' => "D' CAMBRIDGE"]],
        ['q' => ['postal' => '238800'], 'u' => ['type' => 'Private Apartments', 'child' => "ORCHARD PARKSUITES"]],
        ['q' => ['postal' => '238962'], 'u' => ['type' => 'Private Apartments', 'child' => "ACORN"]],
        ['q' => ['postal' => '248196'], 'u' => ['type' => 'Private Apartments', 'child' => "STUDIO 3"]],
        ['q' => ['postal' => '248903'], 'u' => ['type' => 'Private Apartments', 'child' => "MELROSE PARK"]],
        ['q' => ['postal' => '248904'], 'u' => ['type' => 'Private Apartments', 'child' => "MELROSE PARK"]],
        ['q' => ['postal' => '249445'], 'u' => ['type' => 'Private Apartments', 'child' => "SEVEN HOLT ROAD"]],
        ['q' => ['postal' => '257902'], 'u' => ['type' => 'Private Apartments', 'child' => "STEVENS LOFT"]],
        ['q' => ['postal' => '258117'], 'u' => ['type' => 'Private Apartments', 'child' => "THE HERMITAGE"]],
        ['q' => ['postal' => '258376'], 'u' => ['type' => 'Private Apartments', 'child' => "NASSIM 9"]],
        ['q' => ['postal' => '259345'], 'u' => ['type' => 'Private Apartments', 'child' => "CHATEAU LE FAME"]],
        ['q' => ['postal' => '259811'], 'u' => ['type' => 'Private Apartments', 'child' => "BALMORAL GATE"]],
        ['q' => ['postal' => '259796'], 'u' => ['type' => 'Private Apartments', 'child' => "BALMORAL MANSIONS"]],
        ['q' => ['postal' => '277125'], 'u' => ['type' => 'Private Apartments', 'child' => "THE SIERRA"]],
        ['q' => ['postal' => '278738'], 'u' => ['type' => 'Private Apartments', 'child' => "BLISSFUL VIEW"]],
        ['q' => ['postal' => '278738'], 'u' => ['type' => 'Private Apartments', 'child' => "BLISSFUL VIEW"]],
        ['q' => ['postal' => '308107'], 'u' => ['type' => 'Private Apartments', 'child' => "NOVENA SUITES"]],
        ['q' => ['postal' => '308231'], 'u' => ['type' => 'Private Apartments', 'child' => "MONARCHY APARTMENTS"]],
        ['q' => ['postal' => '309494'], 'u' => ['type' => 'Private Apartments', 'child' => "CHANCERY ESQUIRE"]],
        ['q' => ['postal' => '327848'], 'u' => ['type' => 'Private Apartments', 'child' => "TOPAZ MANSIONS"]],
        ['q' => ['postal' => '329341'], 'u' => ['type' => 'Private Apartments', 'child' => "KEMAMAN VIEW"]],
        ['q' => ['postal' => '387945'], 'u' => ['type' => 'Private Apartments', 'child' => "REGAL 35"]],
        ['q' => ['postal' => '388040'], 'u' => ['type' => 'Private Apartments', 'child' => "PLATINUM RESIDENCE"]],
        ['q' => ['postal' => '388492'], 'u' => ['type' => 'Private Apartments', 'child' => "BOUGAINVILLA APARTMENTS"]],
        ['q' => ['postal' => '388869'], 'u' => ['type' => 'Private Apartments', 'child' => "CANNE LODGE"]],
        ['q' => ['postal' => '389781'], 'u' => ['type' => 'Private Apartments', 'child' => "CRYSTAL DEW"]],
        ['q' => ['postal' => '398922'], 'u' => ['type' => 'Private Apartments', 'child' => "WING FONG COURT"]],
        ['q' => ['postal' => '398924'], 'u' => ['type' => 'Private Apartments', 'child' => "WING FONG MANSIONS"]],
        ['q' => ['postal' => '399710'], 'u' => ['type' => 'Private Apartments', 'child' => "NICOLE GREEN"]],
        ['q' => ['postal' => '424247'], 'u' => ['type' => 'Private Apartments', 'child' => "CHAPEL COURT"]],
        ['q' => ['postal' => '428693'], 'u' => ['type' => 'Private Apartments', 'child' => "LOTUS AT JOO CHIAT"]],
        ['q' => ['postal' => '429433'], 'u' => ['type' => 'Private Apartments', 'child' => "CASA AROMA"]],
        ['q' => ['postal' => '439857'], 'u' => ['type' => 'Private Apartments', 'child' => "ORCHID MANSION"]],
        ['q' => ['postal' => '449303'], 'u' => ['type' => 'Private Apartments', 'child' => "TAIPAN GRAND"]],
        ['q' => ['postal' => '466671'], 'u' => ['type' => 'Private Apartments', 'child' => "TANAH MERAH MANSIONS"]],
        ['q' => ['postal' => '486364'], 'u' => ['type' => 'Private Apartments', 'child' => "EASTWOOD GREEN"]],
        ['q' => ['postal' => '547289'], 'u' => ['type' => 'Private Apartments', 'child' => "Palm Haven"]],
        ['q' => ['postal' => '598429'], 'u' => ['type' => 'Private Apartments', 'child' => "PALM RESIDENCE"]],
        ['q' => ['postal' => '738230'], 'u' => ['type' => 'Private Apartments', 'child' => "THE WOODGROVE"]],
        ['q' => ['postal' => '538800'], 'u' => ['type' => 'Private Apartments', 'child' => "EVERGREEN PARK"]]
    ];


    public function action_updatechild()
    {
        $mc = new Mongo('casa.net:27017');
        $mj = new Mongo('localhost:27017');
        $cc = $mc->selectCollection('db', 'singpost');
        $cj = $mj->selectCollection('db', 'singpost');
        echo "<pre>";

        $rc = $cc->find(['address' => ['$in' => ['Block', 'Standard']], 'child' => null,'street' => ['$not'   => new MongoRegex('/tuas|industrial/i')], 'estate' => ['$ne' => NULL], 'building' => ['$not'   => new MongoRegex('/school|swimming|station|temple|church|complex|centre|camp|hospital|clinic|shop|library|college|stadium|car park|exchange|depot|commission|polyclinic|mosque|masjid|association|interchange|aia |industrial|office|kindergarden|mrt|lrt|sports|waterworks|hall|brickwork|tldm|brigade|defence|eating|food|shopping|post|police|fire|safety|club|hotel|motel|assembly|cargo|airport|airbase|cinema|ministry|government|parliment|health|driving|technology| ctr|kindergarten|breweries|materials|amphitheatre|storage|factory|warehouse|institute|logistics|hospice|center|innovation|aquarium|lagoon|sport|theatres|theatre|techpark|flight|terminal|operations|operation|embassy|amusement|flatted|general|chij|npc|convent|cathedral|aviation|academy|chapel|prison|methodist|metal|works|synagogue|pte.ltd|resort|store|megastore|distripark|hub|dhl|bank|barrack|enterprise|fraser|division|aged|disabled|hostel|hostels|ite |marine|sewage|works|motors|child|maintenance|dormitory|shipyard|naval|nursery|sewer|buddhist|taxi|old age|old people|home for|supply|chemical|islamiah|majlis|logit|homes for|academy|arts|museum|university|polytechnic|ntuc|restaurant|outward|bank|nursing|refuse|tuas|medical|plant|mission|indstrl|society|zoological|red cross|automative|engineering|instrument|design|customs|leisure|publications|delivery/i')]]);
        //var_dump($rc->count());
//var_dump($rc);
        $i = 1;
        while($rc->hasNext())
        {
            $i = $rc->getNext();
            echo "$i <br>";
            var_dump($i);
            $i++;
        }
    }

    public function action_child()
    {
        $results = [];
        $m = new Mongo('localhost:27017');
        $c = $m->selectCollection('db', 'singpost');
        echo "<pre>";
//        $c->ensureIndex(['postal' => 1]);
//        $c->ensureIndex(['number' => 1]);
//        $c->ensureIndex(['street' => 1]);
//        $c->ensureIndex(['address' => 1]);
        //$projects = $c->distinct('building', ['address' => 'Condominium', 'child' => null]);
        //$projects = $c->update(['child' => ['$ne' => null], 'type' => null, 'address' => 'HDB']);
        $projects = $c->find(['child' => ['$ne' => null], 'address' => 'HDB']);

        while($projects->hasNext())
        {
            $r = $projects->getNext();
//            $cursor = $c->update([
//                '_id' => Document::check_id($r['_id'])
//                    ], ['$set' => ['child' => null, 'type' => null]], [
//                'safe' => true
//                    ]);
//            var_dump($cursor);
            if(!empty($r['building']))
            {
                var_dump($r);
            }


        }
//            $r = $projects->getNext();
//            $cursor = $c->update([
//                '_id' => Document::check_id($r['_id'])
//                    ], ['$set' => ['child' => static::format($r['child'])]], [
//                'safe' => true
//                    ]);
//            var_dump($cursor);

    }

    protected static function format($building)
    {

        if (strpos($building, '@'))
        {
            if (strpos($building, ' @ ') !== -1)
            {
                $b        = explode('@', $building);
                $building = $b[0] . ' @ ' . $b[1];
            }

            $building = str_replace('-', ' ', $building);
        }
        if (strpos($building, '-'))
        {
            $building = str_replace('-', ' ', $building);
        }
        if (strpos($building, ', THE'))
        {
            $b        = explode(', ', $building);
            $building = $b[1] . ' ' . $b[0];
        }

        $buildings = explode(' ', $building);

        foreach ($buildings as $k => $v)
        {
            switch ($v)
            {
                case 'ILIV' : $buildings[$k] = 'iLiv';break;
                case 'AAEV79' : $buildings[$k] = 'Aaev 79'; break;
                case "D'ELIAS" : $buildings[$k] = "D'Elias"; break;
                case "D'FOCUS" : $buildings[$k] = "D'Focus"; break;
                case "D'LEEDON" : $buildings[$k] = "D'Leedon"; break;
                case "D'MANOR" : $buildings[$k] = "D'Manor"; break;
                case '1A' :
                case '3BHC' :
                case '(HUDC)' :
                case '(IMM BUILDING)' :
                case 'UP' :
                case 'II' :
                case 'APS' :
                case 'KM' :
                case 'DLV' :
                case 'RV' :
                case 'RVG' :
                case 'JLB' :
                case 'M.T.F.A' : break;
                default : $buildings[$k] = ucwords(strtolower($v));
                    break;
            }
        }

        return trim(implode(' ', array_filter($buildings)));
        //return strtoupper($building);
    }

    public function action_onemap()
    {
        echo "<pre>";
        $a = 'qo/s2TnSUmfLz+32CvLC4RMVkzEFYjxqyti1KhByvEacEdMWBpCuSSQ+IFRT84QjGPBCuz/cBom8PfSm3GjEsGc8PkdEEOEr';
        $headers = [
            'Host' => 'www.onemap.sg',
            'Origin' => 'http://www.onemap.sg',
            'Referer' => 'http://www.onemap.sg',
        ];

        $r = Request::factory('http://www.onemap.sg/API/services.svc/basicSearch')
                ->headers($headers)
                ->method(HTTP_Request::GET)
                ->query([
                    'token' => $a,
                    'searchVal' => '55 Newton Road',
                    'returnGeom' => 1,
                    'otptFlds' => 'SEARCHVAL,CATEGORY,POSTALCODE',
                ]);

        $this->response->headers('Content-type', 'application/javascript')->body($r->execute());
    }

    public function action_json()
    {
        $m = new Mongo('localhost:27017');
        $c = $m->selectCollection('db', 'singpost');
        $f = json_decode(file_get_contents('results.json'), true);
        echo "<pre>";
        foreach ($f as $k => $data)
        {
            $set    = ['$set' => $data['$set']];
            $cursor = $c->update([
                '_id' => new MongoID($data['_id']['$id'])
                    ], $set, [
                'safe' => true
                    ]);
            var_dump(Arr::merge($cursor, $data, $c->findOne(['_id' => new MongoID($data['_id']['$id'])])));
            //var_dump($c->findOne(['_id' => new MongoID($data['_id']['$id'])]));
        }
        //print_r($f);
    }

    public function action_pages()
    {
        $rows   = [];
        $m      = new Mongo('localhost:27017');
        $c      = $m->selectCollection('db', 'singpost');
        //fwrite(STDOUT, json_encode($c->count(), JSON_PRETTY_PRINT) . "\n");
        ;
        $c->ensureIndex(['estate']);
        $c->ensureIndex(['number']);
        $c->ensureIndex(['street']);
        $c->ensureIndex(['child' => 1], ['sparse' => true]);
        $c->ensureIndex(['building' => 1], ['sparse' => true]);
        //$c->ensureIndex(['geo' => '2d']);
        //$pages  = Mongo_collection::factory('singpost');
        if (($handle = fopen("newapt.csv", "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE)
            {
                //$rows[] = $data;
                $name = $data[1];

                $cursor = $c->find(['child'    => null, 'building' => new MongoRegex("/^".trim($name)."/i")]);
//                $cursor = $c->update([
//                    'child' => null, 'building' => $name
//                ], $this->format_postal($data), [
//                    'multiple' => true,
//                    'safe' => true
//                ]);
                while ($cursor->hasNext())
                {
                    //fwrite(STDOUT, json_encode($cursor->getNext(), JSON_PRETTY_PRINT) . "\n");
                    $rows[] = Arr::merge($cursor->getNext(), $this->format_postal($data));
                }
            }
            fclose($handle);
        }

        file_put_contents('results.json', json_encode($rows, JSON_PRETTY_PRINT));
        echo "<pre>";
        print_r(json_encode($rows, JSON_PRETTY_PRINT) . "\n");
    }

    public function action_newapt()
    {
        $rows   = [];
        $m      = new Mongo('localhost:27017');
        $c      = $m->selectCollection('db', 'singpost');
        $write  = fopen('newapt.csv', 'w');
        if (($handle = fopen("apt.csv", "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE)
            {
                $cursor = $c->findOne(['building' => trim($data[1])]);

                if (!$cursor)
                {
                    fputcsv($write, array_map('trim', $data));
                }
            }
            fclose($handle);
        }

        //file_put_contents('results.json', json_encode($rows, JSON_PRETTY_PRINT));
        //print_r(json_encode($rows, JSON_PRETTY_PRINT) . "\n");
    }

    protected function format_postal($data)
    {
        $ret         = [];
        $name        = str_replace('  ', ' ', $data[1]);
        $name        = str_replace('@', ' @ ', $name);
        $ret['$set'] = [
            'child'  => trim($name),
            'type'   => trim($data[2]),
            'source' => trim($data[3])
        ];
        if (!empty($data[4]))
        {
            $ret['$set']['property_type'] = trim($data[4]);
        }
        if (!empty($data[5]))
        {
            $ret['$set']['comments'] = trim($data[5]);
        }
//
//        unset($data[0]); // number
//        unset($data[1]); // name
//        unset($data[2]); // type
//        unset($data[3]); // source
//        unset($data[4]); // property type
//        unset($data[5]); // comments
//        //$rows[] = $data;
        return $ret;
    }

    public function action_pop()
    {
        echo "<pre>";
        //50d134c7b90fb80c0a0000bb
        //['$pop' => ['comments' => -1]]
        $f = Collection::factory('comment')->fetch(['post._id' => Document::check_id('50d134c7b90fb80c0a0000bb'), 'status'   => 'active', 'type'     => 'comment']
        );
        print_r($f);
    }

    public function action_education()
    {
        $u = Document::factory('user', '50b7620ab90fb8e884000009');
        $u->set('info.education', []);
        $u->save();
    }

    public function action_title()
    {
        $test = true;

        echo $this->the_title();
    }

    public function the_title()
    {
        $test = true;
        return 'Start the sentence and continue with' . ($test ? ' a test.' : ' a failure.');
    }

    public function action_insert()
    {
        $docs = [];
        $one  = new MongoID(1);
        $two  = new MongoID(2);
        // website, no followup
        $doc  = [
            'status'     => 'pending',
            'interested' => 1
        ];
        for ($i = 0; $i < 11; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }


        // website, registration, no payment, chris
        $doc = [
            'status'     => 'pending',
            'interested' => 0,
            'rep'        => [
                'id'   => $two,
                'name' => 'Chris',
                'type' => 'website'
            ]
        ];
        for ($i = 0; $i < 121; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // website, registration, no payment, jason
        $doc = [
            'status'     => 'pending',
            'interested' => 0,
            'rep'        => [
                'id'   => $one,
                'name' => 'Jason',
                'type' => 'website'
            ]
        ];
        for ($i = 0; $i < 151; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // website, registration, active, chris
        $doc = [
            'status'     => 'active',
            'interested' => 0,
            'rep'        => [
                'id'   => $two,
                'name' => 'Chris',
                'type' => 'website'
            ]
        ];
        for ($i = 0; $i < 51; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // website, registration, active, jason
        $doc = [
            'status'     => 'active',
            'interested' => 0,
            'rep'        => [
                'id'   => $one,
                'name' => 'Jason',
                'type' => 'website'
            ]
        ];
        for ($i = 0; $i < 11; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // presentation, registration, no payment, chris
        $doc = [
            'status'     => 'pending',
            'interested' => 0,
            'rep'        => [
                'id'   => $two,
                'name' => 'Chris',
                'type' => 'presentation'
            ]
        ];
        for ($i = 0; $i < 71; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // presentation, registration, no payment, jason
        $doc = [
            'status'     => 'pending',
            'interested' => 0,
            'rep'        => [
                'id'   => $one,
                'name' => 'Jason',
                'type' => 'presentation'
            ]
        ];
        for ($i = 0; $i < 31; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // presentation, registration, active, chris
        $doc = [
            'status'     => 'active',
            'interested' => 0,
            'rep'        => [
                'id'   => $two,
                'name' => 'Chris',
                'type' => 'presentation'
            ]
        ];
        for ($i = 0; $i < 1301; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        // presentation, registration, active, chris
        $doc = [
            'status'     => 'active',
            'interested' => 0,
            'rep'        => [
                'id'   => $one,
                'name' => 'Jason',
                'type' => 'presentation'
            ]
        ];
        for ($i = 0; $i < 1101; $i++)
        {
            $doc['i'] = time() + .1;
            sleep(.1);
            $docs[]   = $doc;
        }

        echo "<pre>";
        //print_r($docs);
//        $db = new MongoClient;
//        $db = new MongoClient;
        Collection::factory('test')->batchInsert($docs, ['safe'            => true, 'continueOnError' => true]);
    }

    public function action_counts()
    {
        echo "<pre>";
        $query = [
            'interested' => 1
        ];
        echo "<h1>Pending</h1>";
        $r     = Collection::factory('test')->find($query)->limit(2);
        print_r($r->count(false));
        while ($r->hasNext())
        {
            print_r($r->getNext()->as_array());
        }

        //print_r($r->getNext()->as_array());
//        $query = [
//            'rep' => ['$exists' => true],
//            'rep.name' => 'Chris'
//        ];
//        echo "<h1>Chris Contacted</h1>";
//        $r = Collection::factory('test')->find($query)->limit(2)->count(false);
//        print_r($r);
    }

    public function action_plural()
    {
        echo "<pre>";
        $types = [
            'post', 'wallpost', 'message', 'comment', 'testimonial', 'attachment',
            'checkin', 'subscriber', 'like', 'event', 'conversation', 'subscription',
            'liker', 'page', 'user', 'interaction', 'action', 'album', 'library'
        ];
        foreach ($types as $type)
        {
            print_r(Inflector::plural($type));
            echo "<br>";
        }
    }

    public function action_password()
    {
        $out = '<h2>Type in your password to hash it</h2>';
        $out .= Form::open($this->request->uri(), ['enctype' => 'multipart/form-data', 'method'  => 'GET']);
        $out .= Form::input('password');
        $out .= Form::submit('submit', 'Get Hash');
        $out .= Form::close();
        $out .= '<br>';

        $query = $this->request->query();
        if ($query)
        {
            $password = Arr::get($query, 'password');
            $out .= static::hash('password');
        }
        $this->response->body($out);
    }

    public function action_privacy()
    {

    }

    public function action_export()
    {
        $mongo = new Mongo();
        $db    = $mongo->selectDB("db");
        $colls = $db->listCollections();
        $out   = '<h2>Click on a link to export the collection</h2>';
        $out .= '<ul>';
        foreach ($colls as $col)
        {
            $out .= '<li>' . HTML::anchor('/testpad/export/?collection=' . substr($col, 3), $col, ['target' => '_blank']) . '</li>';
        }
        $out .= '</ul>';
        $query = $this->request->query();
        if ($query)
        {
            $collection = Arr::get($query, 'collection');
            $name       = APPPATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . "mongo.$collection.json";
            exec('mongoexport --db db --collection ' . $collection . ' --out ' . $name);
            chmod($name, 0776);
            //$this->response->headers('Content-type', 'application/json');
            return $this->response->send_file($name, NULL, ['delete' => TRUE]);
        }
        $this->response->body($out);
    }

    public function action_import()
    {
        echo '<h2>Choose a file to import to</h2>';
        echo Form::open($this->request->uri(), ['enctype' => 'multipart/form-data']);
        echo Form::file('json');
        echo Form::submit('submit', 'Upload');
        echo Form::close();
        $file   = Arr::get($_FILES, 'json');
        $output = '';

        if ($file)
        {
            if (Arr::get($file, 'type') !== 'application/octet-stream')
            {
                echo "Need a file in a json format!";
            }

            $name = Arr::get($file, 'name');
            if (strpos($name, '.') === false)
            {
                echo "Need a file in named 'mongo.COLLECTION_NAME.json!";
            }
            else
            {
                $names      = explode('.', $name);
                $collection = $names[1];
                $tmp        = Arr::get($file, 'tmp_name');
                $filename   = APPPATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $name;
                move_uploaded_file($tmp, $filename);
                chmod($filename, 0776);

                $output = exec('mongoimport --db db --collection ' . $collection . ' --file ' . $filename);

                if (@file_exists($tmp))
                {
                    unlink($tmp);
                }

                if (@file_exists($filename))
                {
                    unlink($filename);
                }
            }

            $this->response->body($output);
        }

        //////$files = '<pre>' . print_r($_FILES, TRUE);
        //$this->response->body($files);
    }

    public function action_index()
    {
        $user = Mongo_Collection::factory('user')->search(['s' => 'test']);



        $this->response->body('<pre>' . print_r($user, true));
    }

    public function action_cea()
    {
        $letter = '#';
        $file   = 'C:\Users\jj\Sites\www\rta.casa.net\company\\' . $letter . '.html';
        $req    = Request::factory('http://www.cea.gov.sg/cea/app/newimplpublicregister/searchPublicRegister.jspa')
                ->headers([
                    'Accept'           => '*/*',
                    'Accept-Encoding'  => 'gzip, deflate',
                    'Accept-Language'  => 'en-gb,en;q=0.7,en-us;q=0.3',
                    'Cookie'           => 'JSESSIONID=Y4BQQZXT7fpyfHbMhtkCP2XbgkSLW4nPZx8tL2HM2QrVnK8csqxp!1026069047; cookie_gov=1764276490.20480.0000; __utma=135192814.1420356319.1352259485.1352259485.1352259485.1; __utmb=135192814.4.10.1352259485; __utmc=135192814; __utmz=135192814.1352259485.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided',
                    'Connection'       => 'keep-alive',
                    'Host'             => 'www.cea.gov.sg',
                    'Referer'          => 'http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'x-insight'        => 'activate',
                ])
                ->post([
                    'eaStart' => $letter,
                    'type'    => 'searchEaAlpha'
                ])
                ->method(HTTP_Request::POST);
        $body   = $req->execute();
        file_put_contents($file, $body);
        $this->response->body($body);
    }

    public function action_verify()
    {
        /*
         * Request 1 : Get the page to request a cookie
         */
        $cookie = APPPATH . 'cache/cookie.txt';
        $ch     = curl_init('http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Host: www.cea.gov.sg',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-gb,en;q=0.7,en-us;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_exec($ch);
        unset($ch);
        sleep(1);

        /*
         * Request 4 : Get the image.
         */
        $ch4 = curl_init('http://www.cea.gov.sg/cea/simpleImg');
        curl_setopt($ch4, CURLOPT_HTTPHEADER, [
            'Host: www.cea.gov.sg',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Accept: image/png,image/*;q=0.8,*/*;q=0.5',
            'Accept-Language: en-gb,en;q=0.7,en-us;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'Referer: http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa',
            'Connection: keep-alive',
            'x-insight: activate'
        ]);
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch4, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch4, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch4, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch4, CURLOPT_COOKIEJAR, $cookie);
        curl_exec($ch4);
        unset($ch4);
        sleep(1);

        /*
         * Request 3 : Get the HTML (content) page using the cookie
         */
        $ch3 = curl_init('http://www.cea.gov.sg/cea/content/index.html');
        curl_setopt($ch3, CURLOPT_HTTPHEADER, [
            'Host: www.cea.gov.sg',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Accept: */*',
            'Accept-Language: en-gb,en;q=0.7,en-us;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'Referer: http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa',
            'X-Requested-With: XMLHttpRequest',
            'x-insight: activate'
        ]);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch3, CURLOPT_COOKIEFILE, $cookie);
        //curl_setopt($ch3, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch3, CURLOPT_COOKIEJAR, $cookie);
        curl_exec($ch3);
        unset($ch3);
        sleep(1);

        /*
         * Request 4 : Get the image.
         */
        $ch4 = curl_init('http://www.cea.gov.sg/cea/simpleImg?' . time() * 1000);
        curl_setopt($ch4, CURLOPT_HTTPHEADER, [
            'Host: www.cea.gov.sg',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Accept: image/png,image/*;q=0.8,*/*;q=0.5',
            'Accept-Language: en-gb,en;q=0.7,en-us;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'Referer: http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa',
            'Connection: keep-alive',
            'x-insight: activate'
        ]);
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch4, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch4, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch4, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch4, CURLOPT_COOKIEJAR, $cookie);
        curl_exec($ch4);
        unset($ch4);
        sleep(5);

        file_put_contents($cookie, implode(PHP_EOL, $this->google) . PHP_EOL, FILE_APPEND);
        /*
         * Request 5 : Get the damn results.
         */
        $ch5    = curl_init('http://www.cea.gov.sg/cea/app/newimplpublicregister/searchPublicRegister.jspa');
        curl_setopt($ch5, CURLOPT_HTTPHEADER, [
            'Host: www.cea.gov.sg',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Accept: */*',
            'Accept-Language: en-gb,en;q=0.7,en-us;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Referer: http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa',
            'Pragma: no-cache',
            'Cache-Control: no-cache',
            'X-Requested-With: XMLHttpRequest',
            'x-insight: activate'
        ]);
        $post   = [
            'type'      => 'searchSls',
            'slsName'   => '',
            'slsEaName' => '',
            'slsRegNo'  => 'R045608C',
            'slsMblNum' => '',
            'answer'    => '',
        ];
        curl_setopt($ch5, CURLOPT_POST, TRUE);
        curl_setopt($ch5, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch5, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch5, CURLOPT_COOKIEFILE, $cookie);
        //curl_setopt($ch5, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch5, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch5, CURLOPT_COOKIEJAR, $cookie);
        $output = curl_exec($ch5);
//
//
        print_r($output);
    }

    public function action_parseco()
    {
        foreach (range('A', 'Z') as $value)
        {
            $sep   = DIRECTORY_SEPARATOR;
            $s     = SimpleHTML::file_get_html($_SERVER['DOCUMENT_ROOT'] . "/company/$value.html");
            $table = $s->find('table', 0);
            //die(Debug::vars($table));
            $trs   = $table->find('tr');
            for ($t = 1; $t < count($trs); $t++)
            {
                $tds = $trs[$t]->children();
                //echo count($tds);t
                for ($i = 0; $i < count($tds); $i++)
                {
                    if ($tds[$i]->find('a'))
                        $td = $tds[$i]->children(0);
                    else
                        $td = $tds[$i];

                    switch ($i)
                    {
                        case 0: $key = 'license';
                            break;
                        case 1: $key = 'name';
                            break;
                        case 2: $key = 'expiry';
                            break;
                    }
                    if ($key === 'expiry')
                    {
                        $expiry = explode('to                      <br />', trim($td->innertext()));
                        foreach ($expiry as $k => $v)
                        {
                            $ts[$t]['expire'][$k] = strtotime(trim($v));
                        }
                    }
                    else
                    {
                        $ts[$t][$key]      = trim($td->innertext());
                    }
                }
                $ts[$t]['type']    = 'company';
                $ts[$t]['subtype'] = 'agency';
                echo Debug::vars(Document::factory('page')->create($ts[$t]));
            }
        }
        die();
    }

    //put your code here

    public $google = [
        'www.cea.gov.sg	FALSE	/	FALSE	0	__utma          135192814.1621133911.1352435028.1352435028.1352449316.2',
        'www.cea.gov.sg	FALSE	/	FALSE	0	__utmc          135192814',
        'www.cea.gov.sg	FALSE	/	FALSE	0	__utmz          135192814.1352435028.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)',
        'www.cea.gov.sg	FALSE	/	FALSE	0	__utmb          135192814.1.10.1352449316',
    ];

}
