<?php

class Choose_Controller_Action_Admin extends Choose_Controller_Action
{
    protected $_auth;

    public function init()
    {
        parent::init();

        $this->_auth = Choose_Auth::getInstance();
        $controller = $this->getRequest()->getControllerName();

        if (!in_array(strtolower($controller), array('error', 'index_login'))) {
            if($this->_auth->getStorage()->isEmpty()) {
                return $this->redirect('/login/');
            }
        }
    }

    public function preDispatch()
    {
        // ログイン状態でlayout分け
        if($this->_auth && !$this->_auth->getStorage()->isEmpty()) {
            $this->_helper->layout->setLayout('logined');
        } else {
            $this->_helper->layout->setLayout('nologin');
        }

        parent::preDispatch();
    }
}
