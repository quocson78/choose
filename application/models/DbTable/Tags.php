<?php

class App_Model_DbTable_Tags extends Choose_Db_Table_Abstract
{
    protected $_name = 'tags';

    const LOWER_GROUP = 1;
    const LOWER_CATEGORY = 2;
    const LOWER_TYPE = 3;

	public function getByLowerId($lower_id)
    {
        $select = $this->select()
                    ->where('lower_id = ?', $lower_id)
                    ->order(array('order_no', 'tag_id'));

        return $this->fetchAll($select);
	}

	public function getPairsByLowerId($lower_id, $key = 'tag_id', $name = 'contents')
    {
        $list = null;

        foreach ($this->getByLowerId($lower_id) as $row) {
            $list[$row[$key]] = $row[$name];
        }

        return $list;
    }

	public function getAllByGroup($lower_id)
    {
	    $sql = $this->_db->select()
                ->from(
                    array('tags' => $this->_name),
                    array('tags.contents', 'tags.tag_id'))
                ->joinLeft(
                    array('tagd' => 'tag_display'),
                    'tagd.tag_id = tags.tag_id',
                    array('url', 'parent_id'))
                ->joinInner(
                    array('tagg' => 'm_tag_groups'),
                    'tagg.lower_id = tags.lower_id',
                    array())
                ->where('tagg.lower_id = ?', $lower_id)
                ->order(array('tags.order_no', 'tags.tag_id'));

		$rs = $this->_db->fetchAll($sql);

		return $rs;
	}
	
	public function getByTagId($tag_id){
		$where = array($this->_db->quoteInto('tag_id = ?', $tag_id));
		$rs = $this->fetchRow($where);
		return $rs;
	}
	
	public function getTagsByOfferGroup($offer_id, $group_id){
		$sql = $this->_db->select()
						 ->from(array('tag' => $this->_name), array('contents', 'tag_id', 'tag_name'))
						 ->joinInner(array('offertag' => 'offers_tags'), 'offertag.tag_id=tag.tag_id',array())
						 ->where('offertag.offer_id = ?', $offer_id)
						 ->where('tag.lower_id = ?', $group_id)
						 ->order('tag.tag_id asc');
		return $this->_db->fetchAll($sql);
	}
	
	public function getTagbyTagContent($tag_content){
	    $where = array($this->_db->quoteInto('contents = ?', $tag_content));
	    $rs = $this->fetchRow($where);
	    return $rs;
	}
	
	public function getAllTagsByOfferId($offer_id){
		$sql = $this->_db->select()
						 ->from(array('tag' => $this->_name), array('contents', 'tag_id', 'tag_name'))
						 ->joinInner(array('offertag' => 'offers_tags'), 'offertag.tag_id=tag.tag_id',array())
						 ->where('offertag.offer_id = ?', $offer_id)
						 ->order('tag.tag_id asc');
		return $this->_db->fetchAll($sql);
	}
	
	public function getPointTagByOfferId($offer_id){
	    $sql = $this->_db->select()
                	    ->from(array('tag' => $this->_name), array('contents', 'tag_id'))
                	    ->joinInner(array('offertag' => 'offers_tags'), 'offertag.tag_id=tag.tag_id',array())
                	    ->where('offertag.offer_id = ?', $offer_id)
                	    ->where('offertag.point_flag = ?', 1)
                	    ->order('tag.tag_id asc');
	    return $this->_db->fetchAll($sql);
	}
	
	public function getTagsByTagsId($tag_id_arr){
	    if (is_array($tag_id_arr) && count($tag_id_arr)){
    	    $sql = $this->_db->select()
                    	    ->from(array('tag' => $this->_name), array('contents', 'tag_id', 'tag_name'))
                    	    ->where('tag.tag_id IN (?)', $tag_id_arr)
                    	    ->order('tag.tag_id asc');
    	    return $this->_db->fetchAll($sql);
	    }else{
	        return null;
	    }
	}
}