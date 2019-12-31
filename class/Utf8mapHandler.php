<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class Utf8mapHandler
 * @package XoopsModules\Songlist
 */
class Utf8mapHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Utf8mapHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'songlist_utf8map', Utf8map::class, 'utfid', 'from');
    }

    /**
     * @return array
     */
    public function filterFields()
    {
        return ['utfid', 'from', 'to', 'created', 'updated'];
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
     * @return mixed
     */
    public function insert(\XoopsObject $obj, $force = true)
    {
        if ($obj->isNew()) {
            $obj->setVar('created', time());
        } else {
            $obj->setVar('updated', time());
        }

        return parent::insert($obj, $force);
    }

    /**
     * @param string $phrase
     * @param null   $criteria
     * @return string|string[]
     */
    public function convert($phrase = '', $criteria = null)
    {
        foreach ($this->getObjects($criteria, true) as $utfid => $utf8) {
            $phrase = str_replace(mb_strtolower($utf8->getVar('from')), mb_strtolower($utf8->getVar('to')), $phrase);
            $phrase = str_replace(mb_strtoupper($utf8->getVar('from')), mb_strtoupper($utf8->getVar('to')), $phrase);
        }

        return $phrase;
    }
}
