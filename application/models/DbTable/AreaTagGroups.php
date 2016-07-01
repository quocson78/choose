<?php

class App_Model_DbTable_AreaTagGroups extends Choose_Db_Table_Abstract {

	protected $_name = 'area_tag_groups';
	
	public function getAll(){
		$sql = $this->select()
					->from($this->_name, array('group_id', 'name', 'url_name'))
					->order('group_id ASC');
		return $this->fetchAll($sql)->toArray();
	}
}