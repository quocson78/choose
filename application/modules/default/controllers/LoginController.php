<?php

class Default_LoginController extends Choose_Controller_Action_Default
{
    private $errMsg = array(
                    'email' => '', 
                    'password' => ''
                    );
    public function indexAction()
    {
        $this->view->assign('errMsg', $this->errMsg);
        $params = $this->getRequest()->getParams();
        $this->view->assign('params', $params);
    }

    public function checkAction()
    {
        $params = $this->getRequest()->getParams();
        
        $validator_empty = new Zend_Validate();
        $validator_empty->addValidator(new Zend_Validate_NotEmpty());
        $valid_flg = true;
        if (!$validator_empty->isValid($params['email'])){
            $this->errMsg['email'] = 'メールは必須です。';
            $valid_flg = false;
        }
        if (!$validator_empty->isValid($params['password'])){
            $this->errMsg['password'] = 'パスワードは必須です。';
            $valid_flg = false;
        }
        
        if (!$valid_flg){
            $this->view->assign('errMsg', $this->errMsg);
            if (isset($params['offer_id']) && ($params['offer_id']!='')){// 求人応募
                $this->render('index?offer_id='.$params['offer_id']);
            }else{
                $this->render('index');
            }
        }else{
            $adapter = new Choose_Auth_Adapter_Default();
            $adapter->setIdentity($params['email']);
            $adapter->setCredential($params['password']);
    
            $auth = Choose_Auth::getInstance();
            if ($auth->authenticate($adapter)->isValid()) {
                $auth->getStorage()->write($adapter->getResultRowObject());
            } else {
                return $this->_forward('index');
            }
            if (isset($params['offer_id']) && ($params['offer_id']!='')){// 求人応募
                $this->_redirect('/apply/form?offer_id='.$params['offer_id']);
            }else{
                $this->_redirect('/my-page');
            }
        }
    }
}