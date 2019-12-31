<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class Utf8map
 * @package XoopsModules\Songlist
 */
class Utf8map extends \XoopsObject
{
    /**
     * Utf8map constructor.
     * @param null $fid
     */
    public function __construct($fid = null)
    {
        $this->initVar('utfid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('from', XOBJ_DTYPE_TXTBOX, null, false, 2);
        $this->initVar('to', XOBJ_DTYPE_TXTBOX, null, false, 2);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @param bool $as_array
     * @return array|string
     */
    public function getForm($as_array = false)
    {
        return songlist_utf8map_get_form($this, $as_array);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret  = parent::toArray();
        $form = $this->getForm(true);
        foreach ($form as $key => $element) {
            $ret['form'][$key] = $form[$key]->render();
        }
        foreach (['created', 'updated'] as $key) {
            if ($this->getVar($key) > 0) {
                $ret['form'][$key] = date(_DATESTRING, $this->getVar($key));
                $ret[$key]         = date(_DATESTRING, $this->getVar($key));
            }
        }

        return $ret;
    }
}
