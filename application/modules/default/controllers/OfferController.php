<?php
include 'ShopController.php';
class Default_OfferController extends Choose_Controller_Action_Default
{
	// 求人情報
	public function indexAction()
	{
		$obj_shop = new App_Model_Shop();
		$obj_offer = new App_Model_Offer();
		
		$params = $this->getRequest()->getParams();
		$rs_offer = $obj_offer->getOfferById($params['offer_id']);
		
		$shop = $obj_shop->getShopById($rs_offer['shop_id']);
		
		$shop['pref_city'] = '';
		if ($shop['pref_id']!=''){
		    $pref = $obj_shop->getPrefByPrefId($shop['pref_id']);
		    $shop['pref_city'] .= $pref['name'];
		}
		if ($shop['city_code']!=''){
		    $city = $obj_shop->getCityByCityCode($shop['city_code']);
		    $shop['pref_city'] .= $city['city_name'];
		}
		
		$offer_points = $obj_offer->getPointTagByOfferId($rs_offer['offer_id']);
		
		// 住所
		$rs_offer['pref_city'] = '';
		if ($rs_offer['pref_id']!='' && $rs_offer['city_code']!=''){
		    $pref = $obj_shop->getPrefByPrefId($rs_offer['pref_id']);
		    $city = $obj_shop->getCityByCityCode($rs_offer['city_code']);
		    $rs_offer['pref_city'] = $pref['name'].$city['city_name'];
		}
		
		// 雇用形態
		$rs_tag_koyokeitai = $obj_offer->getTagsByOfferGroup($rs_offer['offer_id'], 4);
		$koyokeitai = '';
		foreach ($rs_tag_koyokeitai as $k1=>$v1){
		    $koyokeitai .= "【".$v1['contents']."】\n";
		}
		$rs_offer['koyokeitai'] = $koyokeitai;
		
		// 募集職種
		$rs_tag_sokusyu = $obj_offer->getTagsByOfferGroup($rs_offer['offer_id'], 3);
		$sokusyu = '';
		foreach ($rs_tag_sokusyu as $k1=>$v1){
		    $sokusyu .= $v1['contents']."\n";
		}
		$rs_offer['sokusyu'] = $sokusyu;
		
		// 路線
		$rs_line = $obj_offer->getAllLinesByOfferId($rs_offer['offer_id']);
		$line_name = '';
		foreach ($rs_line as $k1=>$v1){
		    $line_name .= $v1['name']."\n";
		}
		$rs_offer['lines'] = $line_name;
		
		// 駅名前
		$rs_station = $obj_offer->getStationByOfferId($rs_offer['offer_id']);
		/*
		$station_name = '';
		foreach ($rs_station as $k1=>$v1){
		    $station_name .= $v1['station_name'].' ';
		}*/
		$rs_offer['stations'] = $rs_station;
		
		// 性別
		$rs_sex = $obj_offer->getTagsByOfferGroup($rs_offer['offer_id'], 6);
		$sex = '';
		if ($rs_sex){
    		foreach ($rs_sex as $k1=>$v1){
    		    $sex .= $v1['contents']." ";
    		}
		}
		if ($sex !='') $sex .= '(風紀上の理由による)';
		else $sex = '男女問わず';
		$rs_offer['sex'] = $sex;
		
		//  番号作成
		$tmp = sprintf('%06d', $rs_offer['offer_id']);
		$rs_offer_bango = substr($tmp, 0, 3).'-'.substr($tmp, 3);
		$rs_offer['offer_bango'] = $rs_offer_bango;
		
		$this->view->assign('shop', $shop);// 店舗情報
		$this->view->assign('offer', $rs_offer); // 求人情報
		$this->view->assign('offer_points', $offer_points); // 注目ポイント
		$this->view->assign('params', $params);
		
		Default_ShopController::_setOtherOffers($rs_offer['shop_id'], $params['offer_id']);
		Default_ShopController::_setPageMsgUrl($rs_offer['shop_id'], $params['offer_id']);
		
		$this->view->assign('str_header_title', $shop['shopname_kana'].'の求人｜求人募集'); //ヘッダにタイトル
		
		$shop_title_img = $obj_offer->getImagesDisplay($shop['shop_id'], 3); // 店舗メインに表示する写真
		$this->view->assign('shop_title_img', $shop_title_img);
		
		$shop_logo_img = $obj_offer->getImagesDisplay($shop['shop_id'], 2); // 店舗ロゴに表示する写真
		$this->view->assign('shop_logo_img', $shop_logo_img);
		$this->view->assign('type_page', 'employ');
	}
}