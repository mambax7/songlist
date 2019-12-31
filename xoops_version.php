<?php

require_once __DIR__ . '/preloads/autoloader.php';

error_reporting(E_ALL);
$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$moduleHandler             = xoops_getHandler('module');
$configHandler             = xoops_getHandler('config');
$GLOBALS['songlistModule'] = $moduleHandler->getByDirname('songlist');
if (is_object($GLOBALS['songlistModule'])) {
    $GLOBALS['songlistModuleConfig'] = $configHandler->getConfigList($GLOBALS['songlistModule']->getVar('mid'));
}

// $Id: xoops_version.php,v 4.04 2008/06/05 15:35:59 wishcraft Exp $

$modversion['version']             = 2.0;
$modversion['module_status']       = 'Beta 1';
$modversion['release_date']        = '2019/12/22';
$modversion['name']                = _MI_SONGLIST_NAME;
$modversion['description']         = _MI_SONGLIST_DESC;
$modversion['credits']             = 'Orginally Written by wishcraft, Testing Phil and Michael Beck';
$modversion['author']              = 'Simon Roberts (wishcraft)';
$modversion['help']                = 'page=help';
$modversion['license']             = 'GNU General Public License (GPL) see LICENSE';
$modversion['dirname']             = $moduleDirName;
$modversion['image']               = 'assets/images/logoModule.png';
$modversion['releasedate']         = 'Thursday, 05th April, 2012';
$modversion['website']             = 'www.chronolabs.coop';
$modversion['release_info']        = 'Stable 2012/08/16';
$modversion['release_file']        = XOOPS_URL . '/modules/xforum/docs/changelog.txt';
$modversion['author_realname']     = 'Wishcraft';
$modversion['author_website_url']  = 'http://www.chronolabs.coop';
$modversion['author_website_name'] = 'Chronolabs';
$modversion['author_email']        = 'simon@chronolabs.coop';
$modversion['status_version']      = '1.00';
$modversion['warning']             = 'For XOOPS 2.5 or later';
$modversion['min_php']             = '5.6';
$modversion['min_xoops']           = '2.5.10';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];
$modversion['demo_site_url']       = 'http://xoops.demo.chronolabs.coop/';
$modversion['demo_site_name']      = 'Chronolabs';
$modversion['support_site_url']    = 'http://www.chronolabs.coop/';
$modversion['support_site_name']   = 'Chronolabs';
$modversion['submit_feature']      = 'http://www.chronolabs.coop/';
$modversion['submit_bug']          = 'http://www.chronolabs.coop/';

// Sql file
//$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables created by sql file (without prefix!)
//$modversion['tables'][0]  = 'songlist_albums';
//$modversion['tables'][1]  = 'songlist_artists';
//$modversion['tables'][2]  = 'songlist_category';
//$modversion['tables'][3]  = 'songlist_extra';
//$modversion['tables'][4]  = 'songlist_field';
//$modversion['tables'][5]  = 'songlist_genre';
//$modversion['tables'][6]  = 'songlist_voice';
//$modversion['tables'][7]  = 'songlist_requests';
//$modversion['tables'][8]  = 'songlist_songs';
//$modversion['tables'][9]  = 'songlist_utf8map';
//$modversion['tables'][10] = 'songlist_visibility';
//$modversion['tables'][11] = 'songlist_votes';

$modversion = [
    // ------------------- Mysql -----------------------------
    'sqlfile' => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables ----------------------------
    'tables'  => [
        $moduleDirName . '_' . 'albums',
        $moduleDirName . '_' . 'artists',
        $moduleDirName . '_' . 'category',
        $moduleDirName . '_' . 'extra',
        $moduleDirName . '_' . 'field',
        $moduleDirName . '_' . 'genre',
        $moduleDirName . '_' . 'voice',
        $moduleDirName . '_' . 'requests',
        $moduleDirName . '_' . 'songs',
        $moduleDirName . '_' . 'utf8map',
        $moduleDirName . '_' . 'visibility',
        $moduleDirName . '_' . 'votes',
    ],
];

// Admin things
$modversion['system_menu'] = 1;
$modversion['hasAdmin']    = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;

