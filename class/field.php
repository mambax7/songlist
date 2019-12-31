<?php


defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class SonglistField extends XoopsObject
{
    function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT, null);
        $this->initVar('cids', XOBJ_DTYPE_ARRAY, array(0=>'0'), true);
        $this->initVar('field_type', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_valuetype', XOBJ_DTYPE_INT, null, true);
        $this->initVar('field_name', XOBJ_DTYPE_TXTBOX, null, true);
        $this->initVar('field_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('field_required', XOBJ_DTYPE_INT, 0); //0 = no, 1 = yes
        $this->initVar('field_maxlength', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_weight', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_default', XOBJ_DTYPE_TXTAREA, "");
        $this->initVar('field_notnull', XOBJ_DTYPE_INT, 1);
        $this->initVar('field_edit', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_show', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_config', XOBJ_DTYPE_INT, 0);
        $this->initVar('field_options', XOBJ_DTYPE_ARRAY, array() );
    }


	
    function SonglistField()
    {
        $this->__construct();
    }

    /**
     * Extra treatment dealing with non latin encoding
     * Tricky solution
     */
    function setVar($key, $value, $not_gpc = false)
    {
        if ($key == 'field_options' && is_array($value)) {
            foreach (array_keys($value) as $idx ) {
                $value[$idx] = base64_encode($value[$idx]);
            }
        }
        parent::setVar($key, $value, $not_gpc);
    }

    function getVar($key, $format = 's')
    {
        $value = parent::getVar($key, $format);
        if ($key == 'field_options' && !empty($value)) {
            foreach (array_keys($value) as $idx) {
                $value[$idx] = base64_decode($value[$idx]);
            }
        }
        return $value;
    }

    /**
    * Returns a {@link XoopsFormElement} for editing the value of this field
    *
    * @param XoopsUser $user {@link XoopsUser} object to edit the value of
    * @param ObjectsProfile $profile {@link ObjectsProfile} object to edit the value of
    *
    * @return XoopsFormElement
    **/
    function getEditElement($user, $profile)
    {
        $value = in_array($this->getVar('field_name'), $this->getPostVars() ) ? $user->getVar($this->getVar('field_name'), 'e') : $profile->getVar($this->getVar('field_name'), 'e');
        if (is_null($value)) {
            $value = $this->getVar('field_default');
        }
        $caption = $this->getVar('field_title');
        $caption = defined($caption) ? constant($caption) : $caption;
        $name = $this->getVar('field_name', 'e');
        $options = $this->getVar('field_options');
        if (is_array($options)) {
            //asort($options);

            foreach (array_keys($options) as $key) {
                $optval = defined($options[$key]) ? constant($options[$key]) : $options[$key];
                $optkey = defined($key) ? constant($key) : $key;
                unset($options[$key]);
                $options[$optkey] = $optval;
            }
        }
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        switch ($this->getVar('field_type')  ) {
            default:
            case "autotext":
                //autotext is not for editing
                $element = new XoopsFormLabel($caption, $this->getOutputValue($user, $profile));
                break;

            case "textbox":
                $element = new XoopsFormText($caption, $name, 35, $this->getVar('field_maxlength'), $value);
                break;

            case "textarea":
                $element = new XoopsFormTextArea($caption, $name, $value, 4, 30);
                break;

            case "dhtml":
                $element = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 30);
                break;

            case "editor":

				$editor_config['name'] = $name;
				$editor_config['editor'] = $GLOBALS['songlistModuleConfig']['editor'];
				$editor_config['value'] = $value;
				$editor_config['width'] = $GLOBALS['songlistModuleConfig']['editor_width'];
				$editor_config['height'] = $GLOBALS['songlistModuleConfig']['editor_height'];
				$element = new XoopsFormEditor($caption, $name, $editor_config);
                break;

            case "select":
                $element = new XoopsFormSelect($caption, $name, $value);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) {
                    $element->addOption('', _NONE);
                    //trabis
                    if ($this->getVar('field_required') == 1) {
                        $eltmsg = empty($caption) ? sprintf(_FORM_ENTER, $name) : sprintf( _FORM_ENTER, $caption);
                        $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
                        $element->customValidationCode[] = "\nvar hasSelected = false; var selectBox = myform.{$name};" .
                            "for (i = 0; i < selectBox.options.length; i++  ) { if ( selectBox.options[i].selected == true && selectBox.options[i].value != '' ) { hasSelected = true; break; } }" .
                            "if ( !hasSelected ) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
                    }
                }
                $element->addOptionArray($options);
                break;

            case "select_multi":
                $element = new XoopsFormSelect($caption, $name, $value, 5, true);
                $element->addOptionArray($options);
                break;

            case "radio":
                $element = new XoopsFormRadio($caption, $name, $value);
                $element->addOptionArray($options);
                break;

            case "checkbox":
                $element = new XoopsFormCheckBox($caption, $name, $value);
                $element->addOptionArray($options);
                break;

            case "yesno":
                $element = new XoopsFormRadioYN($caption, $name, $value);
                break;

            case "group":
                $element = new XoopsFormSelectGroup($caption, $name, true, $value);
                break;

            case "group_multi":
                $element = new XoopsFormSelectGroup($caption, $name, true, $value, 5, true);
                break;

            case "language":
                $element = new XoopsFormSelectLang($caption, $name, $value);
                break;

            case "date":
                $element = new XoopsFormTextDateSelect($caption, $name, 15, $value);
                break;

            case "longdate":
                $element = new XoopsFormTextDateSelect($caption, $name, 15, str_replace("-", "/", $value) );
                break;

            case "datetime":
                $element = new XoopsFormDatetime($caption, $name, 15, $value);
                break;

            case "list":
                $element = new XoopsFormSelectList($caption, $name, $value, 1, $options[0]);
                break;

            case "timezone":
                $element = new XoopsFormSelectTimezone($caption, $name, $value);
                $element->setExtra("style='width: 280px;'");
                break;

            case "rank":
                $element = new XoopsFormSelect($caption, $name, $value);

                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $ranks = XoopsLists::getUserRankList();
                $element->addOption(0, "--------------");
                $element->addOptionArray($ranks);
                break;

            case 'theme':
                $element = new XoopsFormSelect($caption, $name, $value);
                $element->addOption("0", _OBJS_MF_SITEDEFAULT);
                $handle = opendir(XOOPS_THEME_PATH . '/');
                $dirlist = array();
                while (false !== ($file = readdir($handle) ) ) {
                    if (is_dir(XOOPS_THEME_PATH . '/' . $file) && !preg_match("/^[.]{1,2}$/", $file) && strtolower($file) != 'cvs' ) {
                        if (file_exists(XOOPS_THEME_PATH . "/" . $file . "/theme.html") && in_array($file, $GLOBALS['xoopsConfig']['theme_set_allowed'])) {
                            $dirlist[$file] = $file;
                        }
                    }
                }
                closedir($handle);
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $element->addOptionArray($dirlist);
                }
                break;
        }
        if ($this->getVar('field_description') != "") {
            $element->setDescription($this->getVar('field_description') );
        }
        return $element;
    }

    /**
    * Returns a {@link XoopsFormElement} for editing the value of this field
    *
    * @param XoopsUser $user {@link XoopsUser} object to edit the value of
    * @param ObjectsProfile $profile {@link ObjectsProfile} object to edit the value of
    *
    * @return XoopsFormElement
    **/
    function getSearchElement()
    {
        $caption = $this->getVar('field_title');
        $caption = defined($caption) ? constant($caption) : $caption;
        $name = $this->getVar('field_name', 'e');
        $options = $this->getVar('field_options');
        if (is_array($options)) {
            //asort($options);

            foreach (array_keys($options) as $key) {
                $optval = defined($options[$key]) ? constant($options[$key]) : $options[$key];
                $optkey = defined($key) ? constant($key) : $key;
                unset($options[$key]);
                $options[$optkey] = $optval;
            }
        }
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        switch ($this->getVar('field_type')  ) {
            default:
            case "autotext":
                //autotext is not for editing
                $element = new XoopsFormLabel($caption, $this->getOutputValue($user, $profile));
                break;

            case "textbox":
                $element = new XoopsFormText($caption, $name, 35, $this->getVar('field_maxlength'), $value);
                break;

            case "textarea":
                $element = new XoopsFormTextArea($caption, $name, $value, 4, 30);
                break;

            case "dhtml":
                $element = new XoopsFormText($caption, $name, 35, 255, $value);
                break;

            case "select":
                $element = new XoopsFormSelect($caption, $name, $value);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                if (!in_array('', array_keys($options))) {
                    $element->addOption('', _NONE);
                    //trabis
                    if ($this->getVar('field_required') == 1) {
                        $eltmsg = empty($caption) ? sprintf(_FORM_ENTER, $name) : sprintf( _FORM_ENTER, $caption);
                        $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
                        $element->customValidationCode[] = "\nvar hasSelected = false; var selectBox = myform.{$name};" .
                            "for (i = 0; i < selectBox.options.length; i++  ) { if ( selectBox.options[i].selected == true && selectBox.options[i].value != '' ) { hasSelected = true; break; } }" .
                            "if ( !hasSelected ) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
                    }
                    //end
                }
                $element->addOptionArray($options);
                break;


            case "editor":

                $element = new XoopsFormText($caption, $name, 35, 255, $value);
                break;

            case "select_multi":
                $element = new XoopsFormSelect($caption, $name, $value, 5, true);
                $element->addOptionArray($options);
                break;

            case "radio":
                $element = new XoopsFormRadio($caption, $name, $value);
                $element->addOptionArray($options);
                break;

            case "checkbox":
                $element = new XoopsFormCheckBox($caption, $name, $value);
                $element->addOptionArray($options);
                break;

            case "yesno":
                $element = new XoopsFormRadioYN($caption, $name, $value);
                break;

            case "group":
                $element = new XoopsFormSelectGroup($caption, $name, true, $value);
                break;

            case "group_multi":
                $element = new XoopsFormSelectGroup($caption, $name, true, $value, 5, true);
                break;

            case "language":
                $element = new XoopsFormSelectLang($caption, $name, $value);
                break;

            case "date":
                $element = new XoopsFormTextDateSelect($caption, $name, 15, $value);
                break;

            case "longdate":
                $element = new XoopsFormTextDateSelect($caption, $name, 15, str_replace("-", "/", $value) );
                break;

            case "datetime":
                $element = new XoopsFormDatetime($caption, $name, 15, $value);
                break;

            case "list":
                $element = new XoopsFormSelectList($caption, $name, $value, 1, $options[0]);
                break;

            case "timezone":
                $element = new XoopsFormSelectTimezone($caption, $name, $value);
                $element->setExtra("style='width: 280px;'");
                break;

            case "rank":
                $element = new XoopsFormSelect($caption, $name, $value);

                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $ranks = XoopsLists::getUserRankList();
                $element->addOption(0, "--------------");
                $element->addOptionArray($ranks);
                break;

            case 'theme':
                $element = new XoopsFormSelect($caption, $name, $value);
                $element->addOption("0", _OBJS_MF_SITEDEFAULT);
                $handle = opendir(XOOPS_THEME_PATH . '/');
                $dirlist = array();
                while (false !== ($file = readdir($handle) ) ) {
                    if (is_dir(XOOPS_THEME_PATH . '/' . $file) && !preg_match("/^[.]{1,2}$/", $file) && strtolower($file) != 'cvs' ) {
                        if (file_exists(XOOPS_THEME_PATH . "/" . $file . "/theme.html") && in_array($file, $GLOBALS['xoopsConfig']['theme_set_allowed'])) {
                            $dirlist[$file] = $file;
                        }
                    }
                }
                closedir($handle);
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $element->addOptionArray($dirlist);
                }
                break;
        }
        if ($this->getVar('field_description') != "") {
            $element->setDescription($this->getVar('field_description') );
        }
        return $element;
    }

    /**
    * Returns a value for output of this field
    *
    * @param XoopsUser $user {@link XoopsUser} object to get the value of
    * @param ObjectsProfile $profile object to get the value of
    *
    * @return mixed
    **/
    function getOutputValue($user, $profile)
    {
        if (file_exists($file = $GLOBALS['xoops']->path('modules/objects/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php'))) {
            include_once $file;
        } else {
            include_once $GLOBALS['xoops']->path('modules/objects/language/english/modinfo.php');
        }

        $value = in_array($this->getVar('field_name'), $this->getPostVars() ) ? $user->getVar($this->getVar('field_name') ) : $profile->getVar($this->getVar('field_name'));

        switch ($this->getVar('field_type')  ) {
            default:
            case "textbox":
                if ( $this->getVar('field_name') == 'url' && $value != '') {
                     return '<a href="' . formatURL($value) . '" rel="external">' . $value . '</a>';
                   } else {
                     return $value;
                }
                break;
			case "editor":
            case "textarea":
            case "dhtml":
            case 'theme':
            case "language":
            case "list":
                return $value;
                break;

            case "select":
            case "radio":
                $options = $this->getVar('field_options');
                if (isset($options[$value])) {
                    $value = htmlspecialchars( defined($options[$value]) ? constant($options[$value]) : $options[$value]);
                } else {
                    $value = "";
                }
                return $value;
                break;

            case "select_multi":
            case "checkbox":
                $options = $this->getVar('field_options');
                $ret = array();
                if (count($options) > 0) {
                    foreach (array_keys($options) as $key) {
                        if (in_array($key, $value)) {
                            $$ret[$key] = htmlspecialchars( defined($options[$key]) ? constant($options[$key]) : $options[$key]);
                        }
                    }
                }
                return $ret;
                break;

            case "group":
                //change to retrieve groups and return name of group
                return $value;
                break;

            case "group_multi":
                //change to retrieve groups and return array of group names
                return "";
                break;

            case "longdate":
                //return YYYY/MM/DD format - not optimal as it is not using local date format, but how do we do that
                //when we cannot convert it to a UNIX timestamp?
                return str_replace("-", "/", $value);

            case "date":
                return formatTimestamp($value, 's');
                break;

            case "datetime":
                if (!empty($value)) {
                       return formatTimestamp($value, 'm');
                   } else {
                       return $value = _XFORUM_MI_DATENOTSET;
                   }
                break;

            case "autotext":
                $value = $user->getVar($this->getVar('field_name'), 'n'); //autotext can have HTML in it
                $value = str_replace("{X_UID}", $user->getVar("uid"), $value);
                $value = str_replace("{X_URL}", XOOPS_URL, $value );
                $value = str_replace("{X_UNAME}", $user->getVar("uname"), $value);
                return $value;
                break;

            case "rank":
                $userrank = $user->rank();
                $user_rankimage = "";
                if (isset($userrank['image']) && $userrank['image'] != "") {
                    $user_rankimage = '<img src="'.XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="' . $userrank['title'] . '" /><br />';
                }
                return $user_rankimage.$userrank['title'];
                break;

            case "yesno":
                return $value ? _YES : _NO;
                break;

            case "timezone":
                include_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $timezones = XoopsLists::getTimeZoneList();
                $value = empty($value) ? "0" : strval($value);
                return $timezones[str_replace('.0', '', $value)];
                break;
        }
    }

    /**
    * Returns a value ready to be saved in the database
    *
    * @param mixed $value Value to format
    *
    * @return mixed
    */
    function getValueForSave($value)
    {
        switch ($this->getVar('field_type')) {
            default:
            case "textbox":
            case "textarea":
            case "dhtml":
            case "yesno":
            case "timezone":
            case 'theme':
            case "language":
            case "list":
            case "select":
            case "radio":
            case "select_multi":
            case "checkbox":
            case "group":
            case "group_multi":
            case "longdate":
                return $value;

            case "date":
                if ($value != "") {
                    return strtotime($value);
                }
                return $value;
                break;

            case "datetime":
                if (!empty($value)) {
                    return strtotime($value['date']) + intval($value['time']);
                }
                return $value;
                break;
        }
    }

    /**
     * Get names of user variables
     *
     * @return array
     */
    function getPostVars()
    {
        $objects_handler = xoops_getmodulehandler('extras', 'songlist');
        return $objects_handler->getPostVars();
    }
}

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class SonglistFieldHandler extends XoopsPersistableObjectHandler
{
    function __construct($db)
    {
        parent::__construct($db, 'songlist_field', "SonglistField", "field_id", 'field_title');
    }

    /**
    * Read field information from cached storage
    *
    * @param bool   $force_update   read fields from database and not cached storage
    *
    * @return array
    */
    function loadFields($force_update = false)
    {
        static $fields = array();
        if (!empty($force_update) || count($fields) == 0) {
            $criteria = new Criteria('field_id', 0, "!=");
            $criteria->setSort('field_weight');
            if ($this->getCount($criteria)==0)
            	return false;
            $field_objs = $this->getObjects($criteria);
            foreach (array_keys($field_objs) as $i ) {
                $fields[$field_objs[$i]->getVar('field_name')] = $field_objs[$i];
            }
        }
        return $fields;
    }

    /**
    * save a profile field in the database
    *
    * @param object $obj reference to the object
    * @param bool $force whether to force the query execution despite security settings
    * @param bool $checkObject check if the object is dirty and clean the attributes
    * @return bool FALSE if failed, TRUE if already present and unchanged or successful
    */
    function insert($obj, $force = false)
    {
        $objects_handler = xoops_getmodulehandler('extras', 'songlist');
        $obj->setVar('field_name', str_replace(' ', '_', $obj->getVar('field_name')));
        $obj->cleanVars();
        $defaultstring = "";
        switch ($obj->getVar('field_type')  ) {
            case "datetime":
            case "date":
                $obj->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $obj->setVar('field_maxlength', 10);
                break;

            case "longdate":
                $obj->setVar('field_valuetype', XOBJ_DTYPE_MTIME);
                break;

            case "yesno":
                $obj->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $obj->setVar('field_maxlength', 1);
                break;

            case "textbox":
                if ($obj->getVar('field_valuetype') != XOBJ_DTYPE_INT) {
                    $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                }
                break;

            case "autotext":
                if ($obj->getVar('field_valuetype') != XOBJ_DTYPE_INT) {
                    $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                }
                break;

            case "group_multi":
            case "select_multi":
            case "checkbox":
                $obj->setVar('field_valuetype', XOBJ_DTYPE_ARRAY);
                break;

            case "language":
            case "timezone":
            case "theme":
                $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                break;

            case "dhtml":
            case "textarea":
                $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                break;
        }

        if ($obj->getVar('field_valuetype') == "") {
            $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
        }

        if (!in_array($obj->getVar('field_name'), $this->getPostVars())) {
            if ($obj->isNew()) {
                //add column to table
                $changetype = "ADD";
            } else {
                //update column information
                $changetype = "CHANGE `" . $obj->getVar('field_name', 'n') . "`";
            }
            $maxlengthstring = $obj->getVar('field_maxlength') > 0 ? "(" . $obj->getVar('field_maxlength') . ")" : "";
            $notnullstring = " NOT NULL";
            //set type
            switch ($obj->getVar('field_valuetype')) {
                default:
                case XOBJ_DTYPE_ARRAY:
                case XOBJ_DTYPE_UNICODE_ARRAY:
                    $type = "mediumtext";
                    break;
                case XOBJ_DTYPE_UNICODE_EMAIL:
                case XOBJ_DTYPE_UNICODE_TXTBOX:
                case XOBJ_DTYPE_UNICODE_URL:
                case XOBJ_DTYPE_EMAIL:
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_URL:
                    $type = "varchar";
                    // varchars must have a maxlength
                    if (!$maxlengthstring) {
                        //so set it to max if maxlength is not set - or should it fail?
                        $maxlengthstring = "(255)";
                        $obj->setVar('field_maxlength', 255);
                    }
                    //if ( $obj->getVar('field_default')  ) {
                        $defaultstring = " DEFAULT " . $this->db->quote($obj->cleanVars['field_default']);
                    //}
                    break;

                case XOBJ_DTYPE_INT:
                    $type = "int";
                    if ($obj->getVar('field_default') || $obj->getVar('field_default') !== '') {
                        $defaultstring = " DEFAULT '" . intval($obj->cleanVars['field_default']) . "'";
                        $obj->setVar('field_default', intval($obj->cleanVars['field_default']));
                    }
                    break;

                case XOBJ_DTYPE_DECIMAL:
                    $type = "decimal(14,6)";
                    if ($obj->getVar('field_default') || $obj->getVar('field_default') !== '') {
                        $defaultstring = " DEFAULT '" . doubleval($obj->cleanVars['field_default']) . "'";
                        $obj->setVar('field_default', doubleval($obj->cleanVars['field_default']));
                    }
                    break;

                case XOBJ_DTYPE_FLOAT:
                    $type = "float(15,9)";
                    if ($obj->getVar('field_default') || $obj->getVar('field_default') !== '') {
                        $defaultstring = " DEFAULT '" . floatval($obj->cleanVars['field_default']) . "'";
                        $obj->setVar('field_default', floatval($obj->cleanVars['field_default']));
                    }
                    break;

                case XOBJ_DTYPE_OTHER:
                case XOBJ_DTYPE_UNICODE_TXTAREA:
                case XOBJ_DTYPE_TXTAREA:
                    $type = "text";
                    $maxlengthstring = "";
                    $notnullstring = "";
                    break;

                case XOBJ_DTYPE_MTIME:
                    $type = "date";
                    $maxlengthstring = "";
                    break;
            }

            $sql = "ALTER TABLE `" . $objects_handler->table . "` " .
            $changetype . " `" . $obj->cleanVars['field_name'] . "` " . $type . $maxlengthstring . $notnullstring . $defaultstring;
            if (!$this->db->query($sql)) {
                return false;
            }
        }

        //change this to also update the cached field information storage
        $obj->setDirty();
        if (!parent::insert($obj, $force)) {
            return false;
        }
        return $obj->getVar('field_id');

    }

    /**
    * delete a profile field from the database
    *
    * @param object $obj reference to the object to delete
    * @param bool $force
    * @return bool FALSE if failed.
    **/
    function delete($obj, $force = false)
    {
        $objects_handler = xoops_getmodulehandler('extras', 'songlist');
        // remove column from table
        $sql = "ALTER TABLE " . $objects_handler->table . " DROP `" . $obj->getVar('field_name', 'n') . "`";
        if ($this->db->query($sql)) {
            //change this to update the cached field information storage
            if (!parent::delete($obj, $force)) {
                return false;
            }

            if ($obj->getVar('field_show') || $obj->getVar('field_edit')) {
                $module_handler = xoops_gethandler('module');
                $objects_module = $module_handler->getByDirname('profile');
                if (is_object($objects_module)) {
                    // Remove group permissions
                    $groupperm_handler = xoops_gethandler('groupperm');
                    $criteria = new CriteriaCompo(new Criteria('gperm_modid', $objects_module->getVar('mid')));
                    $criteria->add(new Criteria('gperm_itemid', $obj->getVar('field_id')));
                    return $groupperm_handler->deleteAll($criteria);
                }
            }
        }
        return false;
    }

    /**
     * Get array of standard variable names (song table)
     *
     * @return array
     */
    function getPostVars()
    {
        return array('sid', 'cid', 'gid', 'aids', 'abid', 'songid', 'traxid', 'title', 'lyrics', 'hits', 'rank', 'votes', 'tags',
 					 'created', 'updated');
    }
}
?>