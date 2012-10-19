<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

    public function action_index()
    {
        $session = Session::instance();
        $this->response->headers(array('Content-type' => 'application/json'));
        $this->response->body(nl2br(json_encode(Event::get('post.insert'), JSON_PRETTY_PRINT)));
    }

}

// End Welcome
