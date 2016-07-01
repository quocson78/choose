<?php
class App_Model_DbTable_MRegions extends Choose_Db_Table_Abstract {

	protected $_name = 'm_regions';
	
	public function getAll(){
	    $rs = $this->fetchAll()->toArray();
	    return $rs;
	}
}
