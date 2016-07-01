<?php

class Choose_Crawl_Ehairsalons extends Choose_Crawl
{
    protected $_name = 'イーヘアーサロンズ';

    public function collect()
    {
        $baseUrl = 'http://www.e-hairsalons.com/';

        $prefectures = array(
            '北海道',
            '青森', '岩手', '宮城', '秋田', '山形', '福島',
            '東京', '神奈川', '埼玉', '千葉', '茨城', '栃木', '群馬',
            '新潟', '石川', '福井', '富山', '愛知', '長野', '山梨',
            '静岡', '岐阜', '三重', '滋賀',
            '京都府', '奈良', '和歌山', '大阪府', '兵庫',
            '鳥取', '岡山', '島根', '広島', '山口',
            '香川', '徳島', '愛媛', '高知',
            '大分', '宮崎', '鹿児島', '福岡', '熊本', '佐賀', '長崎',
            '沖縄'
        );

        $table = new App_Model_DbTable_CrawlUrls;

        foreach ($prefectures as $pref) {
            $pref = mb_convert_encoding($pref, 'sjis', 'utf8');
            $url = $baseUrl . urlencode($pref);
var_dump($url);
        }

        return $this;
    }
}
