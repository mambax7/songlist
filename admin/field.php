<?php


include 'header.php';
xoops_cp_header();


$op = (!empty($_GET['op']) ? $_GET['op'] : (!empty($_POST['op']) ? $_POST['op'] : (!empty($_REQUEST['id']) ? "edit" : 'list')));
$field_handler = xoops_getmodulehandler('field', 'songlist');
switch( $op ) {
default:
case "list":
	$indexAdmin = new ModuleAdmin();
	echo $indexAdmin->addNavigation(basename(__FILE__));

	$fields = $field_handler->getObjects(NULL, false, false);

	$module_handler = xoops_gethandler('module');
	$modules = $module_handler->getObjects(null, true);

	$categories = array();
	$weights = array();
	
	$GLOBALS['categoryHandler'] = xoops_getmodulehandler('category');
	$criteria = new CriteriaCompo();
	$criteria->setSort('weight');
	if ($categorys = $GLOBALS['categoryHandler']->getObjects($criteria, true)) {
		unset($criteria);
	
		$categories[0] = array('cid' => 0, 'name' => _AM_SONGLIST_FIELDS_DEFAULT);
		if ( count($categorys) > 0 ) {
			foreach (array_keys($categorys) as $i ) {
				$categories[$categorys[$i]->getVar('cid')] = array('cid' => $categorys[$i]->getVar('cid'), 'name' => $categorys[$i]->getVar('name'));
			}
		}
		$GLOBALS['xoopsTpl']->assign('categories', $categories);
	}
	
	$valuetypes = array(XOBJ_DTYPE_ARRAY => _AM_SONGLIST_FIELDS_ARRAY,
						XOBJ_DTYPE_EMAIL => _AM_SONGLIST_FIELDS_EMAIL,
						XOBJ_DTYPE_INT => _AM_SONGLIST_FIELDS_INT,
						XOBJ_DTYPE_TXTAREA => _AM_SONGLIST_FIELDS_TXTAREA,
						XOBJ_DTYPE_TXTBOX => _AM_SONGLIST_FIELDS_TXTBOX,
						XOBJ_DTYPE_URL => _AM_SONGLIST_FIELDS_URL,
						XOBJ_DTYPE_OTHER => _AM_SONGLIST_FIELDS_OTHER,
						XOBJ_DTYPE_MTIME => _AM_SONGLIST_FIELDS_DATE);

	$fieldtypes = array('checkbox' => _AM_SONGLIST_FIELDS_CHECKBOX,
							'group' => _AM_SONGLIST_FIELDS_GROUP,
							'group_multi' => _AM_SONGLIST_FIELDS_GROUPMULTI,
							'language' => _AM_SONGLIST_FIELDS_LANGUAGE,
							'radio' => _AM_SONGLIST_FIELDS_RADIO,
							'select' => _AM_SONGLIST_FIELDS_SELECT,
							'select_multi' => _AM_SONGLIST_FIELDS_SELECTMULTI,
							'textarea' => _AM_SONGLIST_FIELDS_TEXTAREA,
							'dhtml' => _AM_SONGLIST_FIELDS_DHTMLTEXTAREA,
							'editor' => _AM_SONGLIST_FIELDS_EDITOR,
							'textbox' => _AM_SONGLIST_FIELDS_TEXTBOX,
							'timezone' => _AM_SONGLIST_FIELDS_TIMEZONE,
							'yesno' => _AM_SONGLIST_FIELDS_YESNO,
							'date' => _AM_SONGLIST_FIELDS_DATE,
							'datetime' => _AM_SONGLIST_FIELDS_DATETIME,
							'longdate' => _AM_SONGLIST_FIELDS_LONGDATE,
							'theme' => _AM_SONGLIST_FIELDS_THEME,
							'autotext' => _AM_SONGLIST_FIELDS_AUTOTEXT,
							'rank' => _AM_SONGLIST_FIELDS_RANK);

	foreach (array_keys($fields) as $i ) {
		$fields[$i]['canEdit'] = $fields[$i]['field_config'] || $fields[$i]['field_show'] || $fields[$i]['field_edit'];
		$fields[$i]['canDelete'] = $fields[$i]['field_config'];
		$fields[$i]['fieldtype'] = $fieldtypes[$fields[$i]['field_type']];
		$fields[$i]['valuetype'] = $valuetypes[$fields[$i]['field_valuetype']];
		$fieldcategories[$i][] = $fields[$i];
		$weights[$i] = $fields[$i]['field_weight'];
	}
	//sort fields order in categories
	foreach (array_keys($fields) as $i ) {
		array_multisort($weights[$i], SORT_ASC, array_keys($fieldcategories[$i]), SORT_ASC, $categories[$i]);
	}
	ksort($categories);
	$GLOBALS['xoopsTpl']->assign('fieldcategories', $fieldcategories);
	$GLOBALS['xoopsTpl']->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML() );
	$template_main = "songlist_cpanel_fieldlist.html";
	break;

