<?php

class Choose_Auth_Adapter_Default extends Zend_Auth_Adapter_DbTable
{
    public function __construct()
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        parent::__construct(
            $db,
            'm_users',
            'email_address',
            'password',
            'SHA1(?) AND is_active=1 AND is_leaved=0');
    }
}
