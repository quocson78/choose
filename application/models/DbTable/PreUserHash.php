<?php

class App_Model_DbTable_PreUserHash extends Zend_Db_Table_Abstract
{

    protected $_name = 'pre_user_hash';

    /**
     * 
     * @param string $userId
     * @return query result
     */
    public function getRow($userId) {
        
        $sql = "
            SELECT hash,
            pre_registed,
            period
            FROM {$this->_name}
            WHERE user_id = ?
            ";
        $row = $this->_db->fetchRow($sql, array($userId));
        return $row;
    }

    /**
     * @param  string $userInfo
     * @return either registed hash or FALSE
     */
    public function save($userInfo) {
        
        $current_datetime = date('Y-m-d H:i:s');
        $hash             = sha1($userInfo['user_id'].time());
        $insertData = array(
            'user_id'        => $userInfo['user_id'],
            'hash'           => $hash,
            'pre_registed'   => $current_datetime,
            'period'         => 0,
        );
        // insertDuplication
        $userInfo['hash'] = $hash;
        $result = $this->insert($insertData); 
        return $result?$userInfo:FALSE;
    }
    
    public function getRowByHash($hash) {
    
    	$sql = "
	    	SELECT hash,
	    	user_id,
	    	pre_registed,
	    	period
	    	FROM {$this->_name}
	    	WHERE hash = ?
    	";
    	$row = $this->_db->fetchRow($sql, array($hash));
    	return $row;
    }
    
    public function deleteHash($hash){
    	$where = $this->_db->quoteInto('hash = ?', $hash);
    	$this->delete($where);
    }
}
