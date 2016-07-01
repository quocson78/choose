CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'タグID',
  `upper_id` int(11) DEFAULT NULL COMMENT '大カテゴリ',
  `lower_id` int(11) DEFAULT NULL COMMENT '小カテゴリ',
  `contents` varchar(512) DEFAULT NULL COMMENT 'タグ',
  `modified` int(11) DEFAULT NULL COMMENT '変更日時',
  `updated_id` int(11) DEFAULT NULL COMMENT '更新者ID',
  `registed` datetime DEFAULT NULL COMMENT '登録日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`tag_id`),
  INDEX `upper_id` (`upper_id`),
  INDEX `lower_id` (`lower_id`),
  INDEX `modified` (`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
* ALTER TABLE  `tags` ADD  `order_no` INT NULL DEFAULT NULL COMMENT  '表示順' AFTER  `contents` ,
* ADD INDEX (  `order_no` )
*/

insert into tags(lower_id, contents) values
(7, 'JNA1級'),
(7, 'JNA2級'),
(7, 'JNA3級'),
(7, 'AEA認定エステティシャン'),
(7, 'CIDESCOインターナショナルエステティシャン'),
(7, 'あん摩マッサージ指圧師'),
(7, '鍼灸師 柔道整復師'),
(7, 'JAA認定アロマコーディネーター'),
(7, 'JREC認定リフレクソロジスト'),
(7, 'IHTA認定資格 美容師免許'),
(7, '美容師免許取得予定 理容師'),
(7, 'IBF認定国際メイクアップアーティスト'),
(7, 'あん摩師免許取得予定'),
(7, '鍼灸師免許取得予定'),
(7, '柔整師免許取得予定'),
(7, 'その他');

insert into tags (lower_id, contents) values 
(8, '即日'),
(8, '1ヶ月以内');

insert into tags (lower_id, contents) values 
(20, 'いつでも'),
(20, '9:00～12:00'),
(20, '12:00～15:00'),
(20, '15:00～18:00'),
(20, '18:00～21:00');

ALTER TABLE  `tags` ADD  `tag_name` VARCHAR( 50 ) NULL AFTER  `contents`