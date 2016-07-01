<?php

class Admin_Help_IndexController extends Choose_Controller_Action_Admin
{
    public function indexAction()
    {
        $file = $this->getParam('file');
        $this->render($file);
    }
}
