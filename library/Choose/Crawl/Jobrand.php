<?php

class Choose_Crawl_Jobrand extends Choose_Crawl
{
    protected $_name = 'JOBRAND';

    public function collect()
    {
        $baseUrl = 'http://www.jobrand.jp';

        $types = array(
            '1' => '美容師',
            '2' => 'ネイリスト',
            '3' => 'エステティシャン',
            '4' => '理容師',
            '5' => 'メイク',
            '6' => 'ヘアメイク',
        );

        $tidy = new Tidy;
        $table = new App_Model_DbTable_CrawlUrls;
        $cityTable = new App_Model_DbTable_MCities;

        foreach ($types as $id => $category) {
            $categoryUrl = $baseUrl . '/shop_list.php?type2[]=' . $id;

            $page = 1;
            $noData = 0;

            while (1) {
                if (3 < $noData++) {
                    break;
                }

                $pageUrl = $categoryUrl . '&page=' . $page;
var_dump($pageUrl);

                $response = $this->getResponse($pageUrl);
                $html = $response->getBody();

                $dom = new Zend_Dom_Query;
                $dom->setDocument($html);

                $shops = $dom->query('.list_area');

                if (!count($shops)) {
                    break;
                }

                foreach ($shops as $shop) {

                    $dom = new Zend_Dom_Query;
                    $dom->setDocumentXml($shop->C14N());

                    $res = $dom->queryXpath('//th[text()="勤務地"]/following-sibling::td');
                    if (!count($res)) {
                        continue;
                    }
                    preg_match("|([^>]*?)</td>$|", $res->current()->C14N(), $match);
                    $address = trim($match[1]);

                    $res = $dom->query('h3 a');
                    $shopUrl = $res->current()->getAttribute('href');

                    $response = $this->getResponse($baseUrl . $shopUrl);
                    $html = $response->getBody();
                    $dom = new Zend_Dom_Query;
                    $dom->setDocument($html, 'utf8');

                    $res = $dom->queryXpath('//th[text()="ホームページ"]/following-sibling::td/a');
                    $url = $res->current()->textContent;
                    if (!strlen(trim($url))) {
                        continue;
                    }

                    $res = $dom->queryXpath('//th[text()="店名"]/following-sibling::td');
                    $name = $res->current()->textContent;

                    $res = $dom->queryXpath('//th[text()="電話番号"]/following-sibling::td');
                    $tel = $res->current()->textContent;

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

                    $noData = 0;
                }

                $page++;
            }
        }

        return $this;
    }
}
