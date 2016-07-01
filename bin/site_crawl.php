#!/usr/bin/env php
<?php
include_once 'Cli.php';

chdir(dirname(__FILE__));
mb_regex_encoding('UTF-8');

main();


function main()
{
    $db = Choose_Db_Table_Abstract::getDefaultAdapter();

    $crawlUrls = new App_Model_DbTable_CrawlUrls;
    $crawlOffers = new App_Model_DbTable_CrawlOffers;

    $select = $crawlUrls->select()
                ->where('NOT crawled_flag')
//                ->where('NOT disable_flag')
                ->order('RAND()')
//                ->limit('1');
    ;
    
    $stmt = $db->query($select);

    while ($row = $stmt->fetch()) {

var_dump($row['name']);

        $updateWhere = $db->quoteInto('url = ?', $row['url']);

        // BLOG系とかREJOBは除外
        if (isDenyUrl($row['url'])) {
            $where = $db->quoteInto('url = ?', $row['url']);
            $crawlUrls->update(
                    array(
                        'crawled_flag' => 1,
                        'disable_flag' => 1
                    ),
                    $updateWhere);
            continue;
        }

        // URL存在確認
        try {
            $crawl = new Choose_Crawl;
            $crawl->getResponse($row['url']);
        } catch (Exception $e) {
            $where = $db->quoteInto('url = ?', $row['url']);
            $crawlUrls->update(
                    array(
                        'crawled_flag' => 1,
                        'disable_flag' => 1
                    ),
                    $updateWhere);
            continue;
        }

        $recruitUrl = array();

//        $cmd = 'wget -q -r --level=2 --random-wait --timeout=5 --tries=1 -A html,htm -nH -P html ' . escapeshellarg($row['url']);
        $cmd = 'wget -q -r --level=2 --timeout=5 --tries=1 -A html,htm -nH -P html ' . escapeshellarg($row['url']);
        system('rm -rf ./html');
        system($cmd);

        $line = `find ./html -type f -print`;
        $line = explode("\n", trim($line));

        if (!count($line)) {
            $where = $db->quoteInto('url = ?', $row['url']);
            $crawlUrls->update(
                    array(
                        'crawled_flag' => 1,
                        'disable_flag' => 1
                    ),
                    $updateWhere);
            continue;
        }

var_dump($row['url']);

        $recruitUrl = null;
        foreach ($line as $file) {
            $recruitUrl = find_reqruit($row, $file);
        }

        if (!count($recruitUrl)) {
            $crawlUrls->update(
                    array(
                        'crawled_flag' => 1,
                    ),
                    $updateWhere);

            $offerDetail = null;
            $offerDetail['url'] = $row['url'];
            $offerDetail['shopname'] = $row['name'];
            $offerDetail['tel'] = $row['tel'];
            $offerDetail['zip_code'] = $row['zip_code'];
            $offerDetail['pref_id'] = $row['pref_id'];
            $offerDetail['city_code'] = $row['city_code'];
            $offerDetail['address'] = $row['address'];
            $offerDetail['type'] = $row['type'];
            $offerDetail['checked_flag'] = 0;

            $crawlOffers->insertDuplicate($offerDetail);

            continue;
        }

        $crawlUrls->update(
                array(
                    'crawled_flag' => 1,
                    'recruit_flag' => 1,
                ),
                $updateWhere);

        // 求人情報取得
        foreach (array_unique($recruitUrl) as $uri) {

            $file = './html/recruit.search.text';
            $cmd = "wget -q  -O {$file} " . escapeshellarg($uri);
            system($cmd);

            $time = date("Y-m-d H:i:s", filemtime($file));

            $cmd = "nkf -w8 --overwrite {$file}" ;
            system($cmd);

            $tidy = new Tidy($file, array(), 'utf8');
            $tidy->cleanRepair();
            $html = $tidy->html();

            $dom = new Zend_Dom_Query($html);

            if (!$dom->query('body')) continue;

            $body = $dom->query('body')->current()->C14N();
            $body = preg_replace('|<script.*?script>|', '', $body);
            $body = str_replace(array("\r", "\n", '</br>'), '', $body);
            $body = preg_replace('{<[^br].*?>}', "\n", $body);

            $offer = new Offer($body);
            $offerDetail = $offer->getAll();

            if ($offerDetail) {
                $offerDetail['url'] = $row['url'];
                $offerDetail['shopname'] = $row['name'];
                $offerDetail['tel'] = $row['tel'];
                $offerDetail['zip_code'] = $row['zip_code'];
                $offerDetail['pref_id'] = $row['pref_id'];
                $offerDetail['city_code'] = $row['city_code'];
                $offerDetail['address'] = $row['address'];
                $offerDetail['type'] = $row['type'];
                $offerDetail['checked_flag'] = 0;

                $offerDetail['recruit_url'] = $uri;

                $offerDetail['html'] = $html;
                $offerDetail['html_updated'] = $time;

                $crawlOffers->insertDuplicate($offerDetail);
            }

        }
    }
}

