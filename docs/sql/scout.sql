CREATE TABLE IF NOT EXISTS `scout` (
  `scout_id`               BIGINT   AUTO_INCREMENT COMMENT 'スカウトID',
  `offer_id`               BIGINT                  COMMENT '求人ID',
  `shop_id`                BIGINT                  COMMENT '店舗ID',
  `user_id`                BIGINT                  COMMENT '会員ID',
  `scouted`                DATE                    COMMENT 'スカウト日',
  `scout_limit`            DATE                    COMMENT 'スカウト期限日',
  `refused_flag`           BOOLEAN  DEFAULT 0      COMMENT '辞退フラグ',
  `refused`                DATE                    COMMENT '辞退日',
  `accept_flag`            BOOLEAN  DEFAULT 0      COMMENT '承認フラグ',
  `accepted`               DATE                    COMMENT '承認日',
  `registed`               DATETIME COMMENT '登録日時',
  `updated`                DATETIME COMMENT '更新日時',
  `updated_id`             BIGINT   COMMENT '更新日時',
  PRIMARY KEY (`scout_id`, `offer_id`,`shop_id`, `user_id`),
  INDEX (`refused_flag`),
  INDEX (`accept_flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='応募テーブル';
