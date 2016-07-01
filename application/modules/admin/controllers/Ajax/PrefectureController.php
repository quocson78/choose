<?php
class Admin_Ajax_PrefectureController extends Choose_Controller_Action_Admin
{
    public function preDispatch()
    {
        parent::preDispatch();

        //$this->_helper->viewRenderer->setNoRender(true);
		//$this->_helper->layout->setLayout('json');
        //$this->renderScript('json.phtml');

        $this->_helper->layout->disableLayout();
    }

    public function citiesAction()
    {
    	$table = new App_Model_DbTable_MCities;

		$params = $this->getAllParams();

        if ((int)$params['id']) {
    		$this->view->datas = $table->getAllByPrefId($params['id']);
        } elseif ($params['rome']) {
    		$this->view->datas = $table->getAllByPrefRoma($params['rome']);
        }
	}
}

