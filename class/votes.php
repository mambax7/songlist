<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

include_once(dirname(dirname(__FILE__)).'/include/songlist.object.php');
include_once(dirname(dirname(__FILE__)).'/include/songlist.form.php');

class SonglistVotes extends XoopsObject
{

    function SonglistVotes($fid = null)
    {
        $this->initVar('vid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('sid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('netaddy', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('rank', XOBJ_DTYPE_DECIMAL, 0, false);
	}

	function getForm($as_array = false) {
		return songlist_votes_get_form($this, $as_array);
	}
	
	function toArray() {
		$ret = parent::toArray();
		$form = $this->getForm(true);
		foreach($form as $key => $element) {
			$ret['form'][$key] = $form[$key]->render();	
		}
		
		$ret['rank'] = number_format($this->getVar('rank'),2)._MI_SONGLIST_OFTEN;
    		
		return $ret;
	}
}


class SonglistVotesHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "songlist_votes", 'SonglistVotes', "vid", "ip");
    }

	function filterFields() {
		return array('vid', 'sid', 'uid', 'ip', 'netaddy', 'rank');
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
    	return parent::insert($obj, $force);
    }

    function addVote($sid, $value) {
    	
    	$criteria = new CriteriaCompo(new Criteria('sid', $sid));
    	
    	$ip = songlist_getIPData(false);
    	if ($ip['uid']>0) {
    		$criteria->add(new Criteria('uid', $ip['uid']));
    	} else {
    		$criteria->add(new Criteria('ip', $ip['ip']));
    		$criteria->add(new Criteria('netaddy', $ip['network-addy']));
    	}
    	
    	if ($this->getCount($criteria)==0&&$sid>0&&$value>0) {
    		$vote = $this->create();
    		$vote->setVar('sid', $sid);
    		$vote->setVar('uid', $ip['uid']);
    		$vote->setVar('ip', $ip['ip']);
    		$vote->setVar('netaddy', $ip['network-addy']);
    		$vote->setVar('rank', $value);
    		if ($this->insert($vote)) {
    			$songs_handler = xoops_getmodulehandler('songs', 'songlist');
    			$albums_handler = xoops_getmodulehandler('albums', 'songlist');
    			$artists_handler = xoops_getmodulehandler('artists', 'songlist');
    			$category_handler = xoops_getmodulehandler('category', 'songlist');
    			$genre_handler = xoops_getmodulehandler('genre', 'songlist');
				$voice_handler = xoops_getmodulehandler('voice', 'songlist');				
    			
    			$song = $songs_handler->get($sid);
    			$sql = array();
    			$sql[] = "UPDATE `" . $songs_handler->table . "` SET `rank` = `rank` + ".$value.", `votes` = `votes` + 1 WHERE `". $songs_handler->keyName . "` = " . $sid;
    			$sql[] = "UPDATE `" . $category_handler->table . "` SET `rank` = `rank` + ".$value.", `votes` = `votes` + 1 WHERE `". $category_handler->keyName . "` = " . $song->getVar($category_handler->keyName);
    			$sql[] = "UPDATE `" . $genre_handler->table . "` SET `rank` = `rank` + ".$value.", `votes` = `votes` + 1 WHERE `". $genre_handler->keyName . "` = " . $song->getVar($genre_handler->keyName);
				$sql[] = "UPDATE `" . $voice_handler->table . "` SET `rank` = `rank` + ".$value.", `votes` = `votes` + 1 WHERE `". $voice_handler->keyName . "` = " . $song->getVar($voice_handler->keyName);
    			$sql[] = "UPDATE `" . $albums_handler->table . "` SET `rank` = `rank` + ".$value.", `votes` = `votes` + 1 WHERE `". $albums_handler->keyName . "` = " . $song->getVar($albums_handler->keyName);
    			foreach($song->getVar('aids') as $aid) {
    				$sql[] = "UPDATE `" . $artists_handler->table . "` SET `rank` = `rank` + ".$value.", `votes` = `votes` + 1 WHERE `". $artists_handler->keyName . "` = " . $aid;
    			}
    			foreach($sql as $question) { 
    				$GLOBALS['xoopsDB']->queryF($question);
    			}
    			redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_FINISHED);
    			exit(0);
    		} else {
	    		redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_ALREADY);
	    		exit(0);
	    	}
    	} else {
    		redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_SOMETHINGWRONG);
    		exit(0);
    	}
    	return false;
    }
}
?>