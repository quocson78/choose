<?php
class Choose_Controller_Action extends Zend_Controller_Action
{
    protected $_deviceSuffix = null;

    public function init()
    {
        parent::init();

        $base = Zend_Controller_Front::getInstance()->getModuleDirectory();

        $layoutPath = "{$base}/layouts/scripts/{$this->_deviceSuffix}";
        $viewPath = "{$base}/views/scripts/{$this->_deviceSuffix}";

        Zend_Layout::startMvc(array('layoutPath' => $layoutPath));
        $this->view->setScriptPath($viewPath);
    }

    public function postDispatch()
    {
        if (!$this->view->params) {
            $this->view->params = $this->getAllParams();
        }
    }
}
