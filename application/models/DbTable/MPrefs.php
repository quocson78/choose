<?php

class App_Model_DbTable_MPrefs extends Choose_Db_Table_Abstract
{
	protected $_name = 'm_prefs';

	public function getAll()
    {
	    $rs = $this->fetchAll(null, array('pref_id ASC'))->toArray();

		return $rs;
	}

	public function getPairs($key = 'pref_id', $name = 'name')
    {
        $list = null;

        foreach ($this->getAll() as $row) {
            $list[$row[$key]] = $row[$name];
        }

        return $list;
    }

	public function getAllByRegion($region_id)
    {
	    $where = array($this->_db->quoteInto('region_id = ?', $region_id));
	    $rs = $this->fetchAll($where, array('pref_id ASC'))->toArray();

		return $rs;
	}
	
	public function getByNameRome($name_rome)
    {
		$where = array($this->_db->quoteInto('name_rome = ?', $name_rome));
		$rs = $this->fetchRow($where);

		return $rs;
	}
	
	public function getByName($name)
	{
	    $where = array($this->_db->quoteInto('name = ?', $name));
	    $rs = $this->fetchRow($where);
	
	    return $rs;
	}
	
	public function getByPrefId($pref_id)
    {
		$where = array($this->_db->quoteInto('pref_id = ?', $pref_id));
		$rs = $this->fetchRow($where);

		return $rs;
	}
	
	public function getPrefCityByShopId($shop_id){
	    $sql = $this->_db->select()
	                     ->from(array('pref' => $this->_name),array('pref.name','pref.name_rome'))
	                     ->joinInner(array('city' => 'm_cities'), 'city.pref_id=pref.pref_id', array('city.city_code','city.city_name'))
	                     ->joinInner(array('shop' => 'm_shops'), 'city.zip_code=shop.zip_code', array())
	                     ->where('shop.shop_id = ?', $shop_id);
	    
	    return $this->_db->fetchRow($sql);
	}
	
	public function getPrefCityByOfferId($offer_id){
	    $sql = $this->_db->select()
                	    ->from(array('pref' => $this->_name),array('pref.name','pref.name_rome'))
                	    ->joinInner(array('city' => 'm_cities'), 'city.pref_id=pref.pref_id', array('city.city_code','city.city_name'))
                	    ->joinInner(array('offer' => 'offers'), 'city.zip_code=offer.zip_code', array())
                	    ->where('offer.offer_id = ?', $offer_id);
	     
	    return $this->_db->fetchRow($sql);
	}
}
