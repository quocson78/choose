/** タグ管理にしなければいけないもの
業種                             :1
職種                             :2
勤務形態                         :3
募集年齢                         :4
募集性別                         :5
応募資格                         :6
勤務時間(開始時間)               :7
実働時間                         :8
待遇                             :9
休日                             :10
INSERT INTO m_tag_groups (lower_id, name) VALUE 
(1,  '業界'), 
(2,  '業種'), 
(3,  '職種'), 
(4,  '勤務形態'), 
(5,  '年齢'), 
(6,  '性別'), 
(7,  '資格'), 
(8,  '勤務開始時間'), 
(9,  '実働時間'), 
(10, '待遇'), 
(11, '休日'),
(12, '地方'),
(13, '都道府県'),
(14, '市区町村'),
(15, '路線'),
(16, '駅名')
;

 */
CREATE TABLE IF NOT EXISTS `m_tag_groups` (
  `lower_id` int(11) NOT NULL DEFAULT '0' COMMENT 'タグID',
  `name` varchar(100) DEFAULT NULL COMMENT '名前',
  `delete_flag` tinyint(4) DEFAULT '0' COMMENT '削除フラグ',
  `updated_id` int(11) DEFAULT '0' COMMENT '更新者ID',
  `registed` datetime DEFAULT NULL COMMENT '登録日時',
  `updated` datetime DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`lower_id`),
  INDEX `delete_flag` (`delete_flag`),
  INDEX `updated_id` (`updated_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO  `choose`.`m_tag_groups` (
`lower_id` ,
`name` ,
`delete_flag` ,
`updated_id` ,
`registed` ,
`updated`
)
VALUES (
'20',  '連絡可能時間帯',  '0',  '0', NULL , NULL
);
