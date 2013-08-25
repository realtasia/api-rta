<?php

defined('SYSPATH') or die('No direct script access.');
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2000M');

/**
 *  1. Authenticate a token request
 *      a. Set a global $session variable for consumption by child controllers.
 *  2. Method to check if it is a user authenticated request.
 *  3. Get error utility from trait.
 *  4. Get success utility from trait.
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Page
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
 */
class Controller_Page extends Controller_Resource {

//class Controller_Page extends Controller {


    public function before()
    {
        parent::before();
        if (parent::logged_in())
        {
            static::$model = Document::factory('page', null, static::$user_id);
        }
        else
        {
            static::$model = Document::factory('page');
        }
    }

    /*     * ************************************************************************
     * Default Actions
     * *********************************************************************** */

    /**
     * Fetch requested post and check relation to logged_in user
     *
     * GET post
     * Usage: Issue a GET to /page/index/{id}
     *
     * @return json data
     */
    public function action_index()
    {
        return $this->data(static::$model->get($this->request->param('id')));
    }

    /**
     * Changes to post settings or content and post creation will use this update.
     */
    public function action_update()
    {
        // get the type of request
        $type = Arr::get(static::$params, 'request_type');

        // no type. we need a type
        if (!$type)
            $this->error('invalid_request');

        // let's check if it's valid and run it
        if (in_array($type, static::$valid_post_types) && method_exists($this, $type))
            return $this->{$type}();

        // nope, return an error
        return $this->error('invalid_request');
    }

    /*     * ************************************************************************
     * Public Connections
     * *********************************************************************** */

    /**
     * Retrieves an array of [user_ids and user names] of people who like this page
     */
    public function action_likers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'page';

        $data = Document::factory('interaction')
          ->collection()
          ->likers(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves an array of [user_ids and user names] of people who are subscribed to this post
     */
    public function action_subscribers()
    {
        static::$params['object_id'] = $this->request->param('id');
        static::$params['object_type'] = 'page';

        $data = Document::factory('interaction')
          ->collection()
          ->subscribers(static::$params);

        return $this->data($data);
    }

    /**
     * Retrieves an array of listings belonging to a user.
     */
    public function action_listings()
    {
        $id = $this->request->param('id');
        static::$params['user_id'] = $id;

        $posts = Mongo_Collection::factory('post');

        /*
         * 1. if user is logged in and ids are the same (or admin or client)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed.
         */
        $status = Arr::get(static::$params, 'status', 'active');

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
                // requested user and logged in user must be the same or must be admin
                if (static::logged_in_is_id($id) || parent::has_role('admin'))
                    return $this->data(
                        $posts->listings(static::$params, static::$user_id)
                    );

                // clients only see 'active' (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                    return $this->data(
                        $posts->listings(static::$params, static::$user_id)
                    );

                // oops. zip token.
                return $this->error('access_denied');
        }

