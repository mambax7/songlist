<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'songlist');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"dashboard";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
	case "import":
		switch ($fct)
		{
			default:
			case "actiona":	

				if (isset($_SESSION['xmlfile'])) {
			      	redirect_header($_SERVER['PHP_SELF'].'?file='.$_SESSION['xmlfile'].'&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_UPLOADED);
			      	exit;
				}
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
				
				$GLOBALS['xoopsTpl']->assign('form', songlist_import_get_form(false));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_import_actiona.html');
				break;		
				
			case "upload":
							
				if (isset($_FILES['xmlfile'])&&!empty($_FILES['xmlfile']['title'])) {
						
					if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
						foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders)
							foreach(explode('/', $folders) as $folder) {
								$path .= DS . $folder;
								mkdir($path, 0777);
							}
					}
					
					include_once($GLOBALS['xoops']->path('modules/songlist/include/uploader.php'));
					$albums = $albums_handler->get($id);
					$uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), array('application/xml', 'application/xml-dtd', 'application/xml-external-parsed-entity', 'text/xml xml xsl', 'text/xml-external-parsed-entity'), 1024*1024*32, 0, 0, array('xml'));
					$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
					
					if ($uploader->fetchMedia('xmlfile')) {
					  	if (!$uploader->upload()) {
					  		
					    	echo $uploader->getErrors();
							songlist_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
				  	    } else {
					      	$_SESSION['xmlfile'] = $uploader->getSavedFileName();
					      	redirect_header($_SERVER['PHP_SELF'].'?file='.$uploader->getSavedFileName().'&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_UPLOADED);
					      	exit;
					    }      	
				  	} else {
				  		
				       	echo $uploader->getErrors();
						songlist_footer_adminMenu();
						xoops_cp_footer();
						exit(0);
				   	}
				}
				break;
			case "actionb":
				
				$indexAdmin = new ModuleAdmin();
				echo $indexAdmin->addNavigation(basename(__FILE__));
				
				$GLOBALS['xoopsTpl']->assign('form', songlist_importb_get_form($_SESSION['xmlfile']));
				$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
				$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_import_actionb.html');
				break;		

			case "import":
				
				$songs_handler = xoops_getmodulehandler('songs', 'songlist');
				$albums_handler = xoops_getmodulehandler('albums', 'songlist');
				$artists_handler = xoops_getmodulehandler('artists', 'songlist');
				$genre_handler = xoops_getmodulehandler('genre', 'songlist');
				$voice_handler = xoops_getmodulehandler('voice', 'songlist');				
				$category_handler = xoops_getmodulehandler('category', 'songlist');
				
				$filesize = filesize($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'].$_SESSION['xmlfile']));
				$mb = floor($filesize / 1024 / 1024);
				if ($mb>32) {
					set_ini('memory_limit', ($mb+128).'M');	
				}
				set_time_limit(3600);
								
				$xmlarray = songlist_xml2array(file_get_contents($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'].$_SESSION['xmlfile'])), false, 'tag');

				if (strlen($_POST['collection'])>0) {
					foreach($xmlarray[$_POST['collection']] as $id => $record) {
						foreach ($record as $recid => $data) {
							$gid = 0;
							if (strlen($_POST['genre'])>0&&!empty($data[$_POST['genre']])) {
								$criteria = new Criteria('`name`',  $data[$_POST['genre']]);
								if ($genre_handler->getCount($criteria)>0) {
									$objects = $genre_handler->getObjects($criteria, false);
									$gid = $objects[0]->getVar('gid');
								} else {
									$object = $genre_handler->create();
									$object->setVar('name', $data[$_POST['genre']]);
									$gid = $genre_handler->insert($object);
								} 								
							}	

							$vid = 0;
							if (strlen($_POST['voice'])>0&&!empty($data[$_POST['voice']])) {
								$criteria = new Criteria('`name`',  $data[$_POST['voice']]);
								if ($voice_handler->getCount($criteria)>0) {
									$objects = $voice_handler->getObjects($criteria, false);
									$gid = $objects[0]->getVar('vid');
								} else {
									$object = $voice_handler->create();
									$object->setVar('name', $data[$_POST['voice']]);
									$gid = $voice_handler->insert($object);
								} 								
							}								
							
							$cid = 0;
							if (strlen($_POST['category'])>0&&!empty($data[$_POST['category']])) {
								$criteria = new Criteria('`name`',  $data[$_POST['category']]);
								if ($category_handler->getCount($criteria)>0) {
									$objects = $category_handler->getObjects($criteria, false);
									$cid = $objects[0]->getVar('cid');
								} else {
									$object = $category_handler->create();
									$object->setVar('name', $data[$_POST['category']]);
									$cid = $category_handler->insert($object);
								} 								
							}
							$aids = array();
							if (strlen($_POST['artist'])>0&&!empty($data[$_POST['artist']])) {
								foreach(explode(',', $data[$_POST['artist']]) as $artist) {
									$criteria = new Criteria('`name`',  $artist);
									if ($artists_handler->getCount($criteria)>0) {
										$objects = $artists_handler->getObjects($criteria, false);
										$aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
									} else {
										$object = $artists_handler->create();
										$object->setVar('cid', $cid);
										switch($data[$_POST['singer']]) {
											case $_POST['duet']:
												$object->setVar('singer', '_ENUM_SONGLIST_DUET');
												break;
											case $_POST['solo']:
												$object->setVar('singer', '_ENUM_SONGLIST_SOLO');
												break;
										}
										$object->setVar('name', $data[$_POST['artist']]);
										$aid = $artists_handler->insert($object);
										$aids[$aid] = $aid;
									}
								} 								
							}
							$abid = 0;
							if (strlen($_POST['album'])>0&&!empty($data[$_POST['album']])) {
								$criteria = new Criteria('`name`',  $data[$_POST['album']]);
								if ($albums_handler->getCount($criteria)>0) {
									$objects = $albums_handler->getObjects($criteria, false);
									$abid = $objects[0]->getVar('aid');
								} else {
									$object = $albums_handler->create();
									$object->setVar('cid', $cid);
									$object->setVar('aids', $aids);
									$object->setVar('name', $data[$_POST['album']]);
									$abid = $albums_handler->insert($object);
								} 								
							}
							$sid = 0;
							if (strlen($_POST['songid'])>0&&!empty($data[$_POST['songid']])) {
								$criteria = new Criteria('`songid`',  $data[$_POST['songid']]);
								if ($songs_handler->getCount($criteria)>0) {
									$objects = $songs_handler->getObjects($criteria, false);
									$object = $objects[0]->getVar('sid');
								} else {
									$object = $songs_handler->create();
								}
								if ($object->getVar('cid')>0&&$cid>0) { $object->setVar('cid', $cid); } else { $object->setVar('cid', $cid); }
								if ($object->getVar('gid')>0&&$gid>0) { $object->setVar('gid', $gid); } else { $object->setVar('gid', $gid); }
								if (count($object->getVar('aids'))>0&&count($aids)>0) { $object->setVar('aids', $aids); } else { $object->setVar('aids', $aids); }
								if ($object->getVar('abid')>0&&$abid>0) { $object->setVar('abid', $abid); } else { $object->setVar('abid', $abid); }
								$object->setVar('songid', $data[$_POST['songid']]);
								$object->setVar('title', $data[$_POST['title']]);
								$object->setVar('lyrics', str_replace("\n", "<br/>\n", $data[$_POST['lyrics']]));
								$sid = $songs_handler->insert($object); 								
							}
						}			
					}
				} else {
					foreach ($xmlarray as $recid => $data) {
						$gid = 0;
						if (strlen($_POST['genre'])>0&&!empty($data[$_POST['genre']])) {
							$criteria = new Criteria('`name`',  $data[$_POST['genre']]);
							if ($genre_handler->getCount($criteria)>0) {
								$objects = $genre_handler->getObjects($criteria, false);
								$gid = $objects[0]->getVar('gid');
							} else {
								$object = $genre_handler->create();
								$object->setVar('name', $data[$_POST['genre']]);
								$gid = $genre_handler->insert($object);
							} 								
						}	
						$vid = 0;
						if (strlen($_POST['voice'])>0&&!empty($data[$_POST['voice']])) {
							$criteria = new Criteria('`name`',  $data[$_POST['voice']]);
							if ($voice_handler->getCount($criteria)>0) {
								$objects = $voice_handler->getObjects($criteria, false);
								$gid = $objects[0]->getVar('vid');
							} else {
								$object = $voice_handler->create();
								$object->setVar('name', $data[$_POST['voice']]);
								$gid = $voice_handler->insert($object);
							} 								
						}						
						$cid = 0;
						if (strlen($_POST['category'])>0&&!empty($data[$_POST['category']])) {
							$criteria = new Criteria('`name`',  $data[$_POST['category']]);
							if ($category_handler->getCount($criteria)>0) {
								$objects = $category_handler->getObjects($criteria, false);
								$cid = $objects[0]->getVar('cid');
							} else {
								$object = $category_handler->create();
								$object->setVar('name', $data[$_POST['category']]);
								$cid = $category_handler->insert($object);
							} 								
						}
						$aids = array();
						if (strlen($_POST['artist'])>0&&!empty($data[$_POST['artist']])) {
							foreach(explode(',', $data[$_POST['artist']]) as $artist) {
								$criteria = new Criteria('`name`',  $artist);
								if ($artists_handler->getCount($criteria)>0) {
									$objects = $artists_handler->getObjects($criteria, false);
									$aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
								} else {
									$object = $artists_handler->create();
									switch($data[$_POST['singer']]) {
										case $_POST['duet']:
											$object->setVar('singer', '_ENUM_SONGLIST_DUET');
											break;
										case $_POST['solo']:
											$object->setVar('singer', '_ENUM_SONGLIST_SOLO');
											break;
									}
									$object->setVar('cid', $cid);
									$object->setVar('name', $data[$_POST['artist']]);
									$aid = $artists_handler->insert($object);
									$aids[$aid] = $aid;
								}
							} 								
						}
						$abid = 0;
						if (strlen($_POST['album'])>0&&!empty($data[$_POST['album']])) {
							$criteria = new Criteria('`name`',  $data[$_POST['album']]);
							if ($albums_handler->getCount($criteria)>0) {
								$objects = $albums_handler->getObjects($criteria, false);
								$abid = $objects[0]->getVar('aid');
							} else {
								$object = $albums_handler->create();
								$object->setVar('cid', $cid);
								$object->setVar('aids', $aids);
								$object->setVar('name', $data[$_POST['album']]);
								$abid = $albums_handler->insert($object);
							} 								
						}
						$sid = 0;
						if (strlen($_POST['songid'])>0&&!empty($data[$_POST['songid']])) {
							$criteria = new Criteria('`songid`',  $data[$_POST['songid']]);
							if ($songs_handler->getCount($criteria)>0) {
								$objects = $songs_handler->getObjects($criteria, false);
								$object = $objects[0]->getVar('sid');
							} else {
								$object = $songs_handler->create();
							}
							if ($object->getVar('cid')>0&&$cid>0) { $object->setVar('cid', $cid); } else { $object->setVar('cid', $cid); }
							if ($object->getVar('gid')>0&&$gid>0) { $object->setVar('gid', $gid); } else { $object->setVar('gid', $gid); }
							if (count($object->getVar('aids'))>0&&count($aids)>0) { $object->setVar('aids', $aids); } else { $object->setVar('aids', $aids); }
							if ($object->getVar('abid')>0&&$abid>0) { $object->setVar('abid', $abid); } else { $object->setVar('abid', $abid); }
							$object->setVar('songid', $data[$_POST['songid']]);
							$object->setVar('title', $data[$_POST['title']]);
							$object->setVar('lyrics', str_replace("\n", "<br/>\n", $data[$_POST['lyrics']]));
							$sid = $songs_handler->insert($object); 								
						}
					}
				}
				unlink($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'].$_SESSION['xmlfile']));
				unset($_SESSION['xmlfile']);
				redirect_header($_SERVER['PHP_SELF'].'&op=import&fct=actiona', 10, _AM_SONGLIST_XMLFILE_COMPLETE);
				break;
		}
		break;
				
	}
	
	xoops_cp_footer();
?>