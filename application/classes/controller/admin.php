<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Description of clean
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Clean
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Admin extends Controller {

    public function action_index()
    {
        return $this->response->body(View::factory('admin_clean'));
    }

    public function action_logs()
    {
        $this->auto_render = FALSE;
        $l                 = Collection::factory('log')->find()->fields(['body', 'time']);
        $data              = [];

        while ($l->hasNext())
        {
            $r       = $l->getNext();
            $r->time = date('G:i:s j-m-y', strtotime($r->time));
            $data[]  = $r->stringify($r->as_array());
        }

        $l->rewind();

        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode([
            'total' => $l->count(),
            'data'  => $data
        ]));
    }

    public function action_jobs()
    {
        $return = array();
        $c      = static::_jobs();

        while ($c && $c->hasNext())
        {
            $r       = $c->getNext();
            $r->time = date('G:i:s j-m-y', $r->_id->getTimestamp());
            $return[] = $r->stringify($r->as_array());
        }

        $this->response->headers('Content-type', 'application/json');
        $this->response->body(json_encode([
            'total' => $c->count(),
            'data'  => $return
        ]));
    }

    public function action_deleteuser()
    {
        $user = static::get($this->request->post('search'));

        if ($user->loaded() || $user->load())
        {
            if (isset($user->company['_id']))
            {
                $company = Document::factory('page', $user->company['_id']);
                if ($company->load(null, ['counts'  => 1, 'members' => 1]))
                {
                    foreach ($company->counts as $k => $v)
                    {
                        $company->set("counts.$k", 0);
                    }
                    $company->members = [];
                    $company->save();
                }
            }

            Collection::factory('page')
              ->remove_safe(['user._id' => $user->_id]);
            Collection::factory('post')
              ->remove_safe(['user._id' => $user->_id], ['multiple' => true]);
            Collection::factory('comment')
              ->remove_safe(['user._id' => $user->_id], ['multiple' => true]);
            Collection::factory('job')
              ->remove_safe(['document_id' => $user->_id], ['multiple' => true]);
            Collection::factory('ticker')
              ->remove_safe(['$or' => [
                      ['user_id' => $user->_id], ['owner_id' => $user->_id]
                  ]], ['multiple' => true]);
            Collection::factory('interaction')
              ->remove_safe(['$or' => [
                      ['user._id' => $user->_id], ['object_id' => $user->_id]
                  ]], ['multiple' => true]);

            $user->delete();
        }
    }

    public function action_resetuser()
    {
        $user = static::get($this->request->post('search'));
        if ($user->loaded() || $user->load())
        {
            $user->set('counts.newMessages', 0);
            $user->set('counts.conversations', 0);
            $user->set('counts.subscribers', 0);
            $user->set('counts.likers', 0);
            $user->set('counts.posts', 0);
            $user->set('likers', array());
            $user->set('subscribers', array());
            Collection::factory('post')->remove([
                'user._id' => $user->_id,
                'type'  => ['$in' => [
                    'wallpost', 'post', 'conversation'
                ]]
            ]);
            Collection::factory('comment')->remove([
                'user._id' => $user->_id
            ]);
            $user->save();
        }
    }

    public function action_resetjobs()
    {
        return Collection::factory('job')
          ->update(['messages.private-message' => ['$exists' => true]], ['$set' => ['status'      => 1, 'in_progress' => false]], ['multiple' => true]);
    }

    public function action_deletealllogs()
    {
        return Collection::factory('log')->remove();
    }

    public function action_deletelog()
    {
        return Collection::factory('log')->remove(['_id' => Document::check_id($this->request->param('id'))]);
    }

    /**
     * Searches a user with a given val
     *
     * @param string $val username, email, id or mobile phone
     * @return Document a loaded or empty Document
     */
    protected static function get($val)
    {
        $u = Collection::factory('user')->find([
            '$or' => [
                ['_id' => Document::check_id($val)],
                ['username' => $val],
                ['email' => $val],
                ['info.telephone.mobile' => SMS::valid($val) ? SMS::valid($val) : null],
            ]
        ]);
        if ($u->hasNext())
        {
            return $u->getNext();
        }

        return Document::factory('user');
    }

    protected static function _jobs($_id = null)
    {
        if (is_string($_id) || $_id instanceof MongoID)
            return Document::factory('job', $_id);

        if (is_array($_id))
            $c = Collection::factory('job')->find($_id);
        else
            $c = Collection::factory('job')->find(['messages.private-message' => ['$exists' => true]]);

        return $c ? $c : null;
    }

    protected static function tablify(array $arr)
    {
        $out = "<table class='table table-condensed' style='background-color:#f5f5f5;'>";
        if (\Arr::get($arr, '_id'))
            $out .= "<tr><th colspan='2' style='text-align:center;color:#555'>" . \Arr::get($arr, '_id') . "</th></tr>";

        foreach ($arr as $key => $val)
        {
            $out .= "<tr data-bind='click:toggleTable' class='collapse'><td style='max-width:100px;cursor:pointer;padding:0'><div class='label' style='padding:8px 5px;display:block;text-align:right'>" . $key . "</div></td>";
            if ($key === 'status')
            {
                switch ($val)
                {
                    case 0: $val = '<span style="padding:5px;" class="alert-success">Success</span>';
                        break;
                    case 1: $val = '<span style="padding:5px;" class="alert">Pending</span>';
                        break;
                    case 2: $val = '<span style="padding:5px;" class="alert-error">Failed</span>';
                        break;
                }
            }
            if ($key === 'in_progress' || $key === 'is_single')
            {
                if ($val)
                    $val = "Yes";
                else
                    $val = "No";
            }
            if (is_array($val))
            {
                $out .= "<td>" . static::tablify($val) . "</td>";
            }
            else
            {
                $out .= "<td>" . $val . "</td>";
            }

            $out .= "</tr>";
        }

        $out .= "</table>";

        return $out;
    }

}
