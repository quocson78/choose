<?php
class Default_CategoryController extends Choose_Controller_Action_Default
{
    protected $obj;
	public function init(){
	    $this->obj = new App_Model_Category();
		parent::init();
	}
	
	public function indexAction(){
		
		$prefs = $this->obj->getAllPrefs();
		$this->view->assign('prefs', $prefs);
		
		$offers = $this->obj->getOffersByRecommend();
		foreach ($offers as $k=>$v){
		    $offer_main_img = $this->obj->getImagesDisplay($v['shop_id'], 3);
		    $offers[$k]['image_filename'] = $offer_main_img[0]['image_filename']; 
		}
		$this->view->assign('offers', $offers);
		
		$area_tag_groups = $this->obj->getAllAreaTagGroup();
		$this->view->assign('area_tag_groups', $area_tag_groups);
	}
	
	public function getLinesAction(){
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
	    
	    $pref_name = $this->getParam('pref_name', '');
	    $lines = $this->obj->getLinesByPrefName($pref_name);
	    
	    echo Zend_Json::encode($lines);
	}
	
}