<?php
	
	$module_handler = xoops_gethandler('module');
	$config_handler = xoops_gethandler('config');
	if (!isset($GLOBALS['songlistModule']))
		$GLOBALS['songlistModule'] = $module_handler->getByDirname('songlist');
	if (is_object($GLOBALS['songlistModule']))
		if (!isset($GLOBALS['songlistModuleConfig']))
			$GLOBALS['songlistModuleConfig'] = $config_handler->getConfigList($GLOBALS['songlistModule']->getVar('mid')); 
	
	require_once($GLOBALS['xoops']->path('class/xoopsformloader.php'));
	require_once($GLOBALS['xoops']->path('class/pagenav.php'));
	
	require_once('formselectalbum.php');
	require_once('formselectartist.php');
	require_once('formselectcategory.php');
	require_once('formselectgenre.php');
	require_once('formselectsinger.php');
	require_once('formselectsong.php');
	
	if (file_exists($GLOBALS['xoops']->path('/modules/tag/include/formtag.php')) && $GLOBALS['songlistModuleConfig']['tags'])
		include_once $GLOBALS['xoops']->path('/modules/tag/include/formtag.php');
?>