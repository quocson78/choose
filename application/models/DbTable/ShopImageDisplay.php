<?php
class App_Model_DbTable_ShopImageDisplay extends Choose_Db_Table_Abstract
{
	protected $_name = 'shop_image_display';
	
	public function getAllByShopIdAndType($shop_id, $r_type){
		$sql = $this->_db->select()
						 ->from(array('shopimgdis' => $this->_name), array())
						 ->joinInner(array('shopimg' => 'shop_images'), 'shopimg.shop_id=shopimgdis.shop_id and shopimg.image_id=shopimgdis.image_id', array('shopimg.image_filename', 'shopimg.image_title','shopimg.image_subtext'))
						 ->where('shopimgdis.shop_id = ?', $shop_id)
						 ->where('shopimgdis.r_type = ?', $r_type)
						 ->order('shopimgdis.order_no asc');
		return $this->_db->fetchAll($sql);
	}
}