<?php

	function b_songlist_popular_songs_show($options) {
		xoops_loadLanguage('blocks', 'songlist');
		$handler = xoops_getmodulehandler('songs', 'songlist');
		$objects = $handler->getTop($options[0]);
		if (count($objects)>0) {
			$ret = array();
			foreach($objects as $id => $object)
				$ret[$id] = $object->toArray(true);
			return $ret;
		}
		return false;	
	}
	
	function b_songlist_popular_songs_edit($options) {
		xoops_load('XoopsFormLoader');
		xoops_loadLanguage('blocks', 'songlist');
		$num = new XoopsformText('', 'options[0]', 10, 10, $options[0]);
		return _BL_SONGLIST_NUMBEROFITEMS.$num->render(); 		
	}
	
?>