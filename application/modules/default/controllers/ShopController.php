<?php
class Default_ShopController extends Choose_Controller_Action_Default
{
	protected $obj, $params, $shop;
	
	public function init()
	{
		parent::init();

		$this->obj = new App_Model_Shop();
		
		$this->params = $this->getRequest()->getParams();
		$this->shop = $this->obj->getShopById($this->params['shop_id']);
		
		$this->shop['pref_city'] = '';
		if ($this->shop['pref_id']!=''){
		    $pref = $this->obj->getPrefByPrefId($this->shop['pref_id']);
		    $this->shop['pref_city'] .= $pref['name'];
		}
		if ($this->shop['city_code']!=''){
		    $city = $this->obj->getCityByCityCode($this->shop['city_code']);
		    $this->shop['pref_city'] .= $city['city_name'];
		}
		
		$this->view->assign('shop', $this->shop);
		$this->view->assign('params', $this->params);
		
		if (isset($this->params['offer_id']) && ($this->params['offer_id']!='')){
		    $this->_setPageMsgUrl($this->params['shop_id'], $this->params['offer_id']);
		}else{
		    $this->_setPageMsgUrl($this->params['shop_id']);
		}
		
		$this->view->assign('str_header_title', $this->shop['shopname_kana'].'の求人｜求人募集'); //ヘッダにタイトル
		
		$shop_logo_img = $this->obj->getImagesDisplay($this->shop['shop_id'], 2); // 店舗の名前に表示する写真
		$this->view->assign('shop_logo_img', $shop_logo_img);
		
		if (isset($this->params['offer_id']) && ($this->params['offer_id']!='')){
    		$offer = $this->obj->getOfferByOfferId($this->params['offer_id']);
    		$this->view->assign('offer', $offer);
		}
		
	}

	// 店舗情報
	public function indexAction()
	{
		$recommends = $this->obj->getAllRecommend($this->params['shop_id']);
		$this->view->assign('recommends', $recommends);
		
		$recommend_images = $this->obj->getImagesDisplay($this->shop['shop_id'], 6); // おすすめポイントに表示する全ての写真
		$this->view->assign('recommend_images', $recommend_images);
		
		if (isset($this->params['offer_id']) && ($this->params['offer_id']!='')){
		    $this->_setOtherOffers($this->params['shop_id'], $this->params['offer_id']);
		}else{
		    $this->_setOtherOffers($this->params['shop_id']);
		}
		
		$shop_title_img = $this->obj->getImagesDisplay($this->shop['shop_id'], 3); // 店舗メインに表示する写真
		$this->view->assign('shop_title_img', $shop_title_img);
		
		$shop_owner_img = $this->obj->getImagesDisplay($this->shop['shop_id'], 4); // 店舗のオナーのメッセージに表示する全ての写真
		$this->view->assign('shop_owner_img', $shop_owner_img);
		
		$shop_staff_img = $this->obj->getImagesDisplay($this->shop['shop_id'], 5); // 店舗のスタッフのメッセージに表示する全ての写真
		$this->view->assign('shop_staff_img', $shop_staff_img);
		$this->view->assign('type_page', 'shopinfo');
	}

    public function albumAction(){
		$rs_album = $this->obj->getImagesDisplay($this->shop['shop_id'], 7); //フォトギャラリー
		$this->view->assign('album', $rs_album);
		
		$this->view->assign('type_page', 'album');
	}
	
	public function mapAction(){
	    if (isset($this->params['offer_id']) && ($this->params['offer_id']!='')){
	        $this->_setOtherOffers($this->params['shop_id'], $this->params['offer_id']);
	    }else {
	        $this->_setOtherOffers($this->params['shop_id']);
	    }
	    
	    $this->view->assign('type_page', 'map');
	}
	
	/**
	 * このお店が募集している他の求人情報
	 * @param int $shop_id
	 */
	public function _setOtherOffers($shop_id, $offer_id=''){
	    $obj = new App_Model_Shop();
	    $rs_offers = $obj->getOtherOffersByShopId($shop_id, $offer_id);
	    foreach ($rs_offers as $k=>$v){
	        $tags_name = '';
	        $rs_tags = $obj->getAllTagsByOfferId($v['offer_id']);
	        foreach ($rs_tags as $k1=>$v1){
	            $tags_name .= $v1['contents'].'　';
	        }
	        $rs_offers[$k]['tag_name'] = $tags_name;
	    }
	    $this->view->assign('other_offers', $rs_offers);
	}
	
	public function _setPageMsgUrl($shop_id, $offer_id=''){
	    $obj_shop = new App_Model_Shop();
	    $obj_offer = new App_Model_Offer();
	    
	    $shop = $obj_shop->getShopById($shop_id);
	    
	    $arr_url = array();
	    if ($offer_id!=''){
    	    $rs_gyosu = $obj_offer->getTagsByOfferGroup($offer_id, 2); // 業種	    
    	    $arr_url[] = array('/'.$rs_gyosu[0]['tag_name'], $rs_gyosu[0]['contents'].'|'.$this->arr_category_head[$rs_gyosu[0]['tag_name']]);
    	    $category = $rs_gyosu[0]['tag_name'];
	    }else{
	        $arr_url[] = array('/all', '全業種');
	        $category = 'all';
	    }
	    
	    if ($shop['pref_id']!=''){
	        $pref = $obj_shop->getPrefByPrefId($shop['pref_id']);
	        $arr_url[] = array('/'.$category.'/area/'.$pref['name'], $pref['name']);
	    }
	    if ($shop['city_code']!=''){
	        $city = $obj_shop->getCityByCityCode($shop['city_code']);
	        $arr_url[] = array('/'.$category.'/area/'.$pref['name'].'/'.$city['city_code'], $city['city_name']);
	    }
	    
	    $arr_url[] = array('', $shop['shopname_kana'].'・'.$shop['shopname']);
	    
	    $this->view->assign('arr_url', $arr_url);
	}
}