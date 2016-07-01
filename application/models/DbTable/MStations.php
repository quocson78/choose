<?php
class App_Model_DbTable_MStations extends Choose_Db_Table_Abstract {

	protected $_name = 'm_stations';
	
	public function getAllByPref($pref_id){
	    $sql = $this->_db->select()
	                     ->from(array('station' => $this->_name), array('station_name', 'station_id'))
	                     ->where('pref_id = ?', $pref_id);
	    $rs = $this->_db->fetchAll($sql);
	    return $rs;
	}
	
	public function getAllByLine($line_id){
		$sql = $this->_db->select()
                		->from(array('station' => $this->_name), array('station_name', 'station_id'))
                		->where('line_id = ?', $line_id);
		$rs = $this->_db->fetchAll($sql);
		return $rs;
	}
	
	public function getAllByOffer($offer_id){
		$sql = $this->_db->select()
						->from(array('station' => $this->_name), array('station_name', 'station_id'))
						->joinInner(array('offstation' => 'offers_stations'), 'offstation.station_id=station.station_id', array())
						->where('offstation.offer_id = ?', $offer_id);
		$rs = $this->_db->fetchAll($sql);
		return $rs;
	}
	
	public function getByStationId($station_id){
		$sql = $this->_db->select()
						->from($this->_name, array('station_id', 'station_name', 'line_id'))
						->where('station_id = ?', $station_id);
		return $this->_db->fetchRow($sql);
	}
}