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
}
