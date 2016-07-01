<?php

class Admin_Index_LoginController extends Choose_Controller_Action_Admin
{
    public function indexAction()
    {
        if (!$this->view->form) {
            $this->view->form = new Choose_Form_Admin_Login;
        }
    }

    public function checkAction()
    {
        $params = $this->getRequest()->getParams();

        $form = new Choose_Form_Admin_Login();
        if (!$form->isValid($params)) {
            $this->view->form = $form;
            return $this->_forward('index');
        }

        $adapter = new Choose_Auth_Adapter_Admin;
        $adapter->setIdentity($params['email']);
        $adapter->setCredential($params['password']);

        $auth = Choose_Auth::getInstance();
        if ($auth->authenticate($adapter)->isValid()) {
            $auth->getStorage()
                ->write($adapter->getResultRowObject());
        } else {
        }
        
        $this->_redirect('/');
    }
}