<?php

class App_Model_DbTable_MShops extends Choose_Db_Table_Abstract
{
	protected $_name = 'm_shops';
	
	public function getByShopId($shop_id)
    {
		$where = $this->_db->quoteInto('shop_id = ?', $shop_id);
        
		return $this->fetchRow($where)->toArray();
	}

	public function getShopByShopId($shop_id){
	    $sql = "SELECT pref_id, city_code FROM m_shops WHERE shop_id =".$shop_id;
	    return $this->_db->fetchRow($sql);
	}
    /**
     * クロール済み対象取得
     *
     * @param string $url 公式URL
     * @return int intならクロール対象/０は新規、非対象はfalse
     */
	public function checkClawlShop($url)
    {
		$where = $this->_db->quoteInto('official_url = ?', $url);

        $row = $this->fetchRow($where);

        if (!$row) {
            return 0;
        }

        return $row['crawl_flag'] ? $row['shop_id'] : false ;
    }
}