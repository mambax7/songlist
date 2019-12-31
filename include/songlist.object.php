<?php

$moduleHandler = xoops_getHandler('module');
$configHandler = xoops_getHandler('config');
if (!isset($GLOBALS['songlistModule'])) {
    $GLOBALS['songlistModule'] = $moduleHandler->getByDirname('songlist');
}
if (is_object($GLOBALS['songlistModule'])) {
    if (!isset($GLOBALS['songlistModuleConfig'])) {
        $GLOBALS['songlistModuleConfig'] = $configHandler->getConfigList($GLOBALS['songlistModule']->getVar('mid'));
    }
}

require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
require_once $GLOBALS['xoops']->path('class/pagenav.php');

//require_once 'formselectalbum.php';
//require_once 'formselectartist.php';
//require_once 'formselectcategory.php';
//require_once 'formselectgenre.php';
//require_once 'formselectvoice.php';
//require_once 'formselectsong.php';

if (file_exists($GLOBALS['xoops']->path('/modules/tag/include/formtag.php')) && $GLOBALS['songlistModuleConfig']['tags']) {
    require_once $GLOBALS['xoops']->path('/modules/tag/include/formtag.php');
}
