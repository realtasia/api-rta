<?php

defined('SYSPATH') or die('No direct script access.');
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2000M');

/**
 *  Testing Controller for some tests
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Test
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
 */
class Controller_Test extends Kohana_Controller {

    // use Trait_Oauth2_Utils;
    public $template = 'template';

    public function action_do()
    {
        $client_credentals = [
            'client_id'    => '123456789',
            'redirect_uri' => 'http://jjpc.net',
            'grant_type'   => 'client_credentials',
        ];

        $token = [
            'request_type' => 'registration',
            'user_type'    => 'admin',
            'access_token' => '[REDACTED]',
            'state'        => substr(Security::token(), 0, 5),
        ];

        $registration = [
            'status'     => 'active',
            'first_name' => '[REDACTED]',
            'last_name'  => '[REDACTED]',
            'name'       => '[REDACTED]',
            'roles'      => ['admin'],
            'username'   => '[REDACTED]',
            'password'   => '[REDACTED]',
            'uri'        => '/[REDACTED]',
            'email'      => '[REDACTED]',
            'info'       => ['gender'    => 'male', 'telephone' => ['mobile' => '[REDACTED]']],
        ];

        $return = [];

//        for($i=1;$i<=3;$i++)
//        {
        $values   = Arr::merge($token, $this->users(1), $registration);
//            echo "<pre>";
//            print_r($values);
//            echo "</pre><hr>";
        $request  = Request::factory(URL::site('/user', true))
                ->method(HTTP_Request::POST)
                ->post($values);
        $return[] = "<pre>" . $request->execute() . "</pre>";
//        }
//die;
        return $this->response->body(implode('<hr>', $return));
    }

    public function action_activate()
    {
        $token = [
            'access_token' => '[REDACTED]',
            'state'        => substr(Security::token(), 0, 5),
        ];

        $registration = [
            'request_type' => 'activate',
        ];

        $users = Mongo_Collection::factory('user');

        $cursor = $users->find();
        echo "<pre>";
        while ($cursor->hasNext())
        {
            $row    = $cursor->getNext();
            $values = Arr::merge($token, $registration);


            $request  = Request::factory(URL::site('/user/update/' . $row->stringify($row->as_array())['_id'], true))
                    ->method(HTTP_Request::POST)
                    ->post($values);
            $return[] = "<pre>" . $request->execute() . "</pre>";
        }
        return $this->response->body(implode('<hr>', $return));
    }

