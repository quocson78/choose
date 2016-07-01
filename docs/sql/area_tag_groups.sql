/**
レコードタイプ
1:地方
2:都道府県
3:市区町村
4:路線
5:駅
*/
CREATE TABLE IF NOT EXISTS `area_tag_groups` (
    `group_id`    bigint                               NOT NULL     auto_increment COMMENT 'グループID',
    `name`        varchar(128)                         DEFAULT NULL                COMMENT 'グループ名',      
    `record_type` int                                  NOT NULL                    COMMENT 'レコードタイプ',
    `url_name`    varchar(256)    CHARACTER SET ascii  NOT NULL                    COMMENT 'url名',
    `registed`    DATETIME                             DEFAULT NULL                COMMENT '登録日時',
    `updated`     DATETIME                             DEFAULT NULL                COMMENT '更新日時',
    `updated_id`  int                                  DEFAULT 0                   COMMENT '更新ID',
    PRIMARY KEY (`group_id`),
    UNIQUE (`url_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'ロケーション関連のタググループ';