case "new":

	$indexAdmin = new ModuleAdmin();
	echo $indexAdmin->addNavigation(basename(__FILE__));
	$obj = $field_handler->create();
	$form = songlist_getFieldForm($obj);
	$form->display();
	break;

case "edit":

	$indexAdmin = new ModuleAdmin();
	echo $indexAdmin->addNavigation(basename(__FILE__));
	$obj = $field_handler->get($_REQUEST['id']);
	if ( !$obj->getVar('field_config') && !$obj->getVar('field_show') && !$obj->getVar('field_edit')  ) { //If no configs exist
		redirect_header('field.php', 2, _AM_SONGLIST_FIELDS_FIELDNOTCONFIGURABLE);
	}
	$form = songlist_getFieldForm($obj);
	$form->display();
	break;

case "reorder":
	
	if ( !$GLOBALS['xoopsSecurity']->check()  ) {
		redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
	}

	if ( isset($_POST['field_ids']) && count($_POST['field_ids']) > 0 ) {
		$oldweight = $_POST['oldweight'];
		$oldcat = $_POST['oldcat'];
		$oldcategories = $_POST['oldcategories'];
		$categories = $_POST['categories'];
		$weight = $_POST['weight'];
		$ids = array();
		foreach ($_POST['field_ids'] as $field_id ) {
			if ( $oldweight[$field_id] != $weight[$field_id] || $oldcat[$field_id] != $category[$field_id]  || count($oldcategories[$field_id]) != count(array_unique(array_merge($categories[$field_id], $oldcategories[$field_id]))) ) {
				//if field has changed
				$ids[] = intval($field_id);
			}
		}
		if ( count($ids) > 0 ) {
			$errors = array();
			//if there are changed fields, fetch the fieldcategory objects
			$field_handler = xoops_getmodulehandler('field');
			$fields = $field_handler->getObjects(new Criteria('field_id', "(" . implode(',', $ids) . ")", "IN"), true);
			foreach ($ids as $i ) {
				$fields[$i]->setVar('field_weight', intval($weight[$i]) );
				$fields[$i]->setVar('cids', $categories[$i] );
				if ( !$field_handler->insert($fields[$i])  ) {
					$errors = array_merge($errors, $fields[$i]->getErrors() );
				}
			}
			if ( count($errors) == 0 ) {
				//no errors
				redirect_header('field.php', 2, sprintf(_AM_SONGLIST_FIELDS_SAVEDSUCCESS, _AM_SONGLIST_FIELDS_FIELDS) );
			} else {
				redirect_header('field.php', 3, implode('<br />', $errors) );
			}
		}
	}
	redirect_header('field.php', 2, sprintf(_AM_SONGLIST_FIELDS_SAVEDSUCCESS, _AM_SONGLIST_FIELDS_FIELDS) );
	break;

