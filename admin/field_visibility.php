<?php

/*
Module: Objects

Version: 3.23

Description: Object manager for WHMCS Billing

Author: Written by Simon Roberts aka. Wishcraft (simon@chronolabs.coop)

Owner: Frilogg

License: See docs - End User Licence.pdf
*/

require_once __DIR__ . '/header.php';
xoops_cp_header();

$op = (!empty($_GET['op']) ? $_GET['op'] : (!empty($_POST['op']) ? $_POST['op'] : 'visibility'));

require_once $GLOBALS['xoops']->path('/class/xoopsformloader.php');
$opform    = new \XoopsSimpleForm('', 'opform', 'field_permissions.php', 'post', true);
$op_select = new \XoopsFormSelect('', 'op', $op);
$op_select->setExtra('onchange="document.forms.opform.submit()"');
$op_select->addOption('visibility', _AM_SONGLIST_PROF_VISIBLE);
$op_select->addOption('post', _AM_SONGLIST_PROF_POST);
$op_select->addOption('edit', _AM_SONGLIST_PROF_EDITABLE);
//$op_select->addOption('search', _AM_SONGLIST_PROF_SEARCH);
$opform->addElement($op_select);
$opform->display();

$visibilityHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Visibility');
$fieldHandler      = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Field');
$fields            = $fieldHandler->getList();

if (\Xmf\Request::hasVar('submit', 'REQUEST')) {
    $visibility = $visibilityHandler->create();
    $visibility->setVar('field_id', $_REQUEST['field_id']);
    $visibility->setVar('user_group', $_REQUEST['ug']);
    $visibility->setVar('profile_group', $_REQUEST['pg']);
    $visibilityHandler->insert($visibility, true);
}
if ('del' === $op) {
    $criteria = new \CriteriaCompo(new \Criteria('field_id', \Xmf\Request::getInt('field_id', 0, 'REQUEST')));
    $criteria->add(new \Criteria('user_group', \Xmf\Request::getInt('ug', 0, 'REQUEST')));
    $criteria->add(new \Criteria('profile_group', \Xmf\Request::getInt('pg', 0, 'REQUEST')));
    $visibilityHandler->deleteAll($criteria, true);
    redirect_header('field_visibility.php', 2, sprintf(_AM_SONGLIST_DELETEDSUCCESS, _AM_SONGLIST_PROF_VISIBLE));
}

$criteria = new \CriteriaCompo();
$criteria->setGroupBy('field_id, user_group, profile_group');
$visibilities = $visibilityHandler->getAll($criteria);

$memberHandler = xoops_getHandler('member');
$groups        = $memberHandler->getGroupList();
$groups[0]     = _AM_SONGLIST_FIELDVISIBLETOALL;
asort($groups);

$GLOBALS['xoopsTpl']->assign('fields', $fields);
$GLOBALS['xoopsTpl']->assign('visibilities', $visibilities);
$GLOBALS['xoopsTpl']->assign('groups', $groups);

$add_form = new \XoopsSimpleForm('', 'addform', 'field_visibility.php');

$sel_field = new \XoopsFormSelect(_AM_SONGLIST_FIELDVISIBLE, 'field_id');
$sel_field->setExtra("style='width: 200px;'");
$sel_field->addOptionArray($fields);
$add_form->addElement($sel_field);

$sel_ug = new \XoopsFormSelect(_AM_SONGLIST_FIELDVISIBLEFOR, 'ug');
$sel_ug->addOptionArray($groups);
$add_form->addElement($sel_ug);

unset($groups[XOOPS_GROUP_ANONYMOUS]);
$sel_pg = new \XoopsFormSelect(_AM_SONGLIST_FIELDVISIBLEON, 'pg');
$sel_pg->addOptionArray($groups);
$add_form->addElement($sel_pg);

$add_form->addElement(new \XoopsFormButton('', 'submit', _ADD, 'submit'));
$add_form->assign($GLOBALS['xoopsTpl']);

$GLOBALS['xoopsTpl']->display('db:songlist_cpanel_visibility.tpl');

xoops_cp_footer();
