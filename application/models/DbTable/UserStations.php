
<?php
class App_Model_DbTable_UserStations extends Choose_Db_Table_Abstract
{
	protected $_name = 'user_stations';

	public function getByUserId($user_id){
		$where = $this->_db->quoteInto('user_id = ?', $user_id);
		return $this->fetchRow($where)->toArray();
	}
	
	public function insertData($data){
	    $where = $this->_db->quoteInto('user_id = ?', $data['user_id']);
	    $this->delete($where);
	    $data = array(
	            'user_id' => $data['user_id'],
	            'station_id' => $data['station_id'],
	            'registed' => date('Y-m-d H:i:s')
	    );
	    $this->insert($data);
	}
}