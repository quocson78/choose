CREATE TABLE IF NOT EXISTS `m_shop_accounts` (
  `account_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'アカウントID',
  `shop_id` bigint(20) DEFAULT NULL COMMENT '店舗ID',
  `first_name` varchar(20) DEFAULT NULL COMMENT '氏名（名）',
  `last_name` varchar(20) DEFAULT NULL COMMENT '氏名（姓）',
  `first_name_kana` varchar(20) DEFAULT NULL COMMENT '氏名カナ（名）',
  `last_name_kana` varchar(20) DEFAULT NULL COMMENT '氏名カナ（姓）',
  `email_address` varchar(256) DEFAULT NULL COMMENT 'メールアドレス',
  `password` varchar(128) CHARACTER SET ascii NOT NULL COMMENT 'パスワード',
  `roll_type` int(11) NOT NULL COMMENT '権限の種類',
  `active_flag` tinyint(4) DEFAULT NULL COMMENT 'アクティブフラグ',
  `updated_id` int(11) DEFAULT NULL COMMENT '更新者ID',
  `registed` datetime DEFAULT NULL COMMENT '登録日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`account_id`),
  INDEX `shop_id` (`shop_id`),
  INDEX `active_flag` (`active_flag`),
  INDEX `updated_id` (`updated_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店舗のアカウント';