    public function action_cea()
    {
        $s     = SimpleHTML::file_get_html('agencies.html');
        $table = $s->find('table', 0);
        //die(Debug::vars($table));
        $trs   = $table->find('tr');
        for ($t = 0; $t < count($trs); $t++)
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
                    case 1: $key          = 'title';
                        break;
                    case 2: $key          = 'expiry';
                        break;
                }
                $ts[$t][$key] = trim($td->innertext());
            }
            echo Debug::vars(Model::factory('page')->add($ts[$t], 'company'));
        }
        die();
        unset($ts[0]);

        die(Debug::vars(Model::factory('page')->add($ts, 'company')));
    }

    public static function cleanup($arr)
    {
        $arr = array_values($arr);
        foreach ($arr as $k => $v)
        {
            if (is_array($v))
            {
                $tag = \Arr::get($v, 'tag');
                if ($tag && $tag === 'br')
                {
                    unset($arr[$k]);
                }
                else
                {
                    $childNodes = \Arr::get($v, 'childNodes');
                    if ($childNodes)
                    {
                        if (empty($childNodes))
                        {
                            unset($v['childNodes']);
                            $arr[$k] = $v;
                        }
                        else
                        {
                            foreach ($childNodes as $ck => $cv)
                            {
                                if (!\Arr::get($cv, 'tag'))
                                {
                                    unset($v['childNodes'][$ck]);
                                }
                            }
                            $arr[$k] = $v;
                        }
                    }
                }
            }
        }

        //return $arr;
        return array_filter(array_values($arr));
    }

    public function extractText($parser)
    {
        $return = array();
        $p      = static::cleanup($parser->toArray());
        foreach ($p as $k => $v)
        {
            if (is_array($v))
            {
                $tag       = \Arr::get($v, 'tag');
                $innerHTML = trim(\Arr::get($v, 'innerHTML'));
                switch ($tag)
                {
                    case 'strong':
                        $return['name'] = ucwords(strtolower($innerHTML));
                        break;
                    case 'a':
                        if (Valid::email($innerHTML))
                        {
                            $return['email'] = $innerHTML;
                        }
                        if (strpos(trim($innerHTML), "<img src='http://s3images.sg.ippstatic.com/agencies") === 0)
                        {
                            $agencyNode = @current(\Arr::get($v, 'childNodes'));
                            if ($agencyNode)
                            {
                                $props = @explode('=', \Arr::get($agencyNode, 'stratr'));
                                foreach ($props as $pk => $pv)
                                {
                                    if (strpos($pv, "'http") === 0)
                                    {
                                        $agencyUrl                 = substr($pv, 1, strlen($pv) - 6);
                                        $return['agency']['image'] = $agencyUrl;
                                    }
                                }
                            }
                        }
                        break;
                    case 'span':
                        $numNode                   = @current(static::cleanup(\Arr::get($v, 'childNodes')));
                        if ($numNode && strpos(trim(\Arr::get($numNode, 'innerHTML')), '+6') === 0)
                        {
                            $mobile           = explode(' ', \Arr::get($numNode, 'stratr'));
                            $mobile           = array_pop($mobile);
                            $mobile           = substr($mobile, 1, 11);
                            $return['mobile'] = trim($mobile);
                        }
                        break;
                    case 'td':
                        if (strpos($innerHTML, "<ahref='/realestateagent/") === 0)
                        {
                            $children = static::cleanup(\Arr::get($v, 'childNodes'));
                            foreach ($children as $ck => $child)
                            {
                                $childtag       = \Arr::get($child, 'tag');
                                $childprop      = \Arr::get($child, 'stratr');
                                $childInnerHTML = \Arr::get($child, 'innerHTML');
                                if ($childprop && strpos($childprop, "href='/realestateagent/") === 0)
                                {
                                    $parts          = explode(' ', $childprop);
                                    $part           = str_replace('href=', '', $parts[0]);
                                    $part           = str_replace('\'', '', $part);
                                    $return['link'] = 'http://www.iproperty.com.sg' . $part;
                                }
                                switch ($childtag)
                                {
                                    case 'span':
                                        $numNode = @current(static::cleanup(\Arr::get($child, 'childNodes')));
                                        if ($numNode && strpos(trim(\Arr::get($numNode, 'innerHTML')), '+6') === 0)
                                        {
                                            $mobile           = explode(' ', \Arr::get($numNode, 'stratr'));
                                            $mobile           = array_pop($mobile);
                                            $mobile           = substr($mobile, 1, 11);
                                            $return['mobile'] = trim($mobile);
                                        }
                                        break;
                                    case 'a':
                                        if (Valid::email($childInnerHTML))
                                        {
                                            $return['email'] = $childInnerHTML;
                                        }
                                        if (strpos(trim($childInnerHTML), "<img src='http://s3images.sg.ippstatic.com/agencies") === 0)
                                        {
                                            $agencyNode = @current(\Arr::get($child, 'childNodes'));
                                            if ($agencyNode)
                                            {
                                                $props = @explode('=', \Arr::get($agencyNode, 'stratr'));
                                                foreach ($props as $pk => $pv)
                                                {
                                                    if (strpos($pv, "'http") === 0)
                                                    {
                                                        $agencyUrl                 = substr($pv, 1, strlen($pv) - 6);
                                                        $return['agency']['image'] = $agencyUrl;
                                                    }
                                                }
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                }
            }
        }

        //return \Arr::merge($return, $p);
        return $return;
    }

    public function action_findagent()
    {
        $num = $this->request->param('id');
        echo "<pre>";
        print_r(Collection::factory('agent')->findOne(['mobile' => new MongoRegex("/$num/")]));
    }

    public function action_removest()
    {
      $r = Collection::factory('agent')->find()->fields(['mobile' => true]);
//      $nums = array();
          echo "<ol>";
      while($r->hasNext())
      {
          $row = $r->getNext();

          if(SMS::valid($row->mobile))
          {
              $row->mobile = SMS::valid($row->mobile);
              echo "<li>".SMS::valid($row->mobile)."</li>";
          }

          //echo SMS::valid($row->mobile) . "<br>";
//          $e = @explode(' ', $row->mobile);
//          if($e && !SMS::valid(current($e)))
//          {
//              print_r($e);
//              echo "<br>";
//              $row->mobile = trim(implode('', $e));
//              print_r(['new' => $row->mobile]);
//              echo "<br>";
//              $row->save();
//          }
          $row->mobile = trim($row->mobile);
          $row->save();
      }
          echo "</ol>";
          //print_r($nums);
//      $r->reset();
//      echo "<pre>";
//      while($r->hasNext())
//      {
//          print_r($r->getNext()->as_array());
//      }
        Collection::factory('agent')->remove(['sourcedStProp' => true],['multiple' => true]);
    }

    public function action_stfiles()
    {
        $this->response->headers('Content-type', 'text/plain');
        $dir       = "/home/jason/www/rta.casa.net/stagents/";
        $handle    = opendir($dir);
        $master    = array();
        $personcount = 0;
        $filecount = 0;
        while ($file      = readdir($handle))
        {
            if ($file !== '.' AND $file !== '..')
            {
                $a = SimpleHTML::file_get_html($dir . $file);
                // the agent link and image url
                if ($a)
                {
                    $div   = $a->find('div[itemtype=http://schema.org/Person]');
                    //print_r(gettype($div[0]));
                    foreach($div as $item)
                    {
                        $name = $item->find('.ca-sr-item-info span[itemprop=name]', 0);
                        if(is_object($name))
                        {
                            $master[$personcount]['name'] = trim(ucwords(strtolower($name->innertext())));
                        }

                        $designation = $item->find('.ca-sr-item-info p[itemprop=jobTitle]', 0);
                        if(is_object($designation))
                        {
                            $designation = $designation->innertext();
                            if(strpos($designation, '-' === 0))
                            {
                                $designation = substr($designation, 1, strlen($designation));
                            }
                            if($designation !== '-')
                            {
                                $master[$personcount]['designation'] = trim(ucwords(strtolower($designation)));
                            }
                        }

                        $mobile = $item->find('.ca-sr-item-info p[itemprop=telephone]', 0);
                        if(is_object($mobile))
                        {
                            //$master[$personcount]['mobile'] = trim(ucwords(strtolower($mobile->innertext())));
                            $master[$personcount]['mobile'] = trim(implode('', explode(' ', $mobile->innertext())));
                        }

                        $agency = $item->find('.ca-sr-item-info p[itemprop=worksFor]', 0);
                        if(is_object($agency))
                        {
                            //$master[$personcount]['mobile'] = trim(ucwords(strtolower($mobile->innertext())));
                            $master[$personcount]['agency']['name'] = trim(strtoupper($agency->innertext()));
                        }

                        $master[$personcount]['sourcedStProp'] = true;

                        try
                        {
                            $u = Collection::factory('agent')->find(['mobile' => \Arr::get($master[$personcount], 'mobile')]);
                            while($u->hasNext())
                            {
                                print_r($u->getNext()->as_array());
                            }
                            //$u->ensureIndex(['mobile' => 1]);
                            $u->update([
                                'mobile' => \Arr::get($master[$personcount], 'mobile')
                                    ], $master[$personcount], [
                                'upsert' => true
                            ]);
                            $personcount ++;
//                            echo "\n";
//                            print_r($u);

                        }
                        catch (MongoCursorException $e)
                        {
                            exit;
                            Debug::error(
                                    "Error Message- :message \n"
                                    . "Error Code- :code", array(
                                ':interaction' => $i::action(), ':message'     => $e->getMessage(),
                                ':code'        => $e->getCode()
                            ));
                        }

                    }
                    //print_r($master);
                }
            }
            $filecount++;
        }
//        $cursor   = Collection::factory('agent')->find();
//        while ($cursor->hasNext())
//        {
//            var_dump(iterator_to_array($cursor->cursor()));
//        }
        //file_put_contents('master.php', serialize($master));
        //die();
    }

    public function action_files()
    {
        $this->response->headers('Content-type', 'text/plain');
        $dir       = "/home/jason/www/rta.casa.net/agents/";
        $handle    = opendir($dir);
        $master    = array();
        $filecount = 0;
        while ($file      = readdir($handle))
        {
            if ($file !== '.' AND $file !== '..')
            {
                $a = SimpleHTML::file_get_html($dir . $file);
                // the agent link and image url
                if ($a)
                {
                    $td1   = $a->find('.agentlistingimagespace');
                    // all other details.
                    $td2   = $a->find('.morelistingtext');
                    $count = 0;
                    foreach ($td2 as $child)
                    {

                        $info   = Inflector::br2nl($child->outertext());
                        $parser = new htmlParser($child->outertext());
                        $return = $this->extractText($parser);
                        $info   = preg_replace('/\t/', '', $info);
                        $info   = trim(strip_tags($info));
                        $info   = trim(nl2br($info));
                        if (strlen($info) > 1)
                        {
                            $arr = explode('<br />', $info);
                            //print_r($arr);
                            foreach ($arr as $k => $v)
                            {
                                $arr[$k] = trim($v);
                                $arr[$k] = preg_replace('/\t/', '', $v);
                                $arr[$k] = preg_replace('/\n/', '', $v);
                                $arr[$k] = preg_replace('/\n/', '', $v);
                                $arr[$k] = trim($v);
                            }
                            foreach ($arr as $k => $v)
                            {
                                switch (true)
                                {
                                    case strpos(trim($v), 'CEA Registration Number') === 0;
                                        $l                           = explode(':', $v);
                                        $return['license']           = trim($l[1]);
                                        break;
                                    case strpos(trim($v), 'Agency Licence Number') === 0;
                                        $l                           = explode(':', $v);
                                        $return['agency']['license'] = trim($l[1]);
                                        break;
                                }
                            }
                            if (isset($td1[$count]))
                            {
                                $return['image']  = $td1[$count]->children(0)->children(0)->src;
                            }
                            $return['source'] = 'iproperty';
                            $co               = Collection::factory('page');
                            //$co->ensureIndex(['license' => 1]);
                            $a                = $co->findOne(['license' => $return['agency']['license']], ['name' => 1]);
                            if ($a)
                            {
                                $return['agency']['_id']  = \Arr::get($a, '_id');
                                $return['agency']['name'] = \Arr::get($a, 'name');
                            }
                            ksort($return);
                            try
                            {
                                $u = Collection::factory('agent');
                                //$u->ensureIndex(['email' => 1]);
                                $u->update([
                                    'email' => \Arr::get($return, 'email')
                                        ], $return, [
                                    'upsert' => true
                                ]);
                                echo "\n";
                                print_r($u);
                            }
                            catch (MongoCursorException $e)
                            {
                                Debug::error(
                                        "Error Message- :message \n"
                                        . "Error Code- :code", array(
                                    ':interaction' => $i::action(), ':message'     => $e->getMessage(),
                                    ':code'        => $e->getCode()
                                ));
                            }
                        }
                        $count++;
                    }
                }
            }
            $filecount++;
        }
//        $cursor   = Collection::factory('agent')->find();
//        while ($cursor->hasNext())
//        {
//            var_dump(iterator_to_array($cursor->cursor()));
//        }
        //file_put_contents('master.php', serialize($master));
        //die();
    }


    public function action_view_agents()
    {
        $master = unserialize(file_get_contents('master.php'));

        echo Debug::vars($master);
        die();
    }

    public function action_stagents()
    {
        $letters = range('K', 'T');
        //$letters = ['A'];
        foreach ($letters as $letter)
        {
            $a     = SimpleHTML::str_get_html(gzdecode((string) $this->stprop_url($letter)));
            file_put_contents("stagents/$letter-1.html", $a);
            $found = $a->find('.sr-found h2', 0)->innertext();
            preg_match_all("/\d+/", $found, $find);
            if (!empty($find))
            {
                $total = (int) current(\Arr::flatten($find));
            }
            $pages = (int) ceil($total / 50);
            //print_r($a);
            if ($pages)
            {
                for ($i = 2; $i <= $pages; $i++)
                {
                    $a = SimpleHTML::str_get_html(gzdecode($this->stprop_url($letter, $i)));
                    file_put_contents("stagents/$letter-$i.html", $a);
                }
                sleep(1);
            }
        }
            sleep(1);
    }

    public function action_agents()
    {
//        $letters = range('A', 'Z');
//        foreach ($letters as $letter)
//        {
            $a     = SimpleHTML::str_get_html($this->agent_url('featured'));
            file_put_contents("agents/featured-1.html", $a);
            $found = $a->find('.totalfound', 0)->innertext();
            preg_match_all("/\d+/", $found, $find);
            if (count($find[0]) === 2)
            {
                $pages = (int) $find[0][1];
            }

            if ($pages)
            {
                for ($i = 2; $i <= $pages; $i++)
                {
                    //$a = SimpleHTML::str_get_html(gzdecode($this->agent_url($letter)));
                    $a = SimpleHTML::str_get_html($this->agent_url('featured', $i));
                    file_put_contents("agents/$letter-$i.html", $a);
					sleep(1);
                }
                sleep(1);
            }
//        }
 //       sleep(1);
    }

    public function agent_url($letter, $page = 1)
    {
        $request = Request::factory('http://www.iproperty.com.sg/realestate/findanagent.aspx')
                ->query(array(
                    'ty' => 'al',
                    'ak' => null,
                    'rk' => '',
                    'p'  => $page,
                    's'  => 100,
                ))
                ->headers(array(
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
//            'Accept-Encoding' => 'gzip, deflate',
//            'X-Requested-With' => 'XMLHttpRequest',
            'Accept-Language' => 'en-us,en;q=0.5',
            'Connection'      => 'keep-alive',
            'Host'            => 'www.iproperty.com.sg',
            'User-Agent'      => 'Mozilla/5.0 Gecko/20100101 Firefox/8.0',
                ));

        return $request->execute();
    }

    public function stprop_url($letter, $page = 1)
    {
        //http://schema.org/Person
        $letter  = strtolower($letter);
        $url     = "http://www.stproperty.sg/property-agent/search-by-firstname/first-name-$letter/page$page/size-50/sort-firstname-asc";
        $request = Request::factory($url)->headers([
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept-Language' => 'en-us,en;q=0.5',
            'Connection'      => 'keep-alive',
            'Host'            => 'www.stproperty.sg',
            'Referer'         => 'http://www.stproperty.sg/property-agent/first-name-a/sort-firstname-asc',
            'User-Agent'      => 'Mozilla/5.0 Gecko/20100101 Firefox/8.0',
                ]);

        return $request->execute();
    }

    public function action_testQueue()
    {

        //echo Debug::vars(Model::factory('job')->test());
        echo Debug::vars(Add::create(''));
    }

    public function action_test_email()
    {
        $content_header        = View::factory('emails/header/h4')->set('header', 'Hi Jason!');
        $content_header_plain  = Inflector::br2nl($content_header);
        $content_message       = '3 three User and 2 two User also commented on your post in Parc Vista.';
        $comment_message       = View::factory('emails/content/comment')
                ->set('name', '[REDACTED]')
                ->set('comment', ' i think this is gonna be great!!');
        $comment_message_plain = Inflector::br2nl($comment_message);

        $content = View::factory('emails/content/standard')
                ->set('content_header', $content_header)
                ->set('content_message', $content_message . $comment_message)
                ->render();
//        $content .= View::factory('emails/content/leftimagecontent')
//              ->set('content_header', View::factory('emails/header/h4')->set('header', '{content_header} for left image layout'))
//              ->set('content_message', '{content_message} This can be text, table, or anything you want<br />{content_image_url}')
//              ->set('content_image_url', '{content_image_url}')
//              ->render();
//        $content .= View::factory('emails/content/rightimagecontent')
//              ->set('content_header', View::factory('emails/header/h4')->set('header', '{content_header} for right image layout'))
//              ->set('content_message', '{content_message} This can be text, table, or anything you want<br />{content_image_url}')
//              ->set('content_image_url', '{content_image_url}')
//              ->render();
        $view    = View::factory('emails/email')
                ->set('title', '3 three User and 2 two User also commented on your post in Parc Vista')
                ->set('archive_url', '{archive_url}')
                ->set('notification_url', '{notification_url}')
                ->set('teaser', '3 three User and 2 two User also commented on your post in Parc Vista')
                ->set('content_view', $content);

        $plainview = View::factory('emails/content/plaintext')
                ->set('notification_url', '{notification_url}')
                ->set('content_header', $content_header_plain)
                ->set('content_message', $content_message . $comment_message_plain);

        Email::factory('Sample template 2', $plainview)
                ->message($view, 'text/html')
                //->to('[REDACTED]', '[REDACTED]')
                ->to('[REDACTED]', '[REDACTED]')
                //->to('[REDACTED]', '[REDACTED]')
                //->to('[REDACTED]', '[REDACTED]')
                ->from('[REDACTED]', '[REDACTED]')
                ->send();

        //die($view);
        $this->auto_render = FALSE;
        $this->response->body($view . print_r('<pre>' . $plainview . '</pre>', TRUE));
    }

    public function action_designer()
    {
//        if(!$this->request->is_ajax())
//        {
        $post              = $_POST ? print_r($_POST, TRUE) : '';
        $this->breadcrumbs = '';
        $this->menu        = '';
        $this->title       = '';
        //$this->template->content = View::factory('forms/post_create')->bind('post', $post);
//        }
//        else
//        {
//            if($_POST)
//                $this->response->headers('Content-type', 'application/json')->body(json_encode(array('posts' => $_POST)));
//        }
    }

    public function action_betaUsers()
    {
        $m     = Model::factory('user');
        $users = $m->collection->find();
        foreach ($users as $u)
        {
            if (isset($u['display_name']))
                $name = $u['display_name'];
            else
                $name = $u['first_name'] . ' ' . $u['last_name'];

            $emails = array_values(array_filter($u['email']));

            echo Debug::vars($m->new_beta_user($emails[0], $name, TRUE));
        }
    }

    public function action_session($provider)
    {
        $provider = Provider::forge($provider, array(
                    'id'     => '291017440941918',
                    'secret' => '[REDACTED]',
                ));

        if (!isset($_GET['code']))
        {
            // By sending no options it'll come back here
            $this->request->redirect($provider->authorize_url());
            //$this->request->redirect($provider->authorize_url());
        }
        else
        {
            // Howzit?
            try
            {
                $token = $provider->access($this->request->query('code'));

                // Save access_token for now
                Session::instance()->set('access_token', $token->access_token);

                // Use that to grab basic data about the user
                $user = $provider->get_user_info($token);

                // Here you should use this information to A) look for a user B) help a new user sign up with existing data.
                // If you store it all in a cookie and redirect to a registration page this is crazy-simple.
                echo "<pre>";
                var_dump($user);
            }
            catch (Exception $e)
            {
                Debug::error('That didnt work: ' . $e);
            }
        }
    }

    public function action_link()
    {
        $this->template->sidebar     = '';
        $this->template->breadcrumbs = '';
        $this->template->title       = '';
        $this->template->menu        = '';
        $script                      = html::anchor('/test/session/facebook', 'click me', array('id' => 'clickMe')) .
                <<<EOD
   <script type="text/javascript">
       jQuery(document).ready(function(){
        $('#clickMe').colorbox({
                        width:"80%", height:"80%"
                    });
    });
   </script>
EOD;
        $this->template->content     = $script;
    }

    public static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function action_index()
    {
        $post = array(
            'content'  => 'this is a post. a test post in fact',
            'comments' => array(
                array('content' => 'this is comment one', 'created' => time()),
                array('content' => 'this is comment 2', 'created' => time() + 1),
                array('content' => 'this is comment 3', 'created' => time() + 2),
                array('content' => 'this is comment 4', 'created' => time() + 3),
                array('content' => 'this is comment 5', 'created' => time() + 4),
                array('content' => 'this is comment 6', 'created' => time() + 5),
            )
        );

        $m = Model::factory('post')
                ->collection
                ->find(array(), array('comments' => array('$slice' => array(0, 2)))
        );

        echo Debug::vars(iterator_to_array($m));
    }

    public function users($i)
    {
        $gender    = ['male', 'female'];
        $rand_gend = $gender[array_rand(range(0, 1), 1)];

        if ($rand_gend === 'female')
            $mid = '';
        else
            $mid = '';

        $numword = numword::factory($i);
        $name    = [
            'first_name'  => ucfirst($numword),
            'middle_name' => $mid,
            'last_name'   => 'Agent',
        ];
        return [
            'first_name'  => $name['first_name'],
            'middle_name' => $name['middle_name'],
            'last_name'   => $name['last_name'],
            'name'        => $name['first_name'] . ' ' . $name['last_name'],
            'username'    => $numword . '.user',
            'password'    => $numword,
            'email'       => '[REDACTED]',
            'designation' => 'Senior Sales Executive',
            'roles'       => ['agent'],
            'avatar'      => [90 => '/agents/images/' . $i . '.jpg',],
            'uri'         => '/' . $numword . '.user',
            'active'      => time(),
            'info'        => [
                'telephone' => ['mobile' => '[REDACTED]'],
                'birthday'  => mt_rand(378691200, 694224000),
                'gender'    => $gender[array_rand(range(0, 1), 1)],
            ]
        ];
    }

    public function action_request()
    {
//$item = queue::get_next();
//$v = (isset($item['value'])) ? $item['value'] : FALSE;
//if($v)
//print_r(Model::factory('queue')->collection->update(
//array('_id'  => Model::check_id($v['_id'])),
//array('$set' => array('in_progress' => FALSE, 'status' => 0)),
//array('safe' => TRUE)
//));
//echo Debug::vars(Model::check_id($v['_id']));
//die(Debug::vars($v));

        echo Debug::vars($m = Model::factory('queue')->collection->find(array('in_progress' => TRUE)));
        echo Debug::vars($m->hasNext());
        if ($m->hasNext())
            echo Debug::vars($m->getNext());
    }

    public function action_resetdb()
    {
        $this->auto_render = false;
        $this->reset_pages();
        $this->reset_users();
        $this->clear_logs_and_jobs();
    }

    public function action_jason()
    {
        $this->auto_render = false;
        $update            = array(
            '$addToSet' => array('roles' => 'admin')
        );
        Model::factory('user')->collection->update(
                array('email' => '[REDACTED]'), $update);
    }

    public function reset_pages()
    {
        $coll   = Model::factory('page')->collection;
        $cursor = $coll->find();
        $counts = array_merge(Model::$counts, array(
            'events' => 0,
                ));
        while ($cursor->hasNext())
        {
            $doc    = $cursor->getNext();
            $modify = array(
                '$set' => array(
                    'counts'      => $counts,
                    'subscribers' => array(),
                    'likers'      => array(),
                )
            );

            echo Debug::vars($coll->update(array('_id' => $doc['_id']), $modify, array('safe' => TRUE)));
        }
        $this->auto_render = false;
    }

    public function action_resetusers()
    {
        $cursor   = Collection::factory('user')->find();
        while ($cursor->hasNext())
        {
            $doc    = $cursor->getNext();
			$doc->counts = static::reset_counts($doc->counts);
			$doc->avatar = null;
			$doc->likers = array();
			$doc->subscribers = array();
			$doc->save();
			echo Debug::vars($doc->as_array());
        }
    }
    public function action_resetpages()
    {
        $cursor   = Collection::factory('page')->find();
        while ($cursor->hasNext())
        {
            $doc    = $cursor->getNext();
			$doc->counts = static::reset_counts($doc->counts);
			$doc->likers = array();
			$doc->subscribers = array();
			$doc->save();
			echo Debug::vars($doc->as_array());
        }
    }
	protected static function reset_counts($array)
	{
		foreach($array as $k => $v)
		{
			if(is_array($v))
			{
				$array[$k] = static::reset_counts($v);
			}
			if(is_numeric($v))
			{
				$array[$k] = 0;
			}
		}
		
		return $array;
	}

    public function clear_logs_and_jobs()
    {
        $coll = Model::factory('log')->collection->remove(array());
        $coll = Model::factory('job')->collection->remove(array());
        $coll = Model::factory('mail')->collection->remove(array());
        $coll = Model::factory('post')->collection->remove(array());
        $coll = Model::factory('attachment')->collection->remove(array());
        $coll = Model::factory('comment')->collection->remove(array());
    }

    public function action_viewuser()
    {
//die(Debug::vars(Model::factory('user')->get('[REDACTED]')));
        if (Auth::instance()->logged_in())
            echo Debug::vars(Auth::instance()->get_user());
    }

    public function action_job()
    {
        require_once MODPATH . 'kohana-aws/vendor/aws-sdk-for-php/sdk.class.php';
        ///echo Debug::vars(get_class_methods(AmazonSDK));
//        while(1){
        $i = 0;
        while ($i <= 1)
        {
//                $pid = pcntl_fork();
//                if(!$pid)
//                {
            $Job   = Document::factory('job');
            $j     = $Job->get_next();
            $pro   = new Job_Processor_Immediate($j);
            if ($j['is_single'])
                $e     = $pro->add('single');
            else
                $e     = $pro->add();
            $token = Profiler::start('email', 'Send Email');
            $res   = $queue->send();
            Profiler::stop($token);
            echo Debug::Vars($res);
            echo View::factory('profiler/stats');
//                }
        }
//        }
//        $job = Document::factory('job')->get_next();
//        while ($job->hasNext())
//        {
//            $j   = $job->getNext();
//            $arr = $j->as_array();
//            //die(Debug::vars($j));
//            $pro = new Job_Processor_Immediate($arr);
//            //die;
//            //die(Debug::vars($pro));
//            //echo '<pre>';
//            if ($arr['is_single'])
//                $e = $pro->add('single');
//            else
//                $e = $pro->add();
//
//            $queue = new CFBatchRequest(100);
//
//            foreach($e->emails as $email)
//            {
//                $queue->add($email);
//            }
//
//            //$res = $queue->send();
//
//            $token = Profiler::start('email', 'Send Email');
//            $res = $queue->send();
//            Profiler::stop($token);
//            echo Debug::Vars($res);
//            echo View::factory('profiler/stats');
//            $j->delete();
//        }
//        echo "<pre>";
//        die((string)$e[0]);
        //$this->response->body((string)$e[0]);
    }

    public function action_password()
    {
        //echo static::hash('121481') . '<br>';
        echo Document::hash('[REDACTED]') . '<br>';
        //51a3141e6c01999b0f0000bd
        //51a313b26c0199990f0000c9
    }

}