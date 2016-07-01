<?php


/**
 * 
 */
class Default_MyPageController extends Choose_Controller_Action_Default {

    private $obj;
    private $_errMsg = array(
            'email_address' => '',
            'name' => ''
    );
    public function init(){
        $this->obj = new App_Model_Mypage();
        
        parent::init();
    }
   
    public function indexAction() {
        
        $authStorage = Zend_Auth::getInstance()->getStorage();
        if ($authStorage->isEmpty()) {
            $this->_redirect('/login');
        }
        //$this->authInfo = (array)$authStorage->read();
         
        /*
        $auth = Choose_Auth::getInstance();
        if (!$auth->hasIdentity()){
            return $this->_redirect('/login');
        }*/
    }
  
    public function registOkAction() {
		$hash_id = $this->getRequest()->getParam('pre');
		$obj = new App_Model_PreUser();
		
		if ($obj->getByHash($hash_id) != null){
			$rs_active = $obj->activeUser($hash_id);
			$mail = new Choose_Mail();
			$rs_user = $obj->getUserById($rs_active['user_id']);
			$param = array(
							'name' => $rs_user['first_name'].$rs_user['last_name'],
							'pwd' => $rs_active['pwd']
						);
			$mail->sendUser('ok_regist', $rs_user['user_id'], $param);
		}else{
			$this->forward('pre-regist-ok');
		}
	}
	
	public function userRegistOkAction() {
	    $hash_id = $this->getRequest()->getParam('pre');
	
	    if ($this->obj->getByHash($hash_id) != null){
	        $rs_active = $this->obj->activeUser($hash_id);
	        $mail = new Choose_Mail();
	        $rs_user = $this->obj->getUserByUserId($rs_active['user_id']);
	        $param = array(
	                'name' => $rs_user['first_name'].$rs_user['last_name']
	        );
	        $mail->sendUser('user_regist_ok', $rs_user['user_id'], $param);
	    }else{
	        $this->forward('pre-user-regist-ok');
	    }
	}

    /**
     * 会員登録フォーム
     */
	public function registerAction(){
	    
	    if ($this->getRequest()->isPost()){
	        $postData = $this->getRequest()->getParams();
	        $postData['work_type'] = array_flip(unserialize($postData['work_type']));
	        $postData['career'] = array_flip(unserialize($postData['career']));
	        $postData['start_work'] = array_flip(unserialize($postData['start_work']));
	        $postData['skill'] = array_flip(unserialize($postData['skill']));
	        $postData['tel_time'] = array_flip(unserialize($postData['tel_time']));
	        
            $cities = $this->obj->getCitiesByPrefId($postData['pref_id']);
            $this->view->assign('cities', $cities);
            
            $lines = $this->obj->getLinesByPrefId($postData['pref_id']);
            $this->view->assign('lines', $lines);
	            
            $stations = $this->obj->getAllStationsByLineId($postData['lines']);
            $this->view->assign('stations', $stations);
	        
	        $this->view->assign('input', $postData);
	    }
		$this->_setDataFormRegist();
	}
	
	/**
	 * 会員情報を更新
	 */
	public function userEditAction(){
	    
	    $auth = Choose_Auth::getInstance();
	    $user = $auth->getStorage()->read();
	    $user_id =  $user->user_id;
	    
	    $postData = $this->obj->getUserByUserId($user_id);
	     
	    $birth_day = explode('-', $postData['birth_day']);
	    $postData['birth_day_yy'] = $birth_day[0];
	    $postData['birth_day_mm'] = $birth_day[1];
	    $postData['birth_day_dd'] = $birth_day[2];
	    $zip_code = explode('-', $postData['zip_code']);
	    $postData['zip_code1'] = $zip_code[0];
	    $postData['zip_code2'] = $zip_code[1];
	    
	    $tags = $this->obj->getTagsByUserId($user_id);
	    $tags = array_flip($tags);
	    $postData['gender'] = $this->obj->getTagIdByUserIdGroupId($user_id, 6);
	    $postData['had_manager'] = $this->obj->getTagIdByUserIdGroupId($user_id, 21);
	    $postData['experience'] = $this->obj->getTagIdByUserIdGroupId($user_id, 19);
	    $postData['password'] = $postData['password2'] = '';
	     
	    $postData['work_type'] = $tags;
	    $postData['career'] = $tags;
	    $postData['start_work'] = $tags;
	    $postData['skill'] = $tags;
	    $postData['tel_time'] = $tags;
	     
	    $user_line = $this->obj->getUserLine($user_id);
	    $postData['lines'] = $user_line['line_id'];
	    $user_station = $this->obj->getUserStation($user_id);
	    $postData['stations'] = $user_station['station_id'];
	     
	    $cities = $this->obj->getCitiesByPrefId($postData['pref_id']);
	    $this->view->assign('cities', $cities);
	    
	    $lines = $this->obj->getLinesByPrefId($postData['pref_id']);
	    $this->view->assign('lines', $lines);
	     
	    $stations = $this->obj->getAllStationsByLineId($postData['lines']);
	    $this->view->assign('stations', $stations);
	     
	    $this->view->assign('input', $postData);
	    
	    $this->_setDataFormRegist();
	    
	    $this->render('register');
	    	  
	}
	
