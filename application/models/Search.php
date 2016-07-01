<?php

class App_Model_Search {
    public function getGyousu(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(2);
    }
    
    public function getAllPrefs(){
    	$obj_pref = new App_Model_DbTable_MPrefs();
        return $obj_pref->getAll();
    }

    public function getPrefByName($pref_name){
        $obj_pref = new App_Model_DbTable_MPrefs();
        return $obj_pref->getByName($pref_name);
    }
    
    public function getCitiesByPref($pref_id){
    	$obj_cities = new App_Model_DbTable_MCities();
    	return $obj_cities->getAllByPref($pref_id);
    }
    
    public function getCitiesByPrefName($pref_name){
    	$obj_cities = new App_Model_DbTable_MCities();
    	return $obj_cities->getAllByPrefName($pref_name);
    }
   	
    // 特徴を取得
    public function getFeatures(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(18);
    }
    
    // 勤務形態を取得
    public function getKinds(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(4);
    }
    
    // 年齢を取得
    public function getAges(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(5);
    }
    
    // 性別を取得
    public function getSex(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(6);
    }
    
    // 給料を取得
    public function getSalary(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(17);
    }
    
    // 経験を取得
    public function getExperience(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(19);
    }
    
    public function getPrefByPrefId($pref_id){
    	$obj_pref = new App_Model_DbTable_MPrefs();
    	return $obj_pref->getByPrefId($pref_id);
    }
    
    public function getCityByCityCode($city_code){
    	$obj_cities = new App_Model_DbTable_MCities();
    	return $obj_cities->getByCityCode($city_code);
    }
    
    public function getTagByTagId($tag_id){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getByTagId($tag_id);
    }
    
    public function getTagByUrl($url){
    	$obj = new App_Model_DbTable_TagDisplay();
    	return $obj->getByUrl($url);
    }
    
    public function getLineByLineId($line_id){
    	$obj = new App_Model_DbTable_MLines();
    	return $obj->getByLineId($line_id);
    }
    /*
    public function getLineByLineRome($line_rome){
    	$obj = new App_Model_DbTable_MLines();
    	return $obj->getByLineRome($line_rome);
    }*/
    
    public function getLineByLineName($line_name){
        $obj = new App_Model_DbTable_MLines();
        return $obj->getByLineName($line_name);
    }
    
    public function getAllLinesByLineId($line_id){
    	$obj = new App_Model_DbTable_MLines();
    	return $obj->getAllLinesByLineId($line_id);
    }
    
    public function getAllStationsByLineId($line_id){
    	$obj = new App_Model_DbTable_MStations();
    	return $obj->getAllByLine($line_id);
    }
    
    public function getOfferBySearch($param, $pageNumber=1){
    	$obj = new App_Model_DbTable_Offers();
    	return $obj->getOfferBySearch($param);
    	
    }
    
    public function getTagsByOfferGroup($offer_id, $group_id){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getTagsByOfferGroup($offer_id, $group_id);
    }
    
    public function getStationByOfferId($offer_id){
    	$obj = new App_Model_DbTable_MStations();
    	return $obj->getAllByOffer($offer_id);
    }
    
    public function getTagbyTagContent($tag_content){
        $obj_tag = new App_Model_DbTable_Tags();
        return $obj_tag->getTagbyTagContent($tag_content);
    }
    
    public function getStationByStationId($station_id){
    	$obj = new App_Model_DbTable_MStations();
    	return $obj->getByStationId($station_id);
    }
    
    public function getAllLinesByOfferId($offer_id){
    	$obj = new App_Model_DbTable_MLines();
    	return $obj->getAllByOfferId($offer_id);
    }
    
    public function getAllTagsByOfferId($offer_id){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllTagsByOfferId($offer_id);
    }
    
    public function getPrefCityByOfferId($offer_id){
        $obj = new App_Model_DbTable_MPrefs();
        return $obj->getPrefCityByOfferId($offer_id);
    }
    
    public function getImagesDisplay($shop_id, $r_type){
        $obj = new App_Model_DbTable_ShopImageDisplay();
        return $obj->getAllByShopIdAndType($shop_id, $r_type);
    }
    
    public function getShopByShopId($shop_id){
        $obj = new App_Model_DbTable_MShops();
        return $obj->getShopByShopId($shop_id);
    }
}