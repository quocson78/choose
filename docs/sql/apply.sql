CREATE TABLE IF NOT EXISTS `apply` (
  `apply_id`               BIGINT   AUTO_INCREMENT COMMENT '応募ID',
  `offer_id`               BIGINT                  COMMENT '求人ID',
  `shop_id`                BIGINT                  COMMENT '店舗ID',
  `user_id`                BIGINT                  COMMENT '会員ID',
  `scout_flag`             BOOLEAN  DEFAULT 0      COMMENT 'スカウトフラグ',
  `scout_id`               BIGINT                  COMMENT 'スカウトID',
  `apply_status`           int                     COMMENT '応募ステータス',
  `registed`               DATETIME                COMMENT '登録日時',
  `updated`                DATETIME                COMMENT '更新日時',
  `updated_id`             BIGINT                  COMMENT '更新ID',
  PRIMARY KEY (`apply_id`, `offer_id`,`shop_id`, `user_id`),
  INDEX (`scout_flag`),
  INDEX (`scout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='応募テーブル';
