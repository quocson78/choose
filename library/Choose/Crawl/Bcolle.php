<?php

class Choose_Crawl_Bcolle extends Choose_Crawl
{
    protected $_name = 'ビューティーコレクション';

    public function collect()
    {
        $baseUrl = 'http://b-colle.jp';

        $types = array(
            '美容師' => 'hair',
            'ネイル' => 'nail',
            'エステ' => 'esthe',
            'リフレ' => 'relax',
            'アイリスト' => 'matsuge',
        );

        $regions = array(
            'tohoku',
            'kanto',
            'shinetsu',
            'hokuriku',
            'tokai',
            'kansai',
            'chugoku',
            'shikoku',
            'kyusyu',
        );


        $tidy = new Tidy;
        $table = new App_Model_DbTable_CrawlUrls;
        $cityTable = new App_Model_DbTable_MCities;

        foreach ($types as $category => $type) {
            foreach ($regions as $region) {

                $page = 1;
                while(1) {
                    $pageUrl = sprintf('%s/%s_a_%s_%d.html', $baseUrl, $type, $region, $page);
var_dump($pageUrl);

                    $response = $this->getResponse($pageUrl);
                    $dom = new Zend_Dom_Query;
                    $dom->setDocument($response->getBody(), 'utf8');

                    $res = $dom->query('.list_box ul .area a');

                    $maxCount = $res->current()->textContent;

                    if (!mb_ereg('(\d+)件中.*(\d+)-(\d+)', $maxCount, $maxCount)) {
                        break;
                    }
                    
                    $res = $dom->query('.salon_titlename a');

                    foreach ($res as $shop) {
                        $shopUrl = $shop->getAttribute('href');

                        $response = $this->getResponse($baseUrl . $shopUrl);
                        $html = $response->getBody();
//                        $html = mb_convert_encoding($response->getBody(), 'utf8', 'sjis');
//                        $html = mb_convert_kana($html, 'aKVs');
                        $dom = new Zend_Dom_Query;
                        $dom->setDocument($html, 'utf8');

                        $res = $dom->query('#table_box_data');

                        $res = $dom->queryXpath('//td[text()="ＵＲＬ"]/following-sibling::td');
                        $url = $res->current()->textContent;
                        if (!strlen(trim($url))) {
                            continue;
                        }

                        $res = $dom->queryXpath('//td[text()="サロン名"]/following-sibling::td');
                        $name = $res->current()->textContent;

                        //$res = $dom->queryXpath('//td[text()="電話番号"]/following-sibling::td');
                        //$tel = $res->current()->textContent;

                        $res = $dom->queryXpath('//td[text()="所在地"]/following-sibling::td');
                        $address = $res->current()->textContent;

                        $row = $cityTable->getByAddress($address);
                        $prefecture = $row['pref_id'];
                        $city = $row['city_code'];
                        $zip = $row['zip_code'];

                        $address = mb_substr($address, mb_strlen("{$row['pref_name']}{$row['city_name']}"));

var_dump(trim($name));
                        $table->insertDuplicate(
                                array(
                                    'name' => trim($name),
                                    'type' => $category,
                                    'url' => trim($url),
                                    'tel' => trim($tel),
                                    'zip_code' => trim($zip),
                                    'pref_id' => trim($prefecture),
                                    'city_code' => trim($city),
                                    'address' => trim($address),
                                    'first_access_date' => date('Y-m-d H:i:s'),
                                    'last_access_date' => date('Y-m-d H:i:s')),
                                array(
                                    'name' => trim($name),
                                    'type' => $category,
                                    'url' => trim($url),
                                    'tel' => trim($tel),
                                    'zip_code' => trim($zip),
                                    'pref_id' => trim($prefecture),
                                    'city_code' => trim($city),
                                    'address' => trim($address),
                                    'crawled_flag' => 0,
                                    'last_access_date' => date('Y-m-d H:i:s'))
                        );
                    }

                    if ($maxCount[1] == $maxCount[3]) {
                        break;
                    }

                    $page++;
                }
            }
        }

        return $this;
    }
}
