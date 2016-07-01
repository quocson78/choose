<?php
$init = function()
{
    date_default_timezone_set('Asia/Tokyo');
    mb_internal_encoding('utf-8');
    mb_http_output("utf-8");

    // アプリケーションパスとオートロードの初期化
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

    // Define application environment
    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    set_include_path(implode(PATH_SEPARATOR, array(
        APPLICATION_PATH . '/../library',
        get_include_path(),
    )));

    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->registerNamespace('Choose');

    // Zend_Application の初期化
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );

    // DB リソースの初期化と読み込み
    $bootstrap = $application->getBootstrap();
    $bootstrap->bootstrap('db');
    $db = $bootstrap->getResource('db');
};

$init();