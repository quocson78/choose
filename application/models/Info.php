<?php
class App_Model_Info {
	public function getGyousu(){
		$obj_tag = new App_Model_DbTable_Tags();
		return $obj_tag->getAllByGroup(2);
	}
	
	public function getAllPrefs(){
		$obj_pref = new App_Model_DbTable_MPrefs();
		return $obj_pref->getAll();
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
}