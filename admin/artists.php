<?php

include __DIR__ . '/header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'artists';
$fct    = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'list';
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = !empty($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
    default:
    case 'artists':
        switch ($fct) {
            default:
            case 'list':
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');

                $criteria        = $artistsHandler->getFilterCriteria($GLOBALS['filter']);
                $ttl             = $artistsHandler->getCount($criteria);
                $GLOBALS['sort'] = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';

                $pagenav = new \XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit=' . $GLOBALS['limit'] . '&sort=' . $GLOBALS['sort'] . '&order=' . $GLOBALS['order'] . '&op=' . $GLOBALS['op'] . '&fct=' . $GLOBALS['fct'] . '&filter=' . $GLOBALS['filter']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ($artistsHandler->filterFields() as $id => $key) {
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
                    $GLOBALS['xoopsTpl']->assign('filter_' . mb_strtolower(str_replace('-', '_', $key)) . '_th', $artistsHandler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
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

                $artists = $artistsHandler->getObjects($criteria, true);
                foreach ($artists as $cid => $artist) {
                    if (is_object($artist)) {
                        $GLOBALS['xoopsTpl']->append('artists', $artist->toArray());
                    }
                }
                $GLOBALS['xoopsTpl']->assign('form', songlist_artists_get_form(false));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_artists_list.tpl');
                break;
            case 'new':
            case 'edit':

                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                if (\Xmf\Request::hasVar('id', 'REQUEST')) {
                    $artists = $artistsHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                } else {
                    $artists = $artistsHandler->create();
                }

                $GLOBALS['xoopsTpl']->assign('form', $artists->getForm());
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_artists_edit.tpl');
                break;
            case 'save':

                $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                $id             = 0;
                $id             = \Xmf\Request::getInt('id', 0, 'REQUEST');
                if ($id) {
                    $artists = $artistsHandler->get($id);
                } else {
                    $artists = $artistsHandler->create();
                }
                $artists->setVars($_POST[$id]);

                if (!$id = $artistsHandler->insert($artists)) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ARTISTS_FAILEDTOSAVE);
                    exit(0);
                }
                if ('new' === $_REQUEST['state'][$_REQUEST['id']]) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=edit&id=' . $_REQUEST['id'] . '&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                    _AM_SONGLIST_MSG_ARTISTS_SAVEDOKEY);
                } else {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ARTISTS_SAVEDOKEY);
                }
                exit(0);

                break;
            case 'savelist':

                $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                foreach ($_REQUEST['id'] as $id) {
                    $artists = $artistsHandler->get($id);
                    $artists->setVars($_POST[$id]);
                    if (!$artistsHandler->insert($artists)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ARTISTS_FAILEDTOSAVE);
                        exit(0);
                    }
                }
                redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ARTISTS_SAVEDOKEY);
                exit(0);
                break;
            case 'delete':

                $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                $id             = 0;
                if (\Xmf\Request::hasVar('id', 'POST') && $id = \Xmf\Request::getInt('id', 0, 'POST')) {
                    $artists = $artistsHandler->get($id);
                    if (!$artistsHandler->delete($artists)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                        _AM_SONGLIST_MSG_ARTISTS_FAILEDTODELETE);
                        exit(0);
                    }
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_ARTISTS_DELETED);
                    exit(0);
                }
                $artists = $artistsHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                xoops_confirm(['id' => $_REQUEST['id'], 'op' => $_REQUEST['op'], 'fct' => $_REQUEST['fct'], 'limit' => $_REQUEST['limit'], 'start' => $_REQUEST['start'], 'order' => $_REQUEST['order'], 'sort' => $_REQUEST['sort'], 'filter' => $_REQUEST['filter']], $_SERVER['SCRIPT_NAME'],
                              sprintf(_AM_SONGLIST_MSG_ARTISTS_DELETE, $artists->getVar('name')));

                break;
        }
        break;
}

xoops_cp_footer();
