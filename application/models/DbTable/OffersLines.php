<?php
class App_Model_DbTable_OffersLines extends Choose_Db_Table_Abstract
{
	protected $_name = 'offers_lines';

	public function getOfferByLineId($line_id){
	    $where = $this->_db->quoteInto('line_id = ?', $line_id);
	    return $this->fetchAll($where)->toArray();
	}
}
