<?php

defined('SYSPATH') or die('No direct script access.');

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
        $this->response->headers([
            'X-Accel-Redirect' => '/s3/static2.realtasia.com.sg/avatars/F150.png'
        ]);
        $this->response->send_headers();
//        $id   = $this->request->param('id');
//        $size = (int) $this->request->query('size');
//        $user = Document::factory('user', $id);
//        $user->load(null, [
//            'info.gender' =>1, 'avatar' =>1
//        ]);
//
//        if($user->avatar)
//        {
//            $url = $user->avatar . '-' . $size;
//        }

    }

}
