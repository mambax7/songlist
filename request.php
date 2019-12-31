<?php

	include (dirname(__FILE__).'/header.php');
	
	global $file, $op, $fct, $id, $value, $gid, $cid, $singer, $start, $limit;
	
	$category_element = new SonglistFormSelectCategory('', 'cid', (isset($_GET['cid'])?($_GET['cid']):$cid));
	$genre_element = new SonglistFormSelectGenre('', 'gid', $gid);
	$genre_element = new SonglistFormSelectGenre('', 'vid', $vid);	
	//$singer_element = new SonglistFormSelectSinger('', 'singer', $singer);
			
	$requests_handler = xoops_getmodulehandler('requests', 'songlist');
	
	switch ($op) {
		default:
		case "request":
			
			$url = $requests_handler->getURL();
			if (!strpos($url, $_SERVER['REQUEST_URI'])&&empty($_POST)) {
				header( "HTTP/1.1 301 Moved Permanently" ); 
				header('Location: '.$url);
				exit(0);
			}
			
			switch ($fct) {
			case "save":
				if (checkEmail($_POST[0]['email'], true)&&!empty($_POST[0]['email'])) {
					$request = $requests_handler->create();
					$request->setVars($_POST[0]);
					if ($rid = $requests_handler->insert($request)) {
						redirect_header($_SERVER["PHP_SELF"]."?op=item&fct=list&id=$id&value=$value&start=$start&limit=$limit", 10, _MN_SONGLIST_MSG_REQUESTSENT);
					} else {
						redirect_header($_SERVER["PHP_SELF"]."?op=item&fct=list&id=$id&value=$value&start=$start&limit=$limit", 10, _MN_SONGLIST_MSG_REQUESTNOTSENT);
					}
					exit;
					break;
				} else {
					$error = _MN_SONGLIST_MSG_EMAILNOTSET;
				}
			default:
			case "list":
							
				$xoopsOption['template_main'] = 'songlist_requests_index.html';
				include($GLOBALS['xoops']->path('/header.php'));
				if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
					$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
					$GLOBALS['loaded_jquery']=true;
				}
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);			
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->assign('form', songlist_requests_get_form(false, false));			
				if (strlen($error))
					xoops_error($error);
				include($GLOBALS['xoops']->path('/footer.php'));
				break;

			
			}
	}	
?>