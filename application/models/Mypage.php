<?php
class App_Model_Mypage {
	public function getGyousu(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(2);
	}
	
	public function getAllPrefs(){
		$obj_pref = new App_Model_DbTable_MPrefs();
		return $obj_pref->getAll();
	}
	
	public function getPrefByPrefId($pref_id){
	    $obj_pref = new App_Model_DbTable_MPrefs();
	    return $obj_pref->getByPrefId($pref_id);
	}
	
	public function getCityByCityCode($city_code){
	    $obj_city = new App_Model_DbTable_MCities();
	    return $obj_city->getByCityCode($city_code);
	}
	
	public function getTagsByTagsId($tag_id_arr){
	    $obj_tag = new App_Model_DbTable_Tags();
	    return $obj_tag->getTagsByTagsId($tag_id_arr);
	}
	
	public function getTagByTagId($tag_id){
	    $obj_tag = new App_Model_DbTable_Tags();
	    return $obj_tag->getByTagId($tag_id);
	}
	
	// 勤務形態を取得
	public function getSex(){
	    $obj_tag = new App_Model_DbTable_Tags();
	    return $obj_tag->getAllByGroup(6);
	}
	
	// 勤務形態を取得
	public function getKinds(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(4);
	}
	
	// 開始時間を取得
	public function getStartWork(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(8);
	}
	
	// 連絡可能時間を取得
	public function getCallTime(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(20);
	}
	
	// 資格を取得
	public function getShikaku(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(7);
	}
	
	// 経験を取得
	public function getKeiken(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(19);
	}
	
	// 店長経験を取得
	public function getTenchoKeiken(){
	    $obj_tag = new App_Model_DbTable_Tags();
	    return $obj_tag->getAllByGroup(21);
	}
	
	public function getCitiesByPrefId($pref_id){
	    $obj = new App_Model_DbTable_MCities();
	    return $obj->getAllByPrefId($pref_id);
	}
	
	public function getCitiesByZipcode($zip_code){
	    $obj = new App_Model_DbTable_MCities();
	    return $obj->getAllCitiesByZipCode($zip_code);
	}
	
	public function isExistEmailAddress($emailAddress) {
	    $table_object = new App_Model_DbTable_MUsers();
	    return $table_object->hasEmailAddress($emailAddress);
	}
	
	public function isExistEmailOtherUser($emailAddress, $user_id) {
	    $table_object = new App_Model_DbTable_MUsers();
	    return $table_object->hasEmailOtherUser($emailAddress, $user_id);
	}
	
	/**
	 * m_usersテーブルにデータを保存
	 * @param aray $user_data
	 * @return user_id
	 */
	public function saveUser($user_data, $flag_apply=0){
	    $table_object = new App_Model_DbTable_MUsers();
	    return $table_object->registUser($user_data, $flag_apply);
	}
	
	public function updateUser($user_data){
	    $table_object = new App_Model_DbTable_MUsers();
	    return $table_object->updateUser($user_data);
	}
	
	/**
	 * user_tagsテーブルにデータを保存
	 * @param aray $user_data
	 */
	public function saveUserTags($user_id, $tags_array){
	    $table_object = new App_Model_DbTable_UserTags();
	    $table_object->insertTags($user_id, $tags_array);
	}
	
	public function getUserByUserId($user_id){
	    $table_object = new App_Model_DbTable_MUsers();
	    return $table_object->getByUserId($user_id);
	}
	
	public function getTagsByUserId($user_id){
	    $table_object = new App_Model_DbTable_UserTags();
	    return $table_object->getAllByUserId($user_id);
	}
	
	public function saveHashUser($userInfo){
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
	        $table_object->activeUserRegist($rs_data['user_id']);
	        $table_preUserHash->deleteHash($hash);
	        return $rs_data;
	    }else{
	        return null;
	    }
	}
	
	public function getByHash($hash){
	    $table_object = new App_Model_DbTable_PreUserHash();
	    $rs_data = $table_object->getRowByHash($hash);
	    return $rs_data;
	}
	
	public function getTagIdByUserIdGroupId($user_id, $group_id){
	    $table_object = new App_Model_DbTable_UserTags();
	    return $table_object->getTagIdByUserIdGroupId($user_id, $group_id);
	}
	
	public function getAllStationsByLineId($line_id){
	    $obj = new App_Model_DbTable_MStations();
	    return $obj->getAllByLine($line_id);
	}
	
	public function getLinesByPrefId($pref_id){
	    $obj_line = new App_Model_DbTable_MLines();
	    return $obj_line->getAllByPref($pref_id);
	}
	
	public function saveUserLine($data){
	    $obj = new App_Model_DbTable_UserLines();
	    $obj->insertData($data);
	}
	
	public function saveUserStation($data){
	    $obj = new App_Model_DbTable_UserStations();
	    $obj->insertData($data);
	}
	
	public function getLineByLineId($line_id){
	    $obj = new App_Model_DbTable_MLines();
	    return $obj->getByLineId($line_id);
	}
	
	public function getAllLinesByLineId($line_id){
	    $obj = new App_Model_DbTable_MLines();
	    return $obj->getByLineId($line_id);
	}
	
	public function getStationByStationId($station_id){
	    $obj = new App_Model_DbTable_MStations();
	    return $obj->getByStationId($station_id);
	}
	
	public function getUserLine($user_id){
	    $obj = new App_Model_DbTable_UserLines();
	    return $obj->getByUserId($user_id);
	}
	
	public function getUserStation($user_id){
	    $obj = new App_Model_DbTable_UserStations();
	    return $obj->getByUserId($user_id);
	}
	
	public function getOfferByOfferId($offer_id){
	    $obj = new App_Model_DbTable_Offers();
	    return $obj->getByOfferId($offer_id);
	}
	
	public function saveApply($data){
	    $obj = new App_Model_DbTable_Apply();
	    return $obj->insertData($data);
	}
	
	public function leaveUser($user_id){
	    $obj = new App_Model_DbTable_MUsers();
	    $obj->leaveUser($user_id);
	}
}