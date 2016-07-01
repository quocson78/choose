<?php

class Choose_Form_AdminLogin extends Choose_Form
{
    public function init()
    {
        $this->setAction('/login/check')
             ->setMethod('post');

        $elem = new Zend_Form_Element_Text('email');
        $elem->setLabel('メールアドレス')
            ->setRequired()
            ->addValidator(new Zend_Validate_EmailAddress());
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Password('password');
        $elem->setLabel('パスワード')
            ->setRequired()
            ->addValidator(new Zend_Validate_StringLength(array(6)))
            ->addFilter('StringTrim');
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Submit('login');
        $elem->setLabel('ログイン');
        $this->addElement($elem);
    }
}