<?php

class App_Model_Crawl
{
    public function saveShop($data)
    {
        $auth = Choose_Auth::getInstance();

        $table = new App_Model_DbTable_MShops;

        $shopId = $table->checkClawlShop($data['url']);

        if (false === $shopId) {

            return null;

        } elseif (0 === $shopId) {
            $column = array(
                'shopname'  => $data['shopname'],
                'shopname_kana' => $data['shopname_kana'],
                'zip_code'  => $data['zip_code'],
                'pref_id'   => $data['pref_id'],
                'city_code' => $data['city_code'],
                'address'   => $data['address'],
                'tel'       => $data['tel'],
                'official_url' => $data['url'],
                'crawl_flag' => 1,

                'registed' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
                'updated_id' => $auth->getStorage()->read()->account_id,
            );

            $shopId = $table->insert($column);
        }

        return $shopId;
    }


    public function saveOffer($shopId, $data, $reset = false)
    {
        $auth = Choose_Auth::getInstance();

        $table = new App_Model_DbTable_Offers;

        if ($reset) {
    		$where = $table->getAdapter()->quoteInto('shop_id = ?', $shopId);
            $table->delete($where);
        }

        $column = array(
            'shop_id'  => $shopId,

            'offer_title' => '',
            'offer_subtext' => '',

            'work_contents' => $data['work_contents'],
            'income'    => $data['income'],
            'treatment' => $data['treats'],
            'holidays'  => $data['holidays'],
            'offer_age' => $data['offer_age'],
            'app_qualification' => $data['app_qualification'],
            'reception' => $data['reception'],
            'work_time' => $data['work_time'],
            'note'      => $data['note'],

            'crawl_flag' => 1,

            'registed' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'updated_id' => $auth->getStorage()->read()->account_id,
        );

        return $table->insert($column);
    }


    public function saveOfferTag($offerId, $data)
    {
        $table = new App_Model_DbTable_OffersTags;

        $where = $table->getAdapter()->quoteInto('offer_id = ?', $offerId);
        $table->delete($where);

        $column = array(
            'offer_id' => $offerId,
            'tag_id' =>  $data['category']
        );
        $table->insert($column);

        foreach ($data['types'] as $tagId) {
            $column = array(
                'offer_id' => $offerId,
                'tag_id' =>  $tagId
            );
            $table->insert($column);
        }
    }
}
