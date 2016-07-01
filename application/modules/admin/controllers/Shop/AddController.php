<?php

class Admin_Shop_AddController extends Choose_Controller_Action_Admin
{
    protected $_offer;


    public function init()
    {
        parent::init();
    }


    public function preDispatch()
    {
        parent::preDispatch();

    }


    public function indexAction()
    {
        if (!$this->view->form ) {
            $form = new Choose_Form_Admin_Shop;
            $this->view->form = $form;
        }
    }


    public function saveAction()
    {
        $params = $this->getAllParams();

        $form = new Choose_Form_Admin_Shop;
        if (!$form->isValid($params)) {
            $this->view->form = $form;
            return $this->_forward('index');
        }

        $crawl = new App_Model_Shop;

        $shopId = $crawl->save(null, $params);

        $this->_helper->FlashMessenger("「{$params['shopname']}」新規登録しました。");

        return $this->redirect("/");
    }
}
