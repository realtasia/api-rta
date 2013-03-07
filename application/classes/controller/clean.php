<?php defined('SYSPATH') or die('No direct script access.');
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
class Controller_Clean extends Controller {

    public function action_deleteuser()
    {
        $user = Document::factory('user', $this->request->param('id'));

        if($user->load())
        {
            if(isset($user->company['_id']))
            {
                $company = Document::factory('page', $user->company['_id']);
                if($company->load(null, ['counts' => 1, 'members' => 1]))
                {
                    foreach($company->counts as $k => $v)
                    {
                        $company->set("counts.$k", 0);
                    }
                    $company->members = [];
                    $company->save();
                }
            }

//5135a47f6c0199a24100000a   document_id
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
                        ['user_id' => $user->_id], 'owner_id' => $user->_id
                    ]], ['multiple' => true]);
            Collection::factory('interaction')
                    ->remove_safe(['$or' => [
                        ['user._id' => $user->_id], 'object_id' => $user->_id
                    ]], ['multiple' => true]);
        }

        $user->delete();
    }

    public function action_reset_user()
    {

    }

}
