<?php

class App_Model_Shop {
	public function getShopById($shop_id){
		$obj = new App_Model_DbTable_MShops();
		return $obj->getByShopId($shop_id);
	}
	
	public function getAllRecommend($shop_id){
		$obj = new App_Model_DbTable_ShopRecommend();
		return $obj->getAllByShopId($shop_id);
	}
	
	public function getImagesDisplay($shop_id, $r_type){
		$obj = new App_Model_DbTable_ShopImageDisplay();
		return $obj->getAllByShopIdAndType($shop_id, $r_type);
	}
	
	public function getOtherOffersByShopId($shop_id, $offer_id=''){
	    $obj = new App_Model_DbTable_Offers();
	    return $obj->getAllByShopId($shop_id, $offer_id);
	}
	
	public function getAllTagsByOfferId($offer_id){
	    $obj = new App_Model_DbTable_OffersTags();
	    return $obj->getAllByOfferId($offer_id);
	}
	
	public function getOfferByOfferId($offer_id){
	    $obj = new App_Model_DbTable_Offers();
	    return $obj->getByOfferId($offer_id);
	}
	
	public function save($shopId, $params)
    {
        $table = new App_Model_DbTable_MShops;

        if ($shopId) {
            $row = $table->find($shopId);
        } else {
            $row = $table->createRow($params);
            $row->registed = date('Y-m-d H:i:s');
        }

        $row->updated_id = null;
        $row->updated = date('Y-m-d H:i:s');
        $row->save();

        $shop_id = $row->shop_id;

        $table = new App_Model_DbTable_ShopRecommend();
        foreach ($params['shop_recommends'] as $values) {
            $row = $table->createRow($values);
            $row->shop_id    = $shop_id;
            $row->registed   = date('Y-m-d H:i:s');
            $row->updated_id = null;
            $row->updated    = date('Y-m-d H:i:s');
            $row->save();
        }

        return $row->shop_id;
    }
    
    public function getPrefByPrefId($pref_id){
        $obj = new App_Model_DbTable_MPrefs();
        return $obj->getByPrefId($pref_id);
    }
    
    public function getCityByCityCode($city_code){
        $obj = new App_Model_DbTable_MCities();
        return $obj->getByCityCode($city_code);
    }
}
