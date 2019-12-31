<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class Extras
 * @package XoopsModules\Songlist
 */
class Extras extends \XoopsObject
{
    public $handler;

    /**
     * Extras constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->initVar('sid', XOBJ_DTYPE_INT, null, true);
        $this->init($fields);
    }

    /**
     * Initiate variables
     * @param array $fields field information array of {@link \XoopsObjectsField} objects
     */
    public function init($fields)
    {
        if ($fields && is_array($fields)) {
            foreach (array_keys($fields) as $key) {
                $this->initVar($key, $fields[$key]->getVar('field_valuetype'), $fields[$key]->getVar('field_default', 'n'), $fields[$key]->getVar('field_required'), $fields[$key]->getVar('field_maxlength'));
            }
        }
    }
}
