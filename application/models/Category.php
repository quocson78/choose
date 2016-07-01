<?php

class App_Model_Category {
    
    public function getGyousu(){
    	$obj_tag = new App_Model_DbTable_Tags();
    	return $obj_tag->getAllByGroup(2);
    }
    
    public function getByUrl($url){
        $obj_tag_dispay = new App_Model_DbTable_TagDisplay();
        return $obj_tag_dispay->getByUrl($url);
    }
    
    public function getAllPrefs(){
        $obj_pref = new App_Model_DbTable_MPrefs();
        return $obj_pref->getAll();
    }
    
    public function getLinesByPrefName($pref_name){
        $obj_line = new App_Model_DbTable_MLines();
        return $obj_line->getAllByPrefName($pref_name);
    }
    
    public function getAllRegion(){
        $obj_region = new App_Model_DbTable_MRegions();
        $obj_pref = new App_Model_DbTable_MPrefs();
        $rs = $obj_region->getAll();
        foreach ($rs as $k=>$v){
            $rs1= $obj_pref->getAllByRegion($v['region_id']);
            $rs[$k]['prefs'] = $rs1;
        }
        return $rs;
    }
    
    public function getOffersByRecommend(){
        $obj_offer = new App_Model_DbTable_Offers();
        $obj_station = new App_Model_DbTable_MStations();
        $rs_offer = $obj_offer->getOffersByRecommend();
        foreach ($rs_offer as $k=>$v){
            $rs_station = $obj_station->getAllByOffer($v['offer_id']);
            $str_station = '';
            foreach ($rs_station as $k1=>$v1){
            	if ($str_station!='') $str_station .= ',';
            	$str_station .= $v1['station_name'].'駅';
            }
            $rs_offer[$k]['stations'] = $str_station;
        }
        return $rs_offer;
    }
    
    /**
     * @name:全ての人気のエリアを取得 
     * @return record
     */
    public function getAllAreaTagGroup(){
    	$obj = new App_Model_DbTable_AreaTagGroups();
    	return $obj->getAll();
    }
    
    public function getImagesDisplay($shop_id, $r_type){
        $obj = new App_Model_DbTable_ShopImageDisplay();
        return $obj->getAllByShopIdAndType($shop_id, $r_type);
    }
    
    public function _sortData($rs, $url_tag_id){
    	$tmp = array();
    	$tmp1 = array();
    	$tmp2 = array();
    	$tmp3 = array();
    	if ($url_tag_id == 1 || $url_tag_id == 2 || $url_tag_id == 3 || $url_tag_id == 4){
    		foreach ($rs as $k=>$v){
    			if ($v['parent_id']!=$url_tag_id){
    				$tmp2[] = $v;
    			}else{
    				$tmp1[] = $v;
    			}
    		}
    		$tmp = array_merge($tmp1, $tmp2);
    	}else{
    		foreach ($rs as $k=>$v){
    			if ($v['tag_id'] == $url_tag_id){
    				$tmp1[] = $v;
    				$tag_parent_id = $v['parent_id'];
    				unset($rs[$k]);
    				break;
    			}
    		}
    		foreach ($rs as $k=>$v){
    			if ($v['parent_id'] == $tag_parent_id){
    				$tmp2[] = $v;
    			}else{
    				$tmp3[] = $v;
    			}
    		}
    		$tmp = array_merge($tmp1, $tmp2, $tmp3);
    	}
    	return $tmp;
    }
}