//install
//$modversion['onInstall'] = 'include/module.php';

//update things
$modversion['onUpdate'] = 'include/update.php';

//module css
$modversion['css'] = 'assets/css/songlist.css';

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'songlist_search';

// Smarty
$modversion['use_smarty'] = 1;

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_SONGLIST_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_SONGLIST_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_SONGLIST_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_SONGLIST_SUPPORT, 'link' => 'page=support'],
];

// Templates

$modversion['templates'] = [
    ['file' => 'songlist_category_list.tpl', 'description' => ''],
    ['file' => 'songlist_category_item.tpl', 'description' => ''],
    ['file' => 'songlist_songs_list.tpl', 'description' => ''],
    ['file' => 'songlist_songs_item.tpl', 'description' => ''],
    ['file' => 'songlist_songs_index.tpl', 'description' => ''],
    ['file' => 'songlist_requests_index.tpl', 'description' => ''],
    ['file' => 'songlist_search_search.tpl', 'description' => ''],
    ['file' => 'songlist_search_index.tpl', 'description' => ''],
    ['file' => 'songlist_albums_list.tpl', 'description' => ''],
    ['file' => 'songlist_albums_item.tpl', 'description' => ''],
    ['file' => 'songlist_albums_index.tpl', 'description' => ''],
    ['file' => 'songlist_artists_list.tpl', 'description' => ''],
    ['file' => 'songlist_artists_item.tpl', 'description' => ''],
    ['file' => 'songlist_artists_index.tpl', 'description' => ''],
    ['file' => 'songlist_search_results.tpl', 'description' => ''],
    ['file' => 'songlist_alpha_browse.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_albums_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_albums_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_artists_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_artists_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_category_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_category_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_fieldlist.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_genre_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_genre_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_voice_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_voice_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_import_actiona.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_import_actionb.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_requests_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_requests_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_songs_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_songs_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_utf8map_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_utf8map_list.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_visibility.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_votes_edit.tpl', 'description' => ''],
    ['file' => 'songlist_cpanel_votes_list.tpl', 'description' => ''],
];

// Submenus
$modversion['sub'][] = [
    'name' => _MI_SONGLIST_MENU_ARTISTS,
    'url'  => 'artists.php'
];

if (isset($GLOBALS['songlistModuleConfig']['album'])) {
    if ($GLOBALS['songlistModuleConfig']['album']) {
        $modversion['sub'][] = [
            'name' => _MI_SONGLIST_MENU_ALBUMS,
            'url'  => 'albums.php'
        ];
    }
}
$modversion['sub'][] = [
    'name' => _MI_SONGLIST_MENU_SEARCH,
    'url'  => 'search.php',
];
$modversion['sub'][] = [
    'name' => _MI_SONGLIST_MENU_REQUEST,
    'url'  => 'request.php',
];

// Blocks
$modversion['blocks'][] = [
    'file'        => 'songlist_popular_artist.php',
    'name'        => _MI_SONGLIST_BLOCK_TOP_ARTIST,
    'description' => 'Shows top artist',
    'show_func'   => 'b_songlist_popular_artist_show',
    'options'     => '',
    'edit_func'   => 'b_songlist_popular_artist_edit',
    'template'    => 'songlist_popular_artist.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_artists.php',
    'name'        => _MI_SONGLIST_BLOCK_POPULAR_ARTISTS,
    'description' => 'Shows popular artists',
    'show_func'   => 'b_songlist_popular_artists_show',
    'options'     => '6',
    'edit_func'   => 'b_songlist_popular_artists_edit',
    'template'    => 'songlist_popular_artists.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_album.php',
    'name'        => _MI_SONGLIST_BLOCK_TOP_ALBUM,
    'description' => 'Shows top album',
    'show_func'   => 'b_songlist_popular_album_show',
    'options'     => '',
    'edit_func'   => 'b_songlist_popular_album_edit',
    'template'    => 'songlist_popular_album.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_albums.php',
    'name'        => _MI_SONGLIST_BLOCK_POPULAR_ALBUMS,
    'description' => 'Shows popular albums',
    'show_func'   => 'b_songlist_popular_albums_show',
    'options'     => '6',
    'edit_func'   => 'b_songlist_popular_albums_edit',
    'template'    => 'songlist_popular_albums.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_genre.php',
    'name'        => _MI_SONGLIST_BLOCK_TOP_GENRE,
    'description' => 'Shows top genre',
    'show_func'   => 'b_songlist_popular_genre_show',
    'options'     => '',
    'edit_func'   => 'b_songlist_popular_genre_edit',
    'template'    => 'songlist_popular_genre.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_genres.php',
    'name'        => _MI_SONGLIST_BLOCK_POPULAR_GENRES,
    'description' => 'Shows popular genres',
    'show_func'   => 'b_songlist_popular_genres_show',
    'options'     => '6',
    'edit_func'   => 'b_songlist_popular_genres_edit',
    'template'    => 'songlist_popular_genres.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_song.php',
    'name'        => _MI_SONGLIST_BLOCK_TOP_SONG,
    'description' => 'Shows top song',
    'show_func'   => 'b_songlist_popular_song_show',
    'options'     => '',
    'edit_func'   => 'b_songlist_popular_song_edit',
    'template'    => 'songlist_popular_song.tpl',
];

