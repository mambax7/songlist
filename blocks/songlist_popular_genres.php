<?php

/**
 * @param $options
 * @return array|bool
 */
function b_songlist_popular_genres_show($options)
{
    xoops_loadLanguage('blocks', 'songlist');
    $handler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
    $objects = $handler->getTop($options[0]);
    if (count($objects) > 0) {
        $ret = [];
        foreach ($objects as $id => $object) {
            $ret[$id] = $object->toArray(true);
        }

        return $ret;
    }

    return false;
}

/**
 * @param $options
 * @return string
 */
function b_songlist_popular_genres_edit($options)
{
    xoops_load('XoopsFormLoader');
    xoops_loadLanguage('blocks', 'songlist');
    $num = new \XoopsformText('', 'options[0]', 10, 10, $options[0]);

    return _BL_SONGLIST_NUMBEROFITEMS . $num->render();
}
