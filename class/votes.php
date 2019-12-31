<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class Votes
 * @package XoopsModules\Songlist
 */
class Votes extends \XoopsObject
{
    /**
     * Votes constructor.
     * @param null $fid
     */
    public function __construct($fid = null)
    {
        $this->initVar('vid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('sid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false, 64);
        $this->initVar('netaddy', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('rank', XOBJ_DTYPE_DECIMAL, 0, false);
    }

    /**
     * @param bool $as_array
     * @return array|string
     */
    public function getForm($as_array = false)
    {
        return songlist_votes_get_form($this, $as_array);
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

        $ret['rank'] = number_format($this->getVar('rank'), 2) . _MI_SONGLIST_OFTEN;

        return $ret;
    }
}
