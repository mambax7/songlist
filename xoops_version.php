<?php
error_reporting(E_ALL);

$module_handler = xoops_gethandler('module');
$config_handler = xoops_gethandler('config');
$GLOBALS['songlistModule'] = $module_handler->getByDirname('songlist');
if (is_object($GLOBALS['songlistModule']))
	$GLOBALS['songlistModuleConfig'] = $config_handler->getConfigList($GLOBALS['songlistModule']->getVar('mid')); 

// $Id: xoops_version.php,v 4.04 2008/06/05 15:35:59 wishcraft Exp $

$modversion['name'] = _MI_SONGLIST_NAME;
$modversion['version'] = 1.14;
$modversion['description'] = _MI_SONGLIST_DESC;
$modversion['credits'] = "Orginally Written by wishcraft, Testing Phil and Michael Beck";
$modversion['author'] = "Simon Roberts (wishcraft)";
$modversion['license'] = "GNU General Public License (GPL) see LICENSE";
$modversion['image'] = "images/songlist_slogo.png";
$modversion['dirname'] = "songlist";
$modversion['releasedate'] = "Thursday, 05th April, 2012";
$modversion['module_status'] = "Stable";
$modversion['website'] = "www.chronolabs.coop";

$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] = 'Frameworks/moduleclasses/icons/32';

$modversion['release_info'] = "Stable 2012/08/16";
$modversion['release_file'] = XOOPS_URL."/modules/xforum/docs/changelog.txt";
$modversion['release_date'] = "2012/08/16";

$modversion['author_realname'] = "Wishcraft";
$modversion['author_website_url'] = "http://www.chronolabs.coop";
$modversion['author_website_name'] = "Chronolabs";
$modversion['author_email'] = "simon@chronolabs.coop";
$modversion['status_version'] = "1.00";

$modversion['warning'] = "For XOOPS 2.5.7.2 or later";
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.7.2';

$modversion['demo_site_url'] = "http://xoops.demo.chronolabs.coop/";
$modversion['demo_site_name'] = "Chronolabs";
$modversion['support_site_url'] = "http://www.chronolabs.coop/";
$modversion['support_site_name'] = "Chronolabs";
$modversion['submit_feature'] = "http://www.chronolabs.coop/";
$modversion['submit_bug'] = "http://www.chronolabs.coop/";

// Sql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "songlist_albums";
$modversion['tables'][1] = "songlist_artists";
$modversion['tables'][2] = "songlist_category";
$modversion['tables'][3] = "songlist_extra";
$modversion['tables'][4] = "songlist_field";
$modversion['tables'][5] = "songlist_genre";
$modversion['tables'][6] = "songlist_voice";
$modversion['tables'][7] = "songlist_requests";
$modversion['tables'][8] = "songlist_songs";
$modversion['tables'][9] = "songlist_utf8map";
$modversion['tables'][10] = "songlist_visibility";
$modversion['tables'][11] = "songlist_votes";

// Admin things
$modversion['system_menu'] = 1;
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/dashboard.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu
$modversion['hasMain'] = 1;

//install
//$modversion['onInstall'] = 'include/module.php';

//update things
$modversion['onUpdate'] = 'include/update.php';

//module css
$modversion['css'] = 'css/songlist.css';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "songlist_search";

// Smarty
$modversion['use_smarty'] = 1;

// Templates
$i=0;
$modversion['templates'][$i]['file'] = 'songlist_category_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_category_item.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_songs_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_songs_item.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_songs_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_requests_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_search_search.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_search_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_albums_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_albums_item.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_albums_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_artists_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_artists_item.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_artists_index.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_search_results.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_alpha_browse.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_albums_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_albums_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_artists_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_artists_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_category_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_category_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_fieldlist.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_genre_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_genre_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_voice_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_voice_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_import_actiona.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_import_actionb.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_requests_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_requests_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_songs_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_songs_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_utf8map_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_utf8map_list.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_visibility.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_votes_edit.html';
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = 'songlist_cpanel_votes_list.html';
$modversion['templates'][$i]['description'] = '';

