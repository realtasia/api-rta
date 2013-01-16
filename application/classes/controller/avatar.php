<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of abstract
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Controller/Abstract
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Avatar extends Controller {

    public function action_index()
    {
        $url = '/s3/';
        $bucket = 'static2.realtasia.com.sg/avatars/';

        $this->response->send_headers();
        $id   = trim($this->request->param('id'));
        $size = (int) $this->request->param('size');
        $user = Document::factory('user')->collection();
        $u = $user->findOne(['_id' => new MongoID($id)], ['info.gender' =>1, 'avatar' =>1]);

        if(\Arr::get($u, 'avatar'))
        {
            return $this->response->headers([
                'X-Accel-Redirect' => $url . str_replace($id, "$id/$size", $u['avatar'])
            ]);
        }

        return $this->response->headers([
            'X-Accel-Redirect' => $url . $bucket . ucfirst(substr($u['info']['gender'], 0, 1)) . $size . '.png'
        ]);

    }

}
