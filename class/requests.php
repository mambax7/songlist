<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class Requests
 * @package XoopsModules\Songlist
 */
class Requests extends \XoopsObject
{
    /**
     * Requests constructor.
     * @param null $fid
     */
    public function __construct($fid = null)
    {
        $this->initVar('rid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('aid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('artist', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('album', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('lyrics', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('songid', XOBJ_DTYPE_TXTBOX, null, false, 32);
        $this->initVar('sid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @param bool $as_array
     * @return array|string
     */
    public function getForm($as_array = false)
    {
        return songlist_requests_get_form($this, $as_array);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret            = parent::toArray();
        $form           = $this->getForm(true);
        $form['songid'] = new \XoopsFormText('', $this->getVar('rid') . '[songid]', 11, 32);
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