// Submenus
$i=0;
$modversion['sub'][$i]['name'] = _MI_SONGLIST_MENU_ARTISTS;
$modversion['sub'][$i]['url'] = "artists.php";
$i++;
if (isset($GLOBALS['songlistModuleConfig']['album'])) {
	if ($GLOBALS['songlistModuleConfig']['album']) {
		$modversion['sub'][$i]['name'] = _MI_SONGLIST_MENU_ALBUMS;
		$modversion['sub'][$i]['url'] = "albums.php";
		$i++;
	}
}		
$modversion['sub'][$i]['name'] = _MI_SONGLIST_MENU_SEARCH;
$modversion['sub'][$i]['url'] = "search.php";
$i++;
$modversion['sub'][$i]['name'] = _MI_SONGLIST_MENU_REQUEST;
$modversion['sub'][$i]['url'] = "request.php";
$i++;

$i=0;
// Blocks
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_artist.php",
	'name' => _MI_SONGLIST_BLOCK_TOP_ARTIST,
	'description' => "Shows top artist",
	'show_func' => "b_songlist_popular_artist_show",
	'options' => "",
	'edit_func' => "b_songlist_popular_artist_edit",
	'template' => 'songlist_popular_artist.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_artists.php",
	'name' => _MI_SONGLIST_BLOCK_POPULAR_ARTISTS,
	'description' => "Shows popular artists",
	'show_func' => "b_songlist_popular_artists_show",
	'options' => "6",
	'edit_func' => "b_songlist_popular_artists_edit",
	'template' => 'songlist_popular_artists.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_album.php",
	'name' => _MI_SONGLIST_BLOCK_TOP_ALBUM,
	'description' => "Shows top album",
	'show_func' => "b_songlist_popular_album_show",
	'options' => "",
	'edit_func' => "b_songlist_popular_album_edit",
	'template' => 'songlist_popular_album.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_albums.php",
	'name' => _MI_SONGLIST_BLOCK_POPULAR_ALBUMS,
	'description' => "Shows popular albums",
	'show_func' => "b_songlist_popular_albums_show",
	'options' => "6",
	'edit_func' => "b_songlist_popular_albums_edit",
	'template' => 'songlist_popular_albums.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_genre.php",
	'name' => _MI_SONGLIST_BLOCK_TOP_GENRE,
	'description' => "Shows top genre",
	'show_func' => "b_songlist_popular_genre_show",
	'options' => "",
	'edit_func' => "b_songlist_popular_genre_edit",
	'template' => 'songlist_popular_genre.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_genres.php",
	'name' => _MI_SONGLIST_BLOCK_POPULAR_GENRES,
	'description' => "Shows popular genres",
	'show_func' => "b_songlist_popular_genres_show",
	'options' => "6",
	'edit_func' => "b_songlist_popular_genres_edit",
	'template' => 'songlist_popular_genres.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_song.php",
	'name' => _MI_SONGLIST_BLOCK_TOP_SONG,
	'description' => "Shows top song",
	'show_func' => "b_songlist_popular_song_show",
	'options' => "",
	'edit_func' => "b_songlist_popular_song_edit",
	'template' => 'songlist_popular_song.html');

$i++;
$modversion['blocks'][$i] = array(
	'file' => "songlist_popular_songs.php",
	'name' => _MI_SONGLIST_BLOCK_POPULAR_SONGS,
	'description' => "Shows popular songs",
	'show_func' => "b_songlist_popular_songs_show",
	'options' => "6",
	'edit_func' => "b_songlist_popular_songs_edit",
	'template' => 'songlist_popular_songs.html');

$i=0;
xoops_load('XoopsEditorHandler');
$editor_handler = XoopsEditorHandler::getInstance();
foreach ($editor_handler->getList(false) as $id => $val)
	$options[$val] = $id;
	
$i++;
$modversion['config'][$i]['name'] = 'editor';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_EDITORS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_EDITORS_DESC";
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'tinymce';
$modversion['config'][$i]['options'] = $options;

