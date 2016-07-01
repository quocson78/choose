<?php

class App_Model_DbTable_OffersTags extends Choose_Db_Table_Abstract
{
	protected $_name = 'offers_tags';
	
	public function getTagsByOffer($offer_id, $lower_id){
	    $sql = $this->_db->select()
	                     ->from(array('offtag' => $this->_name), array())
	                     ->joinInner(array('tag' => 'tags'), 'tag.tag_id = offtag.tag_id', array('contents', 'tag_id'))
	                     ->where('offtag.offer_id = ?', $offer_id)
	                     ->where('tag.lower_id = ?', $lower_id);
	    $rs = $this->_db->fetchAll($sql);
	    return $rs;
	}
	
	public function getOfferByTagId($tag_id){
		$where = $this->_db->quoteInto('tag_id = ?', $tag_id);
		return $this->fetchAll($where)->toArray();
	}
	
	public function getAllByOfferId($offer_id){
	    $sql = $this->_db->select()
	                     ->from(array('offtag' => $this->_name), 'tag_id')
	                     ->joinInner(array('tag' => 'tags'), 'offtag.tag_id=tag.tag_id', 'contents')
	                     ->where('offtag.offer_id =?', $offer_id)
	                     ->order('offtag.tag_id asc');
	    
	    return $this->_db->fetchAll($sql);
	}
}