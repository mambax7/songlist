<?php

/*
Module: Songlist

Version: 3.23

Description: Object manager for WHMCS Billing

Author: Written by Simon Roberts aka. Wishcraft (simon@chronolabs.coop)

Owner: Frilogg

License: See docs - End User Licence.pdf
*/

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class SonglistVisibility extends XoopsObject
{
    function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT);
        $this->initVar('user_group', XOBJ_DTYPE_INT);
        $this->initVar('profile_group', XOBJ_DTYPE_INT);
    }

    function SonglistVisibility()
    {
        $this->__construct();
    }
}

class SonglistVisibilityHandler extends XoopsPersistableObjectHandler
{
    function __construct($db)
    {
        parent::__construct($db, 'songlist_visibility', 'SonglistVisibility', 'field_id');
    }

    /**
     * get all objects matching a condition
     *
     * @param   object      $criteria {@link CriteriaElement} to match
     * @param   array       $fields     variables to fetch
     * @param   bool        $asObject     flag indicating as object, otherwise as array
     * @param   bool        $id_as_key use the ID as key for the array
     * @return  array of objects/array {@link XoopsObject}
     */
    function &getAll($criteria = null)
    {
        $limit = null;
        $GLOBALS['start'] = null;
        $sql = "SELECT * FROM `{$this->table}`";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderWhere();
            if ($groupby = $criteria->getGroupby()) {
                $sql .= " " . $groupby;
            }
            if ($sort = $criteria->getSort()) {
                $sql .= " ORDER BY {$sort} " . $criteria->getOrder();
                $orderSet = true;
            }
            $limit = $criteria->getLimit();
            $GLOBALS['start'] = $criteria->getStart();
        }
        if (empty($orderSet)) {
            $sql .= " ORDER BY `{$this->keyName}` DESC";
        }
        $result = $this->db->query($sql, $limit, $GLOBALS['start']);
        $ret = array();
        while ($row = $this->db->fetchArray($result)) {
            $ret[$row['field_id']][] = $row;
        }
        return $ret;
    }

    /**
     * Get fields visible to the $user_groups on a $profile_groups profile
     *
     * @param array $profile_groups groups of the user to be accessed
     * @param array $user_groups    groups of the visitor, default as $GLOBALS['xoopsUser']
     *
     * @return array
     */
    function getVisibleFields($profile_groups, $user_groups = array())
    {
		$profile_groups = array_merge($profile_groups, array('0'));
		$user_groups = array_merge($user_groups, array('0'));
        $profile_groups[] = $user_groups[] = 0;
        $sql = "SELECT field_id FROM {$this->table} WHERE profile_group IN (" . implode(',', $profile_groups) . ")";
        $sql .= " AND user_group IN (" . implode(',', $user_groups) . ")";
        $field_ids = array();
        if ($result = $this->db->query($sql)) {
            while (list($field_id) = $this->db->fetchRow($result)) {
                $field_ids[] = $field_id;
            }
        }
        return $field_ids;
    }

	
}
?>