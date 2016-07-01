<?php

class Choose_Crawl_Itown extends Choose_Crawl
{
    protected $_name = 'iタウン';

    protected $_genrues = array(
        '1022' => 'エステティックサロン',
        '1024' => 'ネイルサロン',
        '1025' => 'ビューティーアドバイザー',
        '1026' => 'プロポーションメーキング',
        '1027' => 'メイクアップサービス',
        '1046' => 'フェイシャルエステティックサロン',
        '1047' => 'フットケア',
        '1051' => 'リンパマッサージ',
        '1054' => 'アロマテラピー',
        '1055' => 'リラクゼーションサロン',
        '1062' => '日焼けサロン',
        '3225' => 'まつげサロン',
        '3226' => 'アーユルヴェーダ',
        '3228' => 'タイ古式マッサージ',
        '3229' => 'ハワイアンロミロミ',
        '3230' => 'ボディーワーク',
        '1028' => '美容院',
        '1029' => 'ヘアデザイナー',
//        '1023' => '化粧品販売',
//        '3187' => 'エッセンシャルオイル販売',
//        '1056' => '健康機器',
//        '4676' => '健康機器販売',
//        '4716' => '香水販売',
//        '4729' => '香油販売',
//        '3285' => '酸素バー',
//        '1030' => '理容店',
//        '1041' => '育毛業',
//        '1050' => '毛髪業',
//        '3224' => 'シェービング',
    );

    public function collect()
    {
        $total = 0;

        $baseUrl = 'http://itp.ne.jp';
        $prefUrl = 'http://itp.ne.jp/genre/location/area/%02d/4/102/?dg=%d';
        $areaUrls = null;

        $genre = $this->_argv->getOption('g');
        if (isset($this->_genrues[$genre])) {
            $genrues = array(
                $genre => $this->_genrues[$genre]
            );
        } else {
            $genrues = $this->_genrues;
        }

        // クロールする一覧ページのURL生成
        // 都道府県別だと5000件までしか表示されないの取得できない

        $table = new App_Model_DbTable_CrawlUrls;

        // ジャンル別
        foreach ($genrues as $genrueCode => $ganrueName) {
var_dump($ganrueName . '--------------------');
            $areaUrls = null;

            // 都道府県
            for ($pref = 1; $pref <= 47; $pref++) {
                $url = sprintf($prefUrl, $pref, $genrueCode);
//var_dump($url);

                $response = $this->getResponse($url);
                $dom = new Zend_Dom_Query($response->getBody());
                $res = $dom->query('h4 a');

                // 詳細エリア
                foreach ($res as $line) {
                    $url = htmlspecialchars_decode($line->getAttribute('href'));
                    preg_match('|\d+|', $line->textContent, $count);
                    $areaUrls[$url] = $count[0];
                }

                sleep(1);
            }


            foreach ($areaUrls as $listUrl => $count) {
                $page = 1;
                $num = 50;

                while (0 < $count) {
                    $listUrl = preg_replace('/pg=\d*/', '', $listUrl);
                    $url = "{$baseUrl}{$listUrl}&num={$num}&pg={$page}";
                    $response = $this->getResponse($url);
                    $dom = new Zend_Dom_Query($response->getBody());
                    $res = $dom->query('.normalResultsBox article');
//var_dump($url);

                    foreach ($res as $shop) {
                        $url = null;
                        $string = mb_convert_kana($shop->textContent, 's');
                        $html = mb_convert_kana($shop->C14N(), 's');

                        $dom = new Zend_Dom_Query;
                        $dom->setDocumentXml($html);

                        $name = $dom->query('h4')->current()->textContent;
                        $name = preg_replace('/\n*[^ ]*$/', '', $name);

                        $node = $dom->queryXpath("//span[text()='住所']/parent::*/text()");
                        if (1 == count($node)) {
                            $text = $node->current()->textContent;
                            if (preg_match('/\d+-\d+/', $text, $match)) {
                                $zipCode = $match[0];
                            }

                            if (preg_match('/(\d+)-(\d+) (.+)/', $text, $match)) {
                                $zipCode = $match[1] . $match[2];
                                $address = $match[3];
                            }
                        }

                        $node = $dom->queryXpath("//span[text()='TEL']/parent::*/text()");
                        if (1 == count($node)) {
                            $tel = $node->current()->textContent;
                        }

                        $elm = $dom->query('a.homePageLink');

                        if (count($elm)) {
                            // 30xリダイレクトじゃない・・・
                            $target = $elm->current()->getAttribute('href');
                            $response = $this->getResponse("{$baseUrl}{$target}");

                            if (mb_ereg('http[s]?:[^"]*', $response->getBody(), $match)) {
                                $url = $match[0];
                            }
                        } elseif (mb_ereg('URL\s*(\S+)\s*', $string, $match)) {
                            $url = $match[1];
                        }

                        if ($url) {
var_dump(trim($name));
                            $table->insertDuplicate(
                                    array(
                                        'name' => trim($name),
                                        'type' => trim($ganrueName),
                                        'url' => trim($url),
                                        'tel' => trim($tel),
                                        'zip_code' => trim($zipCode),
                                        'address' => trim($address),
                                        'first_access_date' => date('Y-m-d H:i:s'),
                                        'last_access_date' => date('Y-m-d H:i:s')),
                                    array(
                                        'crawled_flag' => 0,
                                        'last_access_date' => date('Y-m-d H:i:s'))
                            );
                        }
                    }

                    $page++;
                    $count -= 50;

                    sleep(1);
                }
            }
        }

        return $this;
    }
}
