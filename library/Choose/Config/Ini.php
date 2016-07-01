<?php

class Choose_Config_Ini extends Zend_Config_Ini
{
    public function __construct($filename = 'application.ini')
    {
        $filename = APPLICATION_PATH . "/configs/{$filename}";
        parent::__construct($filename, APPLICATION_ENV);
    }
}