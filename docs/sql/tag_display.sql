CREATE TABLE IF NOT EXISTS `tag_display` (
  `tag_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'タグID',
  `parent_id` bigint(20) DEFAULT '0' COMMENT '親のタグID',
  `order` int(11) DEFAULT '0' COMMENT '表示順序',
  `updated_id` int(11) DEFAULT '0' COMMENT '更新者ID',
  `registed` datetime DEFAULT NULL COMMENT '登録日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`tag_id`),
  INDEX `updated_id` (`updated_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
