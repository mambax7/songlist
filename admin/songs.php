<?php

include 'header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'songs';
$fct    = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'list';
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = !empty($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
    default:
    case 'songs':
        switch ($fct) {
            default:
            case 'list':
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');

                $criteria        = $songsHandler->getFilterCriteria($GLOBALS['filter']);
                $ttl             = $songsHandler->getCount($criteria);
                $GLOBALS['sort'] = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';

                $pagenav = new \XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit=' . $GLOBALS['limit'] . '&sort=' . $GLOBALS['sort'] . '&order=' . $GLOBALS['order'] . '&op=' . $GLOBALS['op'] . '&fct=' . $GLOBALS['fct'] . '&filter=' . $GLOBALS['filter']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ($songsHandler->filterFields() as $id => $key) {
                    $GLOBALS['xoopsTpl']->assign(mb_strtolower(str_replace('-', '_', $key) . '_th'), '<a href="'
                                                                                                     . $_SERVER['SCRIPT_NAME']
                                                                                                     . '?start='
                                                                                                     . $GLOBALS['start']
                                                                                                     . '&limit='
                                                                                                     . $GLOBALS['limit']
                                                                                                     . '&sort='
                                                                                                     . $key
                                                                                                     . '&order='
                                                                                                     . (($key == $GLOBALS['sort']) ? ('DESC' === $GLOBALS['order'] ? 'ASC' : 'DESC') : $GLOBALS['order'])
                                                                                                     . '&op='
                                                                                                     . $GLOBALS['op']
                                                                                                     . '&filter='
                                                                                                     . $GLOBALS['filter']
                                                                                                     . '">'
                                                                                                     . (defined('_AM_SONGLIST_TH_' . mb_strtoupper(str_replace('-', '_', $key))) ? constant('_AM_SONGLIST_TH_' . mb_strtoupper(str_replace('-', '_', $key))) : '_AM_SONGLIST_TH_'
                                                                                                                                                                                                                                                               . mb_strtoupper(str_replace('-', '_', $key)))
                                                                                                     . '</a>');
                    $GLOBALS['xoopsTpl']->assign('filter_' . mb_strtolower(str_replace('-', '_', $key)) . '_th', $songsHandler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
                }

                $GLOBALS['xoopsTpl']->assign('limit', $GLOBALS['limit']);
                $GLOBALS['xoopsTpl']->assign('start', $GLOBALS['start']);
                $GLOBALS['xoopsTpl']->assign('order', $GLOBALS['order']);
                $GLOBALS['xoopsTpl']->assign('sort', $GLOBALS['sort']);
                $GLOBALS['xoopsTpl']->assign('filter', $GLOBALS['filter']);
                $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);

                $criteria->setStart($GLOBALS['start']);
                $criteria->setLimit($GLOBALS['limit']);
                $criteria->setSort('`' . $GLOBALS['sort'] . '`');
                $criteria->setOrder($GLOBALS['order']);

                $songss = $songsHandler->getObjects($criteria, true);
                foreach ($songss as $cid => $songs) {
                    if (is_object($songs)) {
                        $GLOBALS['xoopsTpl']->append('songs', $songs->toArray());
                    }
                }
                $GLOBALS['xoopsTpl']->assign('form', songlist_songs_get_form(false));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_songs_list.tpl');
                break;
            case 'new':
            case 'edit':

                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                require_once $GLOBALS['xoops']->path('/class/pagenav.php');

                $songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                if (\Xmf\Request::hasVar('id', 'REQUEST')) {
                    $songs = $songsHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                } else {
                    $songs = $songsHandler->create();
                }

                $GLOBALS['xoopsTpl']->assign('form', $songs->getForm());
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_songs_edit.tpl');
                break;
            case 'save':

                $songsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                $extrasHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
                $id            = 0;
                $id            = \Xmf\Request::getInt('id', 0, 'REQUEST');
                if ($id) {
                    $songs = $songsHandler->get($id);
                } else {
                    $songs = $songsHandler->create();
                }
                $songs->setVars($_POST[$id]);

                if (\Xmf\Request::hasVar('mp3' . $id, 'FILES') && !empty($_FILES['mp3' . $id]['title'])) {
                    if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
                        foreach (explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders) {
                            foreach (explode('/', $folders) as $folder) {
                                $path .= DS . $folder;
                                mkdir($path, 0777);
                            }
                        }
                    }

                    require_once $GLOBALS['xoops']->path('modules/songlist/include/uploader.php');
                    $uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), explode('|', $GLOBALS['songlistModuleConfig']['mp3_mimetype']), $GLOBALS['songlistModuleConfig']['mp3_filesize'], 0, 0,
                                                          explode('|', $GLOBALS['songlistModuleConfig']['mp3_extensions']));
                    $uploader->setPrefix(mb_substr(md5(microtime(true)), mt_rand(0, 20), 13));

                    if ($uploader->fetchMedia('mp3' . $id)) {
                        if (!$uploader->upload()) {
                            $adminObject = \Xmf\Module\Admin::getInstance();
                            $adminObject->displayNavigation(basename(__FILE__));
                            echo $uploader->getErrors();
                            xoops_cp_footer();
                            exit(0);
                        }
                        if (mb_strlen($songs->getVar('mp3'))) {
                            unlink($GLOBALS['xoops']->path($songs->getVar('path')) . basename($songs->getVar('mp3')));
                        }

                        $songs->setVar('mp3', XOOPS_URL . '/' . str_replace(DS, '/', $GLOBALS['songlistModuleConfig']['upload_areas']) . $uploader->getSavedFileName());
                    } else {
                        $adminObject = \Xmf\Module\Admin::getInstance();
                        $adminObject->displayNavigation(basename(__FILE__));
                        echo $uploader->getErrors();
                        xoops_cp_footer();
                        exit(0);
                    }
                }
                if (!$id = $songsHandler->insert($songs)) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_FAILEDTOSAVE);
                    exit(0);
                }
                $extra = $extrasHandler->get($id);
                $extra->setVars($_POST[$id]);
                $extra->setVar('sid', $id);
                $extrasHandler->insert($extra);

                if ($GLOBALS['songlistModuleConfig']['tags'] && file_exists(XOOPS_ROOT_PATH . '/modules/tag/class/tag.php')) {
                    $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
                    $tagHandler->updateByItem($_POST['tags'], $id, $GLOBALS['songlistModule']->getVar('dirname'), $songs->getVar('cid'));
                }

                if ('new' === $_REQUEST['state'][$_REQUEST['id']]) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=edit&id=' . $_REQUEST['id'] . '&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                    _AM_SONGLIST_MSG_SONGS_SAVEDOKEY);
                } else {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_SAVEDOKEY);
                }
                exit(0);

                break;
            case 'savelist':
                print_r($_FILES);
                exit;
                $songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                foreach ($_REQUEST['id'] as $id) {
                    $songs = $songsHandler->get($id);
                    $songs->setVars($_POST[$id]);
                    if (\Xmf\Request::hasVar('mp3' . $id, 'FILES') && !empty($_FILES['mp3' . $id]['title'])) {
                        if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
                            foreach (explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders) {
                                foreach (explode('/', $folders) as $folder) {
                                    $path .= DS . $folder;
                                    mkdir($path, 0777);
                                }
                            }
                        }

                        require_once $GLOBALS['xoops']->path('modules/songlist/include/uploader.php');
                        $uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), explode('|', $GLOBALS['songlistModuleConfig']['mp3_mimetype']), $GLOBALS['songlistModuleConfig']['mp3_filesize'], 0, 0,
                                                              explode('|', $GLOBALS['songlistModuleConfig']['mp3_extensions']));
                        $uploader->setPrefix(mb_substr(md5(microtime(true)), mt_rand(0, 20), 13));

                        if ($uploader->fetchMedia('mp3' . $id)) {
                            if (!$uploader->upload()) {
                                $adminObject = \Xmf\Module\Admin::getInstance();
                                $adminObject->displayNavigation(basename(__FILE__));
                                echo $uploader->getErrors();
                                xoops_cp_footer();
                                exit(0);
                            }
                            if (mb_strlen($songs->getVar('mp3'))) {
                                unlink($GLOBALS['xoops']->path($songs->getVar('path')) . basename($songs->getVar('mp3')));
                            }

                            $songs->setVar('mp3', XOOPS_URL . '/' . str_replace(DS, '/', $GLOBALS['songlistModuleConfig']['upload_areas']) . $uploader->getSavedFileName());
                        } else {
                            $adminObject = \Xmf\Module\Admin::getInstance();
                            $adminObject->displayNavigation(basename(__FILE__));
                            echo $uploader->getErrors();
                            xoops_cp_footer();
                            exit(0);
                        }
                    }
                    if (!$songsHandler->insert($songs)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_FAILEDTOSAVE);
                        exit(0);
                    }
                }
                redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_SAVEDOKEY);
                exit(0);
                break;
            case 'delete':

                $songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                $id           = 0;
                if (\Xmf\Request::hasVar('id', 'POST') && $id = \Xmf\Request::getInt('id', 0, 'POST')) {
                    $songs = $songsHandler->get($id);
                    if (!$songsHandler->delete($songs)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_FAILEDTODELETE);
                        exit(0);
                    }
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_SONGS_DELETED);
                    exit(0);
                }
                $songs = $songsHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                xoops_confirm(['id' => $_REQUEST['id'], 'op' => $_REQUEST['op'], 'fct' => $_REQUEST['fct'], 'limit' => $_REQUEST['limit'], 'start' => $_REQUEST['start'], 'order' => $_REQUEST['order'], 'sort' => $_REQUEST['sort'], 'filter' => $_REQUEST['filter']], $_SERVER['SCRIPT_NAME'],
                              sprintf(_AM_SONGLIST_MSG_SONGS_DELETE, $songs->getVar('name')));

                break;
        }
        break;
}

xoops_cp_footer();
