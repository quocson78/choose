<?php

class Choose_Crawl_Ehairsalons extends Choose_Crawl
{
    protected $_name = 'イーヘアーサロンズ';

    public function collect()
    {
        $baseUrl = 'http://www.e-hairsalons.com';

        $prefectures = array(
            '北海道',
            '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '東京都', '神奈川県', '埼玉県', '千葉県', '茨城県', '栃木県', '群馬県',
            '新潟県', '石川県', '福井県', '富山県', '愛知県', '長野県', '山梨県',
            '静岡県', '岐阜県', '三重県', '滋賀県',
            '京都府', '奈良県', '和歌山県', '大阪府', '兵庫県',
            '鳥取県', '岡山県', '島根県', '広島県', '山口県',
            '香川県', '徳島県', '愛媛県', '高知県',
            '大分県', '宮崎県', '鹿児島県', '福岡県', '熊本県', '佐賀県', '長崎県',
            '沖縄県'
        );

        $tidy = new Tidy;
        $table = new App_Model_DbTable_CrawlUrls;
        $cityTable = new App_Model_DbTable_MCities;

        foreach ($prefectures as $pref) {
var_Dump($pref);
            $pref = mb_convert_encoding($pref, 'euc', 'utf8');
            $pageUrl = $baseUrl . '/list.php?area=' . urlencode($pref);

            $response = $this->getResponse($pageUrl);
            $html = mb_convert_encoding($response->getBody(), 'utf8', 'euc');
            $html = preg_replace(array(
                        '|<script[\s\S]*?/script>|',
                        '|<!--[\s\S]*?-->|'
                    ), '', $html);

            $tidy->parseString($html,
                    array(
                        'hide-comments' => true,
                        'output-xhtml' => true,
                        'quote-nbsp' => false),
                    'utf8');
            $tidy->cleanRepair();

            $dom = new Zend_Dom_Query;
            $dom->setDocumentXml((string)$tidy->body());
            $res = $dom->query('.pages');
            preg_match('|^page 1/(\d+)$|', $res->current()->textContent, $match);

            if (!(int)$match[1]) {
                continue;
            }

            $maxPage = (int)$match[1];

            do {
                try {
                    $res = $dom->query('.content > p a');
                } catch (Exception $e) {
                    $res = array();
                }
                
                foreach ($res as $name) {
                    $response = $this->getResponse($baseUrl . $name->getAttribute('href'));
                    $html = mb_convert_encoding($response->getBody(), 'utf8', 'euc');
                    $html = mb_convert_kana($html, 'aKVs');
                    $html = str_replace(array("\r", "\n", '&nbsp;'), '', $html);

                    $tidy->parseString($html,
                            array(
                                'hide-comments' => true,
                                'output-xhtml' => true,
                                'quote-nbsp' => false),
                            'utf8');
                    $tidy->cleanRepair();

                    $dom->setDocumentXml((string)$tidy->body());

                    $res = $dom->queryXpath('//img[@alt="ホームページ"]/following-sibling::a');
                    $url = $res->current()->textContent;

                    if(!$url) continue;

                    $res = $dom->query('table font[size="6"]');
                    $name = $res->current()->textContent;

                    $res = $dom->queryXpath('//img[@alt="電話番号"]/following-sibling::text()');
                    $tel = $res->current()->textContent;

                    $res = $dom->queryXpath('//img[@alt="住所"]/following-sibling::text()');
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
                                'type' => '美容師',
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
                                'type' => '美容師',
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

                $response = $this->getResponse($pageUrl . "&page={$maxPage}");
var_dump($pageUrl . "&page={$maxPage}");
                $html = mb_convert_encoding($response->getBody(), 'utf8', 'euc');

                $tidy->parseString($html,
                        array(
                            'hide-comments' => true,
                            'output-xhtml' => true,
                            'quote-nbsp' => false),
                        'utf8');
                $tidy->cleanRepair();

                $dom->setDocumentXml((string)$tidy->body());
                $maxPage--;

            } while (1 < $maxPage);
        }

        return $this;
    }
}