	public function confirmAction(){
	    $postData = $this->getRequest()->getParams();
	    if (!$this->_validateRegistForm($postData)){
	        $this->view->assign('errMsg', $this->_errMsg);
	        $this->_setDataFormRegist();
	        
	        $postData['work_type'] = array_flip($postData['work_type']);
	        $postData['career'] = array_flip($postData['career']);
	        $postData['start_work'] = array_flip($postData['start_work']);
	        $postData['skill'] = array_flip($postData['skill']);
	        $postData['tel_time'] = array_flip($postData['tel_time']);
	        $this->view->assign('input', $postData);
	        
	        if ($postData['pref_id']!=''){
	            $cities = $this->obj->getCitiesByPrefId($postData['pref_id']);
	            $this->view->assign('cities', $cities);
	            
	            $lines = $this->obj->getLinesByPrefId($postData['pref_id']);
	            $this->view->assign('lines', $lines);
	            if ($postData['lines']!=''){ 
    	            $stations = $this->obj->getAllStationsByLineId($postData['lines']);
    	            $this->view->assign('stations', $stations);
	            }
	        }
	        
	        $this->render('register');
	    }else{
	        $pref = $this->obj->getPrefByPrefId($postData['pref_id']);
	        $postData['pref_name'] = $pref['name'];
	        
	        $city = $this->obj->getCityByCityCode($postData['city']);
	        $postData['city_name'] = $city['city_name'];
	        
	        $tag_sex = $this->obj->getTagByTagId($postData['gender']);
	        $postData['gender_name'] = $tag_sex['contents'];
	        
	        $tag_work_type = $this->obj->getTagsByTagsId($postData['work_type']);
	        $postData['tag_work_type'] = $tag_work_type;
	        
	        $tag_career = $this->obj->getTagsByTagsId($postData['career']);
	        $postData['tag_career'] = $tag_career;
	        
	        $tag_start_work = $this->obj->getTagsByTagsId($postData['start_work']);
	        $postData['tag_start_work'] = $tag_start_work;
	        
	        $tag_experience = $this->obj->getTagByTagId($postData['experience']);
	        $experience_name = $tag_experience['contents'];
	        $postData['experience_name'] = $experience_name;
	        
	        $tag_had_manager = $this->obj->getTagByTagId($postData['had_manager']);
	        $had_manager_name = $tag_had_manager['contents'];
	        $postData['had_manager_name'] = $had_manager_name;
	        $line = $this->obj->getLineByLineId($postData['lines']);
	        $postData['line_name'] = $line['name'];  
	        $station = $this->obj->getStationByStationId($postData['stations']);
	        $postData['station_name'] = $station['station_name'];
	        
	        $tag_skill = $this->obj->getTagsByTagsId($postData['skill']);
	        $postData['tag_skill'] = $tag_skill;
	        
	        $tag_tel_time = $this->obj->getTagsByTagsId($postData['tel_time']);
	        $postData['tag_tel_time'] = $tag_tel_time;
	        
	        $this->view->assign('input', $postData);
	    }
	}
	
