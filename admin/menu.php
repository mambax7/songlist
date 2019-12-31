<?php

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

/** @var \XoopsModules\Songlist\Helper $helper */
$helper = \XoopsModules\Songlist\Helper::getInstance();
$helper->loadLanguage('common');
//$helper->loadLanguage('feedback');

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    //    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}

$moduleHandler                   = xoops_getHandler('module');
$configHandler                   = xoops_getHandler('config');
$GLOBALS['songlistModule']       = $moduleHandler->getByDirname('songlist');
$GLOBALS['songlistModuleConfig'] = $configHandler->getConfigList($GLOBALS['songlistModule']->getVar('mid'));

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_DASHBOARD,
    'icon'  => $pathIcon32 . '/home.png',
    'image' => $pathIcon32 . '/home.png',
    'link'  => 'admin/index.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_CATEGORY,
    'icon'  => 'assets/images/icons/32/songlist.category.png',
    'image' => 'assets/images/icons/32/songlist.category.png',
    'link'  => 'admin/category.php',
];

if ($GLOBALS['songlistModuleConfig']['voice']) {
    $adminmenu[] = [
        'title' => _MI_SONGLIST_ADMENU_VOICE,
        'icon'  => 'assets/images/icons/32/songlist.voice.png',
        'image' => 'assets/images/icons/32/songlist.voice.png',
        'link'  => 'admin/voice.php',
    ];
}

if ($GLOBALS['songlistModuleConfig']['album']) {
    $adminmenu[] = [
        'title' => _MI_SONGLIST_ADMENU_ALBUMS,
        'icon'  => 'assets/images/icons/32/songlist.albums.png',
        'image' => 'assets/images/icons/32/songlist.albums.png',
        'link'  => 'admin/albums.php',
    ];
}

if ($GLOBALS['songlistModuleConfig']['genre']) {
    $adminmenu[] = [
        'title' => _MI_SONGLIST_ADMENU_GENRE,
        'icon'  => 'assets/images/icons/32/songlist.genre.png',
        'image' => 'assets/images/icons/32/songlist.genre.png',
        'link'  => 'admin/genre.php',
    ];
}

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_ARTISTS,
    'icon'  => 'assets/images/icons/32/songlist.artists.png',
    'image' => 'assets/images/icons/32/songlist.artists.png',
    'link'  => 'admin/artists.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_SONGS,
    'icon'  => 'assets/images/icons/32/songlist.songs.png',
    'image' => 'assets/images/icons/32/songlist.songs.png',
    'link'  => 'admin/songs.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_VOTE,
    'icon'  => 'assets/images/icons/32/songlist.votes.png',
    'image' => 'assets/images/icons/32/songlist.votes.png',
    'link'  => 'admin/votes.php',
];

//$adminmenu[] = [
//'title' =>  _MI_SONGLIST_ADMENU_PERMISSIONS,
//'icon' =>  'assets/images/icons/32/songlist.permissions.png',
//'image' =>  'assets/images/icons/32/songlist.permissions.png',
//'link' =>  "admin/permissions.php",
//];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_REQUESTS,
    'icon'  => 'assets/images/icons/32/songlist.requests.png',
    'image' => 'assets/images/icons/32/songlist.requests.png',
    'link'  => 'admin/requests.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_UTF8MAP,
    'icon'  => 'assets/images/icons/32/songlist.utf8map.png',
    'image' => 'assets/images/icons/32/songlist.utf8map.png',
    'link'  => 'admin/utf8map.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_IMPORT,
    'icon'  => 'assets/images/icons/32/songlist.import.png',
    'image' => 'assets/images/icons/32/songlist.import.png',
    'link'  => 'admin/import.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_FIELDS,
    'icon'  => 'assets/images/icons/32/songlist.fields.png',
    'image' => 'assets/images/icons/32/songlist.fields.png',
    'link'  => 'admin/field.php',
];

$adminmenu[] = [
    'title' => _MI_SONGLIST_ADMENU_FIELDSPERMS,
    'icon'  => 'assets/images/icons/32/songlist.field.permissions.png',
    'image' => 'assets/images/icons/32/songlist.field.permissions.png',
    'link'  => 'admin/field_permissions.php',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

//Feedback
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_FEEDBACK'),
    'link'  => 'admin/feedback.php',
    'icon'  => $pathIcon32 . '/mail_foward.png',
];

if ($helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

//$adminmenu[] = [
//    'title' => _MI_SONGLIST_ADMENU_ABOUT,
//    'icon' => $pathIcon32 . '/about.png',
//    'image' => $pathIcon32 . '/about.png',
//    'link'  => 'admin/about.php',
//];

$adminmenu[] = [
    'title' => _MI_SONGLIST_MENU_ABOUT,
    'link' => 'admin/about.php',
    'icon' => $pathIcon32 . '/about.png',
];
