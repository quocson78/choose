<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoloader()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Choose');
    }


    protected function _initRouting()
    {
        preg_match('|^[^-\.]*|', $_SERVER['HTTP_HOST'], $subDomain);

        switch ($subDomain[0]) {
            case 'admin':
            case 'shop':
                $routeName = $subDomain[0];
                break;

            default:
                $routeName = 'default';
        }
        
        $config = new Zend_Config_Xml(APPLICATION_PATH . "/configs/routes/{$routeName}.xml");

        $class = 'Choose_Controller_Router_' . ucfirst($routeName);
        $router = new $class;

        $router->addConfig($config);

        $router = Zend_Controller_Front::getInstance()
                    ->setDefaultModule($routeName)
                    ->setRouter($router);
    }


    protected function _initSession()
    {
        Choose_Session::start();
    }

    
    protected function _initView()
    {
        $view = new Choose_View;

        $view->addHelperPath(
                APPLICATION_PATH . '/../library/Choose/View/Helper',
                'Choose_View_Helper');

        $view->addFilterPath(
                APPLICATION_PATH . '/../library/Choose/View/Filter',
                'Choose_View_Filter');

        $view->addFilter('Strip');

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
    }

    protected function _initLang()
    {
        $translator = new Zend_Translate(
                            'array',
                            realpath(APPLICATION_PATH . '/../library/lang/Zend_Validate.php'),
                            'ja',
                            array('scan' => Zend_Translate::LOCALE_FILENAME)
                        );
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }
}
