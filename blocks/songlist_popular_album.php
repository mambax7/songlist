<?php

	function b_songlist_popular_album_show($options) {
		xoops_loadLanguage('blocks', 'songlist');
		$handler = xoops_getmodulehandler('albums', 'songlist');
		$objects = $handler->getTop(1);
		if (is_object($objects[0])) {
			return $objects[0]->toArray(true);
		}
		return false;	
	}
	
	function b_songlist_popular_album_edit($options) {
		
	}
	
?>
