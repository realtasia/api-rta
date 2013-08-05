<?php defined('SYSPATH') or die('No direct script access.');

/**
 * A simple controller that returns the location of an avatar for a given user. It sets nginx headers so it can
 * redirect the request to serve the static file instead of having PHP handle the file.
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Avatar
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Avatar extends Controller {

    /**
     * A simple method that retrieves a user by id, checks for an existing avatar or redirects to the default
     * after checking user gender.
     *
     * @return Response The response headers
     */
    public function action_index()
    {
        $url = '/s3/';
        $b = $url . RT::BUCKET . '/avatars/';

        $this->response->send_headers();
        $id   = trim($this->request->param('id'));
        $size = (int) $this->request->param('size');
        $user = Document::factory('user')->collection();
        $u = $user->findOne(['_id' => new MongoID($id)], ['info.gender' =>1, 'avatar' =>1]);

        if(\Arr::get($u, 'avatar'))
        {
            //echo $url . str_replace($id, "$id/$size", $u['avatar']);
            return $this->response->headers([
                'X-Accel-Redirect' => $url . str_replace($id, "$id/$size", $u['avatar'])
            ]);
        }

        //echo $b . ucfirst(substr($u['info']['gender'], 0, 1)) . $size . '.png';
        //return;
        return $this->response->headers([
            'X-Accel-Redirect' => $b . ucfirst(substr($u['info']['gender'], 0, 1)) . $size . '.png'
        ]);

    }

}
