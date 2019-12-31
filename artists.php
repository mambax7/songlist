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
    foreach ($cids as $cid) {
        $criteria_cat->add(new \Criteria('`cids`', '%"' . $cid . '"%', 'LIKE'), 'OR');
    }
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

$artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
switch ((string)$GLOBALS['op']) {
    default:
    case 'item':
        switch ($fct) {
            default:
            case 'list':
                $pagenav = new \XoopsPageNav($artistsHandler->getCount($criteria_cat), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

                $criteria_cat->setLimit($limit);
                $criteria_cat->setStart($start);

                $artists = $artistsHandler->getObjects($criteria_cat, false);

                $ret = [];
                $col = 1;
                $row = 1;
                foreach ($artists as $artist) {
                    $ret[$row][$col]          = $artist->toArray(true);
                    $ret[$row][$col]['width'] = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
                    ++$col;
                    if ($col > $GLOBALS['songlistModuleConfig']['cols']) {
                        ++$row;
                        $col = 1;
                    }
                }
                if (1 != $col) {
                    for ($j = $col; $j <= $GLOBALS['songlistModuleConfig']['cols']; ++$j) {
                        $ret[$row][$j][$artistsHandler->keyName] = 0;
                        $ret[$row][$j]['width']                  = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
                    }
                }

                $url = $artistsHandler->getURL(false);
                if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);
                    exit(0);
                }

                $GLOBALS['xoopsOption']['template_main'] = 'songlist_artists_index.tpl';
                include $GLOBALS['xoops']->path('/header.php');
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
                $GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
                if (0 != $_SESSION['cid']) {
                    $category = $categoryHandler->get($_SESSION['cid']);
                    $GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
                }
                $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
                include $GLOBALS['xoops']->path('/footer.php');
                break;
            case 'item':
                $artist = $artistsHandler->get($id);

                $url = $artist->getURL(true);
                if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);
                    exit(0);
                }

                $GLOBALS['xoopsOption']['template_main'] = 'songlist_artists_item.tpl';
                include $GLOBALS['xoops']->path('/header.php');
                if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
                    $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
                    $GLOBALS['loaded_jquery'] = true;
                }
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->assign('songs', false);
                $GLOBALS['xoopsTpl']->assign('artist', $artist->toArray(true));
                $GLOBALS['xoopsTpl']->assign('categories', $cat);
                $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
                include $GLOBALS['xoops']->path('/footer.php');
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
                            $browse_criteria->add(new \Criteria('`name`', $u . '%', 'LIKE'), 'OR');
                        }
                        break;
                    default:
                        $browse_criteria->add(new \Criteria('`name`', mb_strtoupper($value) . '%', 'LIKE'), 'OR');
                        $browse_criteria->add(new \Criteria('`name`', mb_strtolower($value) . '%', 'LIKE'), 'OR');
                        break;
                }
                $criteria = new \CriteriaCompo($criteria_cat, 'AND');
                $criteria->add($browse_criteria);
        }

        $pagenav = new \XoopsPageNav($artistsHandler->getCount($criteria), $limit, $start, 'start', "op={$GLOBALS['op']}&fct=$fct&id=$id&value=$value&limit=$limit");

        $criteria->setLimit($limit);
        $criteria->setStart($start);

        $artists = $artistsHandler->getObjects($criteria, false);

        $ret = [];
        $col = 1;
        $row = 1;
        foreach ($artists as $artist) {
            $ret[$row][$col]          = $artist->toArray(true);
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
                $ret[$row][$j][$artistsHandler->keyName] = 0;
                $ret[$row][$j]['width']                  = floor(100 / $GLOBALS['songlistModuleConfig']['cols']) . '%';
            }
        }

        $url = $artistsHandler->getURL(false);
        if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            exit(0);
        }

        $GLOBALS['xoopsOption']['template_main'] = 'songlist_artists_index.tpl';
        include $GLOBALS['xoops']->path('/header.php');
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
        $GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
        if (0 != $_SESSION['cid']) {
            $category = $categoryHandler->get($_SESSION['cid']);
            $GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
        }
        $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
        include $GLOBALS['xoops']->path('/footer.php');

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
        redirect_header($_SERVER['SCRIPT_NAME'] . "?op=item&fct=list&id=0&value=%&start=0&limit=$limit&cid=" . $_SESSION['cid'], 10, _MN_SONGLIST_MSG_CATEGORYCHANGED);
}
