#!/usr/bin/env php
<?php
include_once 'Cli.php';

chdir(dirname(__FILE__));
mb_regex_encoding('UTF-8');

main();

function main()
{
    $types = array(
        'エステ',
        'ネイリスト',
        'アイリスト',
        '美容師',
        '美容部員',
        'セラピスト',
        'アロマ',
        'マッサージ',
        'リフレクソロジー',
        '整体師',
        'カイロプラクター',
        '柔道整復師',
        '鍼灸師',
        'マッサージ師',
        'ヨガ',
        'フィットネス'
    );

    $areas = array(
        'city_410' => '千代田区',
        'city_411' => '中央区',
        'city_412' => '港区',
        'city_413' => '新宿区',
        'city_414' => '文京区',
        'city_415' => '台東区',
        'city_416' => '墨田区',
        'city_417' => '江東区',
        'city_418' => '品川区',
        'city_419' => '目黒区',
        'city_420' => '大田区',
        'city_421' => '世田谷区',
        'city_422' => '渋谷区',
        'city_423' => '中野区',
        'city_424' => '杉並区',
        'city_425' => '豊島区',
        'city_426' => '北区',
        'city_427' => '荒川区',
        'city_428' => '板橋区',
        'city_429' => '練馬区',
        'city_430' => '足立区',
        'city_431' => '葛飾区',
        'city_432' => '江戸川区',
/*
        'city_433' => '八王子市',
        'city_434' => '立川市',
        'city_435' => '武蔵野市',
        'city_436' => '三鷹市',
        'city_437' => '青梅市',
        'city_438' => '府中市',
        'city_439' => '昭島市',
        'city_440' => '調布市',
        'city_441' => '町田市',
        'city_442' => '小金井市',
        'city_443' => '小平市',
        'city_444' => '日野市',
        'city_445' => '東村山市',
        'city_446' => '国分寺市',
        'city_447' => '国立市',
        'city_448' => '福生市',
        'city_449' => '狛江市',
        'city_450' => '東大和市',
        'city_451' => '清瀬市',
        'city_452' => '東久留米市',
        'city_4451' => '武蔵村山市',
        'city_454' => '多摩市',
        'city_455' => '稲城市',
        'city_456' => '羽村市',
        'city_457' => 'あきる野市',
        'city_458' => '西東京市',
        'city_459' => '西多摩郡',
        'city_460' => '大島町',
        'city_461' => '利島村',
        'city_462' => '新島村',
        'city_463' => '神津島村',
        'city_464' => '三宅村',
        'city_465' => '御蔵島村',
        'city_466' => '八丈町',
        'city_467' => '青ヶ島村',
        'city_468' => '小笠原村',
*/
    );

    $fp = fopen('list.csv', 'w');

    foreach ($areas as $areaCode => $areaName) {
        foreach ($types as $typeName) {
            $url = sprintf('http://relax-job.com/search/index.php?type=search&page=0&t_max=1000&s_addr_search=%s&s_type_search=%s',
                    $areaCode, rawurlencode(mb_convert_encoding($typeName, 'euc-jp')));
var_Dump($url);

            try {
                $crawl = new Choose_Crawl;
                $response = $crawl->getResponse($url);
            } catch (Exception $e) {
                var_Dump($e->getMessage());
                exit;
            }

            $dom = new Zend_Dom_Query($response->getBody());
            $res = $dom->query('div.job-box');

            foreach ($res as $job) {

                $line = array(
                    'pref' => '東京都',
                    'area' => $areaName,
                    'type' => $typeName
                );

                $html = $job->ownerDocument->saveXML($job);
                //$html = str_replace('&nbsp;', '', $html);

                $tidy = new Tidy;
                $tidy->parseString($html,
                        array(
                            'hide-comments' => true,
                            'output-xhtml' => true,
                            'quote-nbsp' => false),
                        'utf8');
                $tidy->cleanRepair();

                $dom = new Zend_Dom_Query;
                $dom->setDocumentXml((string)$tidy->body());

                $res = $dom->queryXpath('//h3/a');
                if (count($res)) {
                    $line['name'] = mb_convert_encoding($res->current()->textContent, 'utf8');
                }

                $res = $dom->queryXpath('//a[starts-with(@href,"/shop/")]');
                if (count($res)) {
                    $line['url'] = 'http://relax-job.com'
                            . $res->current()->getAttribute('href');
                }

                $res = $dom->queryXpath('//th[.="利用駅"]/following-sibling::td');
                if (count($res)) {
                    $line['line'] = $res->current()->textContent;
                }

                fputcsv($fp, $line);
            }
        }
    }

    fclose($fp);
}
