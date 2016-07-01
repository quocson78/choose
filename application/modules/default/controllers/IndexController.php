<?php

class Default_IndexController extends Choose_Controller_Action_Default
{

	private $obj;
	private $_errMsg = array(
        				'email_address' => '',
        				'name' => ''
        				);
    public function init()
    {
    	$this->obj = new App_Model_Index();
        parent::init();
    }

    public function indexAction()
    {
        $this->_setViewDataCenter();
        
        $input = array(
        				'email_address' => '', 
        				'last_name' => '',
        				'first_name' => ''
        				);
        $this->view->assign('input', $input);
        $this->view->assign('errMsg', $this->_errMsg);
        
        $nDay = 18-((Date('m')==8)?Date('d'):0);
        $this->view->assign('nDay', $nDay);
    }

    public function preRegistAction() {
    	
        if (!$this->getRequest()->isPost()) {
            return $this->_redirect('/');
        }
        $postData = $this->getRequest()->getParams();
        if (!$this->_validateRegistForm($postData)){
        	$this->view->assign('errMsg', $this->_errMsg);
        	$this->view->assign('input', $postData);
        	
        	if (isset($postData['faq'])){ // faqページから呼ばれる
        		$this->render('/info/faq', null, true);
        	}else{// TOPページから呼ばれる
        		$this->_setViewDataCenter();
        		
        		$nDay = 18-((Date('m')==8)?Date('d'):0);
        		$this->view->assign('nDay', $nDay);
        		
        		$this->render('index');
        	}
        }else{
	        $model_obj = new App_Model_PreUser();
	        $result = $model_obj->savePreUser($postData);
	        
	        // 登録したら仮登録用のメールを送信
	        $url = '';
	        if (stristr($_SERVER['HTTP_HOST'], 'http://') == false){
	        	$url = 'http://';
	        }
	        $url .= $_SERVER['HTTP_HOST'].'/mypage/regist-ok?pre='.$result['hash'];
	        $mail = new Choose_Mail();
	        $param = array(
	        				'url' => $url, 
	        				'name' => $postData['first_name'].$postData['last_name']
	        				);
	        $mail->sendUser('pre_regist', $result['user_id'], $param);
	            
	        $this->_redirect('/mypage/pre-regist-ok');
        }
    }
    
    private function _validateRegistForm($data){
    	$flg = true;
    	$validator = new Zend_Validate();
    	$validator->addValidator(new Zend_Validate_NotEmpty());
    	$validator->addValidator(new Zend_Validate_EmailAddress());
    	if (!$validator->isValid($data['email_address'])){
    		$this->_errMsg['email_address'] = 'メールアドレスは必須です';
    		$flg = false;
    	}else{
            $model_obj = new App_Model_PreUser();
            if ($model_obj->isExistEmailAddress($data['email_address'])){
                $this->_errMsg['email_address'] = '既に登録済みのアドレスです';
                $flg = false;
            }
    	}
    	if (!strlen($data['first_name']) || !strlen($data['last_name'])){
    		$this->_errMsg['name'] = '氏名が入力されていません。';
    		$flg = false;
    	}
    	return $flg;
    }
    
    private function _setViewDataCenter(){
    	$data_center = $this->obj->getGyokai();
    	foreach ($data_center as $k=>$v){
    		$data_center[$k]['children'] = $this->obj->getChildrenFromTag($v['tag_id']);
    		foreach ($data_center[$k]['children'] as $k1=>$v1){
    			$data_center[$k]['children'][$k1]['hoods'] = $this->obj->getChildrenFromTag($v1['tag_id']);
    		}
    	}
    	$this->view->assign('data_center', $data_center);
    }
}
