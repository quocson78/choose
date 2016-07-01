<?php
include 'MyPageController.php';
class Default_ApplyController extends Choose_Controller_Action_Default {

    private $obj;
    public function init(){
        $this->obj = new App_Model_Mypage();
        parent::init();
    }
    
    public function indexAction(){
        $offer_id = $this->getRequest()->getParam('offer_id', '');
        $auth = Choose_Auth::getInstance();
        if ($auth->hasIdentity()){// ログインしている
            $this->_redirect('/apply/form?offer_id='.$offer_id);
        }else{// ログインしていない
            $this->_redirect('/apply/member?offer_id='.$offer_id);
        }
    }
    
    public function memberAction(){
        $params = $this->getRequest()->getParams(); 
        $this->view->assign('input', $params);
    }
    
    public function formAction(){
        $auth = Choose_Auth::getInstance();
        if ($auth->hasIdentity()){
            $user = $auth->getStorage()->read();
            $user_id =  $user->user_id;
        }else{
            $user_id = '';
        }
        
        $postData = array();
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
        }elseif ($user_id !=''){
            $postData = $this->obj->getUserByUserId($user_id);

            $postData['offer_id'] = $session_apply->offer_id;
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
            
        }
        
        $offer_id = $this->getRequest()->getParam('offer_id', '');
        if ($offer_id!=''){            
            $postData['offer_id'] = $offer_id;
        }
        
        Default_MyPageController::_setDataFormRegist();
        $this->view->assign('input', $postData);
        
        $this->render('/my-page/register', null, true);
    }
    
    public function finishAction(){
        
    }
}