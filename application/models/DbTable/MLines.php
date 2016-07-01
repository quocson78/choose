<?php
class App_Model_DbTable_MLines extends Choose_Db_Table_Abstract {

	protected $_name = 'm_lines';
	
	/**
	 * 都道府県のIDですべての線を取得
	 * 
	 * @param int $pref_id
	 * @return recordset
	 */
	public function getAllByPref($pref_id){
		$sql = $this->_db->select()->distinct()
		                            ->from(array('line' =>$this->_name), array('line_id', 'name', 'rome'))
		                            ->joinInner(array('sta' => 'm_stations'), 'line.line_id=sta.line_id', array())
                            		->joinInner(array('pref'=>'m_prefs'), 'sta.pref_id=pref.pref_id', array())
                            		->where('pref.pref_id = ?', $pref_id)
                            		->order('line.order_no ASC');
		$rs = $this->_db->fetchAll($sql);
		return $rs;
	}
	
	/**
	 * 都道府県名ですべての線を取得
	 * 
	 * @param string $pref_name
	 * @return recordset
	 */
	public function getAllByPrefRoma($pref_roma){
		$sql = $this->_db->select()->distinct()
									->from(array('line' =>$this->_name), array('line_id', 'name', 'rome'))
									->joinInner(array('sta' => 'm_stations'), 'line.line_id=sta.line_id', array())
									->joinInner(array('pref'=>'m_prefs'), 'sta.pref_id=pref.pref_id', array())
									->where('pref.name_rome = ?', $pref_roma)
									->order('line.order_no ASC');
		$rs = $this->_db->fetchAll($sql);
		return $rs;
	}
	
	public function getAllByPrefName($pref_name){
	    $sql = $this->_db->select()->distinct()
                	    ->from(array('line' =>$this->_name), array('line_id', 'name', 'rome'))
                	    ->joinInner(array('sta' => 'm_stations'), 'line.line_id=sta.line_id', array())
                	    ->joinInner(array('pref'=>'m_prefs'), 'sta.pref_id=pref.pref_id', array())
                	    ->where('pref.name = ?', $pref_name)
                	    ->order('line.order_no ASC');
	    $rs = $this->_db->fetchAll($sql);
	    return $rs;
	}
	
	public function getByLineId($line_id){
		$where = $this->_db->quoteInto('line_id = ?', $line_id);
		return $this->fetchRow($where);
	}
	
	public function getByLineRome($line_rome){
		$where = $this->_db->quoteInto('rome = ?', $line_rome);
		return $this->fetchRow($where);
	}
	
	public function getByLineName($line_name){
	    $where = $this->_db->quoteInto('name = ?', $line_name);
	    return $this->fetchRow($where);
	}
	
	public function getAllLinesByLineId($line_id){
		$sql = $this->_db->select()
						 ->from('m_stations','pref_id')
						 ->where('line_id=?', $line_id);
		$pref_id = $this->_db->fetchOne($sql);
		return $this->getAllByPref($pref_id);
	}
	
	public function getAllByOfferId($offer_id){
		$sql = $this->_db->select()->distinct()
									->from(array('line' =>$this->_name), array('line_id', 'name', 'rome'))
									->joinInner(array('offline' => 'offers_lines'), 'offline.line_id=line.line_id', array())
									->where('offline.offer_id = ?', $offer_id)
									->order('line.order_no ASC');
		$rs = $this->_db->fetchAll($sql);
		return $rs;
	}
	
    /**
     * 住所から該当する市区町村を探す
     *
     * @param string $address 住所
     * @return array
     */
    public function getByAddress($address)
    {
        $where = array(
            $this->_db->quoteInto('LENGTH(`town_name`)'),
            $this->_db->quoteInto('? LIKE CONCAT(`pref_name`, `city_name`, `town_name`, "%")', $address)
        );

        $rs = $this->fetchRow($where);

        if ($rs) {
            return $rs->toArray();
        }

        $where = array(
            $this->_db->quoteInto('? LIKE CONCAT(`pref_name`, `city_name`, "%")', $address)
        );

        $rs = $this->fetchRow($where);

        if ($rs) {
            return $rs->toArray();
        }

        return null;
    }
}
