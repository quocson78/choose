#!/usr/bin/env php
<?php
var_dump(date('h:i:s'));

include_once 'Cli.php';

// CLI 用のオプション
$opts = new Zend_Console_Getopt(array(
            'site|s=w' => 'saitename',
            'genre|g=w' => 'genre',
        ));

try {
    $opts->parse();

    if (!$opts->site) {
        throw new Zend_Console_Getopt_Exception(
                null, $opts->getUsageMessage());
    }
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    return false;
}

try {
    $class = "Choose_Crawl_"
            . ucwords(strtolower($opts->getOption('site')));
var_Dump($class);
    $crawler = new $class($opts);
    $crawler->collect();

} catch (Choose_Exception $e) {
    echo $e->getMessage();
    return false;
}

var_dump(date('h:i:s'));
