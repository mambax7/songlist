<?php

	include (dirname(__FILE__).'/header.php');
	
	global $file, $op, $fct, $id, $value, $gid, $cid, $vcid,$start, $limit, $singer;
	
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
	
	$category_element = new SonglistFormSelectCategory('', 'cid', (isset($_GET['cid'])?($_GET['cid']):$cid));
	$genre_element = new SonglistFormSelectGenre('', 'gid', $gid);
	$voice_element = new SonglistFormSelectVoice('', 'vcid', $vcid);
	
	$songs_handler = xoops_getmodulehandler('songs', 'songlist');
	switch ($op) {
		case "vote":
			$votes_handler = xoops_getmodulehandler('votes', 'songlist');
			$votes_handler->addVote($id, $value);
			redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_ALREADY);
			exit;
		default:
		case "item":
			switch ($fct) {
			default:
			case "list":
				
				$pagenav = new XoopsPageNav($songs_handler->getCount($criteria_cat), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

				$criteria_cat->setLimit($limit);
				$criteria_cat->setStart($start);
				
				$songs = $songs_handler->getObjects($criteria_cat, false);
				
				$url = $songs_handler->getURL();
				if (!strpos($url, $_SERVER['REQUEST_URI'])) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}

				$xoopsOption['template_main'] = 'songlist_songs_index.html';
				include($GLOBALS['xoops']->path('/header.php'));
				if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
					$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
					$GLOBALS['loaded_jquery']=true;
				}
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);			
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				foreach($songs as $song)
					$GLOBALS['xoopsTpl']->append('results', $song->toArray(true));
				$GLOBALS['xoopsTpl']->assign('songs', true);			
				$GLOBALS['xoopsTpl']->assign('categories', $cat);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
				$GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
				$GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
				$GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());
				$GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
				if ($_SESSION['cid']!=0) {
					$category = $category_handler->get($_SESSION['cid']);
					if (is_object($category))
						$GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
				}				
				$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
				include($GLOBALS['xoops']->path('/footer.php'));
				break;
			case "item":
				$song = $songs_handler->get($id);

				$url = $song->getURL(true);
				if (!strpos($url, $_SERVER['REQUEST_URI'])) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}
				
				$xoopsOption['template_main'] = 'songlist_songs_item.html';
				include($GLOBALS['xoops']->path('/header.php'));
				if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
					$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
					$GLOBALS['loaded_jquery']=true;
				}			
				$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->assign('songs', false);
				$GLOBALS['xoopsTpl']->assign('song', $song->toArray(true));
				$GLOBALS['xoopsTpl']->assign('categories', $cat);
				$GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
				$GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
				$GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());
				$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);				
				include($GLOBALS['xoops']->path('/footer.php'));
				break;	
			}
			break;
		case "browseby":
			switch ($fct) {
			default:
			case "title":
				
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
				break;
				
			case "lyrics":
				
				$browse_criteria = new CriteriaCompo();
				switch ($value) {
					case '0':
						for($u=0;$u<10;$u++) {
							$browse_criteria->add(new Criteria('`lyrics`', $u.'%', 'LIKE'), 'OR');							
						}
						break;
					default:
						$browse_criteria->add(new Criteria('`lyrics`', strtoupper($value).'%', 'LIKE'), 'OR');
						$browse_criteria->add(new Criteria('`lyrics`', strtolower($value).'%', 'LIKE'), 'OR');
						break;
				}
				$criteria = new CriteriaCompo($criteria_cat, 'AND');
				$criteria->add($browse_criteria);
				break;
				
			case "artist":
				
				$browse_criteria = new CriteriaCompo();
				switch ($value) {
					case '0':
						for($u=0;$u<10;$u++) {
							$browse_criteria->add(new Criteria('`name`', $u.'%', 'LIKE'), 'OR');							
						}
						break;
					default:
						$browse_criteria->add(new Criteria('`name`', strtoupper($value).'%', 'LIKE'), 'OR');
						$browse_criteria->add(new Criteria('`name`', strtolower($value).'%', 'LIKE'), 'OR');
						break;
				}
				$artists_handler = xoops_getmodulehandler('artists', 'songlist');
				$browse_criteriab = new CriteriaCompo();
				foreach($artists_handler->getObjects($browse_criteria, true) as $aid => $obj) {
					$browse_criteriab->add(new Criteria('`aids`', '%"'.$aid.'"%', 'LIKE'), 'OR'); 
					
				}
				$criteria = new CriteriaCompo($criteria_cat, 'AND');
				$criteria->add($browse_criteriab);
				break;
				
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
				$ids=array();
				$albums_handler = xoops_getmodulehandler('albums', 'songlist');
				foreach($albums_handler->getObjects($browse_criteria, true) as $id => $obj) {
					$ids[$id] = $id; 
					
				}
				$criteria = new CriteriaCompo($criteria_cat, 'AND');
				if (count($ids)>0)
					$criteria->add(new Criteria('abid', '('.implode(',', $ids).')', 'IN'));
				break;
			}
			
			$pagenav = new XoopsPageNav($songs_handler->getCount($criteria), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

			$criteria->setLimit($limit);
			$criteria->setStart($start);
			
			$songs = $songs_handler->getObjects($criteria, false);
			
			$url = $songs_handler->getURL();
			if (!strpos($url, $_SERVER['REQUEST_URI'])) {
				header( "HTTP/1.1 301 Moved Permanently" ); 
				header('Location: '.$url);
				exit(0);
			}
		
			$xoopsOption['template_main'] = 'songlist_songs_index.html';
			include($GLOBALS['xoops']->path('/header.php'));
			if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
				$GLOBALS['loaded_jquery']=true;
			}			
			$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
			$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			foreach($songs as $song)
				$GLOBALS['xoopsTpl']->append('results', $song->toArray(true));
			$GLOBALS['xoopsTpl']->assign('songs', true);
			$GLOBALS['xoopsTpl']->assign('categories', $cat);
			$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
			$GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
			$GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
			$GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());			
			$GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
			if ($_SESSION['cid']!=0) {
				$category = $category_handler->get($_SESSION['cid']);
				if (is_object($category))
					$GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
			}
			$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
			include($GLOBALS['xoops']->path('/footer.php'));
			break;
		
			break;
			
		case "search":

			$songs_handler = xoops_getmodulehandler('songs', 'songlist');
			$artists_handler = xoops_getmodulehandler('artists', 'songlist');
			$utf8map_handler = xoops_getmodulehandler('utf8map', 'songlist');
			
			$url = $songs_handler->getSearchURL();
			if (!strpos($url, $_SERVER['REQUEST_URI'])) {
				header( "HTTP/1.1 301 Moved Permanently" ); 
				header('Location: '.$url);
				exit(0);
			}
			
			switch ($fct) {
				default:
				case "titleandlyrics":
					$criteria = new CriteriaCompo();
					foreach(explode(' ' , $value) as $keyword) {
						$criteria->add(new Criteria('`title`', '%'.$utf8map_handler->convert($keyword).'%', 'LIKE'));
						$criteria->add(new Criteria('`lyrics`', '%'.$utf8map_handler->convert($keyword).'%', 'LIKE'));
					}
					break;
					
				case "albums":
					$criteria = new CriteriaCompo();
					foreach(explode(' ' , $value) as $keyword) {
						$criteria->add(new Criteria('`title`', '%'.$utf8map_handler->convert($keyword).'%', 'LIKE'));
					}
					$albums = $albums_handler->getObject($criteria, true);
					$criteria = new CriteriaCompo();
					foreach($albums as $abid=> $album) {
						$criteria->add(new Criteria('`abid`', $abid), 'OR');
					}
					break;
				
				case "artists":
					$criteria = new CriteriaCompo();
					foreach(explode(' ' , $value) as $keyword) {
						$criteria->add(new Criteria('`name`', '%'.$utf8map_handler->convert($keyword).'%', 'LIKE'));
					}
					$artists = $artists_handler->getObject($criteria, true);
					$criteria = new CriteriaCompo();
					foreach($artists as $aid=> $artist) {
						$criteria->add(new Criteria('`aids`', '%"'.$aid.'"%', 'LIKE'), 'OR');
					}
					break;				
				
				case "lyrics":
					$criteria = new CriteriaCompo();
					foreach(explode(' ' , $value) as $keyword) {
						$criteria->add(new Criteria('`lyrics`', '%'.$utf8map_handler->convert($keyword).'%', 'LIKE'));
					}
					break;
				case "title":
					$criteria = new CriteriaCompo();
					foreach(explode(' ' , $value) as $keyword) {
						$criteria->add(new Criteria('`title`', '%'.$utf8map_handler->convert($keyword).'%', 'LIKE'));
					}
					break;
			}
			
			if ($gid != 0 && $GLOBALS['songlistModuleConfig']['genre']) {
				$criteria->add(new Criteria('`gids`', '%"'.$gid.'"%', 'LIKE'));
			}
			
			if ($vcid != 0 && $GLOBALS['songlistModuleConfig']['voice']) {
				$criteria->add(new Criteria('`vcid`', $vcid));
			}			

			if ((isset($_GET['cid'])?($_GET['cid']):$cid) != 0) {
				$criteria->add(new Criteria('`cid`',  (isset($_GET['cid'])?($_GET['cid']):$cid)));
			}
			
			$pagenav = new XoopsPageNav($songs_handler->getCount($criteria), $limit, $start, 'start', "op=$op&fct=$fct&gid=$gid&vcid=$vcid&value=$value&limit=$limit");

			$criteria->setLimit($limit);
			$criteria->setStart($start);
			
			$songs = $songs_handler->getObjects($criteria, false);					
			
			$xoopsOption['template_main'] = 'songlist_search_index.html';
			include($GLOBALS['xoops']->path('/header.php'));
			if ($GLOBALS['songlistModuleConfig']['force_jquery']&&!isset($GLOBALS['loaded_jquery'])) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_SONGLIST_JQUERY, array('type'=>'text/javascript'));
				$GLOBALS['loaded_jquery']=true;
			}
			$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL._MI_SONGLIST_STYLESHEET, array('type'=>'text/css'));
			$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);			
			$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
			foreach($songs as $song) {
				$GLOBALS['xoopsTpl']->append('results', $song->toArray(true));
			}
			$GLOBALS['xoopsTpl']->assign('songs', true);			
			$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
			$GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
			$GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
			$GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());		
			$GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
			$GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);	
			include($GLOBALS['xoops']->path('/footer.php'));
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
			redirect_header($_SERVER["PHP_SELF"]."?op=item&fct=list&id=$id&value=$value&start=$start&limit=$limit&cid=".$_SESSION['cid'], 10, _MN_SONGLIST_MSG_CATEGORYCHANGED);
			exit;
	}	
?>