<?php defined('SYSPATH') or die('No direct script access.');
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

    public function action_privacy()
    {
        $user = Mongo_Document::factory('user');
        $user->get('5099cfc6b90fb8380a000002');
        $privacy = Privacy::factory($user);
        //$privacy->test('5099cfc6b90fb8380a000002');
        echo "<pre>";
        print_r('Privacy Doc is User: ' . $privacy->doc_is('user') . '<br>');
        print_r('Privacy is: ' . $privacy->is . '<br>');
        print_r('Privacy contacts: ' . $privacy->contacts . '<br>');
        echo $privacy;
    }

    public function action_index()
    {
        $user = Mongo_Collection::factory('user')->search(['s' =>'test']);



        $this->response->body('<pre>'.print_r($user, true));
    }

    public function action_cea()
    {
        $letter = '#';
        $file = 'C:\Users\jj\Sites\www\rta.casa.net\company\\' . $letter . '.html';
        $req = Request::factory('http://www.cea.gov.sg/cea/app/newimplpublicregister/searchPublicRegister.jspa')
                ->headers([
                    'Accept' => '*/*',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Accept-Language' => 'en-gb,en;q=0.7,en-us;q=0.3',
                    'Cookie' => 'JSESSIONID=Y4BQQZXT7fpyfHbMhtkCP2XbgkSLW4nPZx8tL2HM2QrVnK8csqxp!1026069047; cookie_gov=1764276490.20480.0000; __utma=135192814.1420356319.1352259485.1352259485.1352259485.1; __utmb=135192814.4.10.1352259485; __utmc=135192814; __utmz=135192814.1352259485.1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided',
                    'Connection' => 'keep-alive',
                    'Host' => 'www.cea.gov.sg',
                    'Referer' => 'http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'x-insight' => 'activate',
                ])
                ->post([
                    'eaStart' => $letter,
                    'type' => 'searchEaAlpha'
                ])
                ->method(HTTP_Request::POST);
        $body = $req->execute();
        file_put_contents($file, $body);
        $this->response->body($body);
    }

    public function action_verify()
    {
        /*
         * Request 1 : Get the page to request a cookie
         */
        $cookie = APPPATH . 'cache/cookie.txt';
        $ch     =  curl_init('http://www.cea.gov.sg/cea/app/newimplpublicregister/publicregister.jspa');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Host: www.cea.gov.sg',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-gb,en;q=0.7,en-us;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
        ]);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_exec ($ch);
        unset($ch);
        sleep(1);

        /*
         * Request 4 : Get the image.
         */
        $ch4     =  curl_init('http://www.cea.gov.sg/cea/simpleImg');
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
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt($ch4, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch4, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch4, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch4, CURLOPT_COOKIEJAR, $cookie);
        curl_exec ($ch4);
        unset($ch4);
        sleep(1);

        /*
         * Request 3 : Get the HTML (content) page using the cookie
         */
        $ch3     =  curl_init('http://www.cea.gov.sg/cea/content/index.html');
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
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch3, CURLOPT_COOKIEFILE, $cookie);
        //curl_setopt($ch3, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch3, CURLOPT_COOKIEJAR, $cookie);
        curl_exec ($ch3);
        unset($ch3);
        sleep(1);

        /*
         * Request 4 : Get the image.
         */
        $ch4     =  curl_init('http://www.cea.gov.sg/cea/simpleImg?' . time() * 1000);
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
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt($ch4, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch4, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch4, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch4, CURLOPT_COOKIEJAR, $cookie);
        curl_exec ($ch4);
        unset($ch4);
        sleep(5);

        file_put_contents($cookie, implode(PHP_EOL, $this->google) . PHP_EOL, FILE_APPEND);
        /*
         * Request 5 : Get the damn results.
         */
        $ch5     =  curl_init('http://www.cea.gov.sg/cea/app/newimplpublicregister/searchPublicRegister.jspa');
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
        $post = [
            'type' => 'searchSls',
            'slsName' => '',
            'slsEaName' => '',
            'slsRegNo' => 'R045608C',
            'slsMblNum' => '',
            'answer' => '',
        ];
        curl_setopt($ch5, CURLOPT_POST, TRUE);
        curl_setopt($ch5, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch5, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch5, CURLOPT_COOKIEFILE, $cookie);
        //curl_setopt($ch5, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch5, CURLOPT_FOLLOWLOCATION, true );
        //curl_setopt($ch5, CURLOPT_COOKIEJAR, $cookie);
        $output = curl_exec ($ch5);
//
//
        print_r($output);

    }

    public function action_parseco()
    {
//        foreach (range('Z', 'A') as $value)
//        {
            $sep = DIRECTORY_SEPARATOR;
            $s     = SimpleHTML::file_get_html($_SERVER['DOCUMENT_ROOT'] . "/company/#.html");
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
                        case 0: $key          = 'license';
                            break;
                        case 1: $key          = 'name';
                            break;
                        case 2: $key          = 'expiry';
                            break;
                    }
                    if($key === 'expiry')
                    {
                        $expiry = explode('to                      <br />', trim($td->innertext()));
                        foreach($expiry as $k => $v)
                        {
                            $ts[$t]['expire'][$k] = strtotime(trim($v));
                        }
                    }
                    else
                    {
                        $ts[$t][$key] = trim($td->innertext());
                    }
                }
                $ts[$t]['type'] = 'agency';
                echo Debug::vars(Mongo_Document::factory('page')->create($ts[$t]));
            }
//        }
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
