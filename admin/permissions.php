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
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $GLOBALS['songlistModule']->getVar('dirname') . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

/**
 * Add category navigation to forum casscade structure
 * <ol>Special points:
 *  <li> Use negative values for category IDs to avoid conflict between category and forum
 *  <li> Disabled checkbox for categories to avoid unnecessary permission items for categories in forum permission table
 * </ol>
 *
 * Note: this is a __patchy__ solution. We should have a more extensible and flexible group permission management: not only for data architecture but also for management interface
 */
class forum_XoopsGroupPermForm extends \XoopsGroupPermForm
{
    /**
     * forum_XoopsGroupPermForm constructor.
     * @param        $title
     * @param        $modid
     * @param        $permname
     * @param        $permdesc
     * @param string $url
     */
    public function __construct($title, $modid, $permname, $permdesc, $url = '')
    {
        parent::__construct($title, $modid, $permname, $permdesc, $url);
    }

    /**
     * @return string
     */
    public function render()
    {
        // load all child ids for javascript codes
        foreach (array_keys($this->_itemTree) as $item_id) {
            $this->_itemTree[$item_id]['allchild'] = [];
            $this->_loadAllChildItemIds($item_id, $this->_itemTree[$item_id]['allchild']);
        }
        $grouppermHandler = xoops_getHandler('groupperm');
        $memberHandler    = xoops_getHandler('member');
        $glist            = $memberHandler->getGroupList();
        foreach (array_keys($glist) as $i) {
            // get selected item id(s) for each group
            $selected = $grouppermHandler->getItemIds($this->_permName, $i, $this->_modid);
            $ele      = new forum_XoopsGroupFormCheckBox($glist[$i], 'perms[' . $this->_permName . ']', $i, $selected);
            $ele->setOptionTree($this->_itemTree);
            $this->addElement($ele);
            unset($ele);
        }
        $tray = new \XoopsFormElementTray('');
        $tray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $tray->addElement(new \XoopsFormButton('', 'reset', _CANCEL, 'reset'));
        $this->addElement($tray);
        $ret      = '<h4>' . $this->getTitle() . '</h4>' . $this->_permDesc . '<br>';
        $ret      .= "<form name='" . $this->getName() . "' id='" . $this->getName() . "' action='" . $this->getAction() . "' method='" . $this->getMethod() . "'" . $this->getExtra() . ">\n<table width='100%' class='outer' cellspacing='1' valign='top'>\n";
        $elements = $this->getElements();
        $hidden   = '';
        foreach (array_keys($elements) as $i) {
            if (!is_object($elements[$i])) {
                $ret .= $elements[$i];
            } elseif (!$elements[$i]->isHidden()) {
                $ret .= "<tr valign='top' align='left'><td class='head'>" . $elements[$i]->getCaption();
                if ('' != $elements[$i]->getDescription()) {
                    $ret .= '<br><br><span style="font-weight: normal;">' . $elements[$i]->getDescription() . '</span>';
                }
                $ret .= "</td>\n<td class='even'>\n" . $elements[$i]->render() . "\n</td></tr>\n";
            } else {
                $hidden .= $elements[$i]->render();
            }
        }
        $ret .= "</table>$hidden</form>";
        $ret .= $this->renderValidationJS(true);

        return $ret;
    }
}

/**
 * Class forum_XoopsGroupFormCheckBox
 */
class forum_XoopsGroupFormCheckBox extends \XoopsGroupFormCheckBox
{
    /**
     * forum_XoopsGroupFormCheckBox constructor.
     * @param      $caption
     * @param      $name
     * @param      $groupId
     * @param null $values
     */
    public function __construct($caption, $name, $groupId, $values = null)
    {
        parent::__construct($caption, $name, $groupId, $values);
    }

    /**
     * Renders checkbox options for this group
     *
     * @return string
     * @access public
     */
    public function render()
    {
        $ret  = '<table class="outer"><tr><td class="odd"><table><tr>';
        $cols = 1;
        foreach ($this->_optionTree[0]['children'] as $topitem) {
            if ($cols > 4) {
                $ret  .= '</tr><tr>';
                $cols = 1;
            }
            $tree   = '<td valign="top">';
            $prefix = '';
            $this->_renderOptionTree($tree, $this->_optionTree[$topitem], $prefix);
            $ret .= $tree . '</td>';
            ++$cols;
        }
        $ret .= '</tr></table></td><td class="even">';
        foreach (array_keys($this->_optionTree) as $id) {
            if (!empty($id)) {
                $option_ids[] = "'" . $this->getName() . '[groups][' . $this->_groupId . '][' . $id . ']' . "'";
            }
        }
        $checkallbtn_id = $this->getName() . '[checkallbtn][' . $this->_groupId . ']';
        $option_ids_str = implode(', ', $option_ids);
        $ret            .= _ALL . ' <input id="' . $checkallbtn_id . '" type="checkbox" value="" onclick="var optionids = new Array(' . $option_ids_str . "); xoopsCheckAllElements(optionids, '" . $checkallbtn_id . "');\">";
        $ret            .= '</td></tr></table>';

        return $ret;
    }

