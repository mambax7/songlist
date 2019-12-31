<?php

include 'header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'votes';
$fct    = \Xmf\Request::getString('fct', '', 'REQUEST');
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = !empty($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
    default:
    case 'votes':
        switch ($fct) {
            default:
            case 'list':
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $votesHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');

                $criteria        = $votesHandler->getFilterCriteria($GLOBALS['filter']);
                $ttl             = $votesHandler->getCount($criteria);
                $GLOBALS['sort'] = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';

                $pagenav = new \XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit=' . $GLOBALS['limit'] . '&sort=' . $GLOBALS['sort'] . '&order=' . $GLOBALS['order'] . '&op=' . $GLOBALS['op'] . '&fct=' . $GLOBALS['fct'] . '&filter=' . $GLOBALS['filter']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ($votesHandler->filterFields() as $id => $key) {
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
                    $GLOBALS['xoopsTpl']->assign('filter_' . mb_strtolower(str_replace('-', '_', $key)) . '_th', $votesHandler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
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

                $votess = $votesHandler->getObjects($criteria, true);
                foreach ($votess as $cid => $votes) {
                    if (is_object($votes)) {
                        $GLOBALS['xoopsTpl']->append('categories', $votes->toArray());
                    }
                }
                //$GLOBALS['xoopsTpl']->assign('form', songlist_votes_get_form(false));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_votes_list.tpl');
                break;
            case 'new':
            case 'edit':

                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $votesHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');
                if (\Xmf\Request::hasVar('id', 'REQUEST')) {
                    $votes = $votesHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                } else {
                    $votes = $votesHandler->create();
                }

                $GLOBALS['xoopsTpl']->assign('form', $votes->getForm());
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_votes_edit.tpl');
                break;
            case 'save':

                $votesHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');
                $id           = 0;
                $id           = \Xmf\Request::getInt('id', 0, 'REQUEST');
                if ($id) {
                    $votes = $votesHandler->get($id);
                } else {
                    $votes = $votesHandler->create();
                }
                $votes->setVars($_POST[$id]);

                if (!$id = $votesHandler->insert($votes)) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_VOTES_FAILEDTOSAVE);
                    exit(0);
                }
                if (\Xmf\Request::hasVar('image', 'FILES') && !empty($_FILES['image']['name'])) {
                    if (!is_dir($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']))) {
                        foreach (explode('\\', $GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas'])) as $folders) {
                            foreach (explode('/', $folders) as $folder) {
                                $path .= DS . $folder;
                                mkdir($path, 0777);
                            }
                        }
                    }

                    require_once $GLOBALS['xoops']->path('modules/songlist/include/uploader.php');
                    $votes    = $votesHandler->get($id);
                    $uploader = new SonglistMediaUploader($GLOBALS['xoops']->path($GLOBALS['songlistModuleConfig']['upload_areas']), explode('|', $GLOBALS['songlistModuleConfig']['allowed_mimetype']), $GLOBALS['songlistModuleConfig']['filesize_upload'], 0, 0,
                                                          explode('|', $GLOBALS['songlistModuleConfig']['allowed_extensions']));
                    $uploader->setPrefix(mb_substr(md5(microtime(true)), mt_rand(0, 20), 13));

                    if ($uploader->fetchMedia('image')) {
                        if (!$uploader->upload()) {
                            songlist_adminMenu(1);
                            echo $uploader->getErrors();
                            songlist_footer_adminMenu();
                            xoops_cp_footer();
                            exit(0);
                        }
                        if (mb_strlen($votes->getVar('image'))) {
                            unlink($GLOBALS['xoops']->path($votes->getVar('path')) . $votes->getVar('image'));
                        }

                        $votes->setVar('path', $GLOBALS['songlistModuleConfig']['upload_areas']);
                        $votes->setVar('image', $uploader->getSavedFileName());
                        @$votesHandler->insert($votes);
                    } else {
                        songlist_adminMenu(1);
                        echo $uploader->getErrors();
                        songlist_footer_adminMenu();
                        xoops_cp_footer();
                        exit(0);
                    }
                }

                if ('new' === $_REQUEST['state'][$_REQUEST['id']]) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=edit&id=' . $_REQUEST['id'] . '&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                    _AM_SONGLIST_MSG_VOTES_SAVEDOKEY);
                } else {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_VOTES_SAVEDOKEY);
                }
                exit(0);

                break;
            case 'savelist':

                $votesHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');
                foreach ($_REQUEST['id'] as $id) {
                    $votes = $votesHandler->get($id);
                    $votes->setVars($_POST[$id]);
                    if (!$votesHandler->insert($votes)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_VOTES_FAILEDTOSAVE);
                        exit(0);
                    }
                }
                redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_VOTES_SAVEDOKEY);
                exit(0);
                break;
            case 'delete':

                $votesHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');
                $id           = 0;
                if (\Xmf\Request::hasVar('id', 'POST') && $id = \Xmf\Request::getInt('id', 0, 'POST')) {
                    $votes = $votesHandler->get($id);
                    if (!$votesHandler->delete($votes)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_VOTES_FAILEDTODELETE);
                        exit(0);
                    }
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_VOTES_DELETED);
                    exit(0);
                }
                $votes = $votesHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                xoops_confirm(['id' => $_REQUEST['id'], 'op' => $_REQUEST['op'], 'fct' => $_REQUEST['fct'], 'limit' => $_REQUEST['limit'], 'start' => $_REQUEST['start'], 'order' => $_REQUEST['order'], 'sort' => $_REQUEST['sort'], 'filter' => $_REQUEST['filter']], $_SERVER['SCRIPT_NAME'],
                              sprintf(_AM_SONGLIST_MSG_VOTES_DELETE, $votes->getVar('name')));

                break;
        }
        break;
}

xoops_cp_footer();