        return $this->error('invalid_request');
    }

    /**
     * Retrieves an array of testimonials written by or received for a user.
     */
    public function action_testimonials()
    {
        $type  = Arr::get(static::$params, 'request_type', 'received');
        $id    = $this->request->param('id');
        $posts = Mongo_Collection::factory('post');

        switch ($type)
        {
            case 'written' :
                static::$params['user_id'] = $id;
                break;
            case 'received' :
                static::$params['page_id'] = $id;
                break;
        }
        /*
         * 1. if user is logged in and ids are the same (or admin or client)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed (with a valid token).
         */

        $status = Arr::get(static::$params, 'status', 'active');

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
                if (static::logged_in_is_id($id) || parent::has_role('admin'))
                    return $this->data(
                        $posts->testimonials(static::$params, static::$user_id)
                    );

                // clients only see 'active' (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                    return $this->data(
                        $posts->testimonials(static::$params, static::$user_id)
                    );

                // oops. zip token.
                return $this->error('access_denied');
        }

        return $this->error('invalid_request');
    }

    /**
     * This handles both retrieval and updating of a user's privacy settings
     */
    public function action_privacy()
    {
        $id = $this->request->param('id');
        if ($this->is_GET() && (parent::logged_in() || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['settings' => 1]);
            $this->data(
              $user->stringify($user->as_array()['settings']['privacy'])
            );
        }
        else
        {
            if (static::logged_in_is_id($id))
            {
                // update user by his/her specific field
                if ($this->is_POST())
                {
                    $this->update_privacy_settings(static::$params, $id);
                }
                return true;
            }
        }
    }

    /**
     * This handles both retrieval and updating of a user's info
     */
    public function action_info()
    {
        $id = $this->request->param('id');
        if ($this->is_GET() && (parent::logged_in() || parent::is_client()))
        {
            $user = static::$model;
            $user->load($this->request->param('id'), ['info' => 1]);
            $this->data($user->stringify($user->as_array()['info']));
        }
        else
        {
            if (static::logged_in_is_id($id))
            {
                // update user by his/her specific field
                if ($this->is_POST())
                {
                    $this->update_info(static::$params, $id);
                }
                return true;
            }
        }
    }

    public function action_pictures()
    {

    }

    public function action_checkins()
    {

    }

    public function action_members()
    {

    }

    public function action_feed()
    {
        $id = $this->request->param('id');

        static::$params['type'] = ['$nin' => ['conversation', 'album']];

        $posts = Mongo_Collection::factory('post');

        /*
         * 1. if user is logged in and ids are the same (or admin)
         *      - all statuses allowed.
         * 2. if not
         *      - only active statuses allowed.
         */

        $status = Arr::get(static::$params, 'status', 'active');

        switch ($status)
        {
            case 'pending':
            case 'archived':
            case 'draft':
            case 'trashed':
                // logged in user does not see other statuses if posts don't
                // belong to him
                if (static::logged_in_is_id($id))
                {
                    $p = Arr::merge(static::$params, ['user_id' => static::$user_id]);
                    return $this->data($posts->search($p, static::$user_id));
                }

                // admin sees everything
                if (parent::has_role('admin'))
                    return $this->data($posts->search(static::$params, static::$user_id));

                // clients only see active (public) posts
                return $this->error('access_denied');

            // this is a regular (public) lookup
            default :
                // need a client or user token.
                if (parent::is_client() || parent::logged_in())
                {
                    // only publically viewable posts
                    static::$params['privacy'] = static::logged_in_is_id($id) ? null : 'PUBLIC';
                    // we include posts written on his page
                    return $this->data($posts->search(static::$params, static::$user_id));
                }
        }

        return $this->error('invalid_request');
    }

    public function action_locationkeyword()
    {
        $s = $this->request->query('s');
        if (!$s)
            return $this->error('invalid_request');

        $q = [
            'estate'   => ['$ne' => null],
            'keywords' => new MongoRegex("/^" . strtolower($s) . "/"),
            'child'    => ['$ne' => null]
        ];

        // in case we are in an estate, limiting will be best
        if ($estate = \Arr::get(static::$params, 'object_name'))
        {
            $q['estate'] = urldecode($estate);
        }

        $s       = strtolower($s);
        $results = [];
        $cursor  = (array) Collection::factory('singpost')->distinct('keywords', $q);

        foreach ($cursor as $value)
        {
            if (strpos($value, $s) !== false)
            {
                $results [] = ucwords($value);
            }
        }
        if (count($results) > 15)
        {
            $results = array_slice($results, 0, 15);
        }

        return $this->data($results);
    }

    public function action_companykeyword()
    {
        $s = $this->request->query('s');
        if (!$s)
            return $this->error('invalid_request');

        $results = [];

        $cursor = (array) Collection::factory('page')->distinct('keywords', [
              'keywords'   => new MongoRegex("/^" . strtolower($s) . "/"),
              'type'       => 'company',
              'status'     => 'active',
              'privacy.is' => 'PUBLIC',
        ]);

        foreach ($cursor as $value)
        {
            if (strpos($value, $s) !== false)
            {
                $results [] = ucwords($value);
            }
        }
        if (count($results) > 15)
        {
            $results = array_slice($data, 0, 15);
        }

        return $this->data($results);
    }

    /*     * ************************************************************************
     * Public Actions
     * *********************************************************************** */

    /**
     * Performs a like on the given post
     */
    public function action_like()
    {
        return parent::interact('like', 'page');
    }

    /**
     * Performs an unlike on the given post
     */
    public function action_unlike()
    {
        return parent::interact('unlike', 'page');
    }

    /**
     * Performs a subscribe on the given post
     */
    public function action_subscribe()
    {
        return parent::interact('subscribe', 'page');
    }

    /**
     * Performs an unsubscribe on the given post
     */
    public function action_unsubscribe()
    {
        return parent::interact('unsubscribe', 'page');
    }

    public function action_search()
    {

        $s = \Arr::get(static::$params, 's');

        if ($s)
        {
            unset(static::$params['s']);
            $c = Collection::factory('singpost')
              ->find([
                  'estate'    => ['$ne' => null],
                  'keywords' => new MongoRegex("/^" . strtolower($s) . "/"),
                  'child'    => ['$ne' => null],
              ])
              ->fields(['child'])
              ->limit(50);

            while($c->hasNext())
            {
                static::$params['include_ids'][] = $c->getNext()->child['_id'];
            }

        }

        if (parent::logged_in())
            $pages = Collection::factory('page')->search(static::$params, static::$user_id);
        else
            $pages = Collection::factory('page')->search(static::$params);

        return $this->data($pages);
    }

    public static function valid($page, $field, Validation $v)
    {
        if (!$page)
            return true;

        $id = is_array($page) ? $page['_id'] : $page;
        $id = is_object($page) && isset($page->_id) ? $page->_id : $page;

        return Document::factory('page', $id)->load(['_id' => $id], ['_id' => 1]) ? true : $v->error($field, 'Not a Valid Page', $u);
    }

    /**     * ***********************************************************************
     * TestPad methods
     * *********************************************************************** */
    public function action_standard()
    {
        $pages  = Mongo_collection::factory('singpost');
        $mysqli = new mysqli('localhost', 'root', NULL, 'test');
        $json   = [];
        $result = $mysqli->query('SELECT DISTINCT project FROM `condominiums` ORDER BY project ASC');
        while ($each   = $result->fetch_row())
        {
            $json[] = $this->format($each[0]);
        }

        $query = [
            'address'  => ['$nin' => ['Condominium', 'Apartment', 'HDB', 'PO Box', 'Window Delivery', 'Locked Bag']],
            'building' => ['$not' => new MongoRegex('/school|swimming|station|temple|church|complex|centre|camp|hospital|clinic|shop|library|college|stadium|car.park|exchange|depot|commission|polyclinic|mosque|masjid|association|interchange|aia.|industrial|office|kindergarden|mrt|lrt|sports|waterworks|hall|brickwork|tldm|brigade|defence|eating|food|shopping|post|police|fire|safety|club|hotel|motel|assembly|cargo|airport|airbase|cinema|ministry|government|parliment|health|driving|technology|ctr|kindergarten|breweries|materials|amphitheatre|storage|factory|warehouse|institute|logistics|hospice|center|innovation|aquarium|lagoon|sport|theatres|theatre|techpark|flight|terminal|operations|operation|embassy|amusement|flatted|general|chij|npc|convent|cathedral|aviation|academy|chapel|prison|methodist|metal|works|synagogue|pte.ltd|resort|store|megastore|distripark|hub|dhl|bank|barrack|enterprise|fraser|division|aged|disabled|hostel|hostels|ite|marine|sewage|works|motors|child|maintenance|dormitory|shipyard|naval|nursery|sewer|buddhist|taxi|old age|old people|home for|supply|chemical|islamiah|majlis|logit|homes for|academy|arts|museum|university|polytechnic|ntuc|restaurant|outward|bank|nursing|refuse|tuas|medical|plant|mission|indstrl|society|zoological|red.cross|automative|engineering|instrument|design|customs|leisure|publications|delivery/i')],
            'street'   => ['$not' => new MongoRegex('/tuas/i')],
            'estate'   => ['$ne' => NULL],
        ];

        $cursor = $pages->find($query);
        $row    = [];

        $i = 0;
        while ($cursor->hasNext())
        {
            //$row[] = $cursor->getNext()->as_array();
            $r        = $cursor->getNext()->as_array();
            $building = $this->format($r['building']);
            if (!in_array($building, $json))
            {
                $format = "$building [{$r['address']}]";
                $row[]  = $format;
            }

            $i++;
        }

        $cursor->rewind();
        $count = $cursor->count();

        $return = array_unique($row, SORT_STRING);
        sort($return);

        return $this->data([$count => $return]);
        //return $this->data([$count => $row]);
    }

    protected function format($building)
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

//        $buildings = explode(' ', $building);
//
//        foreach ($buildings as $k => $v)
//        {
//            switch ($v)
//            {
//                case 'ILIV' : $buildings[$k] = 'iLiv';
//                    break;
//                case 'AAEV79' : $buildings[$k] = 'Aaev 79';
//                    break;
//                case '1A' :
//                case 'II' :
//                case 'APS' :
//                case 'DLV' :
//                case 'RV' :
//                case 'RVG' :
//                case 'JLB' :
//                case 'M.T.F.A' : break;
//                default : $buildings[$k] = ucwords(strtolower($v));
//                    break;
//            }
//        }
//
//        return implode(' ', $buildings);
        return strtoupper($building);
    }

}

// End Welcome
