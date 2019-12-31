<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'songlist');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"songs";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
	default:
	case "songs":
		switch ($fct)
		{
			default:
			case "list":				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
				
				$songs_handler =& xoops_getmodulehandler('songs', 'songlist');
					
				$criteria = $songs_handler->getFilterCriteria($GLOBALS['filter']);
				$ttl = $songs_handler->getCount($criteria);
				$GLOBALS['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
									
				$pagenav = new XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit='.$GLOBALS['limit'].'&sort='.$GLOBALS['sort'].'&order='.$GLOBALS['order'].'&op='.$GLOBALS['op'].'&fct='.$GLOBALS['fct'].'&filter='.$GLOBALS['filter']);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
		
				foreach ($songs_handler->filterFields() as $id => $key) {
					$GLOBALS['xoopsTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$GLOBALS['start'].'&limit='.$GLOBALS['limit'].'&sort='.$key.'&order='.(($key==$GLOBALS['sort'])?($GLOBALS['order']=='DESC'?'ASC':'DESC'):$GLOBALS['order']).'&op='.$GLOBALS['op'].'&filter='.$GLOBALS['filter'].'">'.(defined('_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_SONGLIST_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
					$GLOBALS['xoopsTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $songs_handler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
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
					
				$songss = $songs_handler->getObjects($criteria, true);
				foreach($songss as $cid => $songs) {
					if (is_object($songs))					
						$GLOBALS['xoopsTpl']->append('songs', $songs->toArray());
				}
				$GLOBALS['xoopsTpl']->assign('form', songlist_songs_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_songs_list.html');
				break;		
				
			case "new":
			case "edit":
				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
								
				include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
				
				$songs_handler =& xoops_getmodulehandler('songs', 'songlist');
				if (isset($_REQUEST['id'])) {
					$songs = $songs_handler->get(intval($_REQUEST['id']));
				} else {
					$songs = $songs_handler->create();
				}
				
				$GLOBALS['xoopsTpl']->assign('form', $songs->getForm());
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_songs_edit.html');
				break;
			case "save":

				$songs_handler =& xoops_getmodulehandler('songs', 'songlist');
				$extras_handler = xoops_getmodulehandler('extras', 'songlist');
				$id=0;
				if ($id=intval($_REQUEST['id'])) {
					$songs = $songs_handler->get($id);
				} else {
					$songs = $songs_handler->create();
				}
				$songs->setVars($_POST[$id]);
				
				if (isset($_FILES['mp3'.$id])&&!empty($_FILES['mp3'.$id]['title'])) {
						
					if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
						foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders)
							foreach(explode('/', $folders) as $folder) {
							$path .= DS . $folder;
							mkdir($path, 0777);
						}
					}
						
					include_once($GLOBALS['xoops']->path('modules/songlist/include/uploader.php'));
					$uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), explode('|', $GLOBALS['songlistModuleConfig']['mp3_mimetype']), $GLOBALS['songlistModuleConfig']['mp3_filesize'], 0, 0, explode('|', $GLOBALS['songlistModuleConfig']['mp3_extensions']));
					$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
						
					if ($uploader->fetchMedia('mp3'.$id)) {
						if (!$uploader->upload()) {
								
							$indexAdmin = new ModuleAdmin();
							echo $indexAdmin->addNavigation(basename(__FILE__));
							echo $uploader->getErrors();
							xoops_cp_footer();
							exit(0);
						} else {
								
							if (strlen($songs->getVar('mp3')))
								unlink($GLOBALS['xoops']->path($songs->getVar('path')).basename($songs->getVar('mp3')));
								
							$songs->setVar('mp3', XOOPS_URL.'/'.str_replace(DS, '/', $GLOBALS['songlistModuleConfig']['upload_areas']).$uploader->getSavedFileName());
								
						}
					} else {
				
						$indexAdmin = new ModuleAdmin();
						echo $indexAdmin->addNavigation(basename(__FILE__));
						echo $uploader->getErrors();
						xoops_cp_footer();
						exit(0);
					}
				}
				if (!$id=$songs_handler->insert($songs)) {
					redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_FAILEDTOSAVE);
					exit(0);
				} else {
					$extra = $extras_handler->get($id);
					$extra->setVars($_POST[$id]);
					$extra->setVar('sid', $id);
					$extras_handler->insert($extra);
					
					if ($GLOBALS['songlistModuleConfig']['tags']&&file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
						$tag_handler = xoops_getmodulehandler('tag', 'tag');
						$tag_handler->updateByItem($_POST['tags'], $id, $GLOBALS['songlistModule']->getVar("dirname"), $songs->getVar('cid'));
					}
					
					
					if ($_REQUEST['state'][$_REQUEST['id']]=='new')
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=edit&id='.$_REQUEST['id'] . '&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_SAVEDOKEY);
					else 
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_SAVEDOKEY);
					exit(0);
				}
				break;
			case "savelist":
				print_r($_FILES);
				exit;
				$songs_handler =& xoops_getmodulehandler('songs', 'songlist');
				foreach($_REQUEST['id'] as $id) {
					$songs = $songs_handler->get($id);
					$songs->setVars($_POST[$id]);
					if (isset($_FILES['mp3'.$id])&&!empty($_FILES['mp3'.$id]['title'])) {
					
						if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
							foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders)
								foreach(explode('/', $folders) as $folder) {
								$path .= DS . $folder;
								mkdir($path, 0777);
							}
						}
					
						include_once($GLOBALS['xoops']->path('modules/songlist/include/uploader.php'));
						$uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), explode('|', $GLOBALS['songlistModuleConfig']['mp3_mimetype']), $GLOBALS['songlistModuleConfig']['mp3_filesize'], 0, 0, explode('|', $GLOBALS['songlistModuleConfig']['mp3_extensions']));
						$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
					
						if ($uploader->fetchMedia('mp3'.$id)) {
							if (!$uploader->upload()) {
					
								$indexAdmin = new ModuleAdmin();
								echo $indexAdmin->addNavigation(basename(__FILE__));
								echo $uploader->getErrors();
								xoops_cp_footer();
								exit(0);
							} else {
					
								if (strlen($songs->getVar('mp3')))
									unlink($GLOBALS['xoops']->path($songs->getVar('path')).basename($songs->getVar('mp3')));
					
								$songs->setVar('mp3', XOOPS_URL.'/'.str_replace(DS, '/', $GLOBALS['songlistModuleConfig']['upload_areas']).$uploader->getSavedFileName());
					
							}
						} else {
					
							$indexAdmin = new ModuleAdmin();
							echo $indexAdmin->addNavigation(basename(__FILE__));
							echo $uploader->getErrors();
							xoops_cp_footer();
							exit(0);
						}
					}
					if (!$songs_handler->insert($songs)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_FAILEDTOSAVE);
						exit(0);
					} 
				}
				redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_SAVEDOKEY);
				exit(0);
				break;				
			case "delete":	
							
				$songs_handler =& xoops_getmodulehandler('songs', 'songlist');
				$id=0;
				if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
					$songs = $songs_handler->get($id);
					if (!$songs_handler->delete($songs)) {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_FAILEDTODELETE);
						exit(0);
					} else {
						redirect_header($_SERVER['PHP_SELF'].'?op='.$GLOBALS['op'].'&fct=list&limit='.$GLOBALS['limit'].'&start='.$GLOBALS['start'].'&order='.$GLOBALS['order'].'&sort='.$GLOBALS['sort'].'&filter='.$GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_DELETED);
						exit(0);
					}
				} else {
					$songs = $songs_handler->get(intval($_REQUEST['id']));
					xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), $_SERVER['PHP_SELF'], sprintf(_AM_SONGLIST_MSG_SONGS_DELETE, $songs->getVar('name')));
				}
				break;
		}
		break;
				
	}
	
	xoops_cp_footer();
?>