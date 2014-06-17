<?php defined('SYSPATH') or die('No direct script access.');

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
class Controller_Test extends Controller {

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
	
	public function action_childpagecount()
	{
		$cursor   = Collection::factory('page')->find(["subtype" => "Estate"]);
		$parent_ids = [];
        while ($cursor->hasNext())
		{
			
			$parent = $cursor->getNext();
			$parent_ids[] = $parent->_id;
			
			$total = Collection::factory('page')->find(["parent._id" => $parent->_id])->count();
			
			print_r([
				"Name" => $parent->name,
				"Children" => $total
			]);
			
			$parent->counts["locations"] = $total;
			$parent->save();
			
			echo "\n";
			echo "*****************************************************************************";
			echo "\n";
		}
		
		$cursor   = Collection::factory('page')->find([
			"type" => "location",
			"subtype" => [
				'$ne' => "Estate"
			],
			"parent._id" => [
				'$nin' => $parent_ids
			]
		])
		->count();
		print_r("$cursor location pages found");
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
