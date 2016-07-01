CREATE TABLE IF NOT EXISTS `crawl_offers` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '通番',

  `offer_id` bigint(20) DEFAULT NULL COMMENT '求人ID',

  `shopname` varchar(256) NOT NULL COMMENT '店舗名',
  `type` varchar(256) NOT NULL COMMENT '店舗タイプ カテゴリ',
  `zip_code` varchar(8) NOT NULL COMMENT '勤務地郵便場号',

  `prefecture` text COMMENT '都道府県',
  `city` text COMMENT '市区町村郡',
  `address` varchar(128) NOT NULL COMMENT '住所',

  `tel` varchar(20) NOT NULL COMMENT '電話番号',
  `url` varchar(128) CHARACTER SET ascii NOT NULL COMMENT 'official site URL',
  `work_type` text COMMENT '職種',
  `work_detail` text COMMENT '仕事内容',
  `work_location` text COMMENT '勤務地',
  `income` text COMMENT '給与',
  `treats` text COMMENT '待遇',
  `holidays` text COMMENT '休日',
  `employment` text COMMENT '雇用形態',
  `time` text COMMENT '勤務時間',

  `html` text COMMENT 'ソース',
  `html_updated` date NOT NULL COMMENT 'ソース更新日',

  `checked_flag` tinyint(1) DEFAULT '0' COMMENT '人間チェックフラグ',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '削除フラグ',
  `registed` datetime DEFAULT NULL COMMENT '登録日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  `updated_id` int(11) DEFAULT NULL COMMENT '更新者ID',

  PRIMARY KEY (`no`),
  UNIQUE KEY `offer_id` (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='クロール直後の生求人';