$modversion['blocks'][] = [
    'file'        => 'songlist_popular_songs.php',
    'name'        => _MI_SONGLIST_BLOCK_POPULAR_SONGS,
    'description' => 'Shows popular songs',
    'show_func'   => 'b_songlist_popular_songs_show',
    'options'     => '6',
    'edit_func'   => 'b_songlist_popular_songs_edit',
    'template'    => 'songlist_popular_songs.tpl',
];

xoops_load('XoopsEditorHandler');
$editorHandler = XoopsEditorHandler::getInstance();
foreach ($editorHandler->getList(false) as $id => $val) {
    $options[$val] = $id;
}

$modversion['config'][] = [
    'name'        => 'editor',
    'title'       => '_MI_SONGLIST_EDITORS',
    'description' => '_MI_SONGLIST_EDITORS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'tinymce',
    'options'     => $options,
];

$modversion['config'][] = [
    'name'        => 'salt',
    'title'       => '_MI_SONGLIST_SALT',
    'description' => '_MI_SONGLIST_SALT_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(
            0,
            4
        ) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : '')
                     . (2 != mt_rand(0, 4) ? chr(mt_rand(32, 190)) : ''),
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'cols',
    'title'       => '_MI_SONGLIST_COLS',
    'description' => '_MI_SONGLIST_COLS_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 4,
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'rows',
    'title'       => '_MI_SONGLIST_ROWS',
    'description' => '_MI_SONGLIST_ROWS_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 4,
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'email',
    'title'       => '_MI_SONGLIST_EMAIL',
    'description' => '_MI_SONGLIST_EMAIL_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => $GLOBALS['xoopsConfig']['adminmail'],
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'mp3_filesize',
    'title'       => '_MI_SONGLIST_MP3FILESIZE',
    'description' => '_MI_SONGLIST_MP3FILESIZE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '195035100',

];

$modversion['config'][] = [
    'name'        => 'mp3_mimetype',
    'title'       => '_MI_SONGLIST_MP3MIMETYPE',
    'description' => '_MI_SONGLIST_MP3MIMETYPE_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'audio/mpeg|audio/x-mpeg|audio/mp3|audio/x-mp3|audio/mpeg3|audio/x-mpeg3|audio/mpg|audio/x-mpg|audio/x-mpegaudio',

];

$modversion['config'][] = [
    'name'        => 'mp3_extensions',
    'title'       => '_MI_SONGLIST_MP3EXTENSIONS',
    'description' => '_MI_SONGLIST_MP3EXTENSIONS_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'mp3',

];

$modversion['config'][] = [
    'name'        => 'filesize_upload',
    'title'       => '_MI_SONGLIST_FILESIZEUPLD',
    'description' => '_MI_SONGLIST_FILESIZEUPLD_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '1950351',

];

