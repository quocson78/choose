<?php

class Admin_Index_LogoutController extends Choose_Controller_Action_Admin
{
    public function indexAction()
    {
        Choose_Session::destroy();

        $this->_redirect('/login/');
    }
}