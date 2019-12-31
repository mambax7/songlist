<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * @package   kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class FieldHandler extends \XoopsPersistableObjectHandler
{
    /**
     * FieldHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'songlist_field', Field::class, 'field_id', 'field_title');
    }

    /**
     * Read field information from cached storage
     *
     * @param bool $force_update read fields from database and not cached storage
     *
     * @return array
     */
    public function loadFields($force_update = false)
    {
        static $fields = [];
        if (!empty($force_update) || 0 == count($fields)) {
            $criteria = new \Criteria('field_id', 0, '!=');
            $criteria->setSort('field_weight');
            if (0 == $this->getCount($criteria)) {
                return false;
            }
            $field_objs = $this->getObjects($criteria);
            foreach (array_keys($field_objs) as $i) {
                $fields[$field_objs[$i]->getVar('field_name')] = $field_objs[$i];
            }
        }

        return $fields;
    }

    /**
     * save a profile field in the database
     *
     * @param \XoopsObject $obj   reference to the object
     * @param bool        $force whether to force the query execution despite security settings
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     * @internal param bool $checkObject check if the object is dirty and clean the attributes
     */
    public function insert(\XoopsObject $obj, $force = false)
    {
        $objectsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
        $obj->setVar('field_name', str_replace(' ', '_', $obj->getVar('field_name')));
        $obj->cleanVars();
        $defaultstring = '';
        switch ($obj->getVar('field_type')) {
            case 'datetime':
            case 'date':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $obj->setVar('field_maxlength', 10);
                break;
            case 'longdate':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_MTIME);
                break;
            case 'yesno':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_INT);
                $obj->setVar('field_maxlength', 1);
                break;
            case 'textbox':
                if (XOBJ_DTYPE_INT != $obj->getVar('field_valuetype')) {
                    $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                }
                break;
            case 'autotext':
                if (XOBJ_DTYPE_INT != $obj->getVar('field_valuetype')) {
                    $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                }
                break;
            case 'group_multi':
            case 'select_multi':
            case 'checkbox':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_ARRAY);
                break;
            case 'language':
            case 'timezone':
            case 'theme':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
                break;
            case 'dhtml':
            case 'textarea':
                $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTAREA);
                break;
        }

        if ('' == $obj->getVar('field_valuetype')) {
            $obj->setVar('field_valuetype', XOBJ_DTYPE_TXTBOX);
        }

        if (!in_array($obj->getVar('field_name'), $this->getPostVars())) {
            if ($obj->isNew()) {
                //add column to table
                $changetype = 'ADD';
            } else {
                //update column information
                $changetype = 'CHANGE `' . $obj->getVar('field_name', 'n') . '`';
            }
            $maxlengthstring = $obj->getVar('field_maxlength') > 0 ? '(' . $obj->getVar('field_maxlength') . ')' : '';
            $notnullstring   = ' NOT NULL';
            //set type
            switch ($obj->getVar('field_valuetype')) {
                default:
                case XOBJ_DTYPE_ARRAY:
                case XOBJ_DTYPE_UNICODE_ARRAY:
                    $type = 'mediumtext';
                    break;
                case XOBJ_DTYPE_UNICODE_EMAIL:
                case XOBJ_DTYPE_UNICODE_TXTBOX:
                case XOBJ_DTYPE_UNICODE_URL:
                case XOBJ_DTYPE_EMAIL:
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_URL:
                    $type = 'varchar';
                    // varchars must have a maxlength
                    if (!$maxlengthstring) {
                        //so set it to max if maxlength is not set - or should it fail?
                        $maxlengthstring = '(255)';
                        $obj->setVar('field_maxlength', 255);
                    }
                    //if ( $obj->getVar('field_default')  ) {
                    $defaultstring = ' DEFAULT ' . $this->db->quote($obj->cleanVars['field_default']);
                    //}
                    break;
                case XOBJ_DTYPE_INT:
                    $type = 'int';
                    if ($obj->getVar('field_default') || '' !== $obj->getVar('field_default')) {
                        $defaultstring = " DEFAULT '" . (int)$obj->cleanVars['field_default'] . "'";
                        $obj->setVar('field_default', (int)$obj->cleanVars['field_default']);
                    }
                    break;
                case XOBJ_DTYPE_DECIMAL:
                    $type = 'decimal(14,6)';
                    if ($obj->getVar('field_default') || '' !== $obj->getVar('field_default')) {
                        $defaultstring = " DEFAULT '" . (float)$obj->cleanVars['field_default'] . "'";
                        $obj->setVar('field_default', (float)$obj->cleanVars['field_default']);
                    }
                    break;
                case XOBJ_DTYPE_FLOAT:
                    $type = 'float(15,9)';
                    if ($obj->getVar('field_default') || '' !== $obj->getVar('field_default')) {
                        $defaultstring = " DEFAULT '" . (float)$obj->cleanVars['field_default'] . "'";
                        $obj->setVar('field_default', (float)$obj->cleanVars['field_default']);
                    }
                    break;
                case XOBJ_DTYPE_OTHER:
                case XOBJ_DTYPE_UNICODE_TXTAREA:
                case XOBJ_DTYPE_TXTAREA:
                    $type            = 'text';
                    $maxlengthstring = '';
                    $notnullstring   = '';
                    break;
                case XOBJ_DTYPE_MTIME:
                    $type            = 'date';
                    $maxlengthstring = '';
                    break;
            }

            $sql = 'ALTER TABLE `' . $objectsHandler->table . '` ' . $changetype . ' `' . $obj->cleanVars['field_name'] . '` ' . $type . $maxlengthstring . $notnullstring . $defaultstring;
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
     * @param \XoopsObject $obj reference to the object to delete
     * @param bool        $force
     * @return bool FALSE if failed.
     **/
    public function delete(\XoopsObject $obj, $force = false)
    {
        $objectsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
        // remove column from table
        $sql = 'ALTER TABLE ' . $objectsHandler->table . ' DROP `' . $obj->getVar('field_name', 'n') . '`';
        if ($this->db->query($sql)) {
            //change this to update the cached field information storage
            if (!parent::delete($obj, $force)) {
                return false;
            }

            if ($obj->getVar('field_show') || $obj->getVar('field_edit')) {
                $moduleHandler  = xoops_getHandler('module');
                $objects_module = $moduleHandler->getByDirname('profile');
                if (is_object($objects_module)) {
                    // Remove group permissions
                    $grouppermHandler = xoops_getHandler('groupperm');
                    $criteria         = new \CriteriaCompo(new \Criteria('gperm_modid', $objects_module->getVar('mid')));
                    $criteria->add(new \Criteria('gperm_itemid', $obj->getVar('field_id')));

                    return $grouppermHandler->deleteAll($criteria);
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
    public function getPostVars()
    {
        return [
            'sid',
            'cid',
            'gid',
            'aids',
            'abid',
            'songid',
            'traxid',
            'title',
            'lyrics',
            'hits',
            'rank',
            'votes',
            'tags',
            'created',
            'updated',
        ];
    }
}
