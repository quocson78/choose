<?php

class App_Model_PreUser 
{
    /**
     * 
     * @param integer $userId
     * @return query result
     */
    public function getUserById($userId) {
        $table_object = new App_Model_DbTable_MUsers();
        return $table_object->getRowPreUser($userId);
    }

    /**
     * @param  string $emailAddress
     * @return query result
     */
    public function isExistEmailAddress($emailAddress) {
        $table_object = new App_Model_DbTable_MUsers();
        return $table_object->hasEmailAddress($emailAddress);
    }

    /**
     * 
     * @param array $userInfo
     * @return query result
     */
    public function savePreUser($userInfo) {
        $table_object = new App_Model_DbTable_MUsers();
        $userInfo['user_id'] = $table_object->savePreUser($userInfo); 

        $table_object = new App_Model_DbTable_PreUserHash();
        return $table_object->save($userInfo);
    }
    
    /**
     * @param string $hash
     * @return query result
     */
    public function activeUser($hash){
    	$table_preUserHash = new App_Model_DbTable_PreUserHash();
    	$rs_data = $table_preUserHash->getRowByHash($hash);
    	
    	if ($rs_data){
	    	$table_object = new App_Model_DbTable_MUsers();
	    	$pwd = $table_object->activeUser($rs_data['user_id']); 
	    	if ($pwd != null){
	    		$table_preUserHash->deleteHash($hash);
	    		$rs_data['pwd'] = $pwd;
	    		return $rs_data;
    		}
    	}
    	return null;
    }
    
    public function deleteHash($hash){
    	$table_object = new App_Model_DbTable_PreUserHash();
    	$table_object->deleteHash($hash);
    }
    
    public function getByHash($hash){
    	$table_object = new App_Model_DbTable_PreUserHash();
    	$rs_data = $table_object->getRowByHash($hash);
    	return $rs_data;
    }
}