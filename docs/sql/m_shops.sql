CREATE TABLE IF NOT EXISTS `m_shops` (
  `shop_id`                     bigint(20)   NOT NULL     AUTO_INCREMENT COMMENT '店舗ID',
  `company_id`                  bigint(20)   DEFAULT NULL COMMENT '企業ID',
  `shopname`                    varchar(256) DEFAULT NULL COMMENT '店舗名',
  `shopname_kana`               varchar(256) DEFAULT NULL COMMENT '店舗名カナ',
  `shop_sub_title`              varchar(100) DEFAULT NULL COMMENT '店舗サブタイトル',
  `pref_id`                     int          DEFAULT NULL COMMENT '都道府県ID',
  `zip_code`                    varchar(8)   CHARACTER SET ascii DEFAULT NULL COMMENT '郵便場号',
  `city_code`                   varchar(50)  CHARACTER SET ascii DEFAULT NULL COMMENT '市区町村',
  `address`                     varchar(128) DEFAULT NULL COMMENT '市区町村以下',
  `official_url`                varchar(256) CHARACTER SET ascii DEFAULT NULL COMMENT '店舗URL',
  `email_address`               varchar(256) CHARACTER SET ascii DEFAULT NULL COMMENT 'emailアドレス',
  `tel`                         varchar(20)  CHARACTER SET ascii DEFAULT NULL COMMENT '電話番号',
  `fax`                         varchar(20)  CHARACTER SET ascii DEFAULT NULL COMMENT 'fax',
  `responsible_first_name`      varchar(20)  DEFAULT NULL COMMENT '担当者(名)',
  `responsible_last_name`       varchar(20)  DEFAULT NULL COMMENT '担当者(姓)',
  `responsible_first_name_kana` varchar(20)  DEFAULT NULL COMMENT '担当者カナ(名)',
  `responsible_last_name_kana`  varchar(20)  DEFAULT NULL COMMENT '担当者カナ(姓)',
  `title`                       text         DEFAULT NULL COMMENT '店舗情報見出し',
  `subtext`                     text         DEFAULT NULL COMMENT 'サブテキスト',
  `owner_name`                  text         DEFAULT NULL COMMENT 'オーナー名',
  `owner_position_name`         text         DEFAULT NULL COMMENT '肩書き',
  `owner_message`               text         DEFAULT NULL COMMENT 'オーナーからのメッセージ',
  `stuff_name`                  text         DEFAULT NULL COMMENT 'スタッフ名',
  `stuff_position_name`         text         DEFAULT NULL COMMENT 'スタッフ肩書き',
  `stuff_message`               text         DEFAULT NULL COMMENT 'スタッフからのメッセージ',
  `active_flag`                 boolean      DEFAULT 0    COMMENT 'アクティブフラグ',
  `crawl_flag`                  boolean      DEFAULT 0    COMMENT 'クロールフラグ',
  `updated_id`                  int(11)      DEFAULT NULL COMMENT '更新者ID',
  `registed`                    datetime     DEFAULT NULL COMMENT '登録日時',
  `updated`                     datetime     DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`shop_id`),
  INDEX (`company_id`),
  INDEX (`active_flag`),
  INDEX (`updated_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店舗情報テーブル';

INSERT INTO m_shops (shopname, shopname_kana) VALUES ('Groove Gear.Inc', 'グルーヴ・ギア株式会社');

CREATE TABLE IF NOT EXISTS `shop_images` (
  `shop_id`                     bigint(20)   NOT NULL                COMMENT '店舗ID',
  `image_id`                    bigint(20)   NOT NULL AUTO_INCREMENT COMMENT '写真ID',
  `image_filename`              text         DEFAULT NULL            COMMENT '写真ファイル名',
  `registed`                    datetime     DEFAULT NULL COMMENT '登録日時',
  `updated`                     datetime     DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY ( `shop_id`, `image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店舗毎の写真';

CREATE TABLE IF NOT EXISTS `shop_image_display` (
  `shop_id`                     bigint(20)   NOT NULL                   COMMENT '店舗ID',
  `image_title`                   text         DEFAULT NULL                COMMENT '画像タイトル',
  `image_subtext`                 text         DEFAULT NULL                COMMENT '画像サブテキスト',
  `r_type`                      int          NOT NULL                   COMMENT '表示タイプ',
  `image_id`                    bigint(20)   NOT NULL                   COMMENT '写真ID',
  `order_no`                    int          DEFAULT 1                  COMMENT '並び順',
  PRIMARY KEY (`shop_id`, `image_id`,`r_type`),
  UNIQUE (`shop_id`,`r_type`,`order_no`),
  FOREIGN KEY (`shop_id`, `image_id`) REFERENCES shop_images(`shop_id`, `image_id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='写真表示テーブル';

CREATE TABLE IF NOT EXISTS `shop_recommend` (
  `recommend_id`           bigint                    AUTO_INCREMENT    COMMENT 'おすすめID',
  `shop_id`                bigint                                      COMMENT '店舗ID',
  `recommend_title`        text                                        COMMENT 'おすすめタイトル',
  `recommend_subtitle`     text                                        COMMENT 'おすすめサブテキスト',
  `registed`               datetime                                    COMMENT '登録日時',
  `updated`                datetime                                    COMMENT '更新日時',
  PRIMARY KEY (`recommend_id`, `shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='おすすめポイントテーブル';
