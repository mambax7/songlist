<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */
require_once dirname(dirname(__DIR__)) . '/mainfile.php';

if (!defined('_CHARSET')) {
    define('_CHARSET', 'UTF-8');
}
if (!defined('_CHARSET_ISO')) {
    define('_CHARSET_ISO', 'ISO-8859-1');
}

$GLOBALS['myts'] = \MyTextSanitizer::getInstance();

$moduleHandler                   = xoops_getHandler('module');
$configHandler                   = xoops_getHandler('config');
$GLOBALS['songlistModule']       = $moduleHandler->getByDirname('songlist');
$GLOBALS['songlistModuleConfig'] = $configHandler->getConfigList($GLOBALS['songlistModule']->getVar('mid'));

ini_set('memory_limit', $GLOBALS['songlistModuleConfig']['memory_user'] . 'M');
set_time_limit($GLOBALS['songlistModuleConfig']['time_user']);

xoops_load('pagenav');
xoops_load('xoopslists');
xoops_load('xoopsformloader');
require_once $GLOBALS['xoops']->path('class' . DS . 'xoopsmailer.php');

xoops_loadLanguage('user');

require_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['songlistModule']->getVar('dirname') . '/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['songlistModule']->getVar('dirname') . '/include/songlist.object.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['songlistModule']->getVar('dirname') . '/include/songlist.form.php';

xoops_loadLanguage('main', 'songlist');

$GLOBALS['file']  = isset($_REQUEST['file']) ? $_REQUEST['file'] : mb_substr(basename($_SERVER['SCRIPT_NAME']), 0, -4);
$GLOBALS['op']    = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'item';
$GLOBALS['fct']   = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'list';
$GLOBALS['id']    = \Xmf\Request::getInt('id', 0, 'REQUEST');
$GLOBALS['value'] = isset($_REQUEST['value']) ? $_REQUEST['value'] : '%';
$GLOBALS['gid']   = \Xmf\Request::getInt('gid', 0, 'REQUEST');
$GLOBALS['vid']   = \Xmf\Request::getInt('vid', 0, 'REQUEST');
$GLOBALS['vcid']  = \Xmf\Request::getInt('vcid', 0, 'REQUEST');
$GLOBALS['cid']   = (((isset($_POST['cid']) ? $_POST['cid'] : isset($_GET['cid'])) ? $_GET['cid'] : isset($_SESSION['cid'])) ? $_SESSION['cid'] : 0);
$GLOBALS['start'] = \Xmf\Request::getInt('start', 0, 'REQUEST');
$GLOBALS['limit'] = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : $GLOBALS['songlistModuleConfig']['cols'] * $GLOBALS['songlistModuleConfig']['rows'];

if (!isset($_SESSION['cid'])) {
    $_SESSION['cid'] = $GLOBALS['cid'];
}
