CREATE TABLE `songlist_albums` (
  `abid`    INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid`     INT(12) UNSIGNED          DEFAULT '0',
  `aids`    MEDIUMTEXT,
  `sids`    MEDIUMTEXT,
  `title`   VARCHAR(128)              DEFAULT NULL,
  `image`   VARCHAR(128)              DEFAULT NULL,
  `path`    VARCHAR(128)              DEFAULT NULL,
  `artists` INT(12) UNSIGNED          DEFAULT '0',
  `songs`   INT(12) UNSIGNED          DEFAULT '0',
  `hits`    INT(12) UNSIGNED          DEFAULT '0',
  `rank`    DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  `votes`   INT(10) UNSIGNED          DEFAULT '0',
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`abid`),
  KEY `SEARCH` (`cid`, `aids`(25), `sids`(25), `title`(10)),
  KEY `BROWSEBY` (`cid`, `title`(1)),
  KEY `SORT` (`rank`, `votes`, `created`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_artists` (
  `aid`     INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cids`    MEDIUMTEXT,
  `sids`    MEDIUMTEXT,
  `name`    VARCHAR(128)              DEFAULT NULL,
  `albums`  INT(12) UNSIGNED          DEFAULT '0',
  `songs`   INT(12) UNSIGNED          DEFAULT '0',
  `rank`    DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  `votes`   INT(10) UNSIGNED          DEFAULT '0',
  `hits`    INT(12) UNSIGNED          DEFAULT '0',
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `SEARCH` (`cids`(25), `sids`(25), `name`(10)),
  KEY `BROWSEBY` (`cids`(25), `name`(1)),
  KEY `SORT` (`rank`, `votes`, `created`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_category` (
  `cid`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid`         INT(10) UNSIGNED          DEFAULT '0',
  `weight`      INT(5) UNSIGNED           DEFAULT '0',
  `name`        VARCHAR(128)              DEFAULT NULL,
  `description` MEDIUMTEXT,
  `image`       VARCHAR(128)              DEFAULT NULL,
  `path`        VARCHAR(128)              DEFAULT NULL,
  `artists`     INT(12) UNSIGNED          DEFAULT '0',
  `albums`      INT(12) UNSIGNED          DEFAULT '0',
  `songs`       INT(12) UNSIGNED          DEFAULT '0',
  `rank`        DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  `votes`       INT(10) UNSIGNED          DEFAULT '0',
  `hits`        INT(12) UNSIGNED          DEFAULT '0',
  `created`     INT(12) UNSIGNED          DEFAULT '0',
  `updated`     INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `SORT` (`pid`, `weight`, `rank`, `votes`, `created`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_extra` (
  `sid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sid`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_requests` (
  `rid`     INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aid`     INT(12) UNSIGNED          DEFAULT NULL,
  `artist`  VARCHAR(128)              DEFAULT NULL,
  `album`   VARCHAR(128)              DEFAULT NULL,
  `title`   VARCHAR(128)              DEFAULT NULL,
  `lyrics`  MEDIUMTEXT,
  `uid`     INT(12) UNSIGNED          DEFAULT '0',
  `name`    VARCHAR(128)              DEFAULT NULL,
  `email`   VARCHAR(255)              DEFAULT NULL,
  `songid`  VARCHAR(32)               DEFAULT NULL,
  `sid`     INT(12) UNSIGNED          DEFAULT '0',
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `SORT` (`songid`(10), `sid`, `created`)
)
  ENGINE = INNODB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_field` (
  `field_id`          INT(12) UNSIGNED     NOT NULL AUTO_INCREMENT,
  `cids`              MEDIUMTEXT,
  `field_type`        VARCHAR(30)          NOT NULL DEFAULT '',
  `field_valuetype`   TINYINT(2) UNSIGNED  NOT NULL DEFAULT '0',
  `field_name`        VARCHAR(255)         NOT NULL DEFAULT '',
  `field_title`       VARCHAR(255)         NOT NULL DEFAULT '',
  `field_description` TEXT,
  `field_required`    TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `field_maxlength`   SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
  `field_weight`      SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
  `field_default`     TEXT,
  `field_notnull`     TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `field_edit`        TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `field_show`        TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `field_config`      TINYINT(1) UNSIGNED  NOT NULL DEFAULT '0',
  `field_options`     TEXT,
  PRIMARY KEY (`field_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_genre` (
  `gid`     INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`    VARCHAR(128)              DEFAULT NULL,
  `artists` INT(12) UNSIGNED          DEFAULT '0',
  `albums`  INT(12) UNSIGNED          DEFAULT '0',
  `songs`   INT(12) UNSIGNED          DEFAULT '0',
  `rank`    DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  `votes`   INT(10) UNSIGNED          DEFAULT '0',
  `hits`    INT(12) UNSIGNED          DEFAULT '0',
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `SORT` (`rank`, `votes`, `created`)
)
  ENGINE = INNODB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_songs` (
  `sid`     INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid`     INT(12) UNSIGNED          DEFAULT '0',
  `gids`    MEDIUMTEXT,
  `vcid`    INT(12) UNSIGNED          DEFAULT '0',
  `aids`    MEDIUMTEXT,
  `abid`    INT(12) UNSIGNED          DEFAULT '0',
  `songid`  VARCHAR(32)               DEFAULT NULL,
  `traxid`  VARCHAR(32)               DEFAULT NULL,
  `title`   VARCHAR(128)              DEFAULT NULL,
  `lyrics`  LONGTEXT,
  `hits`    INT(12) UNSIGNED          DEFAULT '0',
  `rank`    DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  `votes`   INT(10) UNSIGNED          DEFAULT '0',
  `tags`    VARCHAR(255)              DEFAULT NULL,
  `mp3`     VARCHAR(500)              DEFAULT '',
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `SEARCH` (`cid`, `gids`(25), `vcid`, `aids`(25), `abid`, `songid`(10), `traxid`(10), `title`(10), `lyrics`(15)),
  KEY `BROWSEBY` (`cid`, `title`(1), `lyrics`(1)),
  KEY `SORT` (`rank`, `votes`, `created`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_utf8map` (
  `utfid`   INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from`    VARCHAR(2)                DEFAULT NULL,
  `to`      VARCHAR(2)                DEFAULT NULL,
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`utfid`)
)
  ENGINE = INNODB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_visibility` (
  `field_id`      INT(12) UNSIGNED     NOT NULL DEFAULT '0',
  `user_group`    SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `profile_group` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`, `user_group`, `profile_group`),
  KEY `visible` (`user_group`, `profile_group`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_voice` (
  `vcid`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`    VARCHAR(128)              DEFAULT NULL,
  `artists` INT(12) UNSIGNED          DEFAULT '0',
  `albums`  INT(12) UNSIGNED          DEFAULT '0',
  `songs`   INT(12) UNSIGNED          DEFAULT '0',
  `rank`    DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  `votes`   INT(10) UNSIGNED          DEFAULT '0',
  `hits`    INT(12) UNSIGNED          DEFAULT '0',
  `created` INT(12) UNSIGNED          DEFAULT '0',
  `updated` INT(12) UNSIGNED          DEFAULT '0',
  PRIMARY KEY (`vcid`),
  KEY `SORT` (`name`(32), `rank`, `votes`, `created`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `songlist_votes` (
  `vid`     INT(23) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid`     INT(12) UNSIGNED          DEFAULT '0',
  `uid`     INT(12) UNSIGNED          DEFAULT '0',
  `ip`      VARCHAR(64)               DEFAULT NULL,
  `netaddy` VARCHAR(255)              DEFAULT NULL,
  `rank`    DECIMAL(10, 3) UNSIGNED   DEFAULT '0.000',
  PRIMARY KEY (`vid`),
  KEY `SEARCH` (`uid`, `ip`(15), `netaddy`(25))
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
