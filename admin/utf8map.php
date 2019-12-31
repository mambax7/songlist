<?php

include 'header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'utf8map';
$fct    = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'list';
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = !empty($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
    default:
    case 'utf8map':
        switch ($fct) {
            default:
            case 'list':
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $utf8mapHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Utf8map');

                $criteria        = $utf8mapHandler->getFilterCriteria($GLOBALS['filter']);
                $ttl             = $utf8mapHandler->getCount($criteria);
                $GLOBALS['sort'] = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';

                $pagenav = new \XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit=' . $GLOBALS['limit'] . '&sort=' . $GLOBALS['sort'] . '&order=' . $GLOBALS['order'] . '&op=' . $GLOBALS['op'] . '&fct=' . $GLOBALS['fct'] . '&filter=' . $GLOBALS['filter']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ($utf8mapHandler->filterFields() as $id => $key) {
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
                    $GLOBALS['xoopsTpl']->assign('filter_' . mb_strtolower(str_replace('-', '_', $key)) . '_th', $utf8mapHandler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
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

                $utf8maps = $utf8mapHandler->getObjects($criteria, true);
                foreach ($utf8maps as $cid => $utf8map) {
                    if (is_object($utf8map)) {
                        $GLOBALS['xoopsTpl']->append('utf8map', $utf8map->toArray());
                    }
                }
                $GLOBALS['xoopsTpl']->assign('form', songlist_utf8map_get_form(false));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_utf8map_list.tpl');
                break;
            case 'new':
            case 'edit':

                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $utf8mapHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Utf8map');
                if (\Xmf\Request::hasVar('id', 'REQUEST')) {
                    $utf8map = $utf8mapHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                } else {
                    $utf8map = $utf8mapHandler->create();
                }

                $GLOBALS['xoopsTpl']->assign('form', $utf8map->getForm());
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_utf8map_edit.tpl');
                break;
            case 'save':

                $utf8mapHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Utf8map');
                $id             = 0;
                $id             = \Xmf\Request::getInt('id', 0, 'REQUEST');
                if ($id) {
                    $utf8map = $utf8mapHandler->get($id);
                } else {
                    $utf8map = $utf8mapHandler->create();
                }
                $utf8map->setVars($_POST[$id]);

                if (!$id = $utf8mapHandler->insert($utf8map)) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_UTF8MAP_FAILEDTOSAVE);
                    exit(0);
                }
                if ('new' === $_REQUEST['state'][$_REQUEST['id']]) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=edit&id=' . $_REQUEST['id'] . '&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                    _AM_SONGLIST_MSG_UTF8MAP_SAVEDOKEY);
                } else {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_UTF8MAP_SAVEDOKEY);
                }
                exit(0);

                break;
            case 'savelist':

                $utf8mapHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Utf8map');
                foreach ($_REQUEST['id'] as $id) {
                    $utf8map = $utf8mapHandler->get($id);
                    $utf8map->setVars($_POST[$id]);
                    if (!$utf8mapHandler->insert($utf8map)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_UTF8MAP_FAILEDTOSAVE);
                        exit(0);
                    }
                }
                redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_UTF8MAP_SAVEDOKEY);
                exit(0);
                break;
            case 'delete':

                $utf8mapHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Utf8map');
                $id             = 0;
                if (\Xmf\Request::hasVar('id', 'POST') && $id = \Xmf\Request::getInt('id', 0, 'POST')) {
                    $utf8map = $utf8mapHandler->get($id);
                    if (!$utf8mapHandler->delete($utf8map)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                        _AM_SONGLIST_MSG_UTF8MAP_FAILEDTODELETE);
                        exit(0);
                    }
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_UTF8MAP_DELETED);
                    exit(0);
                }
                $utf8map = $utf8mapHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                xoops_confirm(['id' => $_REQUEST['id'], 'op' => $_REQUEST['op'], 'fct' => $_REQUEST['fct'], 'limit' => $_REQUEST['limit'], 'start' => $_REQUEST['start'], 'order' => $_REQUEST['order'], 'sort' => $_REQUEST['sort'], 'filter' => $_REQUEST['filter']], $_SERVER['SCRIPT_NAME'],
                              sprintf(_AM_SONGLIST_MSG_UTF8MAP_DELETE, $utf8map->getVar('from'), $utf8map->getVar('to')));

                break;
        }
        break;
}

xoops_cp_footer();
