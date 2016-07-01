<?php

class App_Model_DbTable_ShopImages extends Choose_Db_Table_Abstract
{
	protected $_name = 'shop_images';
	
	public function getFileName($shop_id, $image_id)
    {
		$sql = $this->_db->select()
						 ->from($this->_name,'image_filename')
						 ->where('shop_id = ?', $shop_id)
						 ->where('image_id = ?', $image_id);
		return $this->_db->fetchOne($sql);
	}

	public function get($shop_id, $image_id)
    {
		$sql = $this->select()
                    ->where('shop_id = ?', $shop_id)
                    ->where('image_id = ?', $image_id);

		return $this->_db->fetchRow($sql);
	}
}
