<?php

class App_Model_DbTable_Offers extends Choose_Db_Table_Abstract
{
	protected $_name = 'offers';
	
	public function getOffersByRecommend(){
	    $sql = $this->_db->select()
	                     ->from($this->_name, array('offer_id', 'shopname_kana', 'shop_typename', 'offer_subtext', 'shop_id'))
	                     ->where('recommend_flag = ?', 1)
	                     ->where('recommend_start <= ?', date('Y-m-d'))
	                     ->where('recommend_end >= ?', date('Y-m-d'))
	                     ->where('publish_flag = ?', 1)
	                     ->where('publish_start_date <= ?', date('Y-m-d'))
	                     ->where('publish_end_date >= ?', date('Y-m-d'))
	                     ->where('delete_flag = ?', 0);
	    $rs = $this->_db->fetchAll($sql);
	    return $rs;
	}
	
	public function getOfferFullInfo($offer_id){
		$sql = $this->_db->select()
						 ->from(array('offer' => $this->_name), array('offer.offer_id','offer.zip_code','offer.address','offer.tel','offer.fax','offer.offer_title','offer.offer_subtext'))
						 ->joinInner(array('shop' => 'm_shops'), 'shop.shop_id=offer.shop_id', array('shop.shop_id','shop.shopname','shop.shopname_kana',
						 																			 'shop.shop_sub_title','shop.owner_name','shop.owner_position_name',
						 																			 'shop.owner_message','shop.stuff_name','shop.stuff_position_name','shop.stuff_message'))
						 ->where('offer.offer_id =?', $offer_id);
		return $this->_db->fetchRow($sql);
	}
	
	public function getOffer($offer_id){
		$where = array($this->_db->quoteInto('offer_id= ?', $offer_id));
		$rs = $this->fetchRow($where)->toArray();
		return $rs;
	}
	
	public function getByOfferId($offer_id){
	    $sql = $this->_db->select()
	                     ->from($this->_name, array('shop_id', 'gift_amount', 'offer_id'))
	                     ->where('offer_id =?', $offer_id);
	    return $this->_db->fetchRow($sql);
	}
	
	public function getOfferBySearch($params){
		$sql = $this->_db->select()->distinct()
						->from(array('offer' => $this->_name), 'offer.*')
		                ->joinInner(array('shop' => 'm_shops'), 'shop.shop_id=offer.shop_id', array('shop.title', 'shop.subtext', 'shop.shopname', 'shop.shopname_kana'));
		$tags = array();
		
		if (isset($params['line']) && ($params['line']!='')){
			$sql->joinLeft(array('offline' => 'offers_lines'), 'offer.offer_id=offline.offer_id', array());
			$sql->where('offline.line_id =?', $params['line']);
		}
		
		if (isset($params['station']) && ($params['station']!='')){
			$sql->joinLeft(array('offstation' => 'offers_stations'), 'offer.offer_id=offstation.offer_id', array());
			$sql->where('offstation.station_id =?', $params['station']);
		}
		
		if (isset($params['prefecture']) && ($params['prefecture']!='')){
			$sql->where('shop.pref_id =?', $params['prefecture']);
		}
		
		if (isset($params['city']) && ($params['city']!='')){
			$sql->where('shop.city_code =?', $params['city']);
		}
		
		if (isset($params['keyword']) && ($params['keyword']!='')){
			$sql->where("offer.search_text LIKE ?", "%{$params['keyword']}%");
		}
		
		if (isset($params['categories']) && count($params['categories'])){
			$tags = array_keys($params['categories']);
		}
		if (isset($params['features']) && count($params['features'])){
			$tags = array_merge($tags, array_keys($params['features']));
			
		}
		
		if (isset($params['kinds']) && count($params['kinds'])){
			$tags = array_merge($tags, array_keys($params['kinds']));
		}
		
		if (isset($params['salary_fr']) && ($params['salary_fr']!='')){
			$tags[] = (int)$params['salary_fr'];
		}
		if (isset($params['salary_to']) && ($params['salary_to']!='')){
			$tags[] = (int)$params['salary_to'];
		}
		
		if (isset($params['sex']) && ($params['sex']!='')){
			$tags[] = (int)$params['sex'];
		}
		if (isset($params['experience']) && ($params['experience']!='')){
			$tags[] = (int)$params['experience'];
		}
		
		if (count($tags)){
			$sql->joinLeft(array('offtag' => 'offers_tags'), 'offer.offer_id=offtag.offer_id', array());
			$sql->where("offtag.tag_id IN (?)", $tags);
		}
		
		$sql->where('publish_flag = ?', 1);
		$sql->where('publish_start_date <= ?', date('Y-m-d'));
		$sql->where('publish_end_date >= ?', date('Y-m-d'));
		$sql->where('delete_flag = ?', 0);
		$sql->order('registed desc');
		//echo $sql;	
		
		return $this->_db->fetchAll($sql);
	}
	
	public function getAllByShopId($shop_id, $offer_id=''){
	    $sql = $this->_db->select()
	                    ->from($this->_name, array('shop_id', 'offer_id','shopname','shopname_kana'))
	                    ->where('shop_id =?', $shop_id)
	                    ->where('publish_flag = ?', 1)
	                    ->where('publish_start_date <= ?', date('Y-m-d'))
	                    ->where('publish_end_date >= ?', date('Y-m-d'))
	                    ->where('delete_flag = ?', 0);
	    if ($offer_id!='') $sql->where('offer_id != ?', $offer_id);
	    $sql->order('offer_id asc');
	    
	    return $this->_db->fetchAll($sql);
	}
}
