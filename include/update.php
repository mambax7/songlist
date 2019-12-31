<?php

/**
 * @param $module
 * @return bool
 */
function xoops_module_update_songlist(&$module)
{
    $sql = [];

    $sql[] = 'CREATE TABLE `'
             . $GLOBALS['xoopsDB']->prefix('songlist_voice')
             . "` (  `vcid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  `name` VARCHAR(128) DEFAULT NULL,  `artists` INT(12) UNSIGNED DEFAULT '0',  `albums` INT(12) UNSIGNED DEFAULT '0',  `songs` INT(12) UNSIGNED DEFAULT '0',  `rank` DECIMAL(10,3) UNSIGNED DEFAULT '0.000',  `votes` INT(10) UNSIGNED DEFAULT '0',  `created` INT(12) UNSIGNED DEFAULT '0',  `updated` INT(12) UNSIGNED DEFAULT '0',  PRIMARY KEY (`vcid`),  KEY `SORT` (`name`(32),`rank`,`votes`,`created`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $sql[] = 'ALTER TABLE `' . $GLOBALS['xoopsDB']->prefix('songlist_songs') . "` ADD COLUMN `vcid` INT(12) UNSIGNED DEFAULT '0'";
    $sql[] = 'ALTER TABLE `' . $GLOBALS['xoopsDB']->prefix('songlist_songs') . '` CHANGE COLUMN `lyrics` `lyrics` LONGTEXT';
    $sql[] = 'ALTER TABLE `' . $GLOBALS['xoopsDB']->prefix('songlist_songs') . "` ADD COLUMN `mp3` VARCHAR(500) DEFAULT ''";
    $sql[] = 'ALTER TABLE `' . $GLOBALS['xoopsDB']->prefix('songlist_songs') . '` CHANGE COLUMN `traxid` `traxid` INT(4) UNSIGNED ZEROFILL DEFAULT NULL';

    return xoops_module_update_vs_executesql($sql);
}

/**
 * @param $sql
 * @return bool
 */
function xoops_module_update_vs_executesql($sql)
{
    if (is_string($sql)) {
        if ($GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($sql, 'SQL Executed Successfully!!!');
        }
    } elseif (is_array($sql)) {
        foreach ($sql as $id => $question) {
            if (is_array($question)) {
                foreach ($question as $kquestion => $questionb) {
                    if ($GLOBALS['xoopsDB']->queryF($kquestion)) {
                        xoops_error($kquestion, 'SQL Executed Successfully!!!');
                        xoops_module_update_vs_executesql($questionb);
                    }
                }
            } else {
                if ($GLOBALS['xoopsDB']->queryF($id)) {
                    xoops_error($id, 'SQL Executed Successfully!!!');
                    if ($GLOBALS['xoopsDB']->queryF($question)) {
                        xoops_error($question, 'SQL Executed Successfully!!!');
                    }
                } elseif ($GLOBALS['xoopsDB']->queryF($question)) {
                    xoops_error($question, 'SQL Executed Successfully!!!');
                }
            }
        }
    } else {
        return false;
    }

    return true;
}
