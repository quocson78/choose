<?php

class App_Model_DbTable_TagDisplay extends Choose_Db_Table_Abstract
{
	protected $_name = 'tag_display';

	public function getByUrl($url){
		$sql = $this->_db->select()
                		->from(array('tagd' => $this->_name), array('tagd.url', 'tagd.tag_id', 'tagd.parent_id'))
                		->joinInner('tags', 'tagd.tag_id=tags.tag_id', 'tags.contents')
                		->where('tagd.url = ?', $url);
        $rs = $this->_db->fetchRow($sql);
		return $rs;
	}
	

	public function getChildren($tag_id){
	    
		$rs = $this->_db->fetchCol(
				$this->_db->select()
        				->from($this->_name, 'tag_id')
        				->where('parent_id = ?', $tag_id)
		);
	    if ($rs != null){
    		$sql = $this->_db->select()
                    		->from(array('tagd' => $this->_name), array('tagd.tag_id', 'tagd.url'))
                    		->joinLeft('tags', 'tagd.tag_id=tags.tag_id', 'contents')
                    		->where('tagd.tag_id IN (?)', $rs)
                    		->order('tagd.order ASC');
    		$rs1 = $this->_db->fetchAll($sql);
    		return $rs1;
	    }
	    return null;
	}
}