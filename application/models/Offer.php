<?php

class App_Model_Offer {
	public function getOfferById($offer_id){
		$obj = new App_Model_DbTable_Offers();
		return $obj->getOffer($offer_id);
	}
	
	public function getImagesDisplay($shop_id, $r_type){
		$obj = new App_Model_DbTable_ShopImageDisplay();
		return $obj->getAllByShopIdAndType($shop_id, $r_type);
	}

	public function getTagsByOfferGroup($offer_id, $group_id){
	    $obj_tag = new App_Model_DbTable_Tags();
	    return $obj_tag->getTagsByOfferGroup($offer_id, $group_id);
	}
	
	public function getStationByOfferId($offer_id){
	    $obj = new App_Model_DbTable_MStations();
	    return $obj->getAllByOffer($offer_id);
	}
	
	public function getAllLinesByOfferId($offer_id){
	    $obj = new App_Model_DbTable_MLines();
	    return $obj->getAllByOfferId($offer_id);
	}
	
	public function getPointTagByOfferId($offer_id){
	    $obj_tag = new App_Model_DbTable_Tags();
	    return $obj_tag->getPointTagByOfferId($offer_id);
	}
	
	public function getPrefCityByOfferId($offer_id){
	    $obj = new App_Model_DbTable_MPrefs();
	    return $obj->getPrefCityByOfferId($offer_id);
	}
}