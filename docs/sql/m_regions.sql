CREATE TABLE IF NOT EXISTS `m_regions` (
  `region_id`  int                               NOT NULL COMMENT '地方ID',
  `name`       varchar(50)                       NOT NULL COMMENT '地方名',
  `name_rome`  varchar(100)  CHARACTER SET ascii NOT NULL COMMENT '地方名ローマ字',
  PRIMARY KEY (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='地方マスタ';

INSERT INTO `m_regions` (`region_id`, `name`, `name_rome`) VALUES
(1, '北海道', 'hokkaido'),
(2, '東北', 'tohoku'),
(3, '関東', 'kanto'),
(4, '北陸', 'hokuriku'),
(5, '中部', 'chubu'),
(6, '近畿', 'kinki'),
(7, '中国・四国', 'chushikoku'),
(8, '九州・沖縄', 'kyushuokinawa');


CREATE TABLE IF NOT EXISTS `m_prefs` (
  `pref_id`    int(11)      NOT NULL COMMENT '都道府県コード',
  `name`       varchar(50)  NOT NULL COMMENT '都道府県名',
  `name_rome`  varchar(100) CHARACTER SET ascii NOT NULL COMMENT '都道府県名ローマ字',
  `region_id`  int          NOT NULL COMMENT '地方ID',
  PRIMARY KEY (`pref_id`),
  INDEX (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='都道府県マスタ（駅データ.jpより）';

INSERT INTO `m_prefs` (`pref_id`, `name`, `name_rome`, `region_id`) VALUES
(1, '北海道', 'hokkaido', 1),
(2, '青森県', 'aomori', 2),
(3, '岩手県', 'iwate', 2),
(4, '宮城県', 'miyagi', 2),
(5, '秋田県', 'akita', 2),
(6, '山形県', 'yamagata', 2),
(7, '福島県', 'hukushima', 2),
(8, '茨城県', 'ibaraki', 3),
(9, '栃木県', 'tochigi', 3),
(10, '群馬県', 'gunma', 3),
(11, '埼玉県', 'saitama', 3),
(12, '千葉県', 'chiba', 3),
(13, '東京都', 'tokyo', 3),
(14, '神奈川県', 'kanagawa', 3),
(15, '新潟県', 'niigata', 4),
(16, '富山県', 'toyama', 4),
(17, '石川県', 'ishikawa', 4),
(18, '福井県', 'hukui', 4),
(19, '山梨県', 'yamanashi', 4),
(20, '長野県', 'nagano', 4),
(21, '岐阜県', 'gihu', 5),
(22, '静岡県', 'shizuoka', 5),
(23, '愛知県', 'aichi', 5),
(24, '三重県', 'mie', 5),
(25, '滋賀県', 'shiga', 6),
(26, '京都府', 'kyoto', 6),
(27, '大阪府', 'osaka', 6),
(28, '兵庫県', 'hyogo', 6),
(29, '奈良県', 'nara', 6),
(30, '和歌山県', 'wakayama', 6),
(31, '鳥取県', 'tottori', 7),
(32, '島根県', 'shimane', 7),
(33, '岡山県', 'okayama', 7),
(34, '広島県', 'hiroshima', 7),
(35, '山口県', 'yamaguchi', 7),
(36, '徳島県', 'tokushima', 7),
(37, '香川県', 'kagawa', 7),
(38, '愛媛県', 'ehime', 7),
(39, '高知県', 'kochi', 7),
(40, '福岡県', 'hukuoka', 8),
(41, '佐賀県', 'saga', 8),
(42, '長崎県', 'nagasaki', 8),
(43, '熊本県', 'kumamoto', 8),
(44, '大分県', 'oita', 8),
(45, '宮崎県', 'miyazaki', 8),
(46, '鹿児島県', 'kagoshima', 8),
(47, '沖縄県', 'okinawa', 8);


CREATE TABLE IF NOT EXISTS `m_lines` (
  `line_id`      BIGINT       NOT NULL COMMENT '路線コード',
  `company_id`   BIGINT       NOT NULL COMMENT '事業者コード',
  `name`         varchar(80)  COMMENT '路線名称(一般)',
  `alias`        varchar(80)  COMMENT '路線名称(略称)',
  `kana`         varchar(80)  COMMENT '路線名称(一般・カナ)',
  `rome`         varchar(80)  COMMENT '路線名称(ローマ字)',
  `order_no`     int          COMMENT '並び順',
  PRIMARY KEY (`line_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='路線マスタ（駅データ.jpより）';

CREATE TABLE IF NOT EXISTS `m_stations` (
  station_id       BIGINT       COMMENT '駅コード',
  station_g_id     BIGINT       COMMENT '駅グループコード',
  station_name     varchar(80)  COMMENT '駅名称',
  station_name_k   varchar(80)  COMMENT '駅名称(カナ)',
  station_name_r   varchar(200) COMMENT '駅名称(ローマ字)',
  line_id          BIGINT       COMMENT '路線コード',
  pref_id          int          COMMENT '都道府県コード',
  post             varchar(10)  COMMENT '駅郵便番号',
  address          varchar(300) COMMENT '住所',
  lon              DECIMAL(20,17)      COMMENT '経度',
  lat              DECIMAL(20,17)      COMMENT '緯度',
  open_ymd         DATE         COMMENT '開業年月日',
  close_ymd        DATE         COMMENT '廃止年月日',
  e_status         int          COMMENT '状態 0:運用中　1:運用前　2:廃止',
  e_sort           BIGINT       COMMENT '並び順',
  PRIMARY KEY (`station_id`),
  INDEX (`station_g_id`),
  INDEX (`line_id`),
  INDEX (`pref_id`),
  INDEX (`e_status`),
  INDEX (`e_sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='駅マスタ（駅データ.jpより）';

ALTER TABLE  `m_station` CHANGE  `lon` DECIMAL(20, 17)  NULL COMMENT  '緯度';
ALTER TABLE  `m_station` CHANGE  `lat` DECIMAL(20, 17)  NULL COMMENT  '経度';

DELETE FROM  `m_stations` WHERE  `line_id` =99809

CREATE TABLE IF NOT EXISTS `m_cities` (
  `city_id`    BIGINT       NOT NULL auto_increment COMMENT '市区町村ID',
  `name`       varchar(50)  NOT NULL                COMMENT '市区町村名',
  `name_kana`  varchar(50)  NOT NULL                COMMENT '市区町村名かな',
  `pref_id`    int(50)      NOT NULL                COMMENT '都道府県ID',
  PRIMARY KEY (`city_id`),
  INDEX (`pref_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='市区町村マスタ';

CREATE TABLE `ad_address` (
  `id`      int(9) NOT NULL default 0,
  `pref_id`  int(2) default NULL,
  `city_code` int(5) default NULL,
  `town_code` int(9) default NULL,
  `zip_code` varchar(8) CHARACTER SET ascii default NULL,
  `office_flg` tinyint(1) default NULL,
  `delete_flg` tinyint(1) default NULL,
  `pref_name`     varchar(8) default NULL,
  `pref_furi`     varchar(8) default NULL,
  `city_name`    varchar(24) default NULL,
  `city_furi`    varchar(24) default NULL,
  `town_name`    varchar(32) default NULL,
  `town_furi`    varchar(32) default NULL,
  `town_memo`    varchar(16) default NULL,
  `kyoto_street` varchar(32) default NULL,
  `block_name`   varchar(64) default NULL,
  `block_furi`   varchar(64) default NULL,
  `memo`         varchar(255) default NULL,
  `office_name`  varchar(255) default NULL,
  `office_furi`  varchar(255) default NULL,
  `office_address` varchar(255) default NULL,
  `new_id` int(11) default NULL, 
  PRIMARY KEY  (`id`),
  INDEX (`pref_id`),
  INDEX (`city_code`),
  INDEX (`town_code`),
  INDEX (`zip_code`),
  INDEX (`office_flg`),
  INDEX (`delete_flg`)
) COMMENT = '郵便番号と市区町村を紐づけ　http://jusyo.jp/';