case "save":
	if ( !$GLOBALS['xoopsSecurity']->check()  ) {
		redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
	}
	$redirect_to_edit = false;
	if ( isset($_REQUEST['id'])  ) {
		$obj = $field_handler->get($_REQUEST['id']);
		if ( !$obj->getVar('field_config') && !$obj->getVar('field_show') && !$obj->getVar('field_edit')  ) { //If no configs exist
			redirect_header('fields.php', 2, _AM_SONGLIST_FIELDS_FIELDNOTCONFIGURABLE);
		}
	} else {
		$obj = $field_handler->create();
		$obj->setVar('field_name', $_REQUEST['field_name']);
		$obj->setVar('field_moduleid', $GLOBALS['songlistModule']->getVar('mid') );
		$obj->setVar('field_show', 1);
		$obj->setVar('field_edit', 1);
		$obj->setVar('field_config', 1);
		$redirect_to_edit = true;
	}
	$obj->setVar('field_title', $_REQUEST['field_title']);
	$obj->setVar('field_description', $_REQUEST['field_description']);
	if ( $obj->getVar('field_config')  ) {
		$obj->setVar('field_type', $_REQUEST['field_type']);
		if ( isset($_REQUEST['field_valuetype'])  ) {
			$obj->setVar('field_valuetype', $_REQUEST['field_valuetype']);
		}
		$options = $obj->getVar('field_options');
		
		if ( isset($_REQUEST['removeOptions']) && is_array($_REQUEST['removeOptions'])  ) {
			foreach ($_REQUEST['removeOptions'] as $index ) {
				unset($options[$index]);
			}
			$redirect_to_edit = true;
		}
		
		if ( !empty($_REQUEST['addOption'])  ) {
			foreach ($_REQUEST['addOption'] as $option ) {
				if ( empty($option['value']) ) continue;
				$options[$option['key']] = $option['value'];
				$redirect_to_edit = true;
			}
		}
		$obj->setVar('field_options', $options);
	}
	if ( $obj->getVar('field_edit')  ) {
		$required = isset($_REQUEST['field_required']) ? $_REQUEST['field_required'] : 0;
		$obj->setVar('field_required', $required); //0 = no, 1 = yes
		if ( isset($_REQUEST['field_maxlength'])  ) {
			$obj->setVar('field_maxlength', $_REQUEST['field_maxlength']);
		}
		if ( isset($_REQUEST['field_default'])  ) {
			$field_default = $obj->getValueForSave($_REQUEST['field_default']);
			//Check for multiple selections
			if ( is_array($field_default)  ) {
				$obj->setVar('field_default', serialize($field_default) );
			} else {
				$obj->setVar('field_default', $field_default);
			}
		}
	}

	$obj->setVar('field_weight', $_REQUEST['field_weight']);
	$obj->setVar('cids', $_REQUEST['cids']);

	if ( $field_handler->insert($obj)  ) {
	
		$groupperm_handler = xoops_gethandler('groupperm');

		$perm_arr = array();
		if ( $obj->getVar('field_show')  ) {
			$perm_arr[] = 'songlist_show';
			$perm_arr[] = 'songlist_visible';
		}
		if ( $obj->getVar('field_edit')  ) {
			$perm_arr[] = 'songlist_edit';
		}
		if ( $obj->getVar('field_edit') || $obj->getVar('field_show')  ) {
			$perm_arr[] = 'songlist_search';
		}
		if ( count($perm_arr) > 0 ) {
			foreach ($perm_arr as $perm ) {
				$criteria = new CriteriaCompo(new Criteria('gperm_name', $perm) );
				$criteria->add(new Criteria('gperm_itemid', intval($obj->getVar('field_id') )) );
				$criteria->add(new Criteria('gperm_modid', intval($GLOBALS['songlistModule']->getVar('mid') )) );
				if ( isset($_REQUEST[$perm]) && is_array($_REQUEST[$perm])  ) {
					$perms = $groupperm_handler->getObjects($criteria);
					if ( count($perms) > 0 ) {
						foreach (array_keys($perms) as $i ) {
							$groups[$perms[$i]->getVar('gperm_groupid')] = $perms[$i];
						}
					} else {
						$groups = array();
					}
					foreach ($_REQUEST[$perm] as $grouoid ) {
						$grouoid = intval($grouoid);
						if ( !isset($groups[$grouoid])  ) {
							$perm_obj = $groupperm_handler->create();
							$perm_obj->setVar('gperm_name', $perm);
							$perm_obj->setVar('gperm_itemid', intval($obj->getVar('field_id') ));
							$perm_obj->setVar('gperm_modid', $GLOBALS['songlistModule']->getVar('mid') );
							$perm_obj->setVar('gperm_groupid', $grouoid);
							$groupperm_handler->insert($perm_obj);
							unset($perm_obj);
						}
					}
					$removed_groups = array_diff(array_keys($groups), $_REQUEST[$perm]);
					if ( count($removed_groups) > 0 ) {
						$criteria->add(new Criteria('gperm_groupid', "(".implode(',', $removed_groups).")", "IN") );
						$groupperm_handler->deleteAll($criteria);
					}
					unset($groups);

				} else {
					$groupperm_handler->deleteAll($criteria);
				}
				unset($criteria);
			}
		}
		$url = $redirect_to_edit ? 'field.php?op=edit&amp;id=' . $obj->getVar('field_id') : 'field.php';
		redirect_header($url, 3, sprintf(_AM_SONGLIST_FIELDS_SAVEDSUCCESS, _AM_SONGLIST_FIELDS_FIELD) );
	}
	
	echo $obj->getHtmlErrors();
	$form = songlist_getFieldForm($obj);
	$form->display();
	break;

case "delete":
	$obj = $field_handler->get($_REQUEST['id']);
	if ( !$obj->getVar('field_config')  ) {
		redirect_header('index.php', 2, _AM_SONGLIST_FIELDS_FIELDNOTCONFIGURABLE);
	}
	if ( isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1 ) {
		if ( !$GLOBALS['xoopsSecurity']->check()  ) {
			redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors() ));
		}
		if ( $field_handler->delete($obj)  ) {
			redirect_header('field.php', 3, sprintf(_AM_SONGLIST_FIELDS_DELETEDSUCCESS, _AM_SONGLIST_FIELDS_FIELD) );
		} else {
			echo $obj->getHtmlErrors();
		}
	} else {
		xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_SONGLIST_FIELDS_RUSUREDEL, $obj->getVar('field_title') ));
	}
	break;
}

if ( isset($template_main)  ) {
	$GLOBALS['xoopsTpl']->display("db:{$template_main}");
}

xoops_cp_footer();
?>