CREATE TABLE IF NOT EXISTS `crawl_urls` (
  `url` varchar(128) NOT NULL COMMENT 'official site URL',
  `name` text COMMENT '店舗名',
  `tel` text COMMENT '電話番号',
  `zip_code` text COMMENT '郵便番号',
  `prefecture` text COMMENT '都道府県',
  `city` text COMMENT '市区町村郡',
  `address` text COMMENT '住所',
  `type` text COMMENT '店舗タイプ（業種）',
  `first_access_date` datetime DEFAULT NULL COMMENT '初回アクセス日',
  `last_access_date` datetime DEFAULT NULL COMMENT '最終アクセス日',
  `crawled_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'クロール済みフラグ',
  `disable_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'クロール除外フラグ',
  `recruit_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '求人ページ発見フラグ',
  PRIMARY KEY (`url`),
  KEY `last_access_date` (`last_access_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店舗URL収集';