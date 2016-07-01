CREATE TABLE IF NOT EXISTS `area_tags` (
    `tag_id`      bigint                               NOT NULL     auto_increment COMMENT 'タグID',
    `group_id`    bigint                               NOT NULL                    COMMENT 'グループID',
    `area_code`   varchar(100)  CHARACTER SET ascii    NOT NULL                    COMMENT 'エリアコード',
    `registed`    DATETIME                             DEFAULT NULL                COMMENT '登録日時',
    `updated`     DATETIME                             DEFAULT NULL                COMMENT '更新日時',
    `updated_id`  int                                  DEFAULT 0                   COMMENT '更新ID',
    PRIMARY KEY (`group_id`),
    INDEX (`url_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'ロケーション関連のタグ';

INSERT INTO area_tag_groups (name, record_type, url_name, registed, updated) VALUES ('池袋・目白・大塚', 4, 'ikebukuro-mejiro-ootuka', CURDATE(), CURDATE());
INSERT INTO area_tags (group_id, area_code, registed, updated) VALUES (1,131130, CURDATE(), CURDATE());
INSERT INTO area_tags (group_id, area_code, registed, updated) VALUES (1,131041, CURDATE(), CURDATE());
INSERT INTO area_tags (group_id, area_code, registed, updated) VALUES (1,131032, CURDATE(), CURDATE());

