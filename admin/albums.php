<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'songlist');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"albums";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
	default:
	case "albums":
		switch ($fct)
		{
			default:
			case "list":				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
				
				$albums_handler =& xoops_getmodulehandler('albums', 'songlist');
					
				$criteria = $albums_handler->getFilterCriteria($GLOBALS['filter']);
				$ttl = $albums_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($albums_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $albums_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
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
					
				$albumss = $albums_handler->getObjects($criteria, true);
				foreach($albumss as $cid => $albums) {
					if (is_object($albums))					
						$GLOBALS['xoopsTpl']->append('albums', $albums->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', songlist_albums_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_albums_list.html');
				break;		
				
			case "new":
			case "edit":
				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
								
				$albums_handler =& xoops_getmodulehandler('albums', 'songlist');
				if (isset($_REQUEST['id'])) {
					$albums = $albums_handler->get(intval($_REQUEST['id']));
				} else {
					$albums = $albums_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $albums->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_albums_edit.html');
				break;
			case "save":
				
				$albums_handler =& xoops_getmodulehandler('albums', 'songlist');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$albums = $albums_handler->get($id);
				} else {
					$albums = $albums_handler->create();
				}
				$albums->setVars($_POST[$id]);
				
				if (!$id=$albums_handler->insert($albums)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_FAILEDTOSAVE);
					exit(0);
				} else {
					
					if (isset($_FILES['image'])&&!empty($_FILES['image']['title'])) {
						
						if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
							foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders)
								foreach(explode('/', $folders) as $folder) {
									$path .= DS . $folder;
									mkdir($path, 0777);
								}
						}
						
						include_once($GLOBALS['xoops']->path('modules/songlist/include/uploader.php'));
						$albums = $albums_handler->get($id);
						$uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), explode('|', $GLOBALS['songlistModuleConfig']['allowed_mimetype']), $GLOBALS['songlistModuleConfig']['filesize_upload'], 0, 0, explode('|', $GLOBALS['songlistModuleConfig']['allowed_extensions']));
						$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
						
						if ($uploader->fetchMedia('image')) {
						  	if (!$uploader->upload()) {
						  		
						    	songlist_adminMenu(1);
						    	echo $uploader->getErrors();
								songlist_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  	    } else {
					  	    	
						      	if (strlen($albums->getVar('image')))
						      		unlink($GLOBALS['xoops']->path($albums->getVar('path')).$albums->getVar('image'));
						      	
						      	$albums->setVar('path', $GLOBALS['songlistModuleConfig']['upload_areas']);
						      	$albums->setVar('image', $uploader->getSavedFileName());
						      	@$albums_handler->insert($albums);
						      	
						    }      	
					  	} else {
					  		
					  		songlist_adminMenu(1);
					       	echo $uploader->getErrors();
							songlist_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
					   	}
					}
					
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				
				$albums_handler =& xoops_getmodulehandler('albums', 'songlist');
				foreach($_REQUEST['id'] as $id) {
					$albums = $albums_handler->get($id);
					$albums->setVars($_POST[$id]);
					if (!$albums_handler->insert($albums)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$albums_handler =& xoops_getmodulehandler('albums', 'songlist');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$albums = $albums_handler->get($id);
					if (!$albums_handler->delete($albums)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ALBUMS_DELETED);
						exit(0);
					}
				} else {
					$albums = $albums_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_SONGLIST_MSG_ALBUMS_DELETE, $albums->getVar('title')));
				}
				break;
		}
		break;
				
	}
	
	xoops_cp_footer();
?>