	public function registSaveAction(){
	    $postData = $this->getRequest()->getParams();
	    if ($this->getRequest()->isPost()){
	        if ($postData['user_id']!=''){// 更新
	            $this->obj->updateUser($postData);
	            $user_id = $postData['user_id'];
	        }else{ // 新規登録
	            if ($postData['offer_id']!=''){// 求人応募
	                $user_id = $this->obj->saveUser($postData, 1);
	            }else{
	                $user_id = $this->obj->saveUser($postData);
	            }
	        }
	        
	        $this->obj->saveUserLine(array('user_id' => $user_id, 'line_id' => $postData['lines']));
	        $this->obj->saveUserStation(array('user_id' => $user_id, 'station_id' => $postData['stations']));
	        
	        $tags_user = array();
	        $tags_user[] = $postData['gender'];
	        
	        $work_type = unserialize($postData['work_type']);
	        $work_type = array_values($work_type);
	        $tags_user = array_merge($tags_user, $work_type);
	        
	        $career = unserialize($postData['career']);
	        $career = array_values($career);
	        $tags_user = array_merge($tags_user, $career);
	        
	        $start_work = unserialize($postData['start_work']);
	        $start_work = array_values($start_work);
	        $tags_user = array_merge($tags_user, $start_work);
	        
	        $tags_user[] = $postData['had_manager'];
	        $tags_user[] = $postData['experience'];
	        
	        $skill = unserialize($postData['skill']);
	        if (is_array($skill) && count($skill)){
    	        $skill = array_values($skill);
    	        $tags_user = array_merge($tags_user, $skill);
	        }
	        
	        $tel_time = unserialize($postData['tel_time']);
	        if (is_array($tel_time) && count($tel_time)){
    	        $tel_time = array_values($tel_time);
    	        $tags_user = array_merge($tags_user, $tel_time);
	        }
	       
	        $this->obj->saveUserTags($user_id, $tags_user);
	        
	        if ($postData['offer_id']!=''){// 求人応募
	            $url = '';
	            if (stristr($_SERVER['HTTP_HOST'], 'http://') == false){
	                $url = 'http://';
	            }
	            $url .= $_SERVER['HTTP_HOST'].'/offer/'.$postData['offer_id'];
	            // 会員にメールを送信
	            $param_user = array(
	                    'url' => $url,
	                    'name' => $postData['first_name'].$postData['last_name']
	            );
	            $mail_user = new Choose_Mail();
	            $mail_user->sendUser('apply_user', $user_id, $param_user);
	            
	            
	            // GROOVEにメールを送信
	            $param_groove = array(
	                    'user_id' => $user_id,
	                    'url' => $url,
	                    'name' => $postData['first_name'].$postData['last_name']
	            );
	            $to = array(
	                    'name' => 'groove-gear',
	                    'email' => 'choose@groove-gear.jp'
	                    );
	            $mail_groove = new Choose_Mail();
	            $mail_groove->sendTo('apply_groove', $to, $param_groove);
	            
	            
	            // 店舗にメールを送信
    	        $param_shop = array(
    	                'name' => $postData['first_name'].$postData['last_name'],
    	                'name_kana' => $postData['first_name_kana'].$postData['last_name_kana'],
    	                'tel' => $postData['tel'],
    	                'mail' => $postData['email_address'],
    	                'self_pr' => $postData['self_pr'],
    	                'skill_etc' => $postData['skill_etc'],
    	                'url' => $url
    	        );
    	        
    	        $now = date('Ymd');
    	        $birthday = sprintf('%04d%02d%02d', $postData['birth_day_yy'], $postData['birth_day_mm'], $postData['birth_day_dd']);
    	        $param_shop['age'] = floor(($now-$birthday)/10000);
    	        
    	        $gender = $this->obj->getTagByTagId($postData['gender']);
    	        $param_shop['gender'] = $gender['contents'];
    	        
    	        $tag_work_type = $this->obj->getTagsByTagsId(array_values(unserialize($postData['work_type'])));
    	        foreach ($tag_work_type as $k=>$v){
    	            $param_shop['work_type'] .= $v['contents'].'  ';
    	        }
    	         
    	        $tag_career = $this->obj->getTagsByTagsId(array_values(unserialize($postData['career'])));
    	        foreach ($tag_career as $k=>$v){
    	            $param_shop['categories'] .= $v['contents'].'  ';
    	        }
    	        
    	        $tag_start_work = $this->obj->getTagsByTagsId(array_values(unserialize($postData['start_work'])));
    	        foreach ($tag_start_work as $k=>$v){
    	            $param_shop['start_work'] .= $v['contents'].'  ';
    	        }
    	         
    	        $tag_experience = $this->obj->getTagByTagId($postData['experience']);
    	        $param_shop['experience'] = $tag_experience['contents'];
    	         
    	        $tag_had_manager = $this->obj->getTagByTagId($postData['had_manager']);
    	        $param_shop['had_manager'] = $tag_had_manager['contents'];
    	        
    	        $line = $this->obj->getLineByLineId($postData['lines']);
    	        $station = $this->obj->getStationByStationId($postData['stations']);
    	        $param_shop['nearby_station'] = $line['name'].'/'.$station['station_name'];
    	         
    	        $tag_skill = $this->obj->getTagsByTagsId(array_values(unserialize($postData['skill'])));
    	        foreach ($tag_skill as $k=>$v){
    	            $param_shop['skill'] .= $v['contents'].'  ';
    	        }
    	        
	            $offer = $this->obj->getOfferByOfferId($postData['offer_id']);
	            $mail_shop = new Choose_Mail();
	            $mail_shop->sendShop('apply_shop', $offer['shop_id'], $param_shop);
	            
	            // 求人テーブルにデータを保存
	            $param = array(
	                    'offer_id'       => $postData['offer_id'],
	                    'shop_id'        => $offer['shop_id'],
	                    'user_id'        => $user_id
	                    );
	            $this->obj->saveApply($param);
	            
	            $this->_redirect('/apply/finish');
	        }else{//新規登録か編集
	        
	            if ($postData['user_id']==''){// 新規登録
    
        	        $hash = sha1($user_id.time());
        	        // HASHテーブルに保存
        	        $userInfo = array(
        	                    'user_id' => $user_id,
        	                    'hash' => $hash
        	                    );
        	        $this->obj->saveHashUser($userInfo);
        	        
        	        // 登録したら仮登録用のメールを送信
        	        $url = '';
        	        if (stristr($_SERVER['HTTP_HOST'], 'http://') == false){
        	            $url = 'http://';
        	        }
        	        $url .= $_SERVER['HTTP_HOST'].'/my-page/user-regist-ok?pre='.$hash;
        	        $mail = new Choose_Mail();
        	        $param = array(
        	                'url' => $url,
        	                'name' => $postData['first_name'].$postData['last_name']
        	        );
        	        $mail->sendUser('user_regist', $user_id, $param);
        	        $this->_redirect('/my-page/pre-user-regist-ok');
    	        }else{
    	            $this->_redirect('/my-page/user-update-ok');
    	        }
	        }
	    }
	}
	
