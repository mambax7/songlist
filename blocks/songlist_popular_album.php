<?php

/**
 * @param $options
 * @return bool
 */
function b_songlist_popular_album_show($options)
{
    xoops_loadLanguage('blocks', 'songlist');
    $handler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
    $objects = $handler->getTop(1);
    if (is_object($objects[0])) {
        return $objects[0]->toArray(true);
    }

    return false;
}

/**
 * @param $options
 */
function b_songlist_popular_album_edit($options)
{
}
