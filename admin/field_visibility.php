<?php

/*
Module: Objects

Version: 3.23

Description: Object manager for WHMCS Billing

Author: Written by Simon Roberts aka. Wishcraft (simon@chronolabs.coop)

Owner: Frilogg

License: See docs - End User Licence.pdf
*/

include 'header.php';
xoops_cp_header();

 $op = (!empty($_GET['op']) ? $_GET['op'] : (!empty($_POST['op']) ? $_POST['op'] :  "visibility"));

include_once $GLOBALS['xoops']->path( "/class/xoopsformloader.php" );
$opform = new XoopsSimpleForm('', 'opform', 'field_permissions.php', "post");
$op_select = new XoopsFormSelect("", 'op', $op);
$op_select->setExtra('onchange="document.forms.opform.submit()"');
$op_select->addOption('visibility', _AM_SONGLIST_PROF_VISIBLE);
$op_select->addOption('post', _AM_SONGLIST_PROF_POST);
$op_select->addOption('edit', _AM_SONGLIST_PROF_EDITABLE);
//$op_select->addOption('search', _AM_SONGLIST_PROF_SEARCH);
$opform->addElement($op_select);
$opform->display();

$visibility_handler = xoops_getmodulehandler('visibility');
$field_handler = xoops_getmodulehandler('field');
$fields = $field_handler->getList();

if ( isset($_REQUEST['submit'])  ) {
	$visibility = $visibility_handler->create();
	$visibility->setVar('field_id', $_REQUEST['field_id']);
	$visibility->setVar('user_group', $_REQUEST['ug']);
	$visibility->setVar('profile_group', $_REQUEST['pg']);
	$visibility_handler->insert($visibility, true);
}
if ( $op == "del" ) {
	$criteria = new CriteriaCompo(new Criteria('field_id', intval($_REQUEST['field_id']) ));
	$criteria->add(new Criteria('user_group', intval($_REQUEST['ug']) ));
	$criteria->add(new Criteria('profile_group', intval($_REQUEST['pg']) ));
	$visibility_handler->deleteAll($criteria, true);
	redirect_header("field_visibility.php", 2, sprintf(_AM_SONGLIST_DELETEDSUCCESS, _AM_SONGLIST_PROF_VISIBLE) );
	exit();
}

$criteria = new CriteriaCompo();
$criteria->setGroupby("field_id, user_group, profile_group");
$visibilities = $visibility_handler->getAll($criteria);

$member_handler = xoops_gethandler('member');
$groups = $member_handler->getGroupList();
$groups[0] = _AM_SONGLIST_FIELDVISIBLETOALL;
asort($groups);

$GLOBALS['xoopsTpl']->assign('fields', $fields);
$GLOBALS['xoopsTpl']->assign('visibilities', $visibilities);
$GLOBALS['xoopsTpl']->assign('groups', $groups);

$add_form = new XoopsSimpleForm('', 'addform', 'field_visibility.php');

$sel_field = new XoopsFormSelect(_AM_SONGLIST_FIELDVISIBLE, 'field_id');
$sel_field->setExtra("style='width: 200px;'");
$sel_field->addOptionArray($fields);
$add_form->addElement($sel_field);

$sel_ug = new XoopsFormSelect(_AM_SONGLIST_FIELDVISIBLEFOR, 'ug');
$sel_ug->addOptionArray($groups);
$add_form->addElement($sel_ug);

unset($groups[XOOPS_GROUP_ANONYMOUS]);
$sel_pg = new XoopsFormSelect(_AM_SONGLIST_FIELDVISIBLEON, 'pg');
$sel_pg->addOptionArray($groups);
$add_form->addElement($sel_pg);

$add_form->addElement(new XoopsFormButton('', 'submit', _ADD, 'submit') );
$add_form->assign($GLOBALS['xoopsTpl']);

$GLOBALS['xoopsTpl']->display("db:songlist_cpanel_visibility.html");

xoops_cp_footer();
?>