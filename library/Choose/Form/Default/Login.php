<?php

class Choose_Form_Default_Login extends Choose_Form
{
    public function init()
    {
        $this->setAction('/login/check')
             ->setMethod('post')
             ->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag'=>'table')),
                'Form'
             ));

        $this->addElementPrefixPath(
                            'Choose_Decorator',
                            'Choose/Decorator/',
                            'decorator');

        $elem = new Zend_Form_Element_Text('email');
        $elem->setLabel('メールアドレス')
             ->setRequired()
             //->setDecorators(array('Default'))
             ->addValidator(new Zend_Validate_EmailAddress());
        $this->addElement($elem);

        $elem = new Zend_Form_Element_Password('password');
        $elem->setLabel('パスワード')
            ->setRequired()
            ->addValidator(new Zend_Validate_StringLength(array(6)))
            ->addFilter('StringTrim');
        $this->addElement($elem);

        $this->setElementDecorators(array( 
            'ViewHelper', 
            'Errors', 
            array('Label', array('tag' => 'td', 'class' => 'register')), 
            array('FormElements', array('tag' => 'td', 'class' => 'register')), 
            array('HtmlTag', array('tag' => 'td', 'class' => 'register')),
        )); 
        /*
        $elem = new Zend_Form_Element_Checkbox('auto_login');
        $elem->setLabel('自動的にログイン');
        $elem->getDecorator('label')->setOption('placement', 'APPEND');
        $this->addElement($elem);
         */

        $elem = new Zend_Form_Element_Submit('login');
        $elem->setLabel('ログイン');
        $this->addElement($elem);
    }
}