    private function _validateRegistForm($data){
	    $flg = true;
	    $validator_empty = new Zend_Validate();
	    $validator_email = new Zend_Validate();
	    
	    $validator_empty->addValidator(new Zend_Validate_NotEmpty());
	    $validator_email->addValidator(new Zend_Validate_NotEmpty());
	    $validator_email->addValidator(new Zend_Validate_EmailAddress());
	    
	    if (!$validator_empty->isValid($data['first_name']) || !$validator_empty->isValid($data['last_name'])){
	        $this->_errMsg['name'] = '氏名は必須です。';
	        $flg = false;
	    }
	    if (!$validator_empty->isValid($data['first_name_kana']) || !$validator_empty->isValid($data['last_name_kana'])){
	        $this->_errMsg['name_kana'] = 'カタカナでの氏名は必須です。';
	        $flg = false;
	    }
	    if (!isset($data['gender'])){
	        $this->_errMsg['gender'] = '性別は必須です。';
	        $flg = false;
	    }
	    if (!$validator_empty->isValid($data['birth_day_yy']) || !$validator_empty->isValid($data['birth_day_mm']) || !$validator_empty->isValid($data['birth_day_dd'])){
	        $this->_errMsg['birth_day'] = '生年月日 は必須です。';
	        $flg = false;
	    }
	    
	    if (!$validator_empty->isValid($data['pref_id']) || !$validator_empty->isValid($data['city'])){
	        $this->_errMsg['pref_city'] = '都道府県/市区町村は必須です。';
	        $flg = false;
	    }
	    if (!$validator_empty->isValid($data['address'])){
	        $this->_errMsg['address'] = '市区町村以下は必須です。';
	        $flg = false;
	    }
	    if (!$validator_empty->isValid($data['lines']) || !$validator_empty->isValid($data['stations'])){
	        $this->_errMsg['nearby_station'] = '自宅最寄り駅は必須です。';
	        $flg = false;
	    }	    
	    if (!$validator_empty->isValid($data['tel'])){
	        $this->_errMsg['tel'] = 'TELは必須です。';
	        $flg = false;
	    }
	    if (!$validator_email->isValid($data['email_address'])){
	        $this->_errMsg['email_address'] = 'メールアドレスは必要です。';
	        $flg = false;
	    }else{
	        $model_obj = new App_Model_PreUser();
	        if ($data['user_id']!=''){
	            if ($this->obj->isExistEmailOtherUser($data['email_address'], $data['user_id'])){
	                $this->_errMsg['email_address'] = '既に登録済みのアドレスです';
	                $flg = false;
	            }
	        }else{
    	        if ($this->obj->isExistEmailAddress($data['email_address'])){
    	            $this->_errMsg['email_address'] = '既に登録済みのアドレスです';
    	            $flg = false;
    	        }
	        }
	    }
	    
	    if ($data['user_id']==''){// 新規登録
    	    if (!$validator_empty->isValid($data['password'])){
    	        $this->_errMsg['password'] = 'パスワードは必須です。';
    	        $flg = false;
    	    }elseif (strlen($data['password'])<6 || strlen($data['password'])>12){
    	        $this->_errMsg['password'] = 'パスワードは6文字以上12文字以下で入力ください。';
    	        $flg = false;
    	    }
    	    
    	    if (!$validator_empty->isValid($data['password2'])){
    	        $this->_errMsg['password2'] = 'パスワード確認は必須です。';
    	        $flg = false;
    	    }elseif ($data['password']!=$data['password2']){
    	        $this->_errMsg['password2'] = '入力されたパスワードが一致しません。';
    	        $flg = false;
    	    }
	    }else{ // 更新
	        if (($data['password']!='') && (strlen($data['password'])<6 || strlen($data['password'])>12)){
	            $this->_errMsg['password'] = 'パスワードは6文字以上12文字以下で入力ください。';
	            $flg = false;
	        }
	        if ($data['password']!=$data['password2']){
	            $this->_errMsg['password2'] = '入力されたパスワードが一致しません。';
	            $flg = false;
	        }
	    }
	    
	    if (count($data['work_type'])==0){
	        $this->_errMsg['work_type'] = '希望勤務形態は必須です。';
	        $flg = false;
	    }
	    if (count($data['career'])==0){
	        $this->_errMsg['career'] = '希望職種は必須です。';
	        $flg = false;
	    }
	    if (count($data['start_work'])==0){
	        $this->_errMsg['start_work'] = '勤務開始可能日は必須です。';
	        $flg = false;
	    }
	    if (!isset($data['had_manager'])){
	        $this->_errMsg['had_manager'] = '店長経験は必須です。';
	        $flg = false;
	    }
	    if (!isset($data['experience'])){
	        $this->_errMsg['experience'] = '実務経験は必須です。';
	        $flg = false;
	    }
	    
	    return $flg;
	}
	
