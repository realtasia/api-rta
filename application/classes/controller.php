<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of controller
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
 */
class Controller extends Kohana_Controller {

    /**
     * Returns a formatted response containing current session and data in the following format
     * <code> 
     * return json_encode([
     *      'status' => 'success',
     *      'data' => $data
     * ]);
     *</code>
     * 
     * HTTP status code defaults to 200
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
