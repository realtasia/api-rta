<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of controller
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller extends Kohana_Controller {

    /**
     * Returns a formatted response containing current session and data
     *
     * @uses Oauth2_Resource->success
     * @param array|object $data
     * @return boolean
     */
    public function data($data)
    {
        $this->response->status(200);
        $this->response->headers('Content-Type', 'application/javascript');
        return $this->response->body(json_encode(['status' => 'success', 'data' => $data], JSON_PRETTY_PRINT));
    }

}