$i++;
$modversion['config'][$i]['name'] = 'salt';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_SALT";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_SALT_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
mt_srand(microtime(true));
$modversion['config'][$i]['default'] = (mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'').(mt_rand(0,4)!=2?chr(mt_rand(32,190)):'');
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'cols';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_COLS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_COLS_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 4;
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'rows';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_ROWS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_ROWS_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 4;
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'email';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_EMAIL";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_EMAIL_DESC";
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = $GLOBALS['xoopsConfig']['adminmail'];
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'mp3_filesize';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_MP3FILESIZE";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_MP3FILESIZE_DESC";
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '195035100';

$i++;
$modversion['config'][$i]['name'] = 'mp3_mimetype';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_MP3MIMETYPE";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_MP3MIMETYPE_DESC";
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'audio/mpeg|audio/x-mpeg|audio/mp3|audio/x-mp3|audio/mpeg3|audio/x-mpeg3|audio/mpg|audio/x-mpg|audio/x-mpegaudio';

$i++;
$modversion['config'][$i]['name'] = 'mp3_extensions';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_MP3EXTENSIONS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_MP3EXTENSIONS_DESC";
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'mp3';

$i++;
$modversion['config'][$i]['name'] = 'filesize_upload';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_FILESIZEUPLD";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_FILESIZEUPLD_DESC";
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1950351';

$i++;
$modversion['config'][$i]['name'] = 'allowed_mimetype';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_ALLOWEDMIMETYPE";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_ALLOWEDMIMETYPE_DESC";
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'image/gif|image/pjpeg|image/jpeg|image/x-png|image/png';

$i++;
$modversion['config'][$i]['name'] = 'allowed_extensions';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_ALLOWEDEXTENSIONS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_ALLOWEDEXTENSIONS_DESC";
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'gif|pjpeg|jpeg|jpg|png';

$i++;
$modversion['config'][$i]['name'] = 'upload_areas';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_UPLOADAREAS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_UPLOADAREAS_DESC";
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'uploads'.DS.'songlist'.DS;
$modversion['config'][$i]['options'] = array(
												'uploads'.DS => 'uploads'.DS,
												'uploads'.DS.'songlist'.DS => 'uploads'.DS.'songlist'.DS
										);

$i++;
$modversion['config'][$i]['name'] = 'album';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_ALBUM";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_ALBUM_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'genre';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_GENRE";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_GENRE_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'voice';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_VOICE";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_VOICE_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'lyrics';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_LYRICS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_LYRICS_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

/*
$i++;
$modversion['config'][$i]['name'] = 'singer';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_SINGER";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_SINGER_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';
*/

$i++;
$modversion['config'][$i]['name'] = 'htaccess';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_HTACCESS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_HTACCESS_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'baseofurl';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_HTACCESS_BASEOFURL";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_HTACCESS_BASEOFURL_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'songlist';

$i++;
$modversion['config'][$i]['name'] = 'endofurl';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_HTACCESS_ENDOFURL";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_HTACCESS_ENDOFURL_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '.html';

$i++;
$modversion['config'][$i]['name'] = 'tags';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_TAGS";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_TAGS_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'force_jquery';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_FORCE_JQUERY";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_FORCE_JQUERY_DESC";
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = true;

$i++;
$modversion['config'][$i]['name'] = 'memory_admin';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_MEMORY_ADMIN";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_MEMORY_ADMIN_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 128;
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'memory_user';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_MEMORY_USER";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_MEMORY_USER_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 128;
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'time_admin';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_TIME_ADMIN";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_TIME_ADMIN_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 360;
$modversion['config'][$i]['options'] = array();

$i++;
$modversion['config'][$i]['name'] = 'time_user';
$modversion['config'][$i]['title'] = "_MI_SONGLIST_TIME_USER";
$modversion['config'][$i]['description'] = "_MI_SONGLIST_TIME_USER_DESC";
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 360;
$modversion['config'][$i]['options'] = array();
?>