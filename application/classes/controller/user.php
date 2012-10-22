<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author jj
 */
class Controller_User extends Controller_Resource {

    /**
     * Fetch id or username.
     * Check if there is a logged in user
     * Fetch requested user and check relation to logged_in user
     *
     * @return type
     */
    public function action_index()
    {
        // get the id from request
        $id = $this->request->param('id');
        return $this->data($data);
    }

}

?>
