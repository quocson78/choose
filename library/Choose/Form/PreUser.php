<?php

class Choose_Form_PreUser extends Zend_Form {

    public function init() {
        $this->setAction('/pre-user/pre-regist')->setMethod('post');
       
        $emailAddress = new Zend_Form_Element_Text('email_address');
        $emailAddress->addValidator(new Choose_Validate_EmailAddress());
        $emailAddress->setRequired();
        $emailAddress->setLabel('※メールアドレス');
        $emailAddress->setAttrib('class', 'text_box02');
        $emailAddress->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
        		array('Label', array('tag' => 'td')),
        		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));
        

        $password = new Zend_Form_Element_Password('password');
        $password->addValidator(new Choose_Validate_Password());
        $password->setRequired();
        $password->setLabel('※パスワード');
        $password->setAttrib('class', 'text_box02');
        $password->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
        		array('Label', array('tag' => 'td')),
        		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));

        $passwordConfirm = new Zend_Form_Element_Password('confirm_password');
        $passwordConfirm->addValidator('Identical');
        $passwordConfirm->setRequired();
        $passwordConfirm->setLabel('※パスワード（確認）');
        $passwordConfirm->setAttrib('class', 'text_box02');
        $passwordConfirm->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
        		array('Label', array('tag' => 'td')),
        		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));

        $lastName  = new Zend_Form_Element_Text('last_name');
        $lastName->setLabel('※姓');
        $lastName->setRequired();
        $lastName->setAttrib('class', 'text_box03');
        $lastName->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
        		array('Label', array('tag' => 'td')),
        		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));

        $firstName = new Zend_Form_Element_Text('first_name');
        $firstName->setLabel('※名');
        $firstName->setRequired();
        $firstName->setAttrib('class', 'text_box03');
        $firstName->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array(array('data'=>'HtmlTag'), array('tag' => 'td')),
        		array('Label', array('tag' => 'td')),
        		array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));
        
        $submit = new Zend_Form_Element_Submit('btnSubmit');
		$submit->setName('btnSubmit');
		$submit->setLabel('送信');
		$submit->setAttrib('id', 'form1_button');
		$submit->setDecorators(array(
			   'ViewHelper',
               'Description',
               'Errors', 
			   array(array('data'=>'HtmlTag'), array('tag' => 'td', 'colspan'=>'2','align'=>'center')),
               array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
		));
        
        $this->addElement($emailAddress);
        $this->addElement($password);
        $this->addElement($passwordConfirm);
        $this->addElement($lastName);
        $this->addElement($firstName);
        $this->addElement($submit);
        
        $this->setDecorators(array(
        		'FormElements',
        		array(array('data'=>'HtmlTag'),array('tag'=>'table')),
        		'Form'
        ));
    }

    /**
     * @param  array $data
     * @return result Validation
     */
    public function isValid($data) {
        $confirm = $this->getElement('confirm_password');
        $confirm->getValidator('Identical')->setToken($data['password']);
        return parent::isValid($data);
    }
}

?>
