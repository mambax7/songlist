CREATE TABLE `songlist_albums` (
  `abid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid` int(12) unsigned DEFAULT '0',
  `aids` mediumtext,
  `sids` mediumtext,
  `title` varchar(128) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `path` varchar(128) DEFAULT NULL,
  `artists` int(12) unsigned DEFAULT '0',
  `songs` int(12) unsigned DEFAULT '0',
  `hits` int(12) unsigned DEFAULT '0',
  `rank` decimal(10,3) unsigned DEFAULT '0.000',
  `votes` int(10) unsigned DEFAULT '0',  
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`abid`),
  KEY `SEARCH` (`cid`,`aids`(25),`sids`(25),`title`(10)),
  KEY `BROWSEBY` (`cid`,`title`(1)),
  KEY `SORT` (`rank`,`votes`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_artists` (
  `aid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cids` mediumtext,
  `sids` mediumtext,
  `name` varchar(128) DEFAULT NULL,
  `albums` int(12) unsigned DEFAULT '0',
  `songs` int(12) unsigned DEFAULT '0',
  `rank` decimal(10,3) unsigned DEFAULT '0.000',
  `votes` int(10) unsigned DEFAULT '0',  
  `hits` int(12) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `SEARCH` (`cids`(25),`sids`(25),`name`(10)),
  KEY `BROWSEBY` (`cids`(25),`name`(1)),
  KEY `SORT` (`rank`,`votes`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_category` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned DEFAULT '0',
  `weight` int(5) unsigned DEFAULT '0',
  `name` varchar(128) DEFAULT NULL,
  `description` mediumtext,
  `image` varchar(128) DEFAULT NULL,
  `path` varchar(128) DEFAULT NULL,
  `artists` int(12) unsigned DEFAULT '0',
  `albums` int(12) unsigned DEFAULT '0',
  `songs` int(12) unsigned DEFAULT '0',
  `rank` decimal(10,3) unsigned DEFAULT '0.000',
  `votes` int(10) unsigned DEFAULT '0',  
  `hits` int(12) unsigned DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `SORT` (`pid`,`weight`,`rank`,`votes`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_extra` (
  `sid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_requests` (
  `rid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aid` INT(12) UNSIGNED DEFAULT NULL,
  `artist` VARCHAR(128) DEFAULT NULL,
  `album` VARCHAR(128) DEFAULT NULL,
  `title` VARCHAR(128) DEFAULT NULL,
  `lyrics` MEDIUMTEXT,
  `uid` INT(12) UNSIGNED DEFAULT '0',
  `name` VARCHAR(128) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `songid` VARCHAR(32) DEFAULT NULL,
  `sid` INT(12) UNSIGNED DEFAULT '0',
  `created` INT(12) UNSIGNED DEFAULT '0',
  `updated` INT(12) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `SORT` (`songid`(10),`sid`,`created`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_field` (
  `field_id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `cids` mediumtext,
  `field_type` varchar(30) NOT NULL DEFAULT '',
  `field_valuetype` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `field_name` varchar(255) NOT NULL DEFAULT '',
  `field_title` varchar(255) NOT NULL DEFAULT '',
  `field_description` text,
  `field_required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_maxlength` smallint(6) unsigned NOT NULL DEFAULT '0',
  `field_weight` smallint(6) unsigned NOT NULL DEFAULT '0',
  `field_default` text,
  `field_notnull` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_config` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_options` text,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_genre` (
  `gid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) DEFAULT NULL,
  `artists` INT(12) UNSIGNED DEFAULT '0',
  `albums` INT(12) UNSIGNED DEFAULT '0',
  `songs` INT(12) UNSIGNED DEFAULT '0',
  `rank` DECIMAL(10,3) UNSIGNED DEFAULT '0.000',
  `votes` INT(10) UNSIGNED DEFAULT '0',  
  `hits` INT(12) UNSIGNED DEFAULT '0',
  `created` INT(12) UNSIGNED DEFAULT '0',
  `updated` INT(12) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `SORT` (`rank`,`votes`,`created`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_songs` (
  `sid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(12) unsigned DEFAULT '0',
  `gids` mediumtext,
  `vcid` int(12) unsigned DEFAULT '0',
  `aids` mediumtext,
  `abid` int(12) unsigned DEFAULT '0',
  `songid` varchar(32) DEFAULT NULL,
  `traxid` varchar(32) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `lyrics` longtext,
  `hits` int(12) unsigned DEFAULT '0',
  `rank` decimal(10,3) unsigned DEFAULT '0.000',
  `votes` int(10) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT NULL,
  `mp3` varchar(500) DEFAULT '',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `SEARCH` (`cid`,`gids`(25),`vcid`,`aids`(25),`abid`,`songid`(10),`traxid`(10),`title`(10),`lyrics`(15)),
  KEY `BROWSEBY` (`cid`,`title`(1),`lyrics`(1)),
  KEY `SORT` (`rank`,`votes`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_utf8map` (
  `utfid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from` VARCHAR(2) DEFAULT NULL,
  `to` VARCHAR(2) DEFAULT NULL,
  `created` INT(12) UNSIGNED DEFAULT '0',
  `updated` INT(12) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`utfid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_visibility` (
  `field_id` int(12) unsigned NOT NULL DEFAULT '0',
  `user_group` smallint(5) unsigned NOT NULL DEFAULT '0',
  `profile_group` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`,`user_group`,`profile_group`),
  KEY `visible` (`user_group`,`profile_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_voice` (
  `vcid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `artists` int(12) unsigned DEFAULT '0',
  `albums` int(12) unsigned DEFAULT '0',
  `songs` int(12) unsigned DEFAULT '0',
  `rank` decimal(10,3) unsigned DEFAULT '0.000',
  `votes` int(10) unsigned DEFAULT '0',  
  `hits` INT(12) UNSIGNED DEFAULT '0',
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`vcid`),
  KEY `SORT` (`name`(32),`rank`,`votes`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `songlist_votes` (
  `vid` int(23) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(12) unsigned DEFAULT '0',
  `uid` int(12) unsigned DEFAULT '0',
  `ip` varchar(64) DEFAULT NULL,
  `netaddy` varchar(255) DEFAULT NULL,
  `rank` decimal(10,3) unsigned DEFAULT '0.000',
  PRIMARY KEY (`vid`),
  KEY `SEARCH` (`uid`,`ip`(15),`netaddy`(25))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;