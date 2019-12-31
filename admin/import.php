<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'songlist');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"import";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"actiona";
	$limit = isset($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = isset($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = isset($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = isset($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = isset($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
	default:
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
				if ($_POST['file']!='') {
					$file = substr(md5(microtime(true)), mt_rand(0,20), 13).'.xml';
					copy($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']).$_POST['file'], $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']).$file);
				    $_SESSION['xmlfile'] = $file;
			      	redirect_header($_SERVER['PHP_SELF'].'?file='.$file.'&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_COPIED);
			      	exit;
				} elseif (isset($_FILES['xmlfile'])&&strlen($_FILES['xmlfile']['name'])) {
						
					if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
						foreach(explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders)
							foreach(explode('/', $folders) as $folder) {
								$path .= DS . $folder;
								mkdir($path, 0777);
							}
					}
					
					include_once($GLOBALS['xoops']->path('modules/songlist/include/uploader.php'));
					$uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), array('application/xml', 'text/xml', 'application/xml-dtd', 'application/xml-external-parsed-entity', 'text/xml xml xsl', 'text/xml-external-parsed-entity'), 1024*1024*1024*32, 0, 0, array('xml'));
					$uploader->setPrefix(substr(md5(microtime(true)), mt_rand(0,20), 13));
					
					if ($uploader->fetchMedia('xmlfile')) {
					  	if (!$uploader->upload()) {
							$indexAdmin = new ModuleAdmin();
							echo $indexAdmin->addNavigation(basename(__FILE__));
					    	echo $uploader->getErrors();
							xoops_cp_footer();
							exit(0);
				  	    } else {
					      	$_SESSION['xmlfile'] = $uploader->getSavedFileName();
					      	redirect_header($_SERVER['PHP_SELF'].'?file='.$uploader->getSavedFileName().'&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_UPLOADED);
					      	exit;
					    }      	
				  	} else {
						$indexAdmin = new ModuleAdmin();
						echo $indexAdmin->addNavigation(basename(__FILE__));
				       	echo $uploader->getErrors();
						xoops_cp_footer();
						exit(0);
				   	}
				} else {
					$indexAdmin = new ModuleAdmin();
					echo $indexAdmin->addNavigation(basename(__FILE__));
			       	echo _AM_SONGLIST_IMPORT_NOFILE;
					xoops_cp_footer();
					exit(0);
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
				
				$record = 0;
				
				set_time_limit(3600);
								
				$xmlarray = songlist_xml2array(file_get_contents($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'].$_SESSION['xmlfile'])), false, 'tag');

				if (!empty($_POST['collection'])&&strlen($_POST['collection'])>0) {
					if (!empty($_POST['record'])&&strlen($_POST['record'])>0) {
						foreach($xmlarray[$_POST['collection']][$_POST['record']] as $recid => $data) {
							if (isset($_POST['limiting'])) {
								if (intval($_POST['limiting'])==true) {
									$record++;
									if ($record>intval($_POST['records'])) {
										$start = time();
										while(time()-$start<intval($_POST['wait'])) {
											
										}
										$records = 0;
									}
								}
							}
							$gid = 0;
							$gids = array();
							if (!empty($_POST['genre'])&&strlen($_POST['genre'])>1) {
								if (isset($data[$_POST['genre']])&&trim($_POST['genre'])!='') {
									foreach(explode(',', trim($data[$_POST['genre']])) as $genre) {
										$criteria = new Criteria('`name`',  trim($genre));
										if ($genre_handler->getCount($criteria)>0) {
											$objects = $genre_handler->getObjects($criteria, false);
											$gid = $objects[0]->getVar('gid');
										} else {
											$object = $genre_handler->create();
											$object->setVar('name', trim($genre));
											$gid = $genre_handler->insert($object);
										} 								
										$gids[$gid] = $gid;
									}
								}
							}	
							$vcid = 0;
							if (!empty($_POST['voice'])&&strlen($_POST['voice'])>1) {
								if (isset($data[$_POST['voice']])&&trim($_POST['voice'])!='') {
									$criteria = new Criteria('`name`',  trim($data[$_POST['voice']]));
									if ($voice_handler->getCount($criteria)>0) {
										$objects = $voice_handler->getObjects($criteria, false);
										$vcid = $objects[0]->getVar('vcid');
									} else {
										$object = $voice_handler->create();
										$object->setVar('name', trim($data[$_POST['voice']]));
										$vcid = $voice_handler->insert($object);
									} 								
								}
							}	
							
							$cid = 0;
							if (!empty($_POST['category'])&&strlen($_POST['category'])>1) {
								if (isset($data[$_POST['category']])&&trim($_POST['category'])!='') {
									$criteria = new Criteria('`name`',  trim($data[$_POST['category']]));
									if ($category_handler->getCount($criteria)>0) {
										$objects = $category_handler->getObjects($criteria, false);
										$cid = $objects[0]->getVar('cid');
									} else {
										$object = $category_handler->create();
										$object->setVar('name', trim($data[$_POST['category']]));
										$cid = $category_handler->insert($object);
									} 								
								}
							}
							$aids = array();
							if (!empty($_POST['artist'])&&strlen($_POST['artist'])>1) {
								if (isset($data[$_POST['artist']])&&$_POST['artist']!='') {
									foreach(explode(',', trim($data[$_POST['artist']])) as $artist) {
										$criteria = new Criteria('`name`',  trim($artist));
										if ($artists_handler->getCount($criteria)>0) {
											$objects = $artists_handler->getObjects($criteria, false);
											$aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
										} else {
											$object = $artists_handler->create(); // added PL
											$object->setVar('name', trim($artist));
											$aid = $artists_handler->insert($object);
											$aids[$aid] = $aid;
										}
									} 								
								}
							}
							$abid = 0;
							if (!empty($_POST['album'])&&strlen($_POST['album'])>1) {
								if (isset($data[$_POST['album']])&&trim($_POST['album'])!='') {
									$criteria = new Criteria('`title`',  trim($data[$_POST['album']]));
									if ($albums_handler->getCount($criteria)>0) {
										$objects = $albums_handler->getObjects($criteria, false);
										$abid = $objects[0]->getVar('abid');
									} else {
										$object = $albums_handler->create();
										$object->setVar('cid', $cid);
										$object->setVar('aids', $aids);
										$object->setVar('title', trim($data[$_POST['album']]));
										$abid = $albums_handler->insert($object);
									} 							 								
								}
							}
							$sid = 0;
							if ((!empty($_POST['songid'])&&strlen($_POST['songid'])>1)||(!empty($_POST['title'])&&strlen($_POST['title'])>1)) {
								if ((isset($data[$_POST['songid']])&&$_POST['songid']!='')||(isset($data[$_POST['title']])&&$_POST['title']!='')) {
									$criteria = new CriteriaCompo();
									if (trim($data[$_POST['songid']])!='')
										$criteria->add(new Criteria('`songid`',  trim($data[$_POST['songid']])));
									if (trim($data[$_POST['title']])!='')
										$criteria->add(new Criteria('`title`',  trim($data[$_POST['title']])));
									if ($songs_handler->getCount($criteria)>0) {
										$objects = $songs_handler->getObjects($criteria, false);
										$object = $objects[0];
									} else {
										$object = $songs_handler->create();
									}
									$object->setVar('cid', $cid);
									$object->setVar('gids', $gids);
									$object->setVar('vcid', $vcid);
									$object->setVar('aids', $aids); 
									$object->setVar('abid', $abid); 
									$object->setVar('songid', trim($data[$_POST['songid']]));
									$object->setVar('traxid', trim($data[$_POST['traxid']]));
									$object->setVar('title', trim($data[$_POST['title']]));
									$object->setVar('tags', trim($data[$_POST['tags']]));
									$object->setVar('mp3', trim($data[$_POST['mp3']]));
									$object->setVar('lyrics', str_replace("\n", "<br/>\n", trim($data[$_POST['lyrics']])));
									$sid = $songs_handler->insert($object);
									
									if ($GLOBALS['songlistModuleConfig']['tags']&&file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
										$tag_handler = xoops_getmodulehandler('tag', 'tag');
										$tag_handler->updateByItem(trim($data[$_POST['tags']]), $sid, $GLOBALS['songlistModule']->getVar("dirname"), $cid);
									}
										
									$extras_handler = xoops_getmodulehandler('extras', 'songlist');
									$fields = $extras_handler->getFields(NULL);
									$criteria = new CriteriaCompo(new Criteria('`sid`', $sid));
									if ($extras_handler->getCount($criteria)>0) {
										$extras = $extras_handler->getObjects($criteria, false);
										$extra = $extras[0];
									} else {
										$extra = $extras_handler->create();
									}
									$extra->setVar('sid', $sid);
									foreach($fields as $field) {
										if (!empty($_POST[$field->getVar('field_name')])&&strlen($_POST[$field->getVar('field_name')])>1) {
											if (isset($data[$_POST[$field->getVar('field_name')]])&&trim($_POST[$field->getVar('field_name')])!='') {
												$extra->setVar($field->getVar('field_name'), trim($data[$_POST[$field->getVar('field_name')]]));
											}
										}
									}									
									foreach($artists_handler->getObjects(new Criteria('aid', '('.implode(',', $aids).')', 'IN'), true) as $aid => $artist) {
										$artist->setVar('sids', array_merge($artist->getVar('sids'), array($sid=>$sid)));
										$artists_handler->insert($artist, true);
									} 								
								}
							}
						}
					} else {
						foreach($xmlarray[$_POST['collection']] as $id => $records) {
							if (isset($_POST['limiting'])) {
								if (intval($_POST['limiting'])==true) {
									$record++;
									if ($record>intval($_POST['records'])) {
										$start = time();
										while(time()-$start<intval($_POST['wait'])) {
											
										}
										$records = 0;
									}
								}
							}
							$gid = 0;
							$gids = array();
							if (!empty($_POST['genre'])&&strlen($_POST['genre'])>1) {
								if (isset($data[$_POST['genre']])&&trim($_POST['genre'])!='') {
									foreach(explode(',', trim($data[$_POST['genre']])) as $genre) {
										$criteria = new Criteria('`name`',  trim($genre));
										if ($genre_handler->getCount($criteria)>0) {
											$objects = $genre_handler->getObjects($criteria, false);
											$gid = $objects[0]->getVar('gid');
										} else {
											$object = $genre_handler->create();
											$object->setVar('name', trim($genre));
											$gid = $genre_handler->insert($object);
										} 								
										$gids[$gid] = $gid;
									}
								}
							}	
							if (!empty($_POST['voice'])&&strlen($_POST['voice'])>1) {
								if (isset($data[$_POST['voice']])&&trim($_POST['voice'])!='') {
									$criteria = new Criteria('`name`',  trim($data[$_POST['voice']]));
									if ($voice_handler->getCount($criteria)>0) {
										$objects = $voice_handler->getObjects($criteria, false);
										$vcid = $objects[0]->getVar('vcid');
									} else {
										$object = $voice_handler->create();
										$object->setVar('name', trim($data[$_POST['voice']]));
										$vcid = $voice_handler->insert($object);
									} 								
								}
							}							
							$cid = 0;
							if (!empty($_POST['category'])&&strlen($_POST['category'])>1) {
								if (isset($data[$_POST['category']])&&trim($_POST['category'])!='') {
									$criteria = new Criteria('`name`',  trim($data[$_POST['category']]));
									if ($category_handler->getCount($criteria)>0) {
										$objects = $category_handler->getObjects($criteria, false);
										$cid = $objects[0]->getVar('cid');
									} else {
										$object = $category_handler->create();
										$object->setVar('name', trim($data[$_POST['category']]));
										$cid = $category_handler->insert($object);
									} 								
								}
							}
							$aids = array();
							if (!empty($_POST['artist'])&&strlen($_POST['artist'])>1) {
								if (isset($data[$_POST['artist']])&&$_POST['artist']!='') {
									foreach(explode(',', trim($data[$_POST['artist']])) as $artist) {
										$criteria = new Criteria('`name`',  trim($artist));
										if ($artists_handler->getCount($criteria)>0) {
											$objects = $artists_handler->getObjects($criteria, false);
											$aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
										} else {
											$object = $artists_handler->create(); //added PL
											$object->setVar('cid', $cid);
											$object->setVar('name', trim($data[$_POST['artist']]));
											$aid = $artists_handler->insert($object);
											$aids[$aid] = $aid;
										}
									} 								
								}
							}
							$abid = 0;
							if (!empty($_POST['album'])&&strlen($_POST['album'])>1) {
								if (isset($data[$_POST['album']])&&trim($_POST['album'])!='') {
									$criteria = new Criteria('`title`',  trim($data[$_POST['album']]));
									if ($albums_handler->getCount($criteria)>0) {
										$objects = $albums_handler->getObjects($criteria, false);
										$abid = $objects[0]->getVar('abid');
									} else {
										$object = $albums_handler->create();
										$object->setVar('cid', $cid);
										$object->setVar('aids', $aids);
										$object->setVar('title', trim($data[$_POST['album']]));
										$abid = $albums_handler->insert($object);
									} 							 								
								}
							}
							$sid = 0;
							if ((!empty($_POST['songid'])&&strlen($_POST['songid'])>1)||(!empty($_POST['title'])&&strlen($_POST['title'])>1)) {
								if ((isset($data[$_POST['songid']])&&$_POST['songid']!='')||(isset($data[$_POST['title']])&&$_POST['title']!='')) {
									$criteria = new CriteriaCompo();
									if (trim($data[$_POST['songid']])!='')
										$criteria->add(new Criteria('`songid`',  trim($data[$_POST['songid']])));
									if (trim($data[$_POST['title']])!='')
										$criteria->add(new Criteria('`title`',  trim($data[$_POST['title']])));
									if ($songs_handler->getCount($criteria)>0) {
										$objects = $songs_handler->getObjects($criteria, false);
										$object = $objects[0];
									} else {
										$object = $songs_handler->create();
									}
									$object->setVar('cid', $cid);
									$object->setVar('gids', $gids); 
									$object->setVar('vcid', $vcid); 
									$object->setVar('aids', $aids); 
									$object->setVar('abid', $abid); 
									$object->setVar('songid', trim($data[$_POST['songid']]));
									$object->setVar('traxid', trim($data[$_POST['traxid']]));
									$object->setVar('tags', trim($data[$_POST['tags']]));
									$object->setVar('mp3', trim($data[$_POST['mp3']]));
									$object->setVar('title', trim($data[$_POST['title']]));
									$object->setVar('lyrics', str_replace("\n", "<br/>\n", trim($data[$_POST['lyrics']])));
									$sid = $songs_handler->insert($object);
									
									if ($GLOBALS['songlistModuleConfig']['tags']&&file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
										$tag_handler = xoops_getmodulehandler('tag', 'tag');
										$tag_handler->updateByItem(trim($data[$_POST['tags']]), $sid, $GLOBALS['songlistModule']->getVar("dirname"), $cid);
									}
										
									$extras_handler = xoops_getmodulehandler('extras', 'songlist');
									$fields = $extras_handler->getFields(NULL);
									$criteria = new CriteriaCompo(new Criteria('`sid`', $sid));
									if ($extras_handler->getCount($criteria)>0) {
										$extras = $extras_handler->getObjects($criteria, false);
										$extra = $extras[0];
									} else {
										$extra = $extras_handler->create();
									}
									$extra->setVar('sid', $sid);
									foreach($fields as $field) {
										if (!empty($_POST[$field->getVar('field_name')])&&strlen($_POST[$field->getVar('field_name')])>1) {
											if (isset($data[$_POST[$field->getVar('field_name')]])&&trim($_POST[$field->getVar('field_name')])!='') {
												$extra->setVar($field->getVar('field_name'), trim($data[$_POST[$field->getVar('field_name')]]));
											}
										}
									}
									$extras_handler->insert($extra, true);
									foreach($artists_handler->getObjects(new Criteria('aid', '('.implode(',', $aids).')', 'IN'), true) as $aid => $artist) {
										$artist->setVar('sids', array_merge($artist->getVar('sids'), array($sid=>$sid)));
										$artists_handler->insert($artist, true);
									} 								
								}
							}
						}
					}			
				} else {
					
					foreach ($xmlarray as $recid => $data) {
						$cid=0;
						$gid=0; 
						$vcid=0;
						$aids=array(); 
						$abid=array(); 
						if (isset($_POST['limiting'])) {
							if (intval($_POST['limiting'])==true) {
								$record++;
								if ($record>intval($_POST['records'])) {
									$start = time();
									while(time()-$start<intval($_POST['wait'])) {
										
									}
									$records = 0;
								}
							}
						}
						$gid = 0;
						$gids = array();
						if (!empty($_POST['genre'])&&strlen($_POST['genre'])>1) {
							if (isset($data[$_POST['genre']])&&trim($_POST['genre'])!='') {
								foreach(explode(',', trim($data[$_POST['genre']])) as $genre) {
									$criteria = new Criteria('`name`',  trim($genre));
									if ($genre_handler->getCount($criteria)>0) {
										$objects = $genre_handler->getObjects($criteria, false);
										$gid = $objects[0]->getVar('gid');
									} else {
										$object = $genre_handler->create();
										$object->setVar('name', trim($genre));
										$gid = $genre_handler->insert($object);
									} 								
									$gids[$gid] = $gid;
								}
							}
						}	
					
						$vcid = 0;
						if (!empty($_POST['voice'])&&strlen($_POST['voice'])>1) {
							if (isset($data[$_POST['voice']])&&trim($_POST['voice'])!='') {
								$criteria = new Criteria('`name`',  trim($data[$_POST['voice']]));
								if ($voice_handler->getCount($criteria)>0) {
									$objects = $voice_handler->getObjects($criteria, false);
									$vcid = $objects[0]->getVar('vcid');
								} else {
									$object = $voice_handler->create();
									$object->setVar('name', trim($data[$_POST['voice']]));
									$vcid = $voice_handler->insert($object);
								} 								
							}
						}						
						$cid = 0;
						if (!empty($_POST['category'])&&strlen($_POST['category'])>1) {
							if (isset($data[$_POST['category']])&&trim($_POST['category'])!='') {
								$criteria = new Criteria('`name`',  trim($data[$_POST['category']]));
								if ($category_handler->getCount($criteria)>0) {
									$objects = $category_handler->getObjects($criteria, false);
									$cid = $objects[0]->getVar('cid');
								} else {
									$object = $category_handler->create();
									$object->setVar('name', trim($data[$_POST['category']]));
									$cid = $category_handler->insert($object);
								} 								
							}
						}
						$aids = array();
						if (!empty($_POST['artist'])&&strlen($_POST['artist'])>1) {
							if (isset($data[$_POST['artist']])&&$_POST['artist']!='') {
								foreach(explode(',', trim($data[$_POST['artist']])) as $artist) {
									$criteria = new Criteria('`name`',  trim($artist));
									if ($artists_handler->getCount($criteria)>0) {
										$objects = $artists_handler->getObjects($criteria, false);
										$aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
									} else {
										$object = $artists_handler->create(); //Added PL
										$object->setVar('cid', $cid);
										$object->setVar('name', trim($data[$_POST['artist']]));
										$aid = $artists_handler->insert($object);
										$aids[$aid] = $aid;
									}
								} 								
							}
						}
						$abid = 0;
						if (!empty($_POST['album'])&&strlen($_POST['album'])>1) {
							if (isset($data[$_POST['album']])&&trim($_POST['album'])!='') {
								$criteria = new Criteria('`title`',  trim($data[$_POST['album']]));
								if ($albums_handler->getCount($criteria)>0) {
									$objects = $albums_handler->getObjects($criteria, false);
									$abid = $objects[0]->getVar('abid');
								} else {
									$object = $albums_handler->create();
									$object->setVar('cid', $cid);
									$object->setVar('aids', $aids);
									$object->setVar('title', trim($data[$_POST['album']]));
									$abid = $albums_handler->insert($object);
								} 							 								
							}
						}
						$sid = 0;
						if ((!empty($_POST['songid'])&&strlen($_POST['songid'])>1)||(!empty($_POST['title'])&&strlen($_POST['title'])>1)) {
							if ((isset($data[$_POST['songid']])&&$_POST['songid']!='')||(isset($data[$_POST['title']])&&$_POST['title']!='')) {
								$criteria = new CriteriaCompo();
								if (trim($data[$_POST['songid']])!='')
									$criteria->add(new Criteria('`songid`',  trim($data[$_POST['songid']])));
								if (trim($data[$_POST['title']])!='')
									$criteria->add(new Criteria('`title`',  trim($data[$_POST['title']])));
								if ($songs_handler->getCount($criteria)>0) {
									$objects = $songs_handler->getObjects($criteria, false);
									$object = $objects[0];
								} else {
									$object = $songs_handler->create();
								}
								$object->setVar('cid', $cid);
								$object->setVar('gids', $gids); 
								$object->setVar('vcid', $vcid);
								$object->setVar('aids', $aids); 
								$object->setVar('abid', $abid); 
								$object->setVar('songid', trim($data[$_POST['songid']]));
								$object->setVar('traxid', trim($data[$_POST['traxid']]));
								$object->setVar('title', trim($data[$_POST['title']]));
								$object->setVar('tags', trim($data[$_POST['tags']]));
								$object->setVar('mp3', trim($data[$_POST['mp3']]));
								$object->setVar('lyrics', str_replace("\n", "<br/>\n", trim($data[$_POST['lyrics']])));
								$sid = $songs_handler->insert($object);
								
								if ($GLOBALS['songlistModuleConfig']['tags']&&file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
									$tag_handler = xoops_getmodulehandler('tag', 'tag');
									$tag_handler->updateByItem(trim($data[$_POST['tags']]), $sid, $GLOBALS['songlistModule']->getVar("dirname"), $cid);
								}
								
								$extras_handler = xoops_getmodulehandler('extras', 'songlist');
								$fields = $extras_handler->getFields(NULL);
								$criteria = new CriteriaCompo(new Criteria('`sid`', $sid));
								if ($extras_handler->getCount($criteria)>0) {
									$extras = $extras_handler->getObjects($criteria, false);
									$extra = $extras[0];
								} else {
									$extra = $extras_handler->create();
								}
								$extra->setVar('sid', $sid);
								foreach($fields as $field) {
									if (!empty($_POST[$field->getVar('field_name')])&&strlen($_POST[$field->getVar('field_name')])>1) {
										if (isset($data[$_POST[$field->getVar('field_name')]])&&trim($_POST[$field->getVar('field_name')])!='') {
											$extra->setVar($field->getVar('field_name'), trim($data[$_POST[$field->getVar('field_name')]]));
										}
									}
								}
								foreach($artists_handler->getObjects(new Criteria('aid', '('.implode(',', $aids).')', 'IN'), true) as $aid => $artist) {
									$artist->setVar('sids', array_merge($artist->getVar('sids'), array($sid=>$sid)));
									$artists_handler->insert($artist, true);
								} 								
							}
						}
					}
				}
				unlink($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'].$_SESSION['xmlfile']));
				unset($_SESSION['xmlfile']);
				redirect_header($_SERVER['PHP_SELF'].'?op=import&fct=actiona', 10, _AM_SONGLIST_XMLFILE_COMPLETE);
				break;
		}
		break;
				
	}
	
	xoops_cp_footer();
?>