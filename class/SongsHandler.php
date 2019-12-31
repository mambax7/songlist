<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class SongsHandler
 * @package XoopsModules\Songlist
 */
class SongsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * SongsHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $moduleHandler                   = xoops_getHandler('module');
        $configHandler                   = xoops_getHandler('config');
        $GLOBALS['songlistModule']       = $moduleHandler->getByDirname('songlist');
        $GLOBALS['songlistModuleConfig'] = $configHandler->getConfigList($GLOBALS['songlistModule']->getVar('mid'));

        parent::__construct($db, 'songlist_songs', Songs::class, 'sid', 'title');
    }

    /**
     * @return array
     */
    public function filterFields()
    {
        return ['sid', 'cid', 'mp3', 'gid', 'vcid', 'aids', 'abid', 'songid', 'title', 'lyrics', 'hits', 'rank', 'votes', 'tags', 'created', 'updated'];
    }

    /**
     * @param $filter
     * @return \CriteriaCompo
     */
    public function getFilterCriteria($filter)
    {
        $parts    = explode('|', $filter);
        $criteria = new \CriteriaCompo();
        foreach ($parts as $part) {
            $var = explode(',', $part);
            if (!empty($var[1]) && !is_numeric($var[0])) {
                $object = $this->create();
                if (XOBJ_DTYPE_TXTBOX == $object->vars[$var[0]]['data_type']
                    || XOBJ_DTYPE_TXTAREA == $object->vars[$var[0]]['data_type']) {
                    $criteria->add(new \Criteria('`' . $var[0] . '`', '%' . $var[1] . '%', (isset($var[2]) ? $var[2] : 'LIKE')));
                } elseif (XOBJ_DTYPE_INT == $object->vars[$var[0]]['data_type']
                          || XOBJ_DTYPE_DECIMAL == $object->vars[$var[0]]['data_type']
                          || XOBJ_DTYPE_FLOAT == $object->vars[$var[0]]['data_type']) {
                    $criteria->add(new \Criteria('`' . $var[0] . '`', $var[1], (isset($var[2]) ? $var[2] : '=')));
                } elseif (XOBJ_DTYPE_ENUM == $object->vars[$var[0]]['data_type']) {
                    $criteria->add(new \Criteria('`' . $var[0] . '`', $var[1], (isset($var[2]) ? $var[2] : '=')));
                } elseif (XOBJ_DTYPE_ARRAY == $object->vars[$var[0]]['data_type']) {
                    $criteria->add(new \Criteria('`' . $var[0] . '`', '%"' . $var[1] . '";%', (isset($var[2]) ? $var[2] : 'LIKE')));
                }
            } elseif (!empty($var[1]) && is_numeric($var[0])) {
                $criteria->add(new \Criteria($var[0], $var[1]));
            }
        }

        return $criteria;
    }

    /**
     * @param        $filter
     * @param        $field
     * @param string $sort
     * @param string $op
     * @param string $fct
     * @return string
     */
    public function getFilterForm($filter, $field, $sort = 'created', $op = 'dashboard', $fct = 'list')
    {
        $ele = songlist_getFilterElement($filter, $field, $sort, $op, $fct);
        if (is_object($ele)) {
            return $ele->render();
        }

        return '&nbsp;';
    }

    /**
     * @param \XoopsObject $obj
     * @param bool         $force
     * @param null         $object
     * @return bool|mixed
     */
    public function insert(\XoopsObject $obj, $force = true, $object = null)
    {
        if ($obj->isNew()) {
            $new = true;
            $old = $this->create();
            $obj->setVar('created', time());
        } else {
            $new = false;
            $old = $this->get($obj->getVar('sid'));
            $obj->setVar('updated', time());
        }

        $albumsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
        $artistsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
        $genreHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
        $voiceHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Voice');
        $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');

        if (true === $obj->vars['gid']['changed']) {
            if (true === $new || (0 != $obj->vars['gid']['value'])) {
                $genre = $genreHandler->get($obj->vars['gid']['value']);
                if (is_object($genre)) {
                    $genre->setVar('songs', $genre->getVar('songs') + 1);
                    $genreHandler->insert($genre, true, $obj);
                }
            }
            if (!$old->isNew() && $old->getVar('gid') > 0) {
                $genre = $genreHandler->get($old->vars['gid']['value']);
                if (is_object($genre)) {
                    $genre->setVar('songs', $genre->getVar('songs') - 1);
                    $genreHandler->insert($genre, true, null);
                }
            }
        }

        if (true === $obj->vars['vcid']['changed']) {
            if (true === $new || (0 != $obj->vars['vcid']['value'])) {
                $voice = $voiceHandler->get($obj->vars['vcid']['value']);
                if (is_object($voice)) {
                    $voice->setVar('songs', $voice->getVar('songs') + 1);
                    $voiceHandler->insert($voice, true, $obj);
                }
            }
            if (!$old->isNew() && $old->getVar('vcid') > 0) {
                $voice = $voiceHandler->get($old->vars['vcid']['value']);
                if (is_object($voice)) {
                    $voice->setVar('songs', $voice->getVar('songs') - 1);
                    $voiceHandler->insert($voice, true, null);
                }
            }
        }

        if (true === $obj->vars['cid']['changed']) {
            if (true === $new || (0 != $obj->vars['cid']['value'])) {
                $category = $categoryHandler->get($obj->vars['cid']['value']);
                if (is_object($category)) {
                    $category->setVar('songs', $category->getVar('songs') + 1);
                    $categoryHandler->insert($category, true, $obj);
                    foreach ($obj->getVar('aids') as $aid) {
                        $artists                   = $artistsHandler->get($aid);
                        $cids                      = $artists->getVar('cids');
                        $cids[$obj->getVar('cid')] = $obj->getVar('cid');
                        if (is_object($artists)) {
                            $artists->setVar('cids', $cids);
                            $artistsHandler->insert($artists, true, null);
                        }
                    }
                }
            }
            if (!$old->isNew() && $old->getVar('cid') > 0) {
                $category = $categoryHandler->get($old->vars['cid']['value']);
                if (is_object($category)) {
                    $category->setVar('songs', $category->getVar('songs') - 1);
                    $categoryHandler->insert($category, true, null);
                    foreach ($obj->getVar('aids') as $aid) {
                        $artists = $artistsHandler->get($aid);
                        $cids    = [];
                        foreach ($artists->getVar('cids') as $cid) {
                            if ($cid != $old->getVar('cid') || $cid == $obj->getVar('cid')) {
                                $cids[$cid] = $cid;
                            }
                        }
                        if (is_object($artists)) {
                            $artists->setVar('cids', $cids);
                            $artistsHandler->insert($artists, true, null);
                        }
                    }
                }
            }
        }

        if (true === $obj->vars['aids']['changed'] && 0 != count($obj->vars['aids']['value'])) {
            if (true === $new || 0 != count($obj->vars['aids']['value'])) {
                foreach ($obj->vars['aids']['value'] as $aid) {
                    if (!in_array($aid, $old->vars['aids']['value'])) {
                        $artists = $artistsHandler->get($aid);
                        if (is_object($artists)) {
                            $artists->setVar('songs', $artists->getVar('songs') + 1);
                            $artistsHandler->insert($artists, true, $obj);
                            if (true === $new || (0 != $obj->vars['vcid']['value'])) {
                                $voice = $voiceHandler->get($obj->vars['vcid']['value']);
                                if (is_object($voice)) {
                                    $voice->setVar('artists', $voice->getVar('artists') + 1);
                                    $voiceHandler->insert($voice, true, $obj);
                                }
                            }
                        }
                    }
                }
            }
            if (!$old->isNew() && 0 == count($old->getVar('aids'))) {
                foreach ($old->getVar('aids') as $aid) {
                    if (!in_array($aid, $obj->vars['aids']['value'])) {
                        $artists = $artistsHandler->get($aid);
                        if (is_object($artists)) {
                            $artists->setVar('songs', $artists->getVar('songs') - 1);
                            $artistsHandler->insert($artists, true, null);
                            if (!$old->isNew() && $old->getVar('vcid') > 0) {
                                $voice = $voiceHandler->get($old->vars['vcid']['value']);
                                if (is_object($voice)) {
                                    $voice->setVar('artists', $voice->getVar('artists') - 1);
                                    $voiceHandler->insert($voice, true, null);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (true === $obj->vars['abid']['changed']) {
            if (true === $new || (0 != $obj->vars['abid']['value'])) {
                $album = $albumsHandler->get($obj->vars['abid']['value']);
                if (is_object($album)) {
                    $album->setVar('songs', $album->getVar('songs') + 1);
                    $albumsHandler->insert($album, true, $obj);
                    if (true === $new || (0 != $obj->vars['vcid']['value'])) {
                        $voice = $voiceHandler->get($obj->vars['vcid']['value']);
                        if (is_object($voice)) {
                            $voice->setVar('albums', $voice->getVar('albums') + 1);
                            $voiceHandler->insert($voice, true, $obj);
                        }
                    }
                }
            }
            if (!$old->isNew() && $old->getVar('abid') > 0) {
                $album = $albumsHandler->get($obj->vars['abid']['value']);
                if (is_object($album)) {
                    $album->setVar('songs', $album->getVar('songs') - 1);
                    $albumsHandler->insert($album, true, null);
                    if (!$old->isNew() && $old->getVar('vcid') > 0) {
                        $voice = $voiceHandler->get($old->vars['vcid']['value']);
                        if (is_object($voice)) {
                            $voice->setVar('albums', $voice->getVar('albums') - 1);
                            $voiceHandler->insert($voice, true, null);
                        }
                    }
                }
            }
        }

        if ('' == $obj->getVar('title')) {
            return false;
        }

        $sid = parent::insert($obj, $force);
        if ($obj->vars['abid']['value'] > 0) {
            $album      = $albumsHandler->get($obj->vars['abid']['value']);
            $arry       = $album->getVar('sids');
            $arry[$sid] = $sid;
            if (is_object($album)) {
                $album->setVar('sids', $arry);
                $albumsHandler->insert($album);
            }
        }
        if (count($obj->getVar('aids')) > 0) {
            foreach ($obj->getVar('aids') as $aid) {
                $artist     = $artistsHandler->get($aid);
                $arry       = $artist->getVar('sids');
                $arry[$sid] = $sid;
                if (is_object($artists)) {
                    $artist->setVar('sids', $arry);
                    $artistsHandler->insert($artist);
                }
            }
        }

        return $sid;
    }

    public $_objects = ['object' => [], 'array' => []];

    /**
     * @param null $id
     * @param null $fields
     * @return \XoopsObject
     */
    public function get($id = null, $fields = null)//get($id, $fields = '*')
    {
        $fields = $fields ?: '*';
        if (!isset($this->_objects['object'][$id])) {
            $this->_objects['object'][$id] = parent::get($id, $fields);
            if (!isset($GLOBALS['songlistAdmin']) && is_object($this->_objects['object'][$id])) {
                $sql = 'UPDATE `' . $this->table . '` set hits=hits+1 where `' . $this->keyName . '` = ' . $this->_objects['object'][$id]->getVar($this->keyName);
                $GLOBALS['xoopsDB']->queryF($sql);
            }
        }

        return $this->_objects['object'][$id];
    }

    /**
     * @param null|\CriteriaElement $criteria
     * @param bool $id_as_key
     * @param bool $as_object
     * @return array
     */
    public function &getObjects(\CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret = parent::getObjects($criteria, $id_as_key, $as_object);

        /*if (!isset($GLOBALS['songlistAdmin'])) {
            foreach($ret as $data) {
                $id convertedValue;
                if ($as_object==true) {
                    if (!in_array($data->getVar($this->keyName), array_keys($this->_objects['object']))) {
                        $this->_objects['object'][$data->getVar($this->keyName)] = $data;
                        $id[$data->getVar($this->keyName)] = $data->getVar($this->keyName);
                    }
                } else {
                    if (!in_array($data[$this->keyName], array_keys($this->_objects['array']))) {
                        $this->_objects['array'][$data[$this->keyName]] = $data;
                        $id[$data[$this->keyName]] = $data[$this->keyName];;
                    }
                }
            }
        }
        if (!isset($GLOBALS['songlistAdmin'])&&count($id)>0) {
            $sql = 'UPDATE `'.$this->table.'` set hits=hits+1 where `'.$this->keyName.'` IN ('.implode(',', $id).')';
            $GLOBALS['xoopsDB']->queryF($sql);
        }*/

        return $ret;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        global $file, $op, $fct, $id, $value, $gid, $vid, $vcid, $cid, $start, $limit;
        if ($GLOBALS['songlistModuleConfig']['htaccess']) {
            if (0 != $cid) {
                $artistHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                $artist        = $artistHandler->get($cid);
                if (is_object($artist) && !$artist->isNew()) {
                    return XOOPS_URL
                           . '/'
                           . $GLOBALS['songlistModuleConfig']['baseofurl']
                           . '/'
                           . $file
                           . '/'
                           . urlencode(str_replace([' ', chr(9)], '-', $artist->getVar('name')))
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

                return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/' . $file . '/' . $start . '-' . $id . '-' . $op . '-' . $fct . '-' . $gid . '-' . $cid . '/' . urlencode($value) . $GLOBALS['songlistModuleConfig']['endofurl'];
            }

            return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/' . $file . '/' . $start . '-' . $id . '-' . $op . '-' . $fct . '-' . $gid . '-' . $cid . '/' . urlencode($value) . $GLOBALS['songlistModuleConfig']['endofurl'];
        }

        return XOOPS_URL . '/modules/songlist/' . $file . '.php?op=' . $op . '&fct=' . $fct . '&id=' . $id . '&value=' . urlencode($value) . '&gid=' . $gid . '&vid=' . $vid . '&cid=' . $cid . '&start=' . $start;
    }

    /**
     * @return string
     */
    public function getSearchURL()
    {
        global $file, $op, $fct, $id, $value, $gid, $vcid, $cid, $start, $limit;
        if ($GLOBALS['songlistModuleConfig']['htaccess']) {
            return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/' . $file . '/' . $start . '-' . $op . '-' . $fct . '-' . $gid . '-' . (isset($_GET['cid']) ? $_GET['cid'] : $cid) . '-' . $vcid . '/' . urlencode($value) . $GLOBALS['songlistModuleConfig']['endofurl'];
        }

        return XOOPS_URL . '/modules/songlist/' . $file . '.php?op=' . $op . '&fct=' . $fct . '&value=' . urlencode($value) . '&cid=' . $cid . '&gid=' . $gid . '&vcid=' . $vcid . '&start=' . $start;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getTop($limit = 1)
    {
        $sql     = 'SELECT * FROM `' . $this->table . '` WHERE `rank`>=0 ORDER BY (`rank`/`votes`) DESC LIMIT ' . $limit;
        $results = $GLOBALS['xoopsDB']->queryF($sql);
        $ret     = [];
        $i       = 0;
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($results))) {
            $ret[$i] = $this->create();
            $ret[$i]->assignVars($row);
            ++$i;
        }

        return $ret;
    }
}
