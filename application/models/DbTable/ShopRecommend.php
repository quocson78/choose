<?php
class App_Model_DbTable_ShopRecommend extends Choose_Db_Table_Abstract
{
	protected $_name = 'shop_recommend';
	
	public function getAllByShopId($shop_id){
		$sql = $this->_db->select()
						 ->from($this->_name)
                         ->where('shop_id = ?', $shop_id)
						 ->order('recommend_id asc');
		return $this->_db->fetchAll($sql);
	}
}