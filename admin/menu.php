<?php 
$module_handler = xoops_gethandler('module');
$config_handler = xoops_gethandler('config');
$GLOBALS['songlistModule'] = $module_handler->getByDirname('songlist');
$GLOBALS['songlistModuleConfig'] = $config_handler->getConfigList($GLOBALS['songlistModule']->getVar('mid')); 

$i=0;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_DASHBOARD;
$adminmenu[$i]['icon'] = '../../'.$GLOBALS['songlistModule']->getInfo('icons32').'/home.png';
$adminmenu[$i]['image'] = '../../'.$GLOBALS['songlistModule']->getInfo('icons32').'/home.png';
$adminmenu[$i]['link'] = "admin/dashboard.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_CATEGORY;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.category.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.category.png';
$adminmenu[$i]['link'] = "admin/category.php";
if ($GLOBALS['songlistModuleConfig']['voice']) {
	$i++;
	$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_VOICE;
	$adminmenu[$i]['icon'] = 'images/icons/32/songlist.voice.png';
	$adminmenu[$i]['image'] = 'images/icons/32/songlist.voice.png';
	$adminmenu[$i]['link'] = "admin/voice.php";
}
if ($GLOBALS['songlistModuleConfig']['album']) {
	$i++;
	$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_ALBUMS;
	$adminmenu[$i]['icon'] = 'images/icons/32/songlist.albums.png';
	$adminmenu[$i]['image'] = 'images/icons/32/songlist.albums.png';
	$adminmenu[$i]['link'] = "admin/albums.php";
}
if ($GLOBALS['songlistModuleConfig']['genre']) {
	$i++;
	$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_GENRE;
	$adminmenu[$i]['icon'] = 'images/icons/32/songlist.genre.png';
	$adminmenu[$i]['image'] = 'images/icons/32/songlist.genre.png';
	$adminmenu[$i]['link'] = "admin/genre.php";
}
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_ARTISTS;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.artists.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.artists.png';
$adminmenu[$i]['link'] = "admin/artists.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_SONGS;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.songs.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.songs.png';
$adminmenu[$i]['link'] = "admin/songs.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_VOTE;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.votes.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.votes.png';
$adminmenu[$i]['link'] = "admin/votes.php";
/*$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_PERMISSIONS;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.permissions.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.permissions.png';
$adminmenu[$i]['link'] = "admin/permissions.php";
*/
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_REQUESTS;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.requests.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.requests.png';
$adminmenu[$i]['link'] = "admin/requests.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_UTF8MAP;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.utf8map.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.utf8map.png';
$adminmenu[$i]['link'] = "admin/utf8map.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_IMPORT;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.import.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.import.png';
$adminmenu[$i]['link'] = "admin/import.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_FIELDS;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.fields.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.fields.png';
$adminmenu[$i]['link'] = "admin/field.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_FIELDSPERMS;
$adminmenu[$i]['icon'] = 'images/icons/32/songlist.field.permissions.png';
$adminmenu[$i]['image'] = 'images/icons/32/songlist.field.permissions.png';
$adminmenu[$i]['link'] = "admin/field_permissions.php";
$i++;
$adminmenu[$i]['title'] = _MI_SONGLIST_ADMENU_ABOUT;
$adminmenu[$i]['icon'] = '../../'.$GLOBALS['songlistModule']->getInfo('icons32').'/about.png';
$adminmenu[$i]['image'] = '../../'.$GLOBALS['songlistModule']->getInfo('icons32').'/about.png';
$adminmenu[$i]['link'] = "admin/about.php";
?>