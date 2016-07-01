<?php
class App_Model_DbTable_MCities extends Choose_Db_Table_Abstract {

    protected $_name = 'm_cities';

    public function getAllByPrefId($pref_id)
    {
    	$sql = $this->_db->select()->distinct()
                    ->from(
                        $this->_name,
                        array('city_code', 'city_name', 'pref_id'))
                    ->where('pref_id =? ', $pref_id)
                    ->order('city_code asc');

    	return $this->_db->fetchAll($sql);
    }

    public function getPairsByPrefId($pref_id, $key = 'city_code', $name = 'city_name')
    {
        $list = null;

        foreach ($this->getAllByPrefId($pref_id) as $row) {
            $list[$row[$key]] = $row[$name];
        }

        return $list;
    }

    public function getAllByPrefRoma($pref_name_roma)
    {
    	$sql = $this->_db->select()->distinct()
                    ->from(
                        array('city'=>$this->_name),
                        array('city.pref_id', 'city.city_name', 'city.city_code'))
                    ->joinInner(
                        array('pref'=>'m_prefs'),
                        'pref.pref_id=city.pref_id',
                        array())
                    ->where('pref.name_rome = ?', $pref_name_roma)
                    ->order('city.city_code asc');

    	$rs = $this->_db->fetchAll($sql);

    	return $rs;
    }
    
    public function getAllByPrefName($pref_name)
    {
        $sql = $this->_db->select()->distinct()
                ->from(
                array('city'=>$this->_name),
                array('city.pref_id', 'city.city_name', 'city.city_code'))
                ->joinInner(
                        array('pref'=>'m_prefs'),
                        'pref.pref_id=city.pref_id',
                        array())
                        ->where('pref.name = ?', $pref_name)
                        ->order('city.city_code asc');
        $rs = $this->_db->fetchAll($sql);
    
        return $rs;
    }

    public function getByCityCode($city_code)
    {
    	$sql = $this->_db->select()
                    ->from($this->_name, array('city_code', 'city_name', 'pref_id'))
                    ->where('city_code = ?', $city_code);
    	$rs = $this->_db->fetchRow($sql);

    	return $rs;
    }

    public function getByZipCode($zip_code)
    {
    	$sql = $this->_db->select()
                    ->from(
                        array('address' => $this->_name),
                        array(
                            'address.town_name',
                            'address.city_name',
                            'address.pref_name',
                            'address.city_code'))
                    ->joinInner(
                            array('prefs' => 'm_prefs'),
                            'prefs.pref_id=address.pref_id',
                            'prefs.name_rome')
                    ->where('address.zip_code = ?', $zip_code);
    	$rs = $this->_db->fetchRow($sql);

    	return $rs;
    }
    
    public function getAllCitiesByZipCode($zip_code)
    {
        $sql = $this->_db->select()
                         ->from($this->_name, array('pref_id','city_code'))
                         ->where('zip_code= ?', $zip_code);
        $pref_city = $this->_db->fetchRow($sql);
        if ($pref_city){
            $sql = $this->_db->select()->distinct()
                            ->from($this->_name, array('city_name', 'city_code', 'pref_id'))
                            ->where('pref_id = ?', $pref_city['pref_id']);
            $rs = $this->_db->fetchAll($sql);
            foreach($rs as $k=>$v){
                $rs[$k]['citycode'] = $pref_city['city_code'];
            }
        }else{
            $rs = array(array('citycode'=>'', 'pref_id'=>'', 'city_name'=>'', 'city_code'=>''));
        }
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
