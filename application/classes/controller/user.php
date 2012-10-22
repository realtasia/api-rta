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

    public function action_index()
    {
        $data = parent::get_user();
        return $this->data($data);
    }

}

?>