function isDenyUrl($url) {
    $denies = array(
        'ameblo.jp',
        'relax-job.com',
        'hotpepper.jp',
        'ispot.jp',
        'walkerplus.com',
        'fc2.com'
    );

    foreach ($denies as $deny) {
        if (false !== strpos($url, $deny)) {
            return true;
        }
    }

    return false;
}

function find_reqruit($row, $file)
{
    // 文字化けてファイルが取れないこともある
    if (!file_exists($file))
        return null;

    $recruitUrl = null;

    try {
        // 相対パス生成
        $path = str_replace('./html', '', dirname($file));

        $cmd = "nkf -w8 -Lu --overwrite {$file}" ;
        system($cmd);

        $tidy = new Tidy($file, array(), 'utf8');
        $tidy->cleanRepair();

        $dom = new Zend_Dom_Query($tidy->html());

        // 求人に関わりそうなモノがあるかザックリ探す
        $body = $dom->query('body');
        $text = $body->current()->textContent;


        $recruit = array(
            '採用',
            '求人',
            'recruit',
            'Recruit',
            'RECRUIT',
            '人材募集',
            'スタッフ募集',
            'staff募集',
            'Staff募集',
            'STAFF募集',
        );
        if (preg_match_all('/' . implode('|', $recruit) . '/', $text, $matchs)) {

            $recruitUrl = null;

            foreach ($matchs[0] as $key) {
                $node = $dom->queryXpath("//a[contains(., '{$key}')]");
                if (!count($node)) {
                    $node = $dom->queryXpath("//a/img[@alt='{$key}']");
                }

                foreach ($node as $a) {

                    $uri = $a->getAttribute('href');

                    if (0 === strpos($uri, 'http')) {
                        ;
                    } elseif (0 === strpos($uri, '/')) {
                        $uri = "{$row['url']}{$uri}";
                    } else {
                        $uri = "{$row['url']}{$path}/{$uri}";
                    }

                    $recruitUrl[] = $uri;
                }
            }
        }

    } catch (Exception $e) {
        var_dump($e->getMessage());
    }

    return $recruitUrl;
}


class Offer
{
    protected $_text;

    public function __construct($text)
    {
        $this->_text = $text;
    }

    protected function scrape($keys)
    {
        foreach ($keys as $key) {
            $key = implode('\s*?', $this->_split($key));

            if (mb_ereg(".*?{$key}.*?\n+([^\n]*)", $this->_text, $match)) {
                return trim(preg_replace('|<br.*?>|', "\n", $match[1]));
            }

            if (mb_ereg(".*?{$key}[^\n]*?\n*([^\n]*)", $this->_text, $match)) {
                return trim(preg_replace('|<br.*?>|', "\n", $match[1]));
            }
        }

        return null;
    }

    protected function _split($str)
    {
        $res = array();

        while ($iLen = mb_strlen($str)) {
            array_push($res, mb_substr($str, 0, 1));
            $str = mb_substr($str, 1, $iLen);
        }

        return $res;
    }

    public function getAll()
    {
        $keys = array(
            'work_type' => array('職種', '業種', '募集要項'),
            'work_contents' => array('内容'),
            'work_location' => array('勤務地', '住所'),
            'income' => array('給与'),
            'treatment' => array('福利厚生', '待遇', '賞与'),
            'holidays' => array('休日', '休暇'),
            'employment' => array('雇用形態'),
            'time' => array('勤務時間', '営業時間'),
        );

        $offer = null;
        foreach ($keys as $colmun => $key) {
            $offer[$colmun] = $this->scrape($key);
        }

        return $offer;
    }
}
