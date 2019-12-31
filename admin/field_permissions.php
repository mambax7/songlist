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

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));

$op = $op = (!empty($_GET['op']) ? $_GET['op'] : (!empty($_POST['op']) ? $_POST['op'] : 'edit'));

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

$perm_desc = '';
switch ($op) {
    case 'visibility':
        redirect_header('field_visibility.php', 0, _AM_SONGLIST_PROF_VISIBLE);
        //header("Location: visibility.php");
        break;
    case 'edit':
        $title_of_form = _AM_SONGLIST_PROF_EDITABLE;
        $perm_name     = 'songlist_edit';
        $restriction   = 'field_edit';
        $anonymous     = false;
        break;
    case 'post':
        $title_of_form = _AM_SONGLIST_PROF_POST;
        $perm_name     = 'songlist_post';
        $restriction   = '';
        $anonymous     = true;
        break;
    case 'search':
        $title_of_form = _AM_SONGLIST_PROF_SEARCH;
        $perm_name     = 'songlist_search';
        $restriction   = '';
        $anonymous     = true;
        break;
}

$module_id = $GLOBALS['songlistModule']->getVar('mid');
require_once $GLOBALS['xoops']->path('/class/xoopsform/grouppermform.php');
$form = new \XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/field_permissions.php', $anonymous);

if ('access' === $op) {
    $memberHandler = xoops_getHandler('member');
    $glist         = $memberHandler->getGroupList();
    foreach (array_keys($glist) as $i) {
        if (XOOPS_GROUP_ANONYMOUS != $i) {
            $form->addItem($i, $glist[$i]);
        }
    }
} else {
    $extrasHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
    $tempFields    = $extrasHandler->loadFields();
    if ($tempFields && is_array($tempFields)) {
        $fields = array_merge([], $tempFields);
        if ('search' !== $op) {
            foreach (array_keys($fields) as $i) {
                if ('' == $restriction || $fields[$i]->getVar($restriction)) {
                    $form->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
                }
            }
        } else {
            $searchable_types = [
                'textbox',
                'select',
                'radio',
                'yesno',
                'date',
                'datetime',
                'timezone',
                'language',
            ];
            foreach (array_keys($fields) as $i) {
                if (in_array($fields[$i]->getVar('field_type'), $searchable_types)) {
                    $form->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
                }
            }
        }
    }
}
$form->display();

xoops_cp_footer();
