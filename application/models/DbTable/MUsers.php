<?php

class App_Model_DbTable_MUsers extends Zend_Db_Table_Abstract
{

    protected $_name = 'm_users';

    /**
     * 
     * @param string $userId
     * @return array userRow
     */
    public function getRowPreUser($userId) {
        
        $sql = "
            SELECT 
        	user_id,
            first_name,
            last_name,
            first_name_kana,
            last_name_kana,
            password,
            email_address
            FROM m_users
            WHERE user_id = ?
            AND is_leaved = 0";
        $row = $this->_db->fetchRow($sql, array($userId));
        return $row;
    }


    /**
     * @param  array $userInfo
     * @return query result
     */
    public function savePreUser($userInfo) {

        
        $current_datetime = date('Y-m-d H:i:s');
        $insertData = array(
            'first_name'     => $userInfo['first_name'],
            'last_name'      => $userInfo['last_name'],
            'email_address'  => $userInfo['email_address'],
            'is_preopen'     => 1,
            'pre_registed'   => $current_datetime,
            'registed'       => $current_datetime,
            'updated'        => $current_datetime,
        );
        // insertDuplication
        return $this->insert($insertData);
    }

    public function hasEmailAddress($emailAddress) {
        $sql = "
            SELECT count(user_id) 
            FROM m_users
            WHERE email_address = ?
            AND is_leaved = 0";
        return $this->_db->fetchOne($sql, array($emailAddress));
    }
    
    public function hasEmailOtherUser($emailAddress, $user_id) {
        $sql = "
            SELECT count(user_id)
            FROM m_users
            WHERE email_address = ?
            AND is_leaved = 0 AND user_id!= ?";
        return $this->_db->fetchOne($sql, array($emailAddress, $user_id));
    }
    
    public function activeUser($user_id){
    	$current_datetime = date('Y-m-d H:i:s');
    	$pwd = Choose_Lib::getRandomStr(6);
    	$data = array(
    				'password' => sha1($pwd),
    				'is_active' => 1,
    				'activated' => $current_datetime
    			);
    	$where = $this->_db->quoteInto('user_id = ?', $user_id);
    	if ($this->update($data, $where)){
    		return $pwd;
    	}else{
    		return null;
    	}
    }
    
    public function activeUserRegist($user_id){
        $current_datetime = date('Y-m-d H:i:s');
        $data = array(
                'is_active' => 1,
                'activated' => $current_datetime
        );
        $where = $this->_db->quoteInto('user_id = ?', $user_id);
        return $this->update($data, $where);
    }
    
    /**
     * ユーザーの新規登録
     * array $user_data: ユーザー情報
     */
    public function registUser($user_data, $flag_apply=0){
        $current_datetime = date('Y-m-d H:i:s');
        $insertData = array(
                'first_name'        => $user_data['first_name'],
                'last_name'         => $user_data['last_name'],
                'first_name_kana'   => $user_data['first_name_kana'],
                'last_name_kana'    => $user_data['last_name_kana'],
                'birth_day'         => $user_data['birth_day_yy'].'-'.sprintf('%02d', $user_data['birth_day_mm']).'-'.sprintf('%02d', $user_data['birth_day_dd']),
                'zip_code'          => $user_data['zip_code1'].'-'.$user_data['zip_code2'],
                'pref_id'           => $user_data['pref_id'],
                'city'              => $user_data['city'],
                'address'           => $user_data['address'],
                'tel'               => $user_data['tel'],
                'email_address'     => $user_data['email_address'],
                'password'          => sha1($user_data['password']),
                'self_pr'           => $user_data['self_pr'],
                'skill_etc'         => $user_data['skill_etc'],
                
                'pre_registed'      => $current_datetime,
                'registed'          => $current_datetime,
                'updated'           => $current_datetime,
         );
        if ($flag_apply == 1) {
            $insertData['is_active'] = 1;
            $insertData['activated'] = $current_datetime;
        }
        return $this->insert($insertData);
    }
    
    /**
     * ユーザーの登録を更新
     * array $user_data: ユーザー情報
     */
    public function updateUser($user_data){
        $current_datetime = date('Y-m-d H:i:s');
        $where = $this->_db->quoteInto('user_id = ?', $user_data['user_id']);
        $updateData = array(
                'first_name'        => $user_data['first_name'],
                'last_name'         => $user_data['last_name'],
                'first_name_kana'   => $user_data['first_name_kana'],
                'last_name_kana'    => $user_data['last_name_kana'],
                'birth_day'         => $user_data['birth_day_yy'].'-'.sprintf('%02d', $user_data['birth_day_mm']).'-'.sprintf('%02d', $user_data['birth_day_dd']),
                'zip_code'          => $user_data['zip_code1'].'-'.$user_data['zip_code2'],
                'pref_id'           => $user_data['pref_id'],
                'city'              => $user_data['city'],
                'address'           => $user_data['address'],
                'tel'               => $user_data['tel'],
                'email_address'     => $user_data['email_address'],
                'self_pr'           => $user_data['self_pr'],
                'skill_etc'         => $user_data['skill_etc'],
    
                'updated'           => $current_datetime,
        );
        if ($user_data['password']!='') $updateData['password'] = sha1($user_data['password']);
        return $this->update($updateData, $where);
    }
    
    public function getByUserId($user_id){
        $where = $this->_db->quoteInto('user_id =?', $user_id);
        return $this->fetchRow($where)->toArray();
    }
    
    public function leaveUser($user_id){
        $current_datetime = date('Y-m-d H:i:s');
        $sql = "UPDATE m_users SET is_leaved=1, leaved='.$current_datetime.' WHERE user_id =".$user_id;
        $this->_db->query($sql);
    }
}

