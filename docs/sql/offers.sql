CREATE TABLE `offers` (
  `offer_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '求人ID',
  `shop_id` bigint(20) DEFAULT NULL COMMENT '店舗ID',
  `shopname` varchar(256) DEFAULT NULL COMMENT '店舗名',
  `shopname_kana` varchar(256) DEFAULT NULL COMMENT '店舗名カナ',
  `pref_id` int(11) DEFAULT NULL,
  `zip_code` varchar(8) CHARACTER SET ascii DEFAULT NULL COMMENT '勤務地郵便場号',
  `city_code` varchar(50) CHARACTER SET ascii DEFAULT NULL COMMENT '勤務地市区町村コード',
  `address` varchar(128) DEFAULT NULL COMMENT '勤務地市区町村以下',
  `tel` varchar(20) CHARACTER SET ascii DEFAULT NULL COMMENT '勤務先電話番号',
  `fax` varchar(20) CHARACTER SET ascii DEFAULT NULL COMMENT 'fax',
  `official_url` varchar(256) CHARACTER SET ascii DEFAULT NULL COMMENT '店舗URL',
  `interview_location` text COMMENT '面接地',
  `responsible_first_name` varchar(20) DEFAULT NULL COMMENT '担当者(名)',
  `responsible_last_name` varchar(20) DEFAULT NULL COMMENT '担当者(姓)',
  `responsible_first_name_kana` varchar(20) DEFAULT NULL COMMENT '担当者カナ(名)',
  `responsible_last_name_kana` varchar(20) DEFAULT NULL COMMENT '担当者カナ(姓)',
  `offer_title` text NOT NULL COMMENT '求人タイトル',
  `offer_subtext` text NOT NULL COMMENT '求人サブテキスト',
  `work_contents` text NOT NULL COMMENT '仕事内容',
  `income` text NOT NULL COMMENT '給料',
  `treatment` text NOT NULL COMMENT '待遇',
  `holidays` text NOT NULL COMMENT '休日',
  `offer_age` text NOT NULL COMMENT '募集年齢',
  `app_qualification` text NOT NULL COMMENT '応募資格',
  `reception` text NOT NULL COMMENT '歓迎項目',
  `work_time` text NOT NULL COMMENT '勤務時間',
  `note` text NOT NULL COMMENT 'その他',
  `publish_start_date` date DEFAULT NULL COMMENT '公開日',
  `publish_end_date` date DEFAULT NULL COMMENT '公開日',
  `publish_flag` tinyint(1) DEFAULT '0' COMMENT '公開フラグ',
  `updated_id` int(11) DEFAULT NULL COMMENT '更新者ID',
  `delete_flag` tinyint(1) DEFAULT '0' COMMENT '削除フラグ',
  `crawl_flag` tinyint(1) DEFAULT '0' COMMENT 'クローラー求人フラグ',
  `search_text` text COMMENT '全文検索用',
  `recommend_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'おすすめ求人フラグ',
  `recommend_start` date DEFAULT NULL COMMENT 'おすすめ期間開始',
  `recommend_end` date DEFAULT NULL COMMENT 'おすすめ期間終り',
  `shop_typename` text,
  `order_no` bigint(20) DEFAULT NULL COMMENT '一覧表示順序',
  `registed` datetime DEFAULT NULL COMMENT '登録日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`offer_id`),
  KEY `shop_id` (`shop_id`),
  KEY `city_code` (`city_code`),
  KEY `updated_id` (`updated_id`),
  KEY `recommend_flag` (`recommend_flag`),
  KEY `recommend_start` (`recommend_start`),
  KEY `recommend_end` (`recommend_end`),
  KEY `order_no` (`order_no`),
  FULLTEXT KEY `search_text` (`search_text`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='求人テーブル';

CREATE TABLE IF NOT EXISTS `shop_recommend` (
  `recommend_id`           bigint                    AUTO_INCREMENT    COMMENT 'おすすめID',
  `shop_id`                bigint                                      COMMENT '店舗ID',
  `recommend_title`        text                                        COMMENT 'おすすめタイトル',
  `recommend_subtitle`     text                                        COMMENT 'おすすめサブテキスト',
  `registed`               datetime                                    COMMENT '登録日時',
  `updated`                datetime                                    COMMENT '更新日時',
  PRIMARY KEY (`recommend_id`, `offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='おすすめポイントテーブル';