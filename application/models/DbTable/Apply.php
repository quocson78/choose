<?php
class App_Model_DbTable_Apply extends Zend_Db_Table_Abstract
{

    protected $_name = 'apply';
    
    public function insertData($data){
        $current_datetime = date('Y-m-d H:i:s');
        $data_arr = array(
                'offer_id'       => $data['offer_id'],
                'shop_id'        => $data['shop_id'],
                'user_id'        => $data['user_id'],
                'apply_status'   => 1,
                'registed'       => $current_datetime,
                'updated_id'     => $data['user_id']
                );
        return $this->insert($data_arr);
    }
}