    /**
     * @param string $tree
     * @param array  $option
     * @param string $prefix
     * @param array  $parentIds
     */
    public function _renderOptionTree(&$tree, $option, $prefix, $parentIds = [])
    {
        if ($option['id'] > 0):
            $tree .= $prefix . '<input type="checkbox" name="' . $this->getName() . '[groups][' . $this->_groupId . '][' . $option['id'] . ']" id="' . $this->getName() . '[groups][' . $this->_groupId . '][' . $option['id'] . ']" onclick="';
            foreach ($parentIds as $pid) {
                if ($pid <= 0) {
                    continue;
                }
                $parent_ele = $this->getName() . '[groups][' . $this->_groupId . '][' . $pid . ']';
                $tree       .= "var ele = xoopsGetElementById('" . $parent_ele . "'); if(ele.checked !== true) {ele.checked = this.checked;}";
            }
            foreach ($option['allchild'] as $cid) {
                $child_ele = $this->getName() . '[groups][' . $this->_groupId . '][' . $cid . ']';
                $tree      .= "var ele = xoopsGetElementById('" . $child_ele . "'); if(this.checked !== true) {ele.checked = false;}";
            }
            $tree .= '" value="1"';
            if (in_array($option['id'], $this->_value)) {
                $tree .= ' checked';
            }
            $tree .= '>'
                     . $option['name']
                     . '<input type="hidden" name="'
                     . $this->getName()
                     . '[parents]['
                     . $option['id']
                     . ']" value="'
                     . implode(':', $parentIds)
                     . '"><input type="hidden" name="'
                     . $this->getName()
                     . '[itemname]['
                     . $option['id']
                     . ']" value="'
                     . htmlspecialchars($option['name'], ENT_QUOTES | ENT_HTML5)
                     . "\"><br>\n";
        else:
            $tree .= $prefix . $option['name'] . '<input type="hidden" id="' . $this->getName() . '[groups][' . $this->_groupId . '][' . $option['id'] . "]\"><br>\n";
        endif;
        if (isset($option['children'])) {
            foreach ($option['children'] as $child) {
                if ($option['id'] > 0) {
                    array_push($parentIds, $option['id']);
                }
                $this->_renderOptionTree($tree, $this->_optionTree[$child], $prefix . '&nbsp;-', $parentIds);
            }
        }
    }
}

xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
$action    = isset($_REQUEST['action']) ? mb_strtolower($_REQUEST['action']) : '';
$module_id = $GLOBALS['songlistModule']->getVar('mid');
$perms     = array_map('trim', explode(',', FORUM_PERM_ITEMS));

