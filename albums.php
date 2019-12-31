<?php

	include (dirname(__FILE__).'/header.php');
	
	global $file, $op, $fct, $id, $value, $gid, $cid, $start, $limit;
	
	$category_handler = xoops_getmodulehandler('category', 'songlist');
	$criteria_cat = new CriteriaCompo();
	$cids = $category_handler->GetCatAndSubCat($_SESSION['cid']);
	if ($_SESSION['cid']>0)
		$cids[$_SESSION['cid']] = $_SESSION['cid'];
	if (count($cids)>0&&$_SESSION['cid']!=0) {
		$criteria_cat->add(new Criteria('`cid`', '('.implode(',',  $cids).')', 'IN'), 'OR');	
	} else { 
		$criteria_cat->add(new Criteria('1', '1'), 'OR');
	}
	$criteria_cat->setSort('`created`');
	$criteria_cat->setOrder('ASC');
	
	$criteria = new Criteria('pid', $_SESSION['cid']);
	$criteria->setSort('`weight`');
	$criteria->setOrder('ASC');
	$categories = $category_handler->getObjects($criteria, false);
	
	$cat = array();
	$col = 1;
	$row = 1;
	foreach($categories as $category) {
		$cat[$row][$col] = $category->toArray(true);
		$cat[$row][$col]['width'] = floor(100/$GLOBALS['songlistModuleConfig']['cols']).'%';
		$col++;
		if ($col>$GLOBALS['songlistModuleConfig']['cols']) {
			$row++;
			$col=1;
		}
	}
	if ($col!=1) {
		$col--;
		for($j=$col;$j<=$GLOBALS['songlistModuleConfig']['cols'];$j++) {
			$cat[$row][$j][$category_handler->keyName] = 0;
			$cat[$row][$j]['width'] = floor(100/$GLOBALS['songlistModuleConfig']['cols']).'%';
		}
	}
	
	$albums_handler = xoops_getmodulehandler('albums', 'songlist');
	switch ($op) {
		default:
		case "item":
			switch ($fct) {
			default:
			case "list":
				$pagenav = new XoopsPageNav($albums_handler->getCount($criteria_cat), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

				$criteria_cat->setLimit($limit);
				$criteria_cat->setStart($start);
				
				$albums = $albums_handler->getObjects($criteria_cat, false);
				
				$ret = array();
				$col = 1;
				$row = 1;
				foreach($albums as $album) {
					$ret[$row][$col] = $album->toArray(true);
					$ret[$row][$col]['width'] = floor(100/$GLOBALS['songlistModuleConfig']['cols']).'%';
					$col++;
					if ($col>$GLOBALS['songlistModuleConfig']['cols']) {
						$row++;
						$col=1;
					}
				}
				if ($col!=1) {
					for($j=$col;$j<=$GLOBALS['songlistModuleConfig']['cols'];$j++) {
						$ret[$row][$j][$albums_handler->keyName] = 0;
						$ret[$row][$j]['width'] = floor(100/$GLOBALS['songlistModuleConfig']['cols']).'%';
					}
				}
				
				$url = $albums_handler->getURL();
				if (!strpos($url, $_SERVER['REQUEST_URI'])) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}

				$xoopsOption['template_main'] = 'songlist_albums_index.html';
				include($GLOBALS['xoops']->path('/header.php'));
				if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
					$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
					$GLOBALS['loaded_jquery']=true;
				}
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);			
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->assign('results', $ret);
				$GLOBALS['xoopsTpl']->assign('categories', $cat);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
				$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
				if ($_SESSION['cid']!=0) {
					$category = $category_handler->get($_SESSION['cid']);
					$GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
				}				
				include($GLOBALS['xoops']->path('/footer.php'));
				break;
			case "item":
				$album = $albums_handler->get($id);

				$url = $album->getURL(true);
				if (!strpos($url, $_SERVER['REQUEST_URI'])) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}
				
				$xoopsOption['template_main'] = 'songlist_albums_item.html';
				include($GLOBALS['xoops']->path('/header.php'));
				if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
					$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
					$GLOBALS['loaded_jquery']=true;
				}	
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));				
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));	
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->assign('songs', false);
				$GLOBALS['xoopsTpl']->assign('album', $album->toArray(true));
				$GLOBALS['xoopsTpl']->assign('categories', $cat);
				$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']); 				
				include($GLOBALS['xoops']->path('/footer.php'));
				break;	
			}
			break;
		case "browseby":
			switch ($fct) {
			default:
			case "title":
			case "lyrics":
			case "artist":
			case "album":
				
				$browse_criteria = new CriteriaCompo();
				switch ($value) {
					case '0':
						for($u=0;$u<10;$u++) {
							$browse_criteria->add(new Criteria('`title`', $u.'%', 'LIKE'), 'OR');							
						}
						break;
					default:
						$browse_criteria->add(new Criteria('`title`', strtoupper($value).'%', 'LIKE'), 'OR');
						$browse_criteria->add(new Criteria('`title`', strtolower($value).'%', 'LIKE'), 'OR');
						break;
				}
				$criteria = new CriteriaCompo($criteria_cat, 'AND');
				$criteria->add($browse_criteria);
				
			}
			
			$pagenav = new XoopsPageNav($albums_handler->getCount($criteria), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

			$criteria->setLimit($limit);
			$criteria->setStart($start);
			
			$albums = $albums_handler->getObjects($criteria, false);
			
			$ret = array();
			$col = 1;
			$row = 1;
			foreach($albums as $album) {
				$ret[$row][$col] = $album->toArray(true);
				$ret[$row][$col]['width'] = floor(100/$GLOBALS['songlistModuleConfig']['cols']).'%';
				$col++;
				if ($col>$GLOBALS['songlistModuleConfig']['cols']) {
					$row++;
					$col=1;
				}
			}
			if ($col!=1) {
				$col--;
				for($j=$col;$j<=$GLOBALS['songlistModuleConfig']['cols'];$j++) {
					$ret[$row][$j][$albums_handler->keyName] = 0;
					$ret[$row][$j]['width'] = floor(100/$GLOBALS['songlistModuleConfig']['cols']).'%';
				}
			}
			
			$url = $albums_handler->getURL();
			if (!strpos($url, $_SERVER['REQUEST_URI'])) {
				header( "HTTP/1.1 301 Moved Permanently" ); 
				header('Location: '.$url);
				exit(0);
			}
		
			$xoopsOption['template_main'] = 'songlist_albums_index.html';
			include($GLOBALS['xoops']->path('/header.php'));
			if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
				$GLOBALS['loaded_jquery']=true;
			}			
			$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
			$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			$GLOBALS['xoopsTpl']->assign('results', $ret);
			$GLOBALS['xoopsTpl']->assign('songs', false);
			$GLOBALS['xoopsTpl']->assign('categories', $cat);
			$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
			if ($_SESSION['cid']!=0) {
				$category = $category_handler->get($_SESSION['cid']);
				$GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
			}
			$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
			include($GLOBALS['xoops']->path('/footer.php'));
			break;
		
			break;
			
		case "category":
			switch ($fct) {
			default:
			case "set":
				$_SESSION['cid'] = $id;
				break;
			case "home":
				unset($_SESSION['cid']);
				break;
			}
			redirect_header($_SERVER["PHP_SELF"]."?op=item&fct=list&id=0&value=%&start=$start&limit=$limit&cid=".$_SESSION['cid'], 10, _MN_SONGLIST_MSG_CATEGORYCHANGED);
			exit;
	}	
?>