$modversion['config'][] = [
    'name'        => 'allowed_mimetype',
    'title'       => '_MI_SONGLIST_ALLOWEDMIMETYPE',
    'description' => '_MI_SONGLIST_ALLOWEDMIMETYPE_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'image/gif|image/pjpeg|image/jpeg|image/x-png|image/png',

];

$modversion['config'][] = [
    'name'        => 'allowed_extensions',
    'title'       => '_MI_SONGLIST_ALLOWEDEXTENSIONS',
    'description' => '_MI_SONGLIST_ALLOWEDEXTENSIONS_DESC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'gif|pjpeg|jpeg|jpg|png',

];

$modversion['config'][] = [
    'name'        => 'upload_areas',
    'title'       => '_MI_SONGLIST_UPLOADAREAS',
    'description' => '_MI_SONGLIST_UPLOADAREAS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'uploads' . DS . 'songlist' . DS,
    'options'     => [
        'uploads' . DS                   => 'uploads' . DS,
        'uploads' . DS . 'songlist' . DS => 'uploads' . DS . 'songlist' . DS,
    ],
];

$modversion['config'][] = [
    'name'        => 'album',
    'title'       => '_MI_SONGLIST_ALBUM',
    'description' => '_MI_SONGLIST_ALBUM_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',

];

$modversion['config'][] = [
    'name'        => 'genre',
    'title'       => '_MI_SONGLIST_GENRE',
    'description' => '_MI_SONGLIST_GENRE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',

];

$modversion['config'][] = [
    'name'        => 'voice',
    'title'       => '_MI_SONGLIST_VOICE',
    'description' => '_MI_SONGLIST_VOICE_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',

];

$modversion['config'][] = [
    'name'        => 'lyrics',
    'title'       => '_MI_SONGLIST_LYRICS',
    'description' => '_MI_SONGLIST_LYRICS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
];

/*
$modversion['config'][] = [
'name' =>  'singer',
'title' =>  "_MI_SONGLIST_SINGER",
'description' =>  "_MI_SONGLIST_SINGER_DESC",
'formtype' =>  'yesno',
'valuetype' =>  'int',
'default' =>  '0',
];
*/

$modversion['config'][] = [
    'name'        => 'htaccess',
    'title'       => '_MI_SONGLIST_HTACCESS',
    'description' => '_MI_SONGLIST_HTACCESS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',

];

$modversion['config'][] = [
    'name'        => 'baseofurl',
    'title'       => '_MI_SONGLIST_HTACCESS_BASEOFURL',
    'description' => '_MI_SONGLIST_HTACCESS_BASEOFURL_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => 'songlist',

];

$modversion['config'][] = [
    'name'        => 'endofurl',
    'title'       => '_MI_SONGLIST_HTACCESS_ENDOFURL',
    'description' => '_MI_SONGLIST_HTACCESS_ENDOFURL_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => '.tpl',

];

$modversion['config'][] = [
    'name'        => 'tags',
    'title'       => '_MI_SONGLIST_TAGS',
    'description' => '_MI_SONGLIST_TAGS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',

];

$modversion['config'][] = [
    'name'        => 'force_jquery',
    'title'       => '_MI_SONGLIST_FORCE_JQUERY',
    'description' => '_MI_SONGLIST_FORCE_JQUERY_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => true,

];

$modversion['config'][] = [
    'name'        => 'memory_admin',
    'title'       => '_MI_SONGLIST_MEMORY_ADMIN',
    'description' => '_MI_SONGLIST_MEMORY_ADMIN_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 128,
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'memory_user',
    'title'       => '_MI_SONGLIST_MEMORY_USER',
    'description' => '_MI_SONGLIST_MEMORY_USER_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 128,
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'time_admin',
    'title'       => '_MI_SONGLIST_TIME_ADMIN',
    'description' => '_MI_SONGLIST_TIME_ADMIN_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 360,
    'options'     => [],

];

$modversion['config'][] = [
    'name'        => 'time_user',
    'title'       => '_MI_SONGLIST_TIME_USER',
    'description' => '_MI_SONGLIST_TIME_USER_DESC',
    'formtype'    => 'text',
    'valuetype'   => 'int',
    'default'     => 360,
    'options'     => [],
];

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
