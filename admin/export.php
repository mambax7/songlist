<?php

include 'header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'dashboard';
$fct    = \Xmf\Request::getString('fct', '', 'REQUEST');
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = !empty($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
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

                if (\Xmf\Request::hasVar('xmlfile', 'FILES') && !empty($_FILES['xmlfile']['title'])) {
                    if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
                        foreach (explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders) {
                            foreach (explode('/', $folders) as $folder) {
                                $path .= DS . $folder;
                                mkdir($path, 0777);
                            }
                        }
                    }

                    require_once $GLOBALS['xoops']->path('modules/songlist/include/uploader.php');
                    $albums   = $albumsHandler->get($id);
                    $uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), ['application/xml', 'application/xml-dtd', 'application/xml-external-parsed-entity', 'text/xml xml xsl', 'text/xml-external-parsed-entity'], 1024 * 1024 * 32, 0, 0,
                                                          ['xml']);
                    $uploader->setPrefix(mb_substr(md5(microtime(true)), mt_rand(0, 20), 13));

                    if ($uploader->fetchMedia('xmlfile')) {
                        if (!$uploader->upload()) {
                            echo $uploader->getErrors();
                            songlist_footer_adminMenu();
                            xoops_cp_footer();
                            exit(0);
                        }
                        $_SESSION['xmlfile'] = $uploader->getSavedFileName();
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?file=' . $uploader->getSavedFileName() . '&op=import&fct=actionb', 10, _AM_SONGLIST_XMLFILE_UPLOADED);
                    } else {
                        echo $uploader->getErrors();
                        songlist_footer_adminMenu();
                        xoops_cp_footer();
                        exit(0);
                    }
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
                set_time_limit(3600);

                $xmlarray = songlist_xml2array(file_get_contents($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'] . $_SESSION['xmlfile'])), false, 'tag');

                if (mb_strlen($_POST['collection']) > 0) {
                    foreach ($xmlarray[$_POST['collection']] as $id => $record) {
                        foreach ($record as $recid => $data) {
                            $gid = 0;
                            if (mb_strlen($_POST['genre']) > 0 && !empty($data[$_POST['genre']])) {
                                $criteria = new \Criteria('`name`', $data[$_POST['genre']]);
                                if ($genreHandler->getCount($criteria) > 0) {
                                    $objects = $genreHandler->getObjects($criteria, false);
                                    $gid     = $objects[0]->getVar('gid');
                                } else {
                                    $object = $genreHandler->create();
                                    $object->setVar('name', $data[$_POST['genre']]);
                                    $gid = $genreHandler->insert($object);
                                }
                            }

                            $vid = 0;
                            if (mb_strlen($_POST['voice']) > 0 && !empty($data[$_POST['voice']])) {
                                $criteria = new \Criteria('`name`', $data[$_POST['voice']]);
                                if ($voiceHandler->getCount($criteria) > 0) {
                                    $objects = $voiceHandler->getObjects($criteria, false);
                                    $gid     = $objects[0]->getVar('vid');
                                } else {
                                    $object = $voiceHandler->create();
                                    $object->setVar('name', $data[$_POST['voice']]);
                                    $gid = $voiceHandler->insert($object);
                                }
                            }

                            $cid = 0;
                            if (mb_strlen($_POST['category']) > 0 && !empty($data[$_POST['category']])) {
                                $criteria = new \Criteria('`name`', $data[$_POST['category']]);
                                if ($categoryHandler->getCount($criteria) > 0) {
                                    $objects = $categoryHandler->getObjects($criteria, false);
                                    $cid     = $objects[0]->getVar('cid');
                                } else {
                                    $object = $categoryHandler->create();
                                    $object->setVar('name', $data[$_POST['category']]);
                                    $cid = $categoryHandler->insert($object);
                                }
                            }
                            $aids = [];
                            if (mb_strlen($_POST['artist']) > 0 && !empty($data[$_POST['artist']])) {
                                foreach (explode(',', $data[$_POST['artist']]) as $artist) {
                                    $criteria = new \Criteria('`name`', $artist);
                                    if ($artistsHandler->getCount($criteria) > 0) {
                                        $objects                          = $artistsHandler->getObjects($criteria, false);
                                        $aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
                                    } else {
                                        $object = $artistsHandler->create();
                                        $object->setVar('cid', $cid);
                                        switch ($data[$_POST['singer']]) {
                                            case $_POST['duet']:
                                                $object->setVar('singer', '_ENUM_SONGLIST_DUET');
                                                break;
                                            case $_POST['solo']:
                                                $object->setVar('singer', '_ENUM_SONGLIST_SOLO');
                                                break;
                                        }
                                        $object->setVar('name', $data[$_POST['artist']]);
                                        $aid        = $artistsHandler->insert($object);
                                        $aids[$aid] = $aid;
                                    }
                                }
                            }
                            $abid = 0;
                            if (mb_strlen($_POST['album']) > 0 && !empty($data[$_POST['album']])) {
                                $criteria = new \Criteria('`name`', $data[$_POST['album']]);
                                if ($albumsHandler->getCount($criteria) > 0) {
                                    $objects = $albumsHandler->getObjects($criteria, false);
                                    $abid    = $objects[0]->getVar('aid');
                                } else {
                                    $object = $albumsHandler->create();
                                    $object->setVar('cid', $cid);
                                    $object->setVar('aids', $aids);
                                    $object->setVar('name', $data[$_POST['album']]);
                                    $abid = $albumsHandler->insert($object);
                                }
                            }
                            $sid = 0;
                            if (mb_strlen($_POST['songid']) > 0 && !empty($data[$_POST['songid']])) {
                                $criteria = new \Criteria('`songid`', $data[$_POST['songid']]);
                                if ($songsHandler->getCount($criteria) > 0) {
                                    $objects = $songsHandler->getObjects($criteria, false);
                                    $object  = $objects[0]->getVar('sid');
                                } else {
                                    $object = $songsHandler->create();
                                }
                                if ($object->getVar('cid') > 0 && $cid > 0) {
                                    $object->setVar('cid', $cid);
                                } else {
                                    $object->setVar('cid', $cid);
                                }
                                if ($object->getVar('gid') > 0 && $gid > 0) {
                                    $object->setVar('gid', $gid);
                                } else {
                                    $object->setVar('gid', $gid);
                                }
                                if (count($object->getVar('aids')) > 0 && count($aids) > 0) {
                                    $object->setVar('aids', $aids);
                                } else {
                                    $object->setVar('aids', $aids);
                                }
                                if ($object->getVar('abid') > 0 && $abid > 0) {
                                    $object->setVar('abid', $abid);
                                } else {
                                    $object->setVar('abid', $abid);
                                }
                                $object->setVar('songid', $data[$_POST['songid']]);
                                $object->setVar('title', $data[$_POST['title']]);
                                $object->setVar('lyrics', str_replace("\n", "<br>\n", $data[$_POST['lyrics']]));
                                $sid = $songsHandler->insert($object);
                            }
                        }
                    }
                } else {
                    foreach ($xmlarray as $recid => $data) {
                        $gid = 0;
                        if (mb_strlen($_POST['genre']) > 0 && !empty($data[$_POST['genre']])) {
                            $criteria = new \Criteria('`name`', $data[$_POST['genre']]);
                            if ($genreHandler->getCount($criteria) > 0) {
                                $objects = $genreHandler->getObjects($criteria, false);
                                $gid     = $objects[0]->getVar('gid');
                            } else {
                                $object = $genreHandler->create();
                                $object->setVar('name', $data[$_POST['genre']]);
                                $gid = $genreHandler->insert($object);
                            }
                        }
                        $vid = 0;
                        if (mb_strlen($_POST['voice']) > 0 && !empty($data[$_POST['voice']])) {
                            $criteria = new \Criteria('`name`', $data[$_POST['voice']]);
                            if ($voiceHandler->getCount($criteria) > 0) {
                                $objects = $voiceHandler->getObjects($criteria, false);
                                $gid     = $objects[0]->getVar('vid');
                            } else {
                                $object = $voiceHandler->create();
                                $object->setVar('name', $data[$_POST['voice']]);
                                $gid = $voiceHandler->insert($object);
                            }
                        }
                        $cid = 0;
                        if (mb_strlen($_POST['category']) > 0 && !empty($data[$_POST['category']])) {
                            $criteria = new \Criteria('`name`', $data[$_POST['category']]);
                            if ($categoryHandler->getCount($criteria) > 0) {
                                $objects = $categoryHandler->getObjects($criteria, false);
                                $cid     = $objects[0]->getVar('cid');
                            } else {
                                $object = $categoryHandler->create();
                                $object->setVar('name', $data[$_POST['category']]);
                                $cid = $categoryHandler->insert($object);
                            }
                        }
                        $aids = [];
                        if (mb_strlen($_POST['artist']) > 0 && !empty($data[$_POST['artist']])) {
                            foreach (explode(',', $data[$_POST['artist']]) as $artist) {
                                $criteria = new \Criteria('`name`', $artist);
                                if ($artistsHandler->getCount($criteria) > 0) {
                                    $objects                          = $artistsHandler->getObjects($criteria, false);
                                    $aids[$objects[0]->getVar('aid')] = $objects[0]->getVar('aid');
                                } else {
                                    $object = $artistsHandler->create();
                                    switch ($data[$_POST['singer']]) {
                                        case $_POST['duet']:
                                            $object->setVar('singer', '_ENUM_SONGLIST_DUET');
                                            break;
                                        case $_POST['solo']:
                                            $object->setVar('singer', '_ENUM_SONGLIST_SOLO');
                                            break;
                                    }
                                    $object->setVar('cid', $cid);
                                    $object->setVar('name', $data[$_POST['artist']]);
                                    $aid        = $artistsHandler->insert($object);
                                    $aids[$aid] = $aid;
                                }
                            }
                        }
                        $abid = 0;
                        if (mb_strlen($_POST['album']) > 0 && !empty($data[$_POST['album']])) {
                            $criteria = new \Criteria('`name`', $data[$_POST['album']]);
                            if ($albumsHandler->getCount($criteria) > 0) {
                                $objects = $albumsHandler->getObjects($criteria, false);
                                $abid    = $objects[0]->getVar('aid');
                            } else {
                                $object = $albumsHandler->create();
                                $object->setVar('cid', $cid);
                                $object->setVar('aids', $aids);
                                $object->setVar('name', $data[$_POST['album']]);
                                $abid = $albumsHandler->insert($object);
                            }
                        }
                        $sid = 0;
                        if (mb_strlen($_POST['songid']) > 0 && !empty($data[$_POST['songid']])) {
                            $criteria = new \Criteria('`songid`', $data[$_POST['songid']]);
                            if ($songsHandler->getCount($criteria) > 0) {
                                $objects = $songsHandler->getObjects($criteria, false);
                                $object  = $objects[0]->getVar('sid');
                            } else {
                                $object = $songsHandler->create();
                            }
                            if ($object->getVar('cid') > 0 && $cid > 0) {
                                $object->setVar('cid', $cid);
                            } else {
                                $object->setVar('cid', $cid);
                            }
                            if ($object->getVar('gid') > 0 && $gid > 0) {
                                $object->setVar('gid', $gid);
                            } else {
                                $object->setVar('gid', $gid);
                            }
                            if (count($object->getVar('aids')) > 0 && count($aids) > 0) {
                                $object->setVar('aids', $aids);
                            } else {
                                $object->setVar('aids', $aids);
                            }
                            if ($object->getVar('abid') > 0 && $abid > 0) {
                                $object->setVar('abid', $abid);
                            } else {
                                $object->setVar('abid', $abid);
                            }
                            $object->setVar('songid', $data[$_POST['songid']]);
                            $object->setVar('title', $data[$_POST['title']]);
                            $object->setVar('lyrics', str_replace("\n", "<br>\n", $data[$_POST['lyrics']]));
                            $sid = $songsHandler->insert($object);
                        }
                    }
                }
                unlink($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'] . $_SESSION['xmlfile']));
                unset($_SESSION['xmlfile']);
                redirect_header($_SERVER['SCRIPT_NAME'] . '&op=import&fct=actiona', 10, _AM_SONGLIST_XMLFILE_COMPLETE);
                break;
        }
        break;
}

xoops_cp_footer();
