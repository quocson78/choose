CREATE TABLE IF NOT EXISTS `shop_images` (
  `img_id`                      bigint       NOT NULL     AUTO_INCREMENT COMMENT '画像ID',
  `shop_id`                     bigint       NOT NULL                    COMMENT '店舗ID',
  `img_type`                    int          DEFAULT 1                   COMMENT '画像タイプ 1:店舗画像 2:店舗ロゴ画像',
  `img_title`                   text         DEFAULT NULL                COMMENT '画像タイトル',
  `img_subtext`                 text         DEFAULT NULL                COMMENT '画像サブテキスト',
  `img_filename`                text         CHARACTER SET ascii DEFAULT NULL COMMENT '画像ファイル名',
  `updated_id`                  int          DEFAULT NULL COMMENT '更新者ID',
  `registed`                    datetime     DEFAULT NULL COMMENT '登録日時',
  `updated`                     datetime     DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`img_id`,`shop_id`),
  INDEX (`img_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店舗画像テーブル';

