<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

include_once(dirname(dirname(__FILE__)).'/include/songlist.object.php');
include_once(dirname(dirname(__FILE__)).'/include/songlist.form.php');

class SonglistVoice extends XoopsObject
{

    function SonglistVoice($fid = null)
    {
        $this->initVar('vcid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('artists', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('albums', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('songs', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hits', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('rank', XOBJ_DTYPE_DECIMAL, 0, false);
		$this->initVar('votes', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
	}

	function getForm($as_array = false) {
		return songlist_voice_get_form($this, $as_array);
	}
	
	function toArray() {
		$ret = parent::toArray();
		$form = $this->getForm(true);
		foreach($form as $key => $element) {
			$ret['form'][$key] = $form[$key]->render();	
		}
		foreach(array('created', 'updated') as $key) {
			if ($this->getVar($key)>0) {
				$ret['form'][$key] = date(_DATESTRING, $this->getVar($key)); 
				$ret[$key] = date(_DATESTRING, $this->getVar($key));
			}
		}
		$ret['rank'] = number_format(($this->getVar('rank')>0&&$this->getVar('votes')>0?$this->getVar('rank')/$this->getVar('votes'):0),2)._MI_SONGLIST_OFTEN;
    		
		return $ret;
	}
	
	function getURL() {
    	global $file, $op, $fct, $id, $value, $gid, $vid, $cid, $start, $limit;
    	if ($GLOBALS['songlistModuleConfig']['htaccess']) {
    		return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseurl'].'/'.$file.'/'.urlencode(str_replace(array(' ', chr(9)), '-', $this->getVar('name'))).'/'.$op.'-'.$fct.'-'.$this->getVar('gid').'-'.urlencode($value).'-'.$gid.'-'.$vid.'-'.$cid.$GLOBALS['songlistModuleConfig']['endofurl'];
    	} else {
    		return XOOPS_URL.'/modules/songlist/'.$file.'.php?op='.$op.'&fct='.$fct.'&id='.$this->getVar('gid').'&value='.urlencode($value).'&gid='.$gid.'&vid='.$vid.'&cid='.$cid;
    	}
    }
	
	
	
}


class SonglistVoiceHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "songlist_voice", 'SonglistVoice', "vcid", "name");
    }

	function filterFields() {
		return array('vcid', 'name', 'artists', 'albums', 'songs', 'hits', 'rank', 'votes', 'created', 'updated');
	}
	
    function getFilterCriteria($filter) {
    	$parts = explode('|', $filter);
    	$criteria = new CriteriaCompo();
    	foreach($parts as $part) {
    		$var = explode(',', $part);
    		if (!empty($var[1])&&!is_numeric($var[0])) {
    			$object = $this->create();
    			if (		$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTBOX || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTAREA) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%'.$var[1].'%', (isset($var[2])?$var[2]:'LIKE')));
    			} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_INT || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_DECIMAL || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_FLOAT ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));			
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ENUM ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));    				
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ARRAY ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%"'.$var[1].'";%', (isset($var[2])?$var[2]:'LIKE')));    				
				}
    		} elseif (!empty($var[1])&&is_numeric($var[0])) {
    			$criteria->add(new Criteria($var[0], $var[1]));
    		}
    	}
    	return $criteria;
    }
        
    function getFilterForm($filter, $field, $sort='created', $op = 'dashboard', $fct='list') {
    	$ele = songlist_getFilterElement($filter, $field, $sort, $op, $fct);
    	if (is_object($ele))
    		return $ele->render();
    	else 
    		return '&nbsp;';
    }
    
	function insert($obj, $force=true) {
    	if ($obj->isNew()) {
    		$obj->setVar('created', time());	
    	} else {
    		$obj->setVar('updated', time());
    	}
    	if (strlen($obj->getVar('name'))==0)
    		return false;
    	return parent::insert($obj, $force);
    }
     
	var $_objects = array('object'=>array(), 'array'=>array());
    
    function get($id, $fields = '*') {
    	if (!isset($this->_objects['object'][$id])) {
	    	$this->_objects['object'][$id] = parent::get($id, $fields);
	    	if (!isset($GLOBALS['songlistAdmin'])&&is_object($this->_objects['object'][$id])) {
		    	$sql = 'UPDATE `'.$this->table.'` set hits=hits+1 where `'.$this->keyName.'` = '.$this->_objects['object'][$id]->getVar($this->keyName);
		    	$GLOBALS['xoopsDB']->queryF($sql);
	    	}
    	}
    	return $this->_objects['object'][$id];
    }
    
    function getObjects($criteria = NULL, $id_as_key = false, $as_object = true) {
    	$ret = parent::getObjects($criteria, $id_as_key, $as_object);
    	/*if (!isset($GLOBALS['songlistAdmin'])) {
	    	$id = array();
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

    function getURL() {
    	global $file, $op, $fct, $id, $value, $gid, $cid, $start, $limit;
    	if ($GLOBALS['songlistModuleConfig']['htaccess']) {
    		return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseurl'].'/'.$file.'/'.$start.'-'.$op.'-'.$fct.'-'.$id.'-'.urlencode($value).'-'.$gid.'-'.$cid.$GLOBALS['songlistModuleConfig']['endofurl'];
    	} else {
    		return XOOPS_URL.'/modules/songlist/'.$file.'.php?op='.$op.'&fct='.$fct.'&id='.$id.'&value='.urlencode($value).'&gid='.$gid.'&cid='.$cid.'&start='.$start;
    	}
    }
    
	function getTop($limit=1) {
    	$sql = 'SELECT * FROM `'.$this->table.'` WHERE `rank`>=0 ORDER BY (`rank`/`votes`) DESC LIMIT '.$limit;
    	$results = $GLOBALS['xoopsDB']->queryF($sql);
    	$ret = array();
    	$i=0;
    	while ($row = $GLOBALS['xoopsDB']->fetchArray($results)) {
    		$ret[$i] = $this->create();
    		$ret[$i]->assignVars($row);
    		$i++;
    	}
    	return $ret;
    }
    
	function delete($object, $force=true) {
    	parent::delete($object, $force);
    	$sql = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix('songlist_songs') . ' SET `vcid` = 0 WHERE `vcid` = ' . $object->getVar('vcid');
    	return $GLOBALS['xoopsDB']->queryF($sql);	
    }
}
?>