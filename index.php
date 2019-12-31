<?php

require_once __DIR__ . '/header.php';

global $file, $op, $fct, $id, $value, $gid, $cid, $vcid, $start, $limit, $singer;

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

$category_element = new \XoopsModules\Songlist\Form\SelectCategoryForm('', 'cid', (isset($_GET['cid']) ? $_GET['cid'] : $cid));
$genre_element    = new \XoopsModules\Songlist\Form\SelectGenreForm('', 'gid', $gid);
$voice_element    = new \XoopsModules\Songlist\Form\SelectVoiceForm('', 'vcid', $vcid);

$songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
switch ($op) {
    case 'vote':
        $votesHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');
        $votesHandler->addVote($id, $value);
        redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_ALREADY);
    // no break
    default:
    case 'item':
        switch ($fct) {
            default:
            case 'list':

                $pagenav = new \XoopsPageNav($songsHandler->getCount($criteria_cat), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

                $criteria_cat->setLimit($limit);
                $criteria_cat->setStart($start);

                $songs = $songsHandler->getObjects($criteria_cat, false);

                $url = $songsHandler->getURL();
                if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);
                    exit(0);
                }

                $GLOBALS['xoopsOption']['template_main'] = 'songlist_songs_index.tpl';
                require_once $GLOBALS['xoops']->path('/header.php');
                if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
                    $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
                    $GLOBALS['loaded_jquery'] = true;
                }
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                foreach ($songs as $song) {
                    $GLOBALS['xoopsTpl']->append('results', $song->toArray(true));
                }
                $GLOBALS['xoopsTpl']->assign('songs', true);
                $GLOBALS['xoopsTpl']->assign('categories', $cat);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
                $GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
                $GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
                $GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());
                $GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
                if (0 != $_SESSION['cid']) {
                    $category = $categoryHandler->get($_SESSION['cid']);
                    if (is_object($category)) {
                        $GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
                    }
                }
                $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
                require_once $GLOBALS['xoops']->path('/footer.php');
                break;
            case 'item':
                $song = $songsHandler->get($id);

                $url = $song->getURL(true);
                if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . $url);
                    exit(0);
                }

                $GLOBALS['xoopsOption']['template_main'] = 'songlist_songs_item.tpl';
                require_once $GLOBALS['xoops']->path('/header.php');
                if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
                    $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
                    $GLOBALS['loaded_jquery'] = true;
                }
                $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
                $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
                $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
                $GLOBALS['xoopsTpl']->assign('songs', false);
                $GLOBALS['xoopsTpl']->assign('song', $song->toArray(true));
                $GLOBALS['xoopsTpl']->assign('categories', $cat);
                $GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
                $GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
                $GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());
                $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
                require_once $GLOBALS['xoops']->path('/footer.php');
                break;
        }
        break;
    case 'browseby':
        switch ($fct) {
            default:
            case 'title':

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
                break;
            case 'lyrics':

                $browse_criteria = new \CriteriaCompo();
                switch ($value) {
                    case '0':
                        for ($u = 0; $u < 10; ++$u) {
                            $browse_criteria->add(new \Criteria('`lyrics`', $u . '%', 'LIKE'), 'OR');
                        }
                        break;
                    default:
                        $browse_criteria->add(new \Criteria('`lyrics`', mb_strtoupper($value) . '%', 'LIKE'), 'OR');
                        $browse_criteria->add(new \Criteria('`lyrics`', mb_strtolower($value) . '%', 'LIKE'), 'OR');
                        break;
                }
                $criteria = new \CriteriaCompo($criteria_cat, 'AND');
                $criteria->add($browse_criteria);
                break;
            case 'artist':

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
                $artistsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                $browse_criteriab = new \CriteriaCompo();
                foreach ($artistsHandler->getObjects($browse_criteria, true) as $aid => $obj) {
                    $browse_criteriab->add(new \Criteria('`aids`', '%"' . $aid . '"%', 'LIKE'), 'OR');
                }
                $criteria = new \CriteriaCompo($criteria_cat, 'AND');
                $criteria->add($browse_criteriab);
                break;
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
                $ids           = [];
                $albumsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
                foreach ($albumsHandler->getObjects($browse_criteria, true) as $id => $obj) {
                    $ids[$id] = $id;
                }
                $criteria = new \CriteriaCompo($criteria_cat, 'AND');
                if (count($ids) > 0) {
                    $criteria->add(new \Criteria('abid', '(' . implode(',', $ids) . ')', 'IN'));
                }
                break;
        }

        $pagenav = new \XoopsPageNav($songsHandler->getCount($criteria), $limit, $start, 'start', "op=$op&fct=$fct&id=$id&value=$value&limit=$limit");

        $criteria->setLimit($limit);
        $criteria->setStart($start);

        $songs = $songsHandler->getObjects($criteria, false);

        $url = $songsHandler->getURL();
        if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            exit(0);
        }

        $GLOBALS['xoopsOption']['template_main'] = 'songlist_songs_index.tpl';
        require_once $GLOBALS['xoops']->path('/header.php');
        if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
            $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
            $GLOBALS['loaded_jquery'] = true;
        }
        $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
        $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
        $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
        foreach ($songs as $song) {
            $GLOBALS['xoopsTpl']->append('results', $song->toArray(true));
        }
        $GLOBALS['xoopsTpl']->assign('songs', true);
        $GLOBALS['xoopsTpl']->assign('categories', $cat);
        $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
        $GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
        $GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
        $GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());
        $GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
        if (0 != $_SESSION['cid']) {
            $category = $categoryHandler->get($_SESSION['cid']);
            if (is_object($category)) {
                $GLOBALS['xoopsTpl']->assign('category', $category->toArray(true));
            }
        }
        $GLOBALS['xoopsTpl']->assign('uri', $_SERVER['REQUEST_URI']);
        require_once $GLOBALS['xoops']->path('/footer.php');
        break;
        break;
    case 'search':

        $songsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
        $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
        $utf8mapHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Utf8map');

        $url = $songsHandler->getSearchURL();
        if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $url);
            exit(0);
        }

        switch ($fct) {
            default:
            case 'titleandlyrics':
                $criteria = new \CriteriaCompo();
                foreach (explode(' ', $value) as $keyword) {
                    $criteria->add(new \Criteria('`title`', '%' . $utf8mapHandler->convert($keyword) . '%', 'LIKE'));
                    $criteria->add(new \Criteria('`lyrics`', '%' . $utf8mapHandler->convert($keyword) . '%', 'LIKE'));
                }
                break;
            case 'albums':
                $criteria = new \CriteriaCompo();
                foreach (explode(' ', $value) as $keyword) {
                    $criteria->add(new \Criteria('`title`', '%' . $utf8mapHandler->convert($keyword) . '%', 'LIKE'));
                }
                $albums   = $albumsHandler->getObject($criteria, true);
                $criteria = new \CriteriaCompo();
                foreach ($albums as $abid => $album) {
                    $criteria->add(new \Criteria('`abid`', $abid), 'OR');
                }
                break;
            case 'artists':
                $criteria = new \CriteriaCompo();
                foreach (explode(' ', $value) as $keyword) {
                    $criteria->add(new \Criteria('`name`', '%' . $utf8mapHandler->convert($keyword) . '%', 'LIKE'));
                }
                $artists  = $artistsHandler->getObject($criteria, true);
                $criteria = new \CriteriaCompo();
                foreach ($artists as $aid => $artist) {
                    $criteria->add(new \Criteria('`aids`', '%"' . $aid . '"%', 'LIKE'), 'OR');
                }
                break;
            case 'lyrics':
                $criteria = new \CriteriaCompo();
                foreach (explode(' ', $value) as $keyword) {
                    $criteria->add(new \Criteria('`lyrics`', '%' . $utf8mapHandler->convert($keyword) . '%', 'LIKE'));
                }
                break;
            case 'title':
                $criteria = new \CriteriaCompo();
                foreach (explode(' ', $value) as $keyword) {
                    $criteria->add(new \Criteria('`title`', '%' . $utf8mapHandler->convert($keyword) . '%', 'LIKE'));
                }
                break;
        }

        if (0 != $gid && $GLOBALS['songlistModuleConfig']['genre']) {
            $criteria->add(new \Criteria('`gids`', '%"' . $gid . '"%', 'LIKE'));
        }

        if (0 != $vcid && $GLOBALS['songlistModuleConfig']['voice']) {
            $criteria->add(new \Criteria('`vcid`', $vcid));
        }

        if (0 != (isset($_GET['cid']) ? $_GET['cid'] : $cid)) {
            $criteria->add(new \Criteria('`cid`', (isset($_GET['cid']) ? $_GET['cid'] : $cid)));
        }

        $pagenav = new \XoopsPageNav($songsHandler->getCount($criteria), $limit, $start, 'start', "op=$op&fct=$fct&gid=$gid&vcid=$vcid&value=$value&limit=$limit");

        $criteria->setLimit($limit);
        $criteria->setStart($start);

        $songs = $songsHandler->getObjects($criteria, false);

        $GLOBALS['xoopsOption']['template_main'] = 'songlist_search_index.tpl';
        include $GLOBALS['xoops']->path('/header.php');
        if ($GLOBALS['songlistModuleConfig']['force_jquery'] && !isset($GLOBALS['loaded_jquery'])) {
            $GLOBALS['xoTheme']->addScript(XOOPS_URL . _MI_SONGLIST_JQUERY, ['type' => 'text/javascript']);
            $GLOBALS['loaded_jquery'] = true;
        }
        $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . _MI_SONGLIST_STYLESHEET, ['type' => 'text/css']);
        $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['songlistModuleConfig']);
        $GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['SCRIPT_NAME']);
        foreach ($songs as $song) {
            $GLOBALS['xoopsTpl']->append('results', $song->toArray(true));
        }
        $GLOBALS['xoopsTpl']->assign('songs', true);
        $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
        $GLOBALS['xoopsTpl']->assign('category_element', $category_element->render());
        $GLOBALS['xoopsTpl']->assign('genre_element', $genre_element->render());
        $GLOBALS['xoopsTpl']->assign('voice_element', $voice_element->render());
        $GLOBALS['xoopsTpl']->assign('cid', $_SESSION['cid']);
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
        redirect_header($_SERVER['SCRIPT_NAME'] . "?op=item&fct=list&id=$id&value=$value&start=$start&limit=$limit&cid=" . $_SESSION['cid'], 10, _MN_SONGLIST_MSG_CATEGORYCHANGED);
}
