<?php

include 'header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'import';
$fct    = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'actiona';
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = isset($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = isset($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
    default:
    case 'import':
        switch ($fct) {
            default:
            case 'actiona':

                if (\Xmf\Request::hasVar('xmlfile', 'SESSION')) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?file=' . $_SESSION['xmlfile'] . '&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_UPLOADED);
                }
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $GLOBALS['xoopsTpl']->assign('form', songlist_import_get_form(false));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_import_actiona.tpl');
                break;
            case 'upload':
                if ('' != $_POST['file']) {
                    $file = mb_substr(md5(microtime(true)), mt_rand(0, 20), 13) . '.xml';
                    copy($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']) . $_POST['file'], $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']) . $file);
                    $_SESSION['xmlfile'] = $file;
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?file=' . $file . '&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_COPIED);
                } elseif (\Xmf\Request::hasVar('xmlfile', 'FILES') && mb_strlen($_FILES['xmlfile']['name'])) {
                    if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
                        foreach (explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders) {
                            foreach (explode('/', $folders) as $folder) {
                                $path .= DS . $folder;
                                mkdir($path, 0777);
                            }
                        }
                    }

                    require_once $GLOBALS['xoops']->path('modules/songlist/include/uploader.php');
                    $uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), ['application/xml', 'text/xml', 'application/xml-dtd', 'application/xml-external-parsed-entity', 'text/xml xml xsl', 'text/xml-external-parsed-entity'],
                                                          1024 * 1024 * 1024 * 32, 0, 0, ['xml']);
                    $uploader->setPrefix(mb_substr(md5(microtime(true)), mt_rand(0, 20), 13));

                    if ($uploader->fetchMedia('xmlfile')) {
                        if (!$uploader->upload()) {
                            $adminObject = \Xmf\Module\Admin::getInstance();
                            $adminObject->displayNavigation(basename(__FILE__));
                            echo $uploader->getErrors();
                            xoops_cp_footer();
                            exit(0);
                        }
                        $_SESSION['xmlfile'] = $uploader->getSavedFileName();
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?file=' . $uploader->getSavedFileName() . '&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_UPLOADED);
                    } else {
                        $adminObject = \Xmf\Module\Admin::getInstance();
                        $adminObject->displayNavigation(basename(__FILE__));
                        echo $uploader->getErrors();
                        xoops_cp_footer();
                        exit(0);
                    }
                } else {
                    $adminObject = \Xmf\Module\Admin::getInstance();
                    $adminObject->displayNavigation(basename(__FILE__));
                    echo _AM_SONGLIST_IMPORT_NOFILE;
                    xoops_cp_footer();
                    exit(0);
                }
                break;
            case 'actionb':

                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $GLOBALS['xoopsTpl']->assign('form', songlist_importb_get_form($_SESSION['xmlfile']));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_import_actionb.tpl');
                break;
            case 'import':

                $songsHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                $albumsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
                $artistsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                $genreHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
                $voiceHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Voice');
                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');

                $filesize = filesize($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'] . $_SESSION['xmlfile']));
                $mb       = floor($filesize / 1024 / 1024);
                if ($mb > 32) {
                    set_ini('memory_limit', ($mb + 128) . 'M');
                }

                $record = 0;

                set_time_limit(3600);

                $xmlarray = songlist_xml2array(file_get_contents($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'] . $_SESSION['xmlfile'])), false, 'tag');

                if (!empty($_POST['collection']) && mb_strlen($_POST['collection']) > 0) {
                    if (!empty($_POST['record']) && mb_strlen($_POST['record']) > 0) {
                        foreach ($xmlarray[$_POST['collection']][$_POST['record']] as $recid => $data) {
                            if (\Xmf\Request::hasVar('limiting', 'POST')) {
                                if (true === \Xmf\Request::getInt('limiting', 0, 'POST')) {
                                    ++$record;
                                    if ($record > \Xmf\Request::getInt('records', 0, 'POST')) {
                                        $start = time();
                                        while (time() - $start < \Xmf\Request::getInt('wait', 0, 'POST')) {
                                        }
                                        $records = 0;
                                    }
                                }
                            }
                            $gid  = 0;
                            $gids = [];
                            if (!empty($_POST['genre']) && mb_strlen($_POST['genre']) > 1) {
                                if (isset($data[$_POST['genre']]) && '' != trim($_POST['genre'])) {
                                    foreach (explode(',', trim($data[$_POST['genre']])) as $genre) {
                                        $criteria = new \Criteria('`name`', trim($genre));
                                        if ($genreHandler->getCount($criteria) > 0) {
                                            $objects = $genreHandler->getObjects($criteria, false);
                                            $gid     = $objects[0]->getVar('gid');
                                        } else {
                                            $object = $genreHandler->create();
                                            $object->setVar('name', trim($genre));
                                            $gid = $genreHandler->insert($object);
                                        }
                                        $gids[$gid] = $gid;
                                    }
                                }
                            }
                            $vcid = 0;
                            if (!empty($_POST['voice']) && mb_strlen($_POST['voice']) > 1) {
                                if (isset($data[$_POST['voice']]) && '' != trim($_POST['voice'])) {
                                    $criteria = new \Criteria('`name`', trim($data[$_POST['voice']]));
                                    if ($voiceHandler->getCount($criteria) > 0) {
                                        $objects = $voiceHandler->getObjects($criteria, false);
                                        $vcid    = $objects[0]->getVar('vcid');
                                    } else {
                                        $object = $voiceHandler->create();
                                        $object->setVar('name', trim($data[$_POST['voice']]));
                                        $vcid = $voiceHandler->insert($object);
                                    }
                                }
                            }

                            $cid = 0;
                            if (!empty($_POST['category']) && mb_strlen($_POST['category']) > 1) {
                                if (isset($data[$_POST['category']]) && '' != trim($_POST['category'])) {
                                    $criteria = new \Criteria('`name`', trim($data[$_POST['category']]));
                                    if ($categoryHandler->getCount($criteria) > 0) {
                                        $objects = $categoryHandler->getObjects($criteria, false);
                                        $cid     = $objects[0]->getVar('cid');
                                    } else {
                                        $object = $categoryHandler->create();
                                        $object->setVar('name', trim($data[$_POST['category']]));
                                        $cid = $categoryHandler->insert($object);
                                    }
                                }
                            }
                            $aids = [];
                            if (!empty($_POST['artist']) && mb_strlen($_POST['artist']) > 1) {
                                if (isset($data[$_POST['artist']]) && '' != $_POST['artist']) {
                                    foreach (explode(',', trim($data[$_POST['artist']])) as $artist) {
                                        $criteria = new \Criteria('`name`', trim($artist));
                                        if ($artistsHandler->getCount($criteria) > 0) {
                                            $objects                          = $artistsHandler->getObjects($criteria, false);
                                            $aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
                                        } else {
                                            $object = $artistsHandler->create(); // added PL
                                            $object->setVar('name', trim($artist));
                                            $aid        = $artistsHandler->insert($object);
                                            $aids[$aid] = $aid;
                                        }
                                    }
                                }
                            }
                            $abid = 0;
                            if (!empty($_POST['album']) && mb_strlen($_POST['album']) > 1) {
                                if (isset($data[$_POST['album']]) && '' != trim($_POST['album'])) {
                                    $criteria = new \Criteria('`title`', trim($data[$_POST['album']]));
                                    if ($albumsHandler->getCount($criteria) > 0) {
                                        $objects = $albumsHandler->getObjects($criteria, false);
                                        $abid    = $objects[0]->getVar('abid');
                                    } else {
                                        $object = $albumsHandler->create();
                                        $object->setVar('cid', $cid);
                                        $object->setVar('aids', $aids);
                                        $object->setVar('title', trim($data[$_POST['album']]));
                                        $abid = $albumsHandler->insert($object);
                                    }
                                }
                            }
                            $sid = 0;
                            if ((!empty($_POST['songid']) && mb_strlen($_POST['songid']) > 1) || (!empty($_POST['title']) && mb_strlen($_POST['title']) > 1)) {
                                if ((isset($data[$_POST['songid']]) && '' != $_POST['songid']) || (isset($data[$_POST['title']]) && '' != $_POST['title'])) {
                                    $criteria = new \CriteriaCompo();
                                    if ('' != trim($data[$_POST['songid']])) {
                                        $criteria->add(new \Criteria('`songid`', trim($data[$_POST['songid']])));
                                    }
                                    if ('' != trim($data[$_POST['title']])) {
                                        $criteria->add(new \Criteria('`title`', trim($data[$_POST['title']])));
                                    }
                                    if ($songsHandler->getCount($criteria) > 0) {
                                        $objects = $songsHandler->getObjects($criteria, false);
                                        $object  = $objects[0];
                                    } else {
                                        $object = $songsHandler->create();
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
                                    $object->setVar('lyrics', str_replace("\n", "<br>\n", trim($data[$_POST['lyrics']])));
                                    $sid = $songsHandler->insert($object);

                                    if ($GLOBALS['songlistModuleConfig']['tags'] && file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
                                        $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
                                        $tagHandler->updateByItem(trim($data[$_POST['tags']]), $sid, $GLOBALS['songlistModule']->getVar('dirname'), $cid);
                                    }

                                    $extrasHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
                                    $fields        = $extrasHandler->getFields(null);
                                    $criteria      = new \CriteriaCompo(new \Criteria('`sid`', $sid));
                                    if ($extrasHandler->getCount($criteria) > 0) {
                                        $extras = $extrasHandler->getObjects($criteria, false);
                                        $extra  = $extras[0];
                                    } else {
                                        $extra = $extrasHandler->create();
                                    }
                                    $extra->setVar('sid', $sid);
                                    foreach ($fields as $field) {
                                        if (!empty($_POST[$field->getVar('field_name')]) && mb_strlen($_POST[$field->getVar('field_name')]) > 1) {
                                            if (isset($data[$_POST[$field->getVar('field_name')]]) && '' != trim($_POST[$field->getVar('field_name')])) {
                                                $extra->setVar($field->getVar('field_name'), trim($data[$_POST[$field->getVar('field_name')]]));
                                            }
                                        }
                                    }
                                    foreach ($artistsHandler->getObjects(new \Criteria('aid', '(' . implode(',', $aids) . ')', 'IN'), true) as $aid => $artist) {
                                        $artist->setVar('sids', array_merge($artist->getVar('sids'), [$sid => $sid]));
                                        $artistsHandler->insert($artist, true);
                                    }
                                }
                            }
                        }
                    } else {
                        foreach ($xmlarray[$_POST['collection']] as $id => $records) {
                            if (\Xmf\Request::hasVar('limiting', 'POST')) {
                                if (true === \Xmf\Request::getInt('limiting', 0, 'POST')) {
                                    ++$record;
                                    if ($record > \Xmf\Request::getInt('records', 0, 'POST')) {
                                        $start = time();
                                        while (time() - $start < \Xmf\Request::getInt('wait', 0, 'POST')) {
                                        }
                                        $records = 0;
                                    }
                                }
                            }
                            $gid  = 0;
                            $gids = [];
                            if (!empty($_POST['genre']) && mb_strlen($_POST['genre']) > 1) {
                                if (isset($data[$_POST['genre']]) && '' != trim($_POST['genre'])) {
                                    foreach (explode(',', trim($data[$_POST['genre']])) as $genre) {
                                        $criteria = new \Criteria('`name`', trim($genre));
                                        if ($genreHandler->getCount($criteria) > 0) {
                                            $objects = $genreHandler->getObjects($criteria, false);
                                            $gid     = $objects[0]->getVar('gid');
                                        } else {
                                            $object = $genreHandler->create();
                                            $object->setVar('name', trim($genre));
                                            $gid = $genreHandler->insert($object);
                                        }
                                        $gids[$gid] = $gid;
                                    }
                                }
                            }
                            if (!empty($_POST['voice']) && mb_strlen($_POST['voice']) > 1) {
                                if (isset($data[$_POST['voice']]) && '' != trim($_POST['voice'])) {
                                    $criteria = new \Criteria('`name`', trim($data[$_POST['voice']]));
                                    if ($voiceHandler->getCount($criteria) > 0) {
                                        $objects = $voiceHandler->getObjects($criteria, false);
                                        $vcid    = $objects[0]->getVar('vcid');
                                    } else {
                                        $object = $voiceHandler->create();
                                        $object->setVar('name', trim($data[$_POST['voice']]));
                                        $vcid = $voiceHandler->insert($object);
                                    }
                                }
                            }
                            $cid = 0;
                            if (!empty($_POST['category']) && mb_strlen($_POST['category']) > 1) {
                                if (isset($data[$_POST['category']]) && '' != trim($_POST['category'])) {
                                    $criteria = new \Criteria('`name`', trim($data[$_POST['category']]));
                                    if ($categoryHandler->getCount($criteria) > 0) {
                                        $objects = $categoryHandler->getObjects($criteria, false);
                                        $cid     = $objects[0]->getVar('cid');
                                    } else {
                                        $object = $categoryHandler->create();
                                        $object->setVar('name', trim($data[$_POST['category']]));
                                        $cid = $categoryHandler->insert($object);
                                    }
                                }
                            }
                            $aids = [];
                            if (!empty($_POST['artist']) && mb_strlen($_POST['artist']) > 1) {
                                if (isset($data[$_POST['artist']]) && '' != $_POST['artist']) {
                                    foreach (explode(',', trim($data[$_POST['artist']])) as $artist) {
                                        $criteria = new \Criteria('`name`', trim($artist));
                                        if ($artistsHandler->getCount($criteria) > 0) {
                                            $objects                          = $artistsHandler->getObjects($criteria, false);
                                            $aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
                                        } else {
                                            $object = $artistsHandler->create(); //added PL
                                            $object->setVar('cid', $cid);
                                            $object->setVar('name', trim($data[$_POST['artist']]));
                                            $aid        = $artistsHandler->insert($object);
                                            $aids[$aid] = $aid;
                                        }
                                    }
                                }
                            }
                            $abid = 0;
                            if (!empty($_POST['album']) && mb_strlen($_POST['album']) > 1) {
                                if (isset($data[$_POST['album']]) && '' != trim($_POST['album'])) {
                                    $criteria = new \Criteria('`title`', trim($data[$_POST['album']]));
                                    if ($albumsHandler->getCount($criteria) > 0) {
                                        $objects = $albumsHandler->getObjects($criteria, false);
                                        $abid    = $objects[0]->getVar('abid');
                                    } else {
                                        $object = $albumsHandler->create();
                                        $object->setVar('cid', $cid);
                                        $object->setVar('aids', $aids);
                                        $object->setVar('title', trim($data[$_POST['album']]));
                                        $abid = $albumsHandler->insert($object);
                                    }
                                }
                            }
                            $sid = 0;
                            if ((!empty($_POST['songid']) && mb_strlen($_POST['songid']) > 1) || (!empty($_POST['title']) && mb_strlen($_POST['title']) > 1)) {
                                if ((isset($data[$_POST['songid']]) && '' != $_POST['songid']) || (isset($data[$_POST['title']]) && '' != $_POST['title'])) {
                                    $criteria = new \CriteriaCompo();
                                    if ('' != trim($data[$_POST['songid']])) {
                                        $criteria->add(new \Criteria('`songid`', trim($data[$_POST['songid']])));
                                    }
                                    if ('' != trim($data[$_POST['title']])) {
                                        $criteria->add(new \Criteria('`title`', trim($data[$_POST['title']])));
                                    }
                                    if ($songsHandler->getCount($criteria) > 0) {
                                        $objects = $songsHandler->getObjects($criteria, false);
                                        $object  = $objects[0];
                                    } else {
                                        $object = $songsHandler->create();
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
                                    $object->setVar('lyrics', str_replace("\n", "<br>\n", trim($data[$_POST['lyrics']])));
                                    $sid = $songsHandler->insert($object);

                                    if ($GLOBALS['songlistModuleConfig']['tags'] && file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
                                        $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
                                        $tagHandler->updateByItem(trim($data[$_POST['tags']]), $sid, $GLOBALS['songlistModule']->getVar('dirname'), $cid);
                                    }

                                    $extrasHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
                                    $fields        = $extrasHandler->getFields(null);
                                    $criteria      = new \CriteriaCompo(new \Criteria('`sid`', $sid));
                                    if ($extrasHandler->getCount($criteria) > 0) {
                                        $extras = $extrasHandler->getObjects($criteria, false);
                                        $extra  = $extras[0];
                                    } else {
                                        $extra = $extrasHandler->create();
                                    }
                                    $extra->setVar('sid', $sid);
                                    foreach ($fields as $field) {
                                        if (!empty($_POST[$field->getVar('field_name')]) && mb_strlen($_POST[$field->getVar('field_name')]) > 1) {
                                            if (isset($data[$_POST[$field->getVar('field_name')]]) && '' != trim($_POST[$field->getVar('field_name')])) {
                                                $extra->setVar($field->getVar('field_name'), trim($data[$_POST[$field->getVar('field_name')]]));
                                            }
                                        }
                                    }
                                    $extrasHandler->insert($extra, true);
                                    foreach ($artistsHandler->getObjects(new \Criteria('aid', '(' . implode(',', $aids) . ')', 'IN'), true) as $aid => $artist) {
                                        $artist->setVar('sids', array_merge($artist->getVar('sids'), [$sid => $sid]));
                                        $artistsHandler->insert($artist, true);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($xmlarray as $recid => $data) {
                        $cid  = 0;
                        $gid  = 0;
                        $vcid = 0;
                        $aids = [];
                        $abid = [];
                        if (\Xmf\Request::hasVar('limiting', 'POST')) {
                            if (true === \Xmf\Request::getInt('limiting', 0, 'POST')) {
                                ++$record;
                                if ($record > \Xmf\Request::getInt('records', 0, 'POST')) {
                                    $start = time();
                                    while (time() - $start < \Xmf\Request::getInt('wait', 0, 'POST')) {
                                    }
                                    $records = 0;
                                }
                            }
                        }
                        $gid  = 0;
                        $gids = [];
                        if (!empty($_POST['genre']) && mb_strlen($_POST['genre']) > 1) {
                            if (isset($data[$_POST['genre']]) && '' != trim($_POST['genre'])) {
                                foreach (explode(',', trim($data[$_POST['genre']])) as $genre) {
                                    $criteria = new \Criteria('`name`', trim($genre));
                                    if ($genreHandler->getCount($criteria) > 0) {
                                        $objects = $genreHandler->getObjects($criteria, false);
                                        $gid     = $objects[0]->getVar('gid');
                                    } else {
                                        $object = $genreHandler->create();
                                        $object->setVar('name', trim($genre));
                                        $gid = $genreHandler->insert($object);
                                    }
                                    $gids[$gid] = $gid;
                                }
                            }
                        }

                        $vcid = 0;
                        if (!empty($_POST['voice']) && mb_strlen($_POST['voice']) > 1) {
                            if (isset($data[$_POST['voice']]) && '' != trim($_POST['voice'])) {
                                $criteria = new \Criteria('`name`', trim($data[$_POST['voice']]));
                                if ($voiceHandler->getCount($criteria) > 0) {
                                    $objects = $voiceHandler->getObjects($criteria, false);
                                    $vcid    = $objects[0]->getVar('vcid');
                                } else {
                                    $object = $voiceHandler->create();
                                    $object->setVar('name', trim($data[$_POST['voice']]));
                                    $vcid = $voiceHandler->insert($object);
                                }
                            }
                        }
                        $cid = 0;
                        if (!empty($_POST['category']) && mb_strlen($_POST['category']) > 1) {
                            if (isset($data[$_POST['category']]) && '' != trim($_POST['category'])) {
                                $criteria = new \Criteria('`name`', trim($data[$_POST['category']]));
                                if ($categoryHandler->getCount($criteria) > 0) {
                                    $objects = $categoryHandler->getObjects($criteria, false);
                                    $cid     = $objects[0]->getVar('cid');
                                } else {
                                    $object = $categoryHandler->create();
                                    $object->setVar('name', trim($data[$_POST['category']]));
                                    $cid = $categoryHandler->insert($object);
                                }
                            }
                        }
                        $aids = [];
                        if (!empty($_POST['artist']) && mb_strlen($_POST['artist']) > 1) {
                            if (isset($data[$_POST['artist']]) && '' != $_POST['artist']) {
                                foreach (explode(',', trim($data[$_POST['artist']])) as $artist) {
                                    $criteria = new \Criteria('`name`', trim($artist));
                                    if ($artistsHandler->getCount($criteria) > 0) {
                                        $objects                          = $artistsHandler->getObjects($criteria, false);
                                        $aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
                                    } else {
                                        $object = $artistsHandler->create(); //Added PL
                                        $object->setVar('cid', $cid);
                                        $object->setVar('name', trim($data[$_POST['artist']]));
                                        $aid        = $artistsHandler->insert($object);
                                        $aids[$aid] = $aid;
                                    }
                                }
                            }
                        }
                        $abid = 0;
                        if (!empty($_POST['album']) && mb_strlen($_POST['album']) > 1) {
                            if (isset($data[$_POST['album']]) && '' != trim($_POST['album'])) {
                                $criteria = new \Criteria('`title`', trim($data[$_POST['album']]));
                                if ($albumsHandler->getCount($criteria) > 0) {
                                    $objects = $albumsHandler->getObjects($criteria, false);
                                    $abid    = $objects[0]->getVar('abid');
                                } else {
                                    $object = $albumsHandler->create();
                                    $object->setVar('cid', $cid);
                                    $object->setVar('aids', $aids);
                                    $object->setVar('title', trim($data[$_POST['album']]));
                                    $abid = $albumsHandler->insert($object);
                                }
                            }
                        }
                        $sid = 0;
                        if ((!empty($_POST['songid']) && mb_strlen($_POST['songid']) > 1) || (!empty($_POST['title']) && mb_strlen($_POST['title']) > 1)) {
                            if ((isset($data[$_POST['songid']]) && '' != $_POST['songid']) || (isset($data[$_POST['title']]) && '' != $_POST['title'])) {
                                $criteria = new \CriteriaCompo();
                                if ('' != trim($data[$_POST['songid']])) {
                                    $criteria->add(new \Criteria('`songid`', trim($data[$_POST['songid']])));
                                }
                                if ('' != trim($data[$_POST['title']])) {
                                    $criteria->add(new \Criteria('`title`', trim($data[$_POST['title']])));
                                }
                                if ($songsHandler->getCount($criteria) > 0) {
                                    $objects = $songsHandler->getObjects($criteria, false);
                                    $object  = $objects[0];
                                } else {
                                    $object = $songsHandler->create();
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
                                $object->setVar('lyrics', str_replace("\n", "<br>\n", trim($data[$_POST['lyrics']])));
                                $sid = $songsHandler->insert($object);

                                if ($GLOBALS['songlistModuleConfig']['tags'] && file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
                                    $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
                                    $tagHandler->updateByItem(trim($data[$_POST['tags']]), $sid, $GLOBALS['songlistModule']->getVar('dirname'), $cid);
                                }

                                $extrasHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
                                $fields        = $extrasHandler->getFields(null);
                                $criteria      = new \CriteriaCompo(new \Criteria('`sid`', $sid));
                                if ($extrasHandler->getCount($criteria) > 0) {
                                    $extras = $extrasHandler->getObjects($criteria, false);
                                    $extra  = $extras[0];
                                } else {
                                    $extra = $extrasHandler->create();
                                }
                                $extra->setVar('sid', $sid);
                                foreach ($fields as $field) {
                                    if (!empty($_POST[$field->getVar('field_name')]) && mb_strlen($_POST[$field->getVar('field_name')]) > 1) {
                                        if (isset($data[$_POST[$field->getVar('field_name')]]) && '' != trim($_POST[$field->getVar('field_name')])) {
                                            $extra->setVar($field->getVar('field_name'), trim($data[$_POST[$field->getVar('field_name')]]));
                                        }
                                    }
                                }
                                foreach ($artistsHandler->getObjects(new \Criteria('aid', '(' . implode(',', $aids) . ')', 'IN'), true) as $aid => $artist) {
                                    $artist->setVar('sids', array_merge($artist->getVar('sids'), [$sid => $sid]));
                                    $artistsHandler->insert($artist, true);
                                }
                            }
                        }
                    }
                }
                unlink($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'] . $_SESSION['xmlfile']));
                unset($_SESSION['xmlfile']);
                redirect_header($_SERVER['SCRIPT_NAME'] . '?op=import&fct=actiona', 10, _AM_SONGLIST_XMLFILE_COMPLETE);
                break;
        }
        break;
}

xoops_cp_footer();
