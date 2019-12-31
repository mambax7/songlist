<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Category
 * @package XoopsModules\Songlist
 */
class Category extends \XoopsObject
{
    /**
     * Category constructor.
     * @param null $fid
     */
    public function __construct($fid = null)
    {
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('description', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('path', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('artists', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('albums', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('songs', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('rank', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('votes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @param bool $as_array
     * @return array|string
     */
    public function getForm($as_array = false)
    {
        return songlist_category_get_form($this, $as_array);
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
        $ret['picture']  = $this->getImage('image', false);
        $ret['rank']     = number_format(($this->getVar('rank') > 0 && $this->getVar('votes') > 0 ? $this->getVar('rank') / $this->getVar('votes') : 0), 2) . _MI_SONGLIST_OFTEN;
        $ret['url']      = $this->getURL();
        $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
        if ($categoryHandler->getCount(new \Criteria('pid', $this->getVar('cid')))) {
            foreach ($categoryHandler->getObjects(new \Criteria('pid', $this->getVar('cid')), true) as $cid => $cat) {
                $ret['subcategories'][$cid] = $cat->toArray();
            }
        }

        return $ret;
    }

    /**
     * @param string $field
     * @param bool   $local
     * @return bool|string
     */
    public function getImage($field = 'image', $local = false)
    {
        if ('' == $this->getVar($field)) {
            return false;
        }
        if (!file_exists($GLOBALS['xoops']->path($this->getVar('path') . $this->getVar($field)))) {
            return false;
        }
        if (false === $local) {
            return XOOPS_URL . '/' . str_replace(DS, '/', $this->getVar('path')) . $this->getVar($field);
        }

        return XOOPS_ROOT_PATH . DS . $this->getVar('path') . $this->getVar($field);
    }

    /**
     * @return string
     */
    public function getURL()
    {
        global $file, $op, $fct, $id, $value, $gid, $cid, $start, $limit;

        return XOOPS_URL . '/modules/songlist/' . $file . '.php?op=category&fct=set&id=' . $this->getVar('cid') . '&value=' . urlencode($file) . '&gid=' . $gid . '&cid=' . $cid;
    }
}
