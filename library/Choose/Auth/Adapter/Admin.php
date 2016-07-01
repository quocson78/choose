<?php

class Choose_Auth_Adapter_Admin extends Zend_Auth_Adapter_DbTable
{
    public function __construct()
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        parent::__construct(
            $db,
            'm_shop_accounts',
            'email_address',
            'password',
            'SHA1(?) AND active_flag AND shop_id = 1');
    }
}
