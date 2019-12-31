<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

include_once(dirname(dirname(__FILE__)).'/include/songlist.object.php');
include_once(dirname(dirname(__FILE__)).'/include/songlist.form.php');

class SonglistArtists extends XoopsObject
{

    function SonglistArtists($fid = null)
    {
        $this->initVar('aid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('cids', XOBJ_DTYPE_ARRAY, array(), false);
        $this->initVar('sids', XOBJ_DTYPE_ARRAY, array(), false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('albums', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('songs', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hits', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('rank', XOBJ_DTYPE_DECIMAL, 0, false);
		$this->initVar('votes', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
	}

	function getForm($as_array = false) {
		return songlist_artists_get_form($this, $as_array);
	}
	
	function toArray($extra = false) {
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
		$ret['url'] = $this->getURL(true);
		
		xoops_loadLanguage('enum', 'songlist');
		if (!empty($ret['singer']))
			$ret['singer'] = constant($ret['singer']); 
		
		if ($extra==false)
    		return $ret;
    		
		if (count($this->getVar('cids'))!=0) {
    		$categories_handler = xoops_getmodulehandler('category', 'songlist');
    		foreach($this->getVar('cids') as $aid) {
    			$category = $categories_handler->get($aid);
    			if (is_object($category)) {
    				$ret['categories_array'][$aid] = $category->toArray(false);
    			}
    		} 	
    	}
    		
    	
		if (count($this->getVar('aids'))!=0) {
    		$artists_handler = xoops_getmodulehandler('artists', 'songlist');
    		foreach($this->getVar('aids') as $aid) {
    			$artist = $artists_handler->get($aid);
    			if (is_object($artist)) {
    				$ret['artists_array'][$aid] = $artist->toArray(false);
    			}
    		} 	
    	}
    	
		
		if (count($this->getVar('sids'))!=0) {
    		$songs_handler = xoops_getmodulehandler('songs', 'songlist');
    		$criteria = new Criteria('`aids`', '%"'.$this->getVar('aid').'"%', 'LIKE');
    		$criteria->setSort('`songid`');
    		$criteria->setOrder('ASC');
    		foreach($songs_handler->getObjects($criteria, true) as $sid => $song) {
    			if (is_object($song)) {
    				$ret['songs_array'][$sid] = $song->toArray(false);
    			}
    		} 	
    	}
    	
		return $ret;
	}
	
    function getURL() {
    	global $file, $op, $fct, $id, $value, $gid, $vid, $vcid, $cid, $start, $limit;
    	if ($GLOBALS['songlistModuleConfig']['htaccess']) {
    	    if ($id!=0) {
    			$artist_handler = xoops_getmodulehandler('artists', 'songlist');
    			$artist = $artist_handler->get($id);
    			if (is_object($artist)&&!$artist->isNew()) {
    				return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseofurl'].'/artists/'.urlencode(str_replace(array(' ', chr(9)), '-', $artist->getVar('name'))).'/'.$start.'-'.$id.'-'.$op.'-'.$fct.'-'.$gid.'-'.$cid.'/'.urlencode($value).$GLOBALS['songlistModuleConfig']['endofurl'];
    			} else {
    				return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseofurl'].'/artists/'.$start.'-'.$id.'-'.$op.'-'.$fct.'-'.$gid.'-'.$cid.'-'.$vcid.'/'.urlencode($value).$GLOBALS['songlistModuleConfig']['endofurl'];
    			}
    		} else {
    			return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseofurl'].'/artists/'.$start.'-'.$id.'-'.$op.'-'.$fct.'-'.$gid.'-'.$cid.'-'.$vcid.'/'.urlencode($value).$GLOBALS['songlistModuleConfig']['endofurl'];
    		}
    	} else {
    		return XOOPS_URL.'/modules/songlist/artists.php?op='.$op.'&fct='.$fct.'&id='.$id.'&value='.urlencode($value).'&gid='.$gid.'&vid='.$vid.'&cid='.$cid.'&start='.$start;
    	}
    }
		
}


class SonglistArtistsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "songlist_artists", 'SonglistArtists', "aid", "name");
    }

	function filterFields() {
		return array('aid', 'cids', 'singer', 'name', 'albums', 'songs', 'hits', 'rank', 'votes', 'created', 'updated');
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
    
	function insert($obj, $force=true, $object = null) {
    	if ($obj->isNew()) {
    		$new = true;
    		$old = $this->create();
    		$obj->setVar('created', time());	
    	} else {
    		$new = false;
    		$old = $this->get($obj->getVar('aid'));
    		$obj->setVar('updated', time());
    	}
		
    	$albums_handler = xoops_getmodulehandler('albums', 'songlist');
		$songs_handler = xoops_getmodulehandler('songs', 'songlist');
		$genre_handler = xoops_getmodulehandler('genre', 'songlist');
		$voice_handler = xoops_getmodulehandler('voice', 'songlist');		
		$category_handler = xoops_getmodulehandler('category', 'songlist');
   	
		if (is_a($object, 'SonglistSongs')) {
			if ($object->vars['cid']['changed']==true) {
				foreach($obj->vars['cids']['value'] as $cid) {
					if (!in_array($cid, $object->getVar('cid'))&&$cid!=0) {
						$obj->setVar('cids', array_merge($obj->getVar('cids'), array($object->getVar('cid')=>$object->getVar('cid'))));
						$category = $category_handler->get($cid);
						if (is_object($category)) {
			    			$category->setVar('artists', $category->getVar('artists')+1);
			    			$category_handler->insert($category, true, $obj);
						}	
					}
				}
				if (!$old->isNew()) {
					foreach($old->getVar('cids') as $cid) {
						if (!in_array($cid, $obj->vars['cids']['value'])&&$cid!=0) {
							$category = $category_handler->get($cid);
							if (is_object($category)) {
				    			$category->setVar('artists', $category->getVar('artists')-1);
				    			$category_handler->insert($category, true, $obj);
							}	
						}
					}
				}
			}
		    	
	    	if ($object->vars['abid']['value']!=0&&$object->vars['aids']['changed']==true) {
    			$album = $albums_handler->get($object->vars['abid']['value']);
    			if (is_object($album)) {
    				$album->setVar('artists', $album->getVar('artists')+1);
    				$albums_handler->insert($album, true, $obj);
    			}
	       	}
	    	
			if ($object->vars['gid']['value']!=0&&$object->vars['gid']['changed']==true) {
    			$genre = $genre_handler->get($object->vars['gid']['value']);
    			if (is_object($genre)) {
	    			$genre->setVar('artists', $genre->getVar('artists')+1);
	    			$genre_handler->insert($genre, true, $obj);
    			}
	       	}
			if ($object->vars['vid']['value']!=0&&$object->vars['vid']['changed']==true) {
    			$voice = $voice_handler->get($object->vars['vid']['value']);
    			if (is_object($voice)) {
	    			$voice->setVar('artists', $voice->getVar('artists')+1);
	    			$voice_handler->insert($voice, true, $obj);
    			}
	       	}
			
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
    	global $file, $op, $fct, $id, $value, $gid, $vid, $cid, $start, $limit;
    	if ($GLOBALS['songlistModuleConfig']['htaccess']) {
    	    if ($cid!=0) {
    			$artist_handler = xoops_getmodulehandler('artists', 'songlist');
    			$artist = $artist_handler->get($cid);
    			if (is_object($artist)&&!$artist->isNew()) {
    				return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseofurl'].'/'.$file.'/'.urlencode(str_replace(array(' ', chr(9)), '-', $artist->getVar('name'))).'/'.$start.'-'.$id.'-'.$op.'-'.$fct.'-'.$gid.'-'.$cid.'/'.urlencode($value).$GLOBALS['songlistModuleConfig']['endofurl'];
    			} else {
    				return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseofurl'].'/'.$file.'/'.$start.'-'.$id.'-'.$op.'-'.$fct.'-'.$gid.'-'.$cid.'/'.urlencode($value).$GLOBALS['songlistModuleConfig']['endofurl'];
    			}
    		} else {
    			return XOOPS_URL.'/'.$GLOBALS['songlistModuleConfig']['baseofurl'].'/'.$file.'/'.$start.'-'.$id.'-'.$op.'-'.$fct.'-'.$gid.'-'.$cid.'/'.urlencode($value).$GLOBALS['songlistModuleConfig']['endofurl'];
    		}
    	} else {
    		return XOOPS_URL.'/modules/songlist/'.$file.'.php?op='.$op.'&fct='.$fct.'&id='.$id.'&value='.urlencode($value).'&gid='.$gid.'&vid='.$vid.'&cid='.$cid.'&start='.$start;
    	}
    }
    
    
    function getSIDs($criteria = NULL) {
    	$ret = array();
    	$song_handler = xoops_getmodulehandler('songs', 'songlist');
    	foreach($this->getObjects($criteria, true) as $aid => $object) {
    		$crita = new Criteria('`aids`', '%"'.$aid.'"%', 'LIKE');
    		foreach($song_handler->getObjects($crita, true) as $sid => $song) {
    			$ret[$sid] = $sid;
    		}
    	}
    	return $ret;
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
}
?>