	public function _setDataFormRegist(){
	    $obj = new App_Model_Mypage();
	    $prefs = $obj->getAllPrefs();
	    $this->view->assign('prefs', $prefs);
	    
	    $categories = $obj->getGyousu();
	    $this->view->assign('categories', $categories);
	    
	    $gender = $obj->getSex();
	    $this->view->assign('gender', $gender);
	    
	    $work_type = $obj->getKinds();
	    $this->view->assign('work_type', $work_type);
	    
	    $start_work = $obj->getStartWork();
	    $this->view->assign('start_work', $start_work);
	    
	    $tel_time = $obj->getCallTime();
	    $this->view->assign('tel_time', $tel_time);
	    
	    $skill = $obj->getShikaku();
	    $this->view->assign('skill', $skill);
	    
	    $experience = $obj->getKeiken();
	    $this->view->assign('experience', $experience);
	    
	    $had_manager = $obj->getTenchoKeiken();
	    $this->view->assign('had_manager', $had_manager);
	}
	
	public function getCitiesPrefidAction(){
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
	    	
	    $pref_id = $this->getParam('pref_id', '');
	    $cities = $this->obj->getCitiesByPrefId($pref_id);
	    echo Zend_Json::encode($cities);
	}
	
	public function getCitiesZipcodeAction(){
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
	
	    $zip_code = $this->getParam('zip_code', '');
	    $cities = $this->obj->getCitiesByZipcode($zip_code);
	    echo Zend_Json::encode($cities);
	}
	
	public function getLinesAction(){
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
	     
	    $pref_id = $this->getParam('pref_id', '');
	    $lines = $this->obj->getLinesByPrefId($pref_id);
	     
	    echo Zend_Json::encode($lines);
	}
	
	public function getStationsAction(){
	    $this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();
	    	
	    $line_id = $this->getParam('line_id', '');
	    $stations = $this->obj->getAllStationsByLineId($line_id);
	    echo Zend_Json::encode($stations);
	}
	

	public function preRegistOkAction() {
	
	}
	
	/**
	 * 会員登録の仮登録
	 */
	public function preUserRegistOkAction() {
	    
	}
		
	public function userUpdateOkAction() {
	     
	}
	
    /**
     * パスワードを忘れた人
     */
    public function forgotPasswordAction() {

    }

    /**
     * パスワード再発行処理
     */
    public function reissuePasswordAction() {

    }

    /**
     * 退会処理
     */
    public function leaveAction() {
         $authStorage = Zend_Auth::getInstance()->getStorage();
         if (!$authStorage->isEmpty()){
             $this->authInfo = (array)$authStorage->read();
             $this->obj->leaveUser($this->authInfo['user_id']);
         }
    }
}
?>
