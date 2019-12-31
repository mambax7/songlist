<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class AlbumsHandler
 * @package XoopsModules\Songlist
 */
class AlbumsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * AlbumsHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'songlist_albums', Albums::class, 'abid', 'title');
    }

    /**
     * @return array
     */
    public function filterFields()
    {
        return ['abid', 'cid', 'aids', 'sids', 'title', 'image', 'path', 'artists', 'songs', 'hits', 'rank', 'votes', 'created', 'updated'];
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
            $old = $this->get($obj->getVar('abid'));
            $obj->setVar('updated', time());
        }

        $artistsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
        $genreHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
        $voiceHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Voice');
        $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');

        if ($object instanceof \XoopsModules\Songlist\Songs) {
            if (true === $obj->vars['cid']['changed']) {
                if ($obj->vars['cid']['value'] != $old->vars['cid']['value']) {
                    $category = $categoryHandler->get($obj->vars['cid']['value']);
                    if (is_object($category)) {
                        $category->setVar('albums', $category->getVar('albums') + 1);
                        $categoryHandler->insert($category, true, $obj);
                        if (!$old->isNew() && $old->vars['cid']['value'] > 0) {
                            $category = $categoryHandler->get($old->vars['cid']['value']);
                            if (is_object($category)) {
                                $category->setVar('albums', $category->getVar('albums') - 1);
                                $categoryHandler->insert($category, true, $obj);
                            }
                        }
                    }
                }
            }

            if (0 != count($obj->vars['aids']['value']) && true === $obj->vars['aids']['changed']) {
                foreach ($obj->vars['aids']['value'] as $aid) {
                    if (!is_array($aid, $old->getVar('aids')) && 0 != $aid) {
                        $artists = $artistsHandler->get($aid);
                        if (is_object($artists)) {
                            $artists->setVar('albums', $artists->getVar('albums') + 1);
                            $artistsHandler->insert($artists, true, $obj);
                        }
                    }
                }
                if (!$old->isNew()) {
                    foreach ($old->getVar('aids') as $aid) {
                        if (!is_array($aid, $obj->vars['aids']['value']) && 0 != $aid) {
                            $artists = $artistsHandler->get($aid);
                            if (is_object($artists)) {
                                $artists->setVar('albums', $artists->getVar('albums') - 1);
                                $artistsHandler->insert($artists, true, $obj);
                            }
                        }
                    }
                }
            }

            if (0 != $object->vars['gid']['value'] && true === $object->vars['gid']['changed']) {
                $genre = $genreHandler->get($object->vars['gid']['value']);
                if (is_object($genre)) {
                    $genre->setVar('albums', $genre->getVar('albums') + 1);
                    $genreHandler->insert($genre, true, $obj);
                }
            }
            if (0 != $object->vars['vid']['value'] && true === $object->vars['vid']['changed']) {
                $voice = $voiceHandler->get($object->vars['vid']['value']);
                if (is_object($voice)) {
                    $voice->setVar('albums', $voice->getVar('albums') + 1);
                    $voiceHandler->insert($voice, true, $obj);
                }
            }
        }
        if ('' == $obj->getVar('title')) {
            return false;
        }

        return parent::insert($obj, $force);
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

        /* if (!isset($GLOBALS['songlistAdmin'])) {
            $id convertedValue;
            foreach($ret as $data) {
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
        global $file, $op, $fct, $id, $value, $gid, $vid, $cid, $start, $limit;
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
