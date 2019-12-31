<?php

include 'header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op     = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'category';
$fct    = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'list';
$limit  = \Xmf\Request::getInt('limit', 30, 'REQUEST');
$start  = \Xmf\Request::getInt('start', 0, 'REQUEST');
$order  = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
$sort   = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';
$filter = !empty($_REQUEST['filter']) ? '' . $_REQUEST['filter'] . '' : '1,1';

switch ($op) {
    default:
    case 'category':
        switch ($fct) {
            default:
            case 'list':
                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');

                $criteria        = $categoryHandler->getFilterCriteria($GLOBALS['filter']);
                $ttl             = $categoryHandler->getCount($criteria);
                $GLOBALS['sort'] = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : 'created';

                $pagenav = new \XoopsPageNav($ttl, $GLOBALS['limit'], $GLOBALS['start'], 'start', 'limit=' . $GLOBALS['limit'] . '&sort=' . $GLOBALS['sort'] . '&order=' . $GLOBALS['order'] . '&op=' . $GLOBALS['op'] . '&fct=' . $GLOBALS['fct'] . '&filter=' . $GLOBALS['filter']);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());

                foreach ($categoryHandler->filterFields() as $id => $key) {
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
                    $GLOBALS['xoopsTpl']->assign('filter_' . mb_strtolower(str_replace('-', '_', $key)) . '_th', $categoryHandler->getFilterForm($GLOBALS['filter'], $key, $GLOBALS['sort'], $GLOBALS['op'], $GLOBALS['fct']));
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

                $categorys = $categoryHandler->getObjects($criteria, true);
                foreach ($categorys as $cid => $category) {
                    if (is_object($category)) {
                        $GLOBALS['xoopsTpl']->append('categories', $category->toArray());
                    }
                }
                $GLOBALS['xoopsTpl']->assign('form', songlist_category_get_form(false));
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_category_list.tpl');
                break;
            case 'new':
            case 'edit':

                $adminObject = \Xmf\Module\Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));

                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
//                  if (isset($_REQUEST['id'])) {
                if (\Xmf\Request::hasVar('id', 'REQUEST')) {
                    $category = $categoryHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                } else {
                    $category = $categoryHandler->create();
                }

                $GLOBALS['xoopsTpl']->assign('form', $category->getForm());
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->display('db:songlist_cpanel_category_edit.tpl');
                break;
            case 'save':

                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
                $id              = 0;
                $id              = \Xmf\Request::getInt('id', 0, 'REQUEST');
                if ($id) {
                    $category = $categoryHandler->get($id);
                } else {
                    $category = $categoryHandler->create();
                }
                $category->setVars($_POST[$id]);

                if (!$id = $categoryHandler->insert($category)) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_CATEGORY_FAILEDTOSAVE);
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
                    $category = $categoryHandler->get($id);
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
                        if (mb_strlen($category->getVar('image'))) {
                            unlink($GLOBALS['xoops']->path($category->getVar('path')) . $category->getVar('image'));
                        }

                        $category->setVar('path', $GLOBALS['songlistModuleConfig']['upload_areas']);
                        $category->setVar('image', $uploader->getSavedFileName());
                        @$categoryHandler->insert($category);
                    } else {
                        $adminObject = \Xmf\Module\Admin::getInstance();
                        $adminObject->displayNavigation(basename(__FILE__));
                        echo $uploader->getErrors();
                        songlist_footer_adminMenu();
                        xoops_cp_footer();
                        exit(0);
                    }
                }

                if ('new' === $_REQUEST['state'][$_REQUEST['id']]) {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=edit&id=' . $_REQUEST['id'] . '&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                    _AM_SONGLIST_MSG_CATEGORY_SAVEDOKEY);
                } else {
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_CATEGORY_SAVEDOKEY);
                }
                exit(0);

                break;
            case 'savelist':

                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
                foreach ($_REQUEST['id'] as $id) {
                    $category = $categoryHandler->get($id);
                    $category->setVars($_POST[$id]);
                    if (!$categoryHandler->insert($category)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                        _AM_SONGLIST_MSG_CATEGORY_FAILEDTOSAVE);
                        exit(0);
                    }
                }
                redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_CATEGORY_SAVEDOKEY);
                exit(0);
                break;
            case 'delete':

                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
                $id              = 0;
                if (\Xmf\Request::hasVar('id', 'POST') && $id = \Xmf\Request::getInt('id', 0, 'POST')) {
                    $category = $categoryHandler->get($id);
                    if (!$categoryHandler->delete($category)) {
                        redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10,
                                        _AM_SONGLIST_MSG_CATEGORY_FAILEDTODELETE);
                        exit(0);
                    }
                    redirect_header($_SERVER['SCRIPT_NAME'] . '?op=' . $GLOBALS['op'] . '&fct=list&limit=' . $GLOBALS['limit'] . '&start=' . $GLOBALS['start'] . '&order=' . $GLOBALS['order'] . '&sort=' . $GLOBALS['sort'] . '&filter=' . $GLOBALS['filter'], 10, _AM_SONGLIST_MSG_CATEGORY_DELETED);
                    exit(0);
                }
                $category = $categoryHandler->get(\Xmf\Request::getInt('id', 0, 'REQUEST'));
                xoops_confirm(['id' => $_REQUEST['id'], 'op' => $_REQUEST['op'], 'fct' => $_REQUEST['fct'], 'limit' => $_REQUEST['limit'], 'start' => $_REQUEST['start'], 'order' => $_REQUEST['order'], 'sort' => $_REQUEST['sort'], 'filter' => $_REQUEST['filter']], $_SERVER['SCRIPT_NAME'],
                              sprintf(_AM_SONGLIST_MSG_CATEGORY_DELETE, $category->getVar('name')));

                break;
        }
        break;
}

xoops_cp_footer();
