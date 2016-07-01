
<?php
class App_Model_DbTable_OffersStations extends Choose_Db_Table_Abstract
{
	protected $_name = 'offers_stations';

	public function getOfferByStationId($station_id){
		$where = $this->_db->quoteInto('station_id = ?', $station_id);
		return $this->fetchAll($where)->toArray();
	}
}