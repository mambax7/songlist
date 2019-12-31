<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class Albums
 * @package XoopsModules\Songlist
 */
class Albums extends \XoopsObject
{
    /**
     * Albums constructor.
     * @param null $fid
     */
    public function __construct($fid = null)
    {
        $this->initVar('abid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('aids', XOBJ_DTYPE_ARRAY, [], false);
        $this->initVar('sids', XOBJ_DTYPE_ARRAY, [], false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('path', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('artists', XOBJ_DTYPE_INT, 0, false);
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
        return songlist_albums_get_form($this, $as_array);
    }

    /**
     * @param bool $extra
     * @return array
     */
    public function toArray($extra = true)
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
        $ret['picture'] = $this->getImage('image', false);
        $ret['rank']    = number_format(($this->getVar('rank') > 0 && $this->getVar('votes') > 0 ? $this->getVar('rank') / $this->getVar('votes') : 0), 2) . _MI_SONGLIST_OFTEN;
        $ret['url']     = $this->getURL(true);

        if (false === $extra) {
            return $ret;
        }

        if (0 != $this->getVar('cid')) {
            $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
            $category        = $categoryHandler->get($this->getVar('cid'));
            if (is_object($category)) {
                $ret['category'] = $category->toArray(false);
            }
        }

        if (0 != count($this->getVar('aids'))) {
            $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
            foreach ($this->getVar('aids') as $aid) {
                $artist = $artistsHandler->get($aid);
                if (is_object($artist)) {
                    $ret['artists_array'][$aid] = $artist->toArray(false);
                }
            }
        }

        if (0 != count($this->getVar('sids'))) {
            $songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
            $criteria     = new \Criteria('sid', '(' . implode(',', $this->getVar('sids')) . ')', 'IN');
            $criteria->setSort('`traxid`');
            $criteria->setOrder('ASC');
            foreach ($songsHandler->getObjects($criteria, true) as $sid => $song) {
                if (is_object($song)) {
                    $ret['songs_array'][$sid] = $song->toArray(false);
                }
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
        global $file, $op, $fct, $id, $value, $gid, $vid, $vcid, $cid, $start, $limit;
        if ($GLOBALS['songlistModuleConfig']['htaccess']) {
            if (0 != $id) {
                $artistHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
                $artist        = $artistHandler->get($id);
                if (is_object($artist) && !$artist->isNew()) {
                    return XOOPS_URL
                           . '/'
                           . $GLOBALS['songlistModuleConfig']['baseofurl']
                           . '/albums/'
                           . urlencode(str_replace([' ', chr(9)], '-', $artist->getVar('title')))
                           . '/'
                           . $start
                           . '-'
                           . $id
                           . '-'
                           . $op
                           . '-'
                           . $fct
                           . '-'
                           . $gid
                           . '-'
                           . $cid
                           . '/'
                           . urlencode($value)
                           . $GLOBALS['songlistModuleConfig']['endofurl'];
                }

                return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/albums/' . $start . '-' . $id . '-' . $op . '-' . $fct . '-' . $gid . '-' . $cid . '/' . urlencode($value) . $GLOBALS['songlistModuleConfig']['endofurl'];
            }

            return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/albums/' . $start . '-' . $id . '-' . $op . '-' . $fct . '-' . $gid . '-' . $cid . '/' . urlencode($value) . $GLOBALS['songlistModuleConfig']['endofurl'];
        }

        return XOOPS_URL . '/modules/songlist/albums.php?op=' . $op . '&fct=' . $fct . '&id=' . $id . '&value=' . urlencode($value) . '&gid=' . $gid . '&vid=' . $vid . '&cid=' . $cid . '&start=' . $start;
    }
}
