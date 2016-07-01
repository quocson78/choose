<?php
// pre-regist-ok
class Default_InfoController extends Choose_Controller_Action_Default
{
    public function init()
    {
        parent::init();
    }

    public function postDispatch() {
        parent::postDispatch();
        $this->_helper->layout->setLayout('static');
    }
    public function indexAction() {
        $template_name = $this->getParam('static_name');
        
        $this->render($template_name);
    }

}