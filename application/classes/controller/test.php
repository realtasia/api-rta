<?php

ini_set("max_execution_time", 0);
ini_set('memory_limit', '2000M');

class Controller_Test extends Controller {
    use Trait_Oauth2_Utils;
    public $template = 'template';

    public function action_do()
    {
        $client_credentals = [
            'client_id' => '123456789',
            'redirect_uri' => 'http://jjpc.net',
            'grant_type' => 'client_credentials',
        ];

        $token = [
            'access_token' => '[REDACTED]',
            'state' => substr(Security::token(), 0, 5),
        ];

        $registration = [
            'request_type' => 'registration',
            'user_type' => 'user',
        ];

        $return = [];

        for($i=0;$i<=100;$i++)
        {
            $values = Arr::merge($token, $registration, $this->users($i));

            $request = Request::factory(URL::site('/user', true))
                    ->method(HTTP_Request::POST)
                    ->post($values);
            $return[] = "<pre>".$request->execute()."</pre>";
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

    public function action_files()
    {
        $dir    = "/home/realtasia/www/beta.casa.net/main.realtasia/agents/";
        $handle = opendir($dir);
        $master = array();
        $filecount = 0;
        while ($file      = readdir($handle))
        {
            if ($file !== '.' AND $file !== '..')
            {
                $a     = SimpleHTML::file_get_html($dir . $file);
                // the agent link and image url
                $td1   = $a->find('.agentlistingimagespace');
                // all other details.
                $td2   = $a->find('.morelistingtext');
                $count = 0;
                foreach ($td2 as $child)
                {
                    $info = Inflector::br2nl($child->outertext());
                    $info = preg_replace('/\t/', '', $info);
                    $info = trim(strip_tags($info));
                    $info = trim(nl2br($info));
                    if (strlen($info) > 1)
                    {
                        $arr = explode('<br />', $info);
                        foreach ($arr as $k => $v)
                        {
                            $arr[$k] = trim($v);
                            $arr[$k] = preg_replace('/\t/', '', $v);
                            $arr[$k] = preg_replace('/\n/', '', $v);
                            $arr[$k] = preg_replace('/\n/', '', $v);
                            $arr[$k] = trim($v);
                            //echo "<li><label>" . $k . ": </label> $v</li>";
                        }
                        if (isset($td1[$count]))
                        {
                            $url        = explode('/', $td1[$count]->children(0)->children(0)->src);
                            $image_file = $url[count($url) - 1];
                            if ($image_file == 'nolistings.gif')
                            {
                                $image = 'http://images2.sg.ippstatic.com/v2/min/iprop/common/no_image-agent.png';
                            }
                            else
                            {
                                $image    = 'http://s3images.sg.ippstatic.com/agents/' . $image_file;
                            }
                        }
                        $finalArr = array(
                            'name'     => $arr[0],
                            'license'  => trim(str_replace('Agent Reg. No. : ', '', $arr[1])),
                            'number'   => trim(str_replace('Handphone : ', '', $arr[2])),
                            'email'    => isset($arr[3]) ? trim(str_replace('Email : ', '', $arr[3])) : null,
                            'listings' => isset($arr[4]) ? trim(str_replace('Total Listings : ', '', $arr[4])) : 0,
                            'link'     => isset($td1[$count]) ? 'http://www.iproperty.com.sg' . $td1[$count]->children(0)->href : null,
                            'image'    => isset($td1[$count]) ? $image : null,
                            'source'   => 'iProperty',
                        );
                        try
                        {
                            Model::factory('agent')->collection->insert($finalArr, array('safe'   => TRUE, 'upsert' => TRUE));
                        } catch (MongoCursorException $e)
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
            $filecount++;
        }
        $cursor        = Model::factory('agent')->collection->find();
        foreach ($cursor as $doc)
        {
            var_dump($doc);
        }
        //file_put_contents('master.php', serialize($master));
        //die();
    }

    public function action_view_agents()
    {
        $master = unserialize(file_get_contents('master.php'));

        echo Debug::vars($master);
        die();
    }

    public function action_agents()
    {
        $letters = range('B', 'Z');
        foreach ($letters as $letter)
        {
            $a     = SimpleHTML::str_get_html($this->agent_url($letter));
            file_put_contents("agents/$letter-1.html", $a);
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
                    $a = SimpleHTML::str_get_html($this->agent_url($letter, $i));
                    file_put_contents("agents/$letter-$i.html", $a);
                }
                sleep(1);
            }
        }
        sleep(1);
    }

    public function agent_url($letter, $page = 1)
    {
        $request = Request::factory('http://www.iproperty.com.sg/property/findanagent.aspx')
                ->query(array(
                    'ty' => 'al',
                    'ak' => '',
                    'rk' => $letter,
                    'p'  => $page,
                    's'  => 100,
                ))
                ->headers(array(
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept-Language' => 'en-us,en;q=0.5',
            'Connection'      => 'keep-alive',
            'Host'            => 'www.iproperty.com.sg',
            'User-Agent'      => 'Mozilla/5.0 Gecko/20100101 Firefox/8.0',
                ));

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
            } catch (Exception $e)
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
        $script                      = html::anchor('/test/session/facebook', 'click me', array('id'                     => 'clickMe')) .
                <<<EOD
   <script type="text/javascript">
       jQuery(document).ready(function(){
        $('#clickMe').colorbox({
                        width:"80%", height:"80%"
                    });
    });
   </script>
EOD;
        $this->template->content = $script;
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
        $gender = ['male', 'female'];
        $rand_gend = $gender[array_rand(range(0, 1), 1)];

        if($rand_gend === 'female')
            $mid = 'd/o';
        else
            $mid = 's/o';

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
            'name'        => implode(' ', $name),
            'username'    => $numword . '.user',
            'password'    => static::hash($numword),
            'email'       => '[REDACTED]',
            'designation' => 'Senior Sales Executive',
            'roles'       => ['agent'],
            'avatar'      => [90 => '/agents/images/' . $i . '.jpg',],
            'uri'         => '/' . $numword . '.user',
            'active'      => time(),
            'info'        => [
                'telephone' => ['mobile'   => '[REDACTED]'],
                'birthday' => mt_rand(378691200, 694224000),
                'gender'   => $gender[array_rand(range(0, 1), 1)],
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

    public function action_chrishen()
    {
        $user =
                array(
                    'first_name'   => 'Chrishen',
                    'last_name'    => 'Bijay',
                    'display_name' => '[REDACTED]',
                    'username'     => 'ChrishenBijay',
                    'password'     => Auth::instance()->hash('98529487'),
                    'email'        =>
                    array(
                        0           => 'chrishenbijay@realtasia.com',
                    ),
                    'telephone' =>
                    array(
                        'mobile'      => '+65 94574065',
                        'DID'         => '+65 65190079',
                    ),
                    'designation' => 'Realtasia Staff',
                    'social'      => array(),
                    'websites' => array(),
                    'roles' =>
                    array(
                        0        => 'login',
                        1        => 'admin',
                    ),
                    'avatar' =>
                    array(
                        'cropped' => array(),
                        'uncropped' => NULL,
                        150         => 'beta.realtasia.com.sg/assets/images/avatar.jpg',
                        60          => 'beta.realtasia.com.sg/assets/images/avatar-60X60.jpg',
                        32          => 'beta.realtasia.com.sg/assets/images/avatar-32X32.jpg',
                    ),
                    'uri'       => '/ChrishenBijay',
                    'active'    => time(),
                    'counts'    =>
                    array(
                        'posts'       => 0,
                        'likes'       => 0,
                        'listings'    => 0,
                        'comments'    => 0,
                        'subscribers' => 0,
                        'attachments' => 0,
                    )
        );

//Model::factory('user')->collection->remove(array('username' => $user['username']));
        Model::factory('user')->collection->save($user);
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
                    'likers' => array(),
                )
            );

            echo Debug::vars($coll->update(array('_id' => $doc['_id']), $modify, array('safe'             => TRUE)));
        }
        $this->auto_render = false;
    }

    public function reset_users()
    {
        $coll   = Model::factory('user')->collection;
        $cursor = $coll->find();
        $counts = array_merge(Model::$counts, array(
            'subscriptions' => 0,
            'testimonials'  => 0,
            'likes'         => 0,
            'events'        => 0,
                ));
        $subs           = array(
            'events' => array(),
            'testimonials' => array(),
            'listings' => array(),
            'locations' => array(),
            'posts' => array(),
            'users' => array(),
        );
        while ($cursor->hasNext())
        {
            $doc    = $cursor->getNext();
            $modify = array(
                '$set' => array(
                    'counts'        => $counts,
                    'subscriptions' => $subs,
                    'subscribers'   => array(),
                    'likers' => array(),
                    'likes'  => $subs,
                    'status' => 'active',
                    'avatar' => array('cropped' => array(), 'uncropped' => array()),
                    'notifications' => array(
                        'likers' => array(
                            'own_post'    => 1,
                            'other_posts' => 1,
                            'profile'     => 1
                        ),
                        'subscribers' => array(
                            'own_post' => 1,
                            'profile'  => 1,
                        ),
                        'comments' => array(
                            'own_post'      => 1,
                            'other_posts'   => 1
                        ),
                        'testimonials'  => 1,
                        'profile_posts' => 1,
                        'subscriptions' => array(
                            'locations' => 'weekly',
                            'profiles'  => 'weekly'
                        )
                    )
                ),
                '$unset'    => array(
                    'subscriptionsusers' => 1
                )
            );

            echo Debug::vars($coll->update(array('_id' => $doc['_id']), $modify, array('safe' => TRUE)));
        }
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
        $job = Model::factory('job')->collection->find();
        while ($job->hasNext())
        {
            print_r('<style type="text/css">' .
                    file_get_contents(Kohana::find_file('views', 'profiler/style', 'css'))
                    . '</style>'
            );
            $j   = $job->getNext();
            $pro = new Job_Processor_Immediate($j);
            echo '<pre>';
            if ($j['is_single'])
                echo Debug::vars($pro->add('single')->emails);
            else
                echo Debug::vars($pro->add()->emails);
            echo '</pre>';
        }
        echo View::factory('profiler/stats');

        die();
    }

}