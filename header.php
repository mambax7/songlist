<?php

// $Id: admin_header.php,v 4.03 2008/06/05 15:35:32 wishcraft Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.chronolabs.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License 2.0 as published by //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.chronolabs.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
	
	require_once (dirname(dirname(dirname(__FILE__))).'/mainfile.php');
	
	if (!defined('_CHARSET'))
		define ("_CHARSET","UTF-8");
	if (!defined('_CHARSET_ISO'))
		define ("_CHARSET_ISO","ISO-8859-1");
	
	$GLOBALS['myts'] = MyTextSanitizer::getInstance();
	
	$module_handler = xoops_gethandler('module');
	$config_handler = xoops_gethandler('config');
	$GLOBALS['songlistModule'] = $module_handler->getByDirname('songlist');
	$GLOBALS['songlistModuleConfig'] = $config_handler->getConfigList($GLOBALS['songlistModule']->getVar('mid')); 
		
	ini_set('memory_limit', $GLOBALS['songlistModuleConfig']['memory_user'].'M');
	set_time_limit($GLOBALS['songlistModuleConfig']['time_user']);
	
	xoops_load('pagenav');	
	xoops_load('xoopslists');
	xoops_load('xoopsformloader');
	include_once $GLOBALS['xoops']->path('class'.DS.'xoopsmailer.php');
	
	xoops_loadLanguage('user');
	
	include_once XOOPS_ROOT_PATH."/modules/".$GLOBALS['songlistModule']->getVar("dirname")."/include/functions.php";
	include_once XOOPS_ROOT_PATH."/modules/".$GLOBALS['songlistModule']->getVar("dirname")."/include/songlist.object.php";
	include_once XOOPS_ROOT_PATH."/modules/".$GLOBALS['songlistModule']->getVar("dirname")."/include/songlist.form.php";

	xoops_loadLanguage('main', 'songlist');
	
	$GLOBALS['file']=isset($_REQUEST['file'])?$_REQUEST['file']:substr(basename($_SERVER['PHP_SELF']),0,strlen(basename($_SERVER['PHP_SELF']))-4);
	$GLOBALS['op']=isset($_REQUEST['op'])?$_REQUEST['op']:'item';
	$GLOBALS['fct']=isset($_REQUEST['fct'])?$_REQUEST['fct']:'list';
	$GLOBALS['id']=isset($_REQUEST['id'])?$_REQUEST['id']:0;
	$GLOBALS['value']=isset($_REQUEST['value'])?$_REQUEST['value']:'%';
	$GLOBALS['gid']=isset($_REQUEST['gid'])?$_REQUEST['gid']:0;
	$GLOBALS['vid']=isset($_REQUEST['vid'])?$_REQUEST['vid']:0;
	$GLOBALS['vcid']=isset($_REQUEST['vcid'])?$_REQUEST['vcid']:0;
	$GLOBALS['cid']=(((isset($_POST['cid'])?$_POST['cid']:isset($_GET['cid']))?$_GET['cid']:isset($_SESSION['cid']))?$_SESSION['cid']:0);
	$GLOBALS['start']=isset($_REQUEST['start'])?$_REQUEST['start']:0;
	$GLOBALS['limit']=isset($_REQUEST['limit'])?$_REQUEST['limit']:$GLOBALS['songlistModuleConfig']['cols']*$GLOBALS['songlistModuleConfig']['rows'];
	
	if (!isset($_SESSION['cid']))
		$_SESSION['cid'] = $GLOBALS['cid'];
		
?>