<?php


function xoops_module_update_songlist(&$module) {
	
	$sql = array();
	
	$sql[] = "CREATE TABLE `".$GLOBALS['xoopsDB']->prefix('songlist_voice')."` (  `vcid` int(10) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(128) DEFAULT NULL,  `artists` int(12) unsigned DEFAULT '0',  `albums` int(12) unsigned DEFAULT '0',  `songs` int(12) unsigned DEFAULT '0',  `rank` decimal(10,3) unsigned DEFAULT '0.000',  `votes` int(10) unsigned DEFAULT '0',  `created` int(12) unsigned DEFAULT '0',  `updated` int(12) unsigned DEFAULT '0',  PRIMARY KEY (`vcid`),  KEY `SORT` (`name`(32),`rank`,`votes`,`created`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$sql[] = "ALTER TABLE `".$GLOBALS['xoopsDB']->prefix('songlist_songs')."` ADD COLUMN `vcid` int(12) unsigned DEFAULT '0'";
	$sql[] = "ALTER TABLE `".$GLOBALS['xoopsDB']->prefix('songlist_songs')."` CHANGE COLUMN `lyrics` `lyrics` longtext";
	$sql[] = "ALTER TABLE `".$GLOBALS['xoopsDB']->prefix('songlist_songs')."` ADD COLUMN `mp3` varchar(500) DEFAULT ''";
	$sql[] = "ALTER TABLE `".$GLOBALS['xoopsDB']->prefix('songlist_songs')."` CHANGE COLUMN `traxid` `traxid` int(4) unsigned zerofill DEFAULT NULL";
	
	return xoops_module_update_vs_executesql($sql);

}


function xoops_module_update_vs_executesql($sql) {
	if (is_string($sql)) {
		if ($GLOBALS['xoopsDB']->queryF($sql)) {
			xoops_error($sql, 'SQL Executed Successfully!!!');
		}
	} elseif (is_array($sql)) {
		foreach($sql as $id => $question) {
			if (is_array($question)) {
				foreach($question as $kquestion => $questionb) {
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
?>

