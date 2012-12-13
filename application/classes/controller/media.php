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
        $headers = [
            'X-Sendfile: '
        ];
        $id   = $this->request->param('id');
        $size = (int) $this->request->query('size');
        $user = Document::factory('user', $id);
        $user->load(null, [
            'info.gender' =>1, 'avatar' =>1
        ]);

        if($user->avatar)
        {
            $url = $user->avatar . '-' . $size;
        }

    }

}
