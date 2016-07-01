<?php

class Choose_Session extends Zend_Session
{
    public static function start()
    {
        $config = new Choose_Config_Ini('application.ini');
        Zend_Session::start($config->session->toArray());
    }
}