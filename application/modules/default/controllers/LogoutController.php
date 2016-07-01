<?php

class Default_LogoutController extends Choose_Controller_Action_Default
{
    public function indexAction()
    {
        Choose_Session::destroy();
        $this->_redirect('/login');
    }
}