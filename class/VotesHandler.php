<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class VotesHandler
 * @package XoopsModules\Songlist
 */
class VotesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * VotesHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'songlist_votes', Votes::class, 'vid', 'ip');
    }

    /**
     * @return array
     */
    public function filterFields()
    {
        return ['vid', 'sid', 'uid', 'ip', 'netaddy', 'rank'];
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
     * @param $sid
     * @param $value
     * @return bool
     */
    public function addVote($sid, $value)
    {
        $criteria = new \CriteriaCompo(new \Criteria('sid', $sid));

        $ip = songlist_getIPData(false);
        if ($ip['uid'] > 0) {
            $criteria->add(new \Criteria('uid', $ip['uid']));
        } else {
            $criteria->add(new \Criteria('ip', $ip['ip']));
            $criteria->add(new \Criteria('netaddy', $ip['network-addy']));
        }

        if (0 == $this->getCount($criteria) && $sid > 0 && $value > 0) {
            $vote = $this->create();
            $vote->setVar('sid', $sid);
            $vote->setVar('uid', $ip['uid']);
            $vote->setVar('ip', $ip['ip']);
            $vote->setVar('netaddy', $ip['network-addy']);
            $vote->setVar('rank', $value);
            if ($this->insert($vote)) {
                $songsHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                $albumsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
                $artistsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
                $genreHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
                $voiceHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Voice');

                $song  = $songsHandler->get($sid);
                $sql   = [];
                $sql[] = 'UPDATE `' . $songsHandler->table . '` SET `rank` = `rank` + ' . $value . ', `votes` = `votes` + 1 WHERE `' . $songsHandler->keyName . '` = ' . $sid;
                $sql[] = 'UPDATE `' . $categoryHandler->table . '` SET `rank` = `rank` + ' . $value . ', `votes` = `votes` + 1 WHERE `' . $categoryHandler->keyName . '` = ' . $song->getVar($categoryHandler->keyName);
                $sql[] = 'UPDATE `' . $genreHandler->table . '` SET `rank` = `rank` + ' . $value . ', `votes` = `votes` + 1 WHERE `' . $genreHandler->keyName . '` = ' . $song->getVar($genreHandler->keyName);
                $sql[] = 'UPDATE `' . $voiceHandler->table . '` SET `rank` = `rank` + ' . $value . ', `votes` = `votes` + 1 WHERE `' . $voiceHandler->keyName . '` = ' . $song->getVar($voiceHandler->keyName);
                $sql[] = 'UPDATE `' . $albumsHandler->table . '` SET `rank` = `rank` + ' . $value . ', `votes` = `votes` + 1 WHERE `' . $albumsHandler->keyName . '` = ' . $song->getVar($albumsHandler->keyName);
                foreach ($song->getVar('aids') as $aid) {
                    $sql[] = 'UPDATE `' . $artistsHandler->table . '` SET `rank` = `rank` + ' . $value . ', `votes` = `votes` + 1 WHERE `' . $artistsHandler->keyName . '` = ' . $aid;
                }
                foreach ($sql as $question) {
                    $GLOBALS['xoopsDB']->queryF($question);
                }
                redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_FINISHED);
                exit(0);
            }
            redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_ALREADY);
            exit(0);
        }
        redirect_header($_POST['uri'], 10, _MN_SONGLIST_MSG_VOTED_SOMETHINGWRONG);
        exit(0);

        return false;
    }
}
