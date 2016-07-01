<?php

class App_Model_CrawlOffers
{
    public function fetchByNo($no)
    {
        $offers = new App_Model_DbTable_CrawlOffers;

        $select = $offers->select()
                    ->where('NOT checked_flag')
                    ->where('no >= ?', (int)$no)
                    ->order('no')
                    ->limit(1);

        return $offers->fetchRow($select);
    }
}
