<?php

class Admin_Crawl_ShopController extends Choose_Controller_Action_Admin
{
    protected $_offer;


    public function init()
    {
        parent::init();

        if (!$this->_offer) {
            $offers = new App_Model_CrawlOffers;
            $this->_offer = $offers->fetchByNo($this->getParam('no'));
        }
    }


    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->_offer)
            return false;

        $this->view->offer = $this->_offer->toArray();

        if ($this->_helper->FlashMessenger) {
            $this->view->messages = $this->_helper->FlashMessenger->getMessages();
        }
    }


    public function indexAction()
    {
        if (!$this->view->form && $this->view->offer) {
            $form = new Choose_Form_Admin_CrawlOffer;

            $form->setDefaults($this->view->offer);

            $this->view->form = $form;
        }
    }


    public function saveAction()
    {
        if ($this->getParam('reject')) {
            return $this->_forward('reject');

        } elseif ($this->getParam('delete')) {
            return $this->_forward('delete');

        } elseif ($this->getParam('suspend')) {
            return $this->_forward('suspend');

        } elseif ($this->getParam('next')) {
            return $this->_forward('next');

        } elseif ($this->getParam('accept')) {
            return $this->_forward('accept');

        } elseif ($this->getParam('shop')) {
            return $this->_forward('shop');
        }

        throw new Choose_Exception('');
    }


    public function rejectAction()
    {
        $params = $this->getAllParams();

        $this->_offer->checked_flag = 1;
        $this->_offer->delete_flag = 1;
        $this->_offer->save();

        $no = (int)$params['no'] + 1;

        return $this->redirect("/crawl/shop/?no={$no}");
    }


    public function suspendAction()
    {
        $params = $this->getAllParams();

        $this->_offer->checked_flag = 1;
        $this->_offer->suspend_flag = 1;
        $this->_offer->save();

        $no = (int)$params['no'] + 1;

        return $this->redirect("/crawl/shop/?no={$no}");
    }


    public function nextAction()
    {
        $params = $this->getAllParams();

        $no = (int)$params['no'];

        if ($this->_offer->shop_id
            || $this->_offer->checked_flag) {

            $this->_offer->checked_flag = 1;
            $this->_offer->save();

            $no++;

        } else {

            $this->_helper->FlashMessenger('操作がされずに次へ進もうとしました。');

        }

        return $this->redirect("/crawl/shop/?no={$no}");
    }


    public function acceptAction()
    {
        $params = $this->getAllParams();

        $form = new Choose_Form_Admin_CrawlOffer;
        if (!$form->isValid($params)) {
            $this->view->form = $form;
            return $this->_forward('index');
        }

        $crawl = new App_Model_Crawl;

        $shopId = $crawl->saveShop($params);
        $offerId = $crawl->saveOffer($shopId, $params);
        $crawl->saveOfferTag($offerId, $params);

        $this->_offer->shop_id = $shopId;
        $this->_offer->save();

        $form->setDefault('category', null);
        $form->setDefault('types', null);

        $this->view->form = $form;
        return $this->_forward('index');
    }


    public function shopAction()
    {
        $params = $this->getAllParams();

        $form = new Choose_Form_Admin_CrawlShop;
        if (!$form->isValid($params)) {
            $this->view->form = $form;
            return $this->_forward('index');
        }

        $crawl = new App_Model_Crawl;

        $shopId = $crawl->saveShop($params);

        $this->_offer->shop_id = $shopId;
        $this->_offer->checked_flag = 1;
        $this->_offer->save();

        $no = (int)$params['no'] + 1;
        return $this->redirect("/crawl/shop/?no={$no}");
    }
}
