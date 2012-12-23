<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Description of testpad
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Testpad extends Controller {

    use Trait_Oauth2_Utils;

    public function action_pop()
    {
        echo "<pre>";
        //50d134c7b90fb80c0a0000bb
        //['$pop' => ['comments' => -1]]
        $f = Collection::factory('comment')->fetch( ['post._id' => Document::check_id('50d134c7b90fb80c0a0000bb'), 'status' => 'active', 'type' => 'comment']
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
