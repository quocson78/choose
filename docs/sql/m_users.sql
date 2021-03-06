CREATE TABLE IF NOT EXISTS `m_users` (
  `user_id`                bigint(20)                       NOT NULL AUTO_INCREMENT COMMENT '会員ID',
  `first_name`             varchar(20)                      NOT NULL     COMMENT '氏名（名）',
  `last_name`              varchar(20)                      NOT NULL     COMMENT '氏名（姓）',
  `first_name_kana`        varchar(20)                      DEFAULT NULL COMMENT '氏名かな（名）',
  `last_name_kana`         varchar(20)                      DEFAULT NULL COMMENT '氏名かな（姓）',
  `gender`                 boolean                          DEFAULT '0'  COMMENT '性別',
  `birth_day`              int(11)                          DEFAULT NULL COMMENT '生年月日',
  `zip_code`               varchar(7)                       DEFAULT NULL COMMENT '郵便場号',
  `pref_id`                int(11)                          DEFAULT NULL COMMENT '都道府県コード',
  `city`                   varchar(50)                      DEFAULT NULL COMMENT '市区町村',
  `address`                varchar(128)                     DEFAULT NULL COMMENT '市区町村以下',
  `nearby_station`         varchar(20)                      DEFAULT NULL COMMENT '最寄駅',
  `email_address`          varchar(256) CHARACTER SET ascii DEFAULT NULL COMMENT 'emailアドレス',
  `tel`                    varchar(20)                      DEFAULT NULL COMMENT '電話番号',
  `password`               varchar(128) CHARACTER SET ascii NOT NULL     COMMENT 'パスワード',
  `work_type`              int(11)                          DEFAULT NULL COMMENT '希望勤務形態',
  `start_work`             int(11)                          DEFAULT NULL COMMENT '勤務開始可能日',
  `had_operational`        int(11)                          DEFAULT NULL COMMENT '業務経験',
  `had_manager`            boolean                          DEFAULT NULL COMMENT '店長経験',
  `career`                 int(11)                          DEFAULT NULL COMMENT '経歴',
  `self_pr`                text                                          COMMENT '自己PR、職歴など',
  `self_pr_for_scout`      text                                          COMMENT '自己PR（スカウト用）',
  `qualification`          int(11) DEFAULT '0'                           COMMENT '保持資格',
  `contact_time_zone`      int(11) DEFAULT '0'                           COMMENT '連絡可能時間帯',
  `accept_scout`           int(11) DEFAULT '1'                           COMMENT '店舗からのスカウト',
  `accept_auto_scout`      int(11) DEFAULT '1'                           COMMENT '自動処理によるスカウト',
  `accept_mail_magazine`   boolean DEFAULT NULL                          COMMENT 'Chooseからのメールマガジン',
  `job_status`             int(11) DEFAULT '0'                           COMMENT '求職状態',
  `nearby_line`            varchar(128) DEFAULT NULL                     COMMENT '自宅最寄路線',
  `expect_line1`           varchar(128) DEFAULT NULL                     COMMENT '第1希望路線',
  `expect_line2`           varchar(128) DEFAULT NULL                     COMMENT '第2希望路線',
  `expect_line3`           varchar(128) DEFAULT NULL                     COMMENT '第3希望路線',
  `expect_area`            varchar(128) DEFAULT NULL                     COMMENT '希望勤務地',
  `possible_commuter_time` varchar(128) DEFAULT NULL                     COMMENT '通勤可能時間',
  `expect_income`          varchar(128) DEFAULT NULL                     COMMENT '希望給与',
  `another_expect`         text                                          COMMENT 'その他要望',
  `expect_work_period`     int(11)      DEFAULT '0'                      COMMENT '勤務可能時間帯',
  `self_picture`           varchar(256) DEFAULT NULL                     COMMENT '自己PR用写真',
  `updated_id`             int(11)      DEFAULT NULL                     COMMENT '更新者ID',
  `is_preopen`             boolean      DEFAULT 0                        COMMENT 'プレーオープン登録',
  `is_leaved`              boolean      DEFAULT 0                        COMMENT '退会フラグ',
  `is_active`              boolean      DEFAULT 0                        COMMENT 'アクティベートフラグ',
  `leaved`                 datetime     DEFAULT NULL                     COMMENT '退会日時',
  `pre_registed`           datetime     DEFAULT NULL                     COMMENT 'プレ登録日時',
  `activated`              datetime     DEFAULT NULL                     COMMENT 'アクティベート日時',
  `registed`               datetime     DEFAULT NULL                     COMMENT '登録日時',
  `updated`                datetime     DEFAULT NULL                     COMMENT '更新日時',
  PRIMARY KEY (`user_id`),
  INDEX (`is_leaved`),
  INDEX (`is_active`),
  INDEX (`is_preopen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会員マスタ';
    
CREATE TABLE IF NOT EXISTS `pre_user_hash` (
    `user_id`      bigint(20)                       NOT NULL  COMMENT '会員ID',
    `hash`         varchar(128) CHARACTER SET ascii NOT NULL  COMMENT 'ハッシュ',
    `pre_registed` datetime                         NOT NULL  COMMENT '登録日時',
    `period`       int                              DEFAULT 0 COMMENT '有効期間（日）',
    PRIMARY KEY (`user_id`),
    INDEX (`hash`),
    INDEX (`pre_registed`),
    INDEX (`period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='仮登録会員のidハッシュを保存（期限切れで物理削除）';

ALTER TABLE  `m_users` CHANGE  `password`  `password` VARCHAR( 128 ) CHARACTER SET ASCII COLLATE ascii_general_ci NULL COMMENT  'パスワード';
ALTER TABLE  `m_users` ADD  `skill_etc` VARCHAR( 100 ) NOT NULL COMMENT 'その他のスキル' AFTER  `self_pr` ;

ALTER TABLE  `m_users` ADD  `skill_etc` VARCHAR( 100 ) NOT NULL AFTER  `self_pr`;
ALTER TABLE  `m_users` CHANGE  `skill_etc`  `skill_etc` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;