<?php

require_once __DIR__ . '/header.php';

global $file, $op, $fct, $id, $value, $gid, $cid, $start, $limit;

$categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
$criteria_cat    = new \CriteriaCompo();
$cids            = $categoryHandler->getCatAndSubCat($_SESSION['cid']);
if ($_SESSION['cid'] > 0) {
    $cids[$_SESSION['cid']] = $_SESSION['cid'];
}
if (count($cids) > 0 && 0 != $_SESSION['cid']) {
    $criteria_cat->add(new \Criteria('`cid`', '(' . implode(',', $cids) . ')', 'IN'), 'OR');
} else {
    $criteria_cat->add(new \Criteria(''), 'OR');
}
$criteria_cat->setSort('`created`');
$criteria_cat->setOrder('ASC');

$criteria = new \Criteria('pid', $_SESSION['cid']);
$criteria->setSort('`weight`');
$criteria->setOrder('ASC');
$categories = $categoryHandler->getObjects($criteria, false);

$cat = [];
$col = 1;
$row = 1;
foreach ($categories as $category) {
    $cat[$row][$col]          = $category->toArray(true);
    $cat[$row][$col]['width'] = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
    ++$col;
    if ($col > $GLOBALS['songlistModuleConfig']['cols']) {
        ++$row;
        $col = 1;
    }
}
if (1 != $col) {
    $col--;
    for ($j = $col; $j <= $GLOBALS['songlistModuleConfig']['cols']; ++$j) {
        $cat[$row][$j][$categoryHandler->keyName] = 0;
        $cat[$row][$j]['width']                   = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
    }
}

$albumsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
switch ($op) {
    default:
    case 'item':
        switch ($fct) {
            default:
            case 'list':
                $pagenav = new \XoopsPageNav($albumsHandler->getCount($criteria_cat), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

                $criteria_cat->setLimit($limit);
                $criteria_cat->setStart($start);

                $albums = $albumsHandler->getObjects($criteria_cat, false);

                $ret = [];
                $col = 1;
                $row = 1;
                foreach ($albums as $album) {
                    $ret[$row][$col]          = $album->toArray(true);
                    $ret[$row][$col]['width'] = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
                    ++$col;
                    if ($col > $GLOBALS['songlistModuleConfig']['cols']) {
                        ++$row;
                        $col = 1;
                    }
                }
                if (1 != $col) {
                    for ($j = $col; $j <= $GLOBALS['songlistModuleConfig']['cols']; ++$j) {
                        $ret[$row][$j][$albumsHandler->keyName] = 0;
                        $ret[$row][$j]['width']                 = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
                    }
                }

                $url = $albumsHandler->getURL();
                if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);
                    exit(0);
                }

                $GLOBALS['xoopsOption']['template_main'] = 'songlist_albums_index.tpl';
                require_once $GLOBALS['xoops']->path('/header.php');
                if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
                    $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
                    $GLOBALS['loaded_jquery'] = true;
                }
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->assign('results', $ret);
                $GLOBALS['xoopsTpl']->assign('categories', $cat);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
                if (0 != $_SESSION['cid']) {
                    $category = $categoryHandler->get($_SESSION['cid']);
                    $GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
                }
                require_once $GLOBALS['xoops']->path('/footer.php');
                break;
            case 'item':
                $album = $albumsHandler->get($id);

                $url = $album->getURL(true);
                if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);
                    exit(0);
                }

                $GLOBALS['xoopsOption']['template_main'] = 'songlist_albums_item.tpl';
                require_once $GLOBALS['xoops']->path('/header.php');
                if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
                    $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
                    $GLOBALS['loaded_jquery'] = true;
                }
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->assign('songs', false);
                $GLOBALS['xoopsTpl']->assign('album', $album->toArray(true));
                $GLOBALS['xoopsTpl']->assign('categories', $cat);
                $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
                require_once $GLOBALS['xoops']->path('/footer.php');
                break;
        }
        break;
    case 'browseby':
        switch ($fct) {
            default:
            case 'title':
            case 'lyrics':
            case 'artist':
            case 'album':

                $browse_criteria = new \CriteriaCompo();
                switch ($value) {
                    case '0':
                        for ($u = 0; $u < 10; ++$u) {
                            $browse_criteria->add(new \Criteria('`title`', $u . '%', 'LIKE'), 'OR');
                        }
                        break;
                    default:
                        $browse_criteria->add(new \Criteria('`title`', mb_strtoupper($value) . '%', 'LIKE'), 'OR');
                        $browse_criteria->add(new \Criteria('`title`', mb_strtolower($value) . '%', 'LIKE'), 'OR');
                        break;
                }
                $criteria = new \CriteriaCompo($criteria_cat, 'AND');
                $criteria->add($browse_criteria);
        }

        $pagenav = new \XoopsPageNav($albumsHandler->getCount($criteria), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

        $criteria->setLimit($limit);
        $criteria->setStart($start);

        $albums = $albumsHandler->getObjects($criteria, false);

        $ret = [];
        $col = 1;
        $row = 1;
        foreach ($albums as $album) {
            $ret[$row][$col]          = $album->toArray(true);
            $ret[$row][$col]['width'] = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
            ++$col;
            if ($col > $GLOBALS['songlistModuleConfig']['cols']) {
                ++$row;
                $col = 1;
            }
        }
        if (1 != $col) {
            $col--;
            for ($j = $col; $j <= $GLOBALS['songlistModuleConfig']['cols']; ++$j) {
                $ret[$row][$j][$albumsHandler->keyName] = 0;
                $ret[$row][$j]['width']                 = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
            }
        }

        $url = $albumsHandler->getURL();
        if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            exit(0);
        }

        $GLOBALS['xoopsOption']['template_main'] = 'songlist_albums_index.tpl';
        require_once $GLOBALS['xoops']->path('/header.php');
        if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
            $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
            $GLOBALS['loaded_jquery'] = true;
        }
        $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
        $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
        $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
        $GLOBALS['xoopsTpl']->assign('results', $ret);
        $GLOBALS['xoopsTpl']->assign('songs', false);
        $GLOBALS['xoopsTpl']->assign('categories', $cat);
        $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
        if (0 != $_SESSION['cid']) {
            $category = $categoryHandler->get($_SESSION['cid']);
            $GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
        }
        $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
        require_once $GLOBALS['xoops']->path('/footer.php');
        break;
        break;
    case 'category':
        switch ($fct) {
            default:
            case 'set':
                $_SESSION['cid'] = $id;
                break;
            case 'home':
                unset($_SESSION['cid']);
                break;
        }
        redirect_header($_SERVER['SCRIPT_NAME'] . "?op=item&fct=list&id=0&value=%&start=$start&limit=$limit&cid=" . $_SESSION['cid'], 10, _MN_SONGLIST_MSG_CATEGORYCHANGED);
}