switch ($action) {
    case 'template':
        $opform    = new \XoopsSimpleForm(_AM_SONGLIST_PERM_ACTION, 'actionform', 'permissions.php', 'get');
        $op_select = new \XoopsFormSelect('', 'action');
        $op_select->setExtra('onchange="document.forms.actionform.submit()"');
        $op_select->addOptionArray([
                                       'no'       => _SELECT,
                                       'template' => _AM_SONGLIST_PERM_TEMPLATE,
                                       'apply'    => _AM_SONGLIST_PERM_TEMPLATEAPP,
                                       'default'  => _AM_SONGLIST_PERM_SETBYGROUP,
                                   ]);
        $opform->addElement($op_select);
        $opform->display();

        $memberHandler       = xoops_getHandler('member');
        $glist               = $memberHandler->getGroupList();
        $elements            = [];
        $songlistpermHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Permission');
        $perm_template       = $songlistpermHandler->getTemplate($groupid = 0);
        foreach (array_keys($glist) as $i) {
            $selected   = !empty($perm_template[$i]) ? array_keys($perm_template[$i]) : [];
            $ret_ele    = '<tr align="left" valign="top"><td class="head">' . $glist[$i] . '</td>';
            $ret_ele    .= '<td class="even">';
            $ret_ele    .= '<table class="outer"><tr><td class="odd"><table><tr>';
            $ii         = 0;
            $option_ids = [];
            foreach ($perms as $perm) {
                ++$ii;
                if (0 == $ii % 5) {
                    $ret_ele .= '</tr><tr>';
                }
                $checked      = in_array('forum_' . $perm, $selected) ? ' checked' : '';
                $option_id    = $perm . '_' . $i;
                $option_ids[] = $option_id;
                $ret_ele      .= '<td><input name="perms[' . $i . '][' . 'forum_' . $perm . ']" id="' . $option_id . '" onclick="" value="1" type="checkbox"' . $checked . '>' . constant('_AM_SONGLIST_CAN_' . mb_strtoupper($perm)) . '<br></td>';
            }
            $ret_ele    .= '</tr></table></td><td class="even">';
            $ret_ele    .= _ALL . ' <input id="checkall[' . $i . ']" type="checkbox" value="" onclick="var optionids = new Array(' . implode(', ', $option_ids) . '); xoopsCheckAllElements(optionids, \'checkall[' . $i . ']\')">';
            $ret_ele    .= '</td></tr></table>';
            $ret_ele    .= '</td></tr>';
            $elements[] = $ret_ele;
        }
        $tray = new \XoopsFormElementTray('');
        $tray->addElement(new \XoopsFormHidden('action', 'template_save'));
        $tray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $tray->addElement(new \XoopsFormButton('', 'reset', _CANCEL, 'reset'));
        $ret = '<h4>' . _AM_SONGLIST_PERM_TEMPLATE . '</h4>' . _AM_SONGLIST_PERM_TEMPLATE_DESC . '<br><br><br>';
        $ret .= "<form name='template' id='template' method='post'>\n<table width='100%' class='outer' cellspacing='1'>\n";
        $ret .= implode("\n", $elements);
        $ret .= '<tr align="left" valign="top"><td class="head"></td><td class="even">';
        $ret .= $tray->render();
        $ret .= '</td></tr>';
        $ret .= '</table></form>';
        echo $ret;
        break;
    case 'template_save':
        $songlistpermHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Permission');
        $res                 = $songlistpermHandler->setTemplate($_POST['perms'], $groupid = 0);
        if ($res) {
            redirect_header('permissions.php?action=template', 2, _AM_SONGLIST_PERM_TEMPLATE_CREATED);
        } else {
            redirect_header('permissions.php?action=template', 2, _AM_SONGLIST_PERM_TEMPLATE_ERROR);
        }
        break;
    case 'apply':
        $songlistpermHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Permission');
        $perm_template       = $songlistpermHandler->getTemplate();
        if (null === $perm_template) {
            redirect_header('permissions.php?action=template', 2, _AM_SONGLIST_PERM_TEMPLATE);
        }

        $opform    = new \XoopsSimpleForm(_AM_SONGLIST_PERM_ACTION, 'actionform', 'permissions.php', 'get');
        $op_select = new \XoopsFormSelect('', 'action');
        $op_select->setExtra('onchange="document.forms.actionform.submit()"');
        $op_select->addOptionArray(['no' => _SELECT, 'template' => _AM_SONGLIST_PERM_TEMPLATE, 'apply' => _AM_SONGLIST_PERM_TEMPLATEAPP]);
        $opform->addElement($op_select);
        $opform->display();

        $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
        $categories      = $categoryHandler->getAllCats('', true, false, true);

        $GLOBALS['forumHandler'] = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Forum');
        $songlists               = $GLOBALS['forumHandler']->getForumsByCategory(0, '', false, false, true);
        $fm_options              = [];
        foreach (array_keys($categories) as $c) {
            $fm_options[-1 * $c] = '[' . $categories[$c]->getVar('cat_title') . ']';
            foreach (array_keys($songlists[$c]) as $f) {
                $fm_options[$f] = $songlists[$c][$f]['title'];
                if (!isset($songlists[$c][$f]['sub'])) {
                    continue;
                }
                foreach (array_keys($songlists[$c][$f]['sub']) as $s) {
                    $fm_options[$s] = '-- ' . $songlists[$c][$f]['sub'][$s]['title'];
                }
            }
        }
        unset($songlists, $categories);
        $fmform    = new \XoopsThemeForm(_AM_SONGLIST_PERM_TEMPLATEAPP, 'fmform', 'permissions.php', 'post', true);
        $fm_select = new \XoopsFormSelect(_AM_SONGLIST_PERM_FORUMS, 'forums', null, 10, true);
        $fm_select->addOptionArray($fm_options);
        $fmform->addElement($fm_select);
        $tray = new \XoopsFormElementTray('');
        $tray->addElement(new \XoopsFormHidden('action', 'apply_save'));
        $tray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $tray->addElement(new \XoopsFormButton('', 'reset', _CANCEL, 'reset'));
        $fmform->addElement($tray);
        $fmform->display();
        break;
    case 'apply_save':
        if (empty($_POST['forums'])) {
            break;
        }
        $songlistpermHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Permission');
        foreach ($_POST['forums'] as $songlist) {
            if ($songlist < 1) {
                continue;
            }
            $songlistpermHandler->applyTemplate($songlist, $module_id);
        }
        redirect_header('permissions.php', 2, _AM_SONGLIST_PERM_TEMPLATE_APPLIED);
        break;
    default:

        $opform    = new \XoopsSimpleForm(_AM_SONGLIST_PERM_ACTION, 'actionform', 'permissions.php', 'get');
        $op_select = new \XoopsFormSelect('', 'action');
        $op_select->setExtra('onchange="document.forms.actionform.submit()"');
        $op_select->addOptionArray([
                                       'no'       => _SELECT,
                                       'template' => _AM_SONGLIST_PERM_TEMPLATE,
                                       'apply'    => _AM_SONGLIST_PERM_TEMPLATEAPP,
                                       'default'  => _AM_SONGLIST_PERM_SETBYGROUP,
                                   ]);
        $opform->addElement($op_select);
        $opform->display();

        $GLOBALS['forumHandler'] = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Forum');
        $songlists               = $GLOBALS['forumHandler']->getForumsByCategory(0, '', false, false, true);
        $op_options              = ['category' => _AM_SONGLIST_CAT_ACCESS];
        $fm_options              = ['category' => ['title' => _AM_SONGLIST_CAT_ACCESS, 'item' => 'category_access', 'desc' => '', 'anonymous' => true]];
        foreach ($perms as $perm) {
            $op_options[$perm] = constant('_AM_SONGLIST_CAN_' . mb_strtoupper($perm));
            $fm_options[$perm] = ['title' => constant('_AM_SONGLIST_CAN_' . mb_strtoupper($perm)), 'item' => 'forum_' . $perm, 'desc' => '', 'anonymous' => true];
        }

        $op_keys = array_keys($op_options);
        $op      = isset($_GET['op']) ? mb_strtolower($_GET['op']) : (isset($_COOKIE['op']) ? mb_strtolower($_COOKIE['op']) : '');
        if (empty($op)) {
            $op = $op_keys[0];
            setcookie('op', isset($op_keys[1]) ? $op_keys[1] : '');
        } else {
            for ($i = 0, $iMax = count($op_keys); $i < $iMax; ++$i) {
                if ($op_keys[$i] == $op) {
                    break;
                }
            }
            setcookie('op', isset($op_keys[$i + 1]) ? $op_keys[$i + 1] : '');
        }

        $opform    = new \XoopsSimpleForm('', 'opform', 'permissions.php', 'get');
        $op_select = new \XoopsFormSelect('', 'op', $op);
        $op_select->setExtra('onchange="document.forms.opform.submit()"');
        $op_select->addOptionArray($op_options);
        $opform->addElement($op_select);
        $opform->display();

        $perm_desc = '';

        $form = new forum_XoopsGroupPermForm($fm_options[$op]['title'], $module_id, $fm_options[$op]['item'], $fm_options[$op]['desc'], 'admin/permissions.php', $fm_options[$op]['anonymous']);

        $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
        $categories      = $categoryHandler->getObjects(null, true);
        if ('category' === $op) {
            foreach (array_keys($categories) as $c) {
                $form->addItem($c, $categories[$c]->getVar('cat_title'));
            }
            unset($categories);
        } else {
            foreach (array_keys($categories) as $c) {
                $key_c = -1 * $c;
                $form->addItem($key_c, '<strong>[' . $categories[$c]->getVar('cat_title') . ']</strong>');
                foreach (array_keys($songlists[$c]) as $f) {
                    $form->addItem($f, $songlists[$c][$f]['title'], $key_c);
                    if (!isset($songlists[$c][$f]['sub'])) {
                        continue;
                    }
                    foreach (array_keys($songlists[$c][$f]['sub']) as $s) {
                        $form->addItem($s, '&rarr;' . $songlists[$c][$f]['sub'][$s]['title'], $f);
                    }
                }
            }
            unset($songlists, $categories);
        }
        $form->display();

        break;
}

echo chronolabs_inline(false);
xoops_cp_footer();
