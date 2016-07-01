<?php

class Default_PreUserController extends Choose_Controller_Action_Default {

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        return $this->_redirect('/');
    }


}