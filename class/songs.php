<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class Songs
 * @package XoopsModules\Songlist
 */
class Songs extends \XoopsObject
{
    /**
     * Songs constructor.
     * @param null $fid
     */
    public function __construct($fid = null)
    {
        $this->initVar('sid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('gids', XOBJ_DTYPE_ARRAY, 0, false);
        $this->initVar('vcid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('aids', XOBJ_DTYPE_ARRAY, [], false);
        $this->initVar('abid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('songid', XOBJ_DTYPE_TXTBOX, null, false, 32);
        $this->initVar('traxid', XOBJ_DTYPE_TXTBOX, null, false, 32);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('lyrics', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('rank', XOBJ_DTYPE_DECIMAL, 0, false);
        $this->initVar('votes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tags', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('mp3', XOBJ_DTYPE_OTHER, null, false, 500);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @param bool $as_array
     * @return array|string
     */
    public function getForm($as_array = false)
    {
        return songlist_songs_get_form($this, $as_array);
    }

    /**
     * @param bool $extra
     * @return array
     */
    public function toArray($extra = true)
    {
        $ret = parent::toArray();

        $GLOBALS['myts'] = \MyTextSanitizer::getInstance();

        $ret['lyrics'] = $GLOBALS['myts']->displayTarea($this->getVar('lyrics'), true, true, true, true, true);

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

        $ret['url'] = $this->getURL();

        $ret['rank'] = number_format(($this->getVar('rank') > 0 && $this->getVar('votes') > 0 ? $this->getVar('rank') / $this->getVar('votes') : 0), 2) . _MI_SONGLIST_OFTEN;

        if (!empty($ret['mp3'])) {
            $ret['mp3'] = '<embed flashvars="playerID=1&amp;bg=0xf8f8f8&amp;leftbg=0x3786b3&amp;lefticon=0x78bee3&amp;rightbg=0x3786b3&amp;rightbghover=0x78bee3&amp;righticon=0x78bee3&amp;righticonhover=0x3786b3&amp;text=0x666666&amp;slider=0x3786b3&amp;track=0xcccccc&amp;border=0x666666&amp;loader=0x78bee3&amp;loop=no&amp;soundFile='
                          . $ret['mp3']
                          . "\" quality='high' menu='false' wmode='transparent' pluginspage='http://www.macromedia.com/go/getflashplayer' src='"
                          . XOOPS_URL
                          . "/images/form/player.swf'  width=290 height=24 type='application/x-shockwave-flash'></embed>";
        }

        if (file_exists($GLOBALS['xoops']->path('modules/tag/include/tagbar.php')) && $GLOBALS['songlistModuleConfig']['tags']) {
            require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
            $ret['tagbar'] = tagBar($this->getVar('sid'), $this->getVar('cid'));
        }

        $extrasHandler     = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Extras');
        $fieldHandler      = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Field');
        $visibilityHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Visibility');

        $extras = $extrasHandler->get($this->getVar('sid'));
        if ($extras) {
            if (is_object($GLOBALS['xoopsUser'])) {
                $fields_id = $visibilityHandler->getVisibleFields([], $GLOBALS['xoopsUser']->getGroups());
            } elseif (!is_object($GLOBALS['xoopsUser'])) {
                $fields_id = $visibilityHandler->getVisibleFields([], []);
            }

            if (count($fields_id) > 0) {
                $criteria = new \Criteria('field_id', '(' . implode(',', $fields_id) . ')', 'IN');
                $criteria->setSort('field_weight');
                $fields = $fieldHandler->getObjects($criteria, true);
                foreach ($fields as $id => $field) {
                    if (in_array($this->getVar('cid'), $field->getVar('cids'))) {
                        $ret['fields'][$id]['title'] = $field->getVar('field_title');
                        if (is_object($GLOBALS['xoopsUser'])) {
                            $ret['fields'][$id]['value'] = htmlspecialchars_decode($field->getOutputValue($GLOBALS['xoopsUser'], $extras));
                        } elseif (!is_object($GLOBALS['xoopsUser'])) {
                            $ret['fields'][$id]['value'] = htmlspecialchars_decode($extras->getVar($field->getVar('field_name')));
                        }
                    }
                }
            }
        }

        if (false === $extra) {
            return $ret;
        }

        if (0 != $this->getVar('cid')) {
            $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
            $category        = $categoryHandler->get($this->getVar('cid'));
            $ret['category'] = $category->toArray(false);
        }

        if (0 != count($this->getVar('gids'))) {
            $i            = 0;
            $genreHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
            $ret['genre'] = '';
            $genres       = $genreHandler->getObjects(new \Criteria('gid', '(' . implode(',', $this->getVar('gids')) . ')', 'IN'), true);
            foreach ($genres as $gid => $genre) {
                $ret['genre_array'][$gid] = $genre->toArray(false);
                ++$i;
                $ret['genre'] .= $genre->getVar('name') . ($i < count($genres) ? ', ' : '');
            }
        }
        if (0 != $this->getVar('vcid')) {
            $voiceHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Voice');
            $voice        = $voiceHandler->get($this->getVar('vcid'));
            $ret['voice'] = $voice->toArray(false);
        }

        if (0 != count($this->getVar('aids'))) {
            $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
            foreach ($this->getVar('aids') as $aid) {
                $artist                     = $artistsHandler->get($aid);
                $ret['artists_array'][$aid] = $artist->toArray(false);
            }
        }

        if (0 != $this->getVar('abid')) {
            $albumsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
            $albums        = $albumsHandler->get($this->getVar('abid'));
            $ret['album']  = $albums->toArray(false);
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        global $file, $op, $fct, $id, $value, $vcid, $gid, $cid, $start, $limit;
        if ($GLOBALS['songlistModuleConfig']['htaccess']) {
            return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/index/' . urlencode(str_replace([' ', chr(9)], '-', $this->getVar('title'))) . '/item-item-' . $this->getVar('sid') . $GLOBALS['songlistModuleConfig']['endofurl'];
        }

        return XOOPS_URL . '/modules/songlist/index.php?op=item&fct=item&id=' . $this->getVar('sid') . '&value=' . urlencode($value) . '&vcid=' . $vcid . '&gid=' . $gid . '&cid=' . $cid;
    }
}
