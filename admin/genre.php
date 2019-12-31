<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'songlist');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"genre";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
	default:
	case "genre":
		switch ($fct)
		{
			default:
			case "list":				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
				
				$genre_handler =& xoops_getmodulehandler('genre', 'songlist');
					
				$criteria = $genre_handler->getFilterCriteria($GLOBALS['filter']);
				$ttl = $genre_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($genre_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $genre_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
				}
				
				$GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
				$GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
				$GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
				$GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
				$GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
									
				$criteria->setStart($GLOBALS['start']);
				$criteria->setLimit($GLOBALS['limit']);
				$criteria->setSort('`'.$GLOBALS['sort'].'`');
				$criteria->setOrder($GLOBALS['order']);
					
				$genres = $genre_handler->getObjects($criteria, true);
				foreach($genres as $cid => $genre) {
					if (is_object($genre))					
						$GLOBALS['xoopsTpl']->append('genre', $genre->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', songlist_genre_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_genre_list.html');
				break;		
				
			case "new":
			case "edit":
				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
											
				$genre_handler =& xoops_getmodulehandler('genre', 'songlist');
				if (isset($_REQUEST['id'])) {
					$genre = $genre_handler->get(intval($_REQUEST['id']));
				} else {
					$genre = $genre_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $genre->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_genre_edit.html');
				break;
			case "save":
				
				$genre_handler =& xoops_getmodulehandler('genre', 'songlist');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$genre = $genre_handler->get($id);
				} else {
					$genre = $genre_handler->create();
				}
				$genre->setVars($_POST[$id]);
				
				if (!$id=$genre_handler->insert($genre)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_FAILEDTOSAVE);
					exit(0);
				} else {
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				
				$genre_handler =& xoops_getmodulehandler('genre', 'songlist');
				foreach($_REQUEST['id'] as $id) {
					$genre = $genre_handler->get($id);
					$genre->setVars($_POST[$id]);
					if (!$genre_handler->insert($genre)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$genre_handler =& xoops_getmodulehandler('genre', 'songlist');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$genre = $genre_handler->get($id);
					if (!$genre_handler->delete($genre)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_GENRE_DELETED);
						exit(0);
					}
				} else {
					$genre = $genre_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_SONGLIST_MSG_GENRE_DELETE, $genre->getVar('name')));
				}
				break;
		}
		break;
				
	}
	
	xoops_cp_footer();
?>