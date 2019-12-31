<?php

namespace XoopsModules\Songlist;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once dirname(__DIR__) . '/include/songlist.object.php';
require_once dirname(__DIR__) . '/include/songlist.form.php';

/**
 * Class RequestsHandler
 * @package XoopsModules\Songlist
 */
class RequestsHandler extends \XoopsPersistableObjectHandler
{
    /**
     * RequestsHandler constructor.
     * @param $db
     */
    public function __construct($db)
    {
        parent::__construct($db, 'songlist_requests', Requests::class, 'rid', 'name');
    }

    /**
     * @return array
     */
    public function filterFields()
    {
        return ['rid', 'artist', 'album', 'title', 'lyrics', 'uid', 'name', 'email', 'songid', 'sid', 'created', 'updated'];
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
            $new      = true;
            $sendmail = true;
        } else {
            $obj->setVar('updated', time());
            $new = false;
            if (true === $obj->vars['songid']['changed']) {
                $songsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                $criteria     = new \Criteria('songid', $obj->getVar('songid'));
                $songs        = $songsHandler->getObjects($criteria, false);
                if (is_object($songs[0])) {
                    foreach ($songs[0]->getVar('aids') as $aid) {
                        $ad[] = $aid;
                    }
                    $obj->setVar('sid', $songs[0]->getVar('sid'));
                    $obj->setVar('aid', $ad[0]);
                    $sendmail = true;
                }
            }
        }
        $rid = parent::insert($obj, $force);
        if ($rid) {
            if (true === $sendmail) {
                if (true === $new) {
                    xoops_loadLanguage('email', 'songlist');
                    $xoopsMailer = &getMailer();
                    $xoopsMailer->setHTML(true);
                    $xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('/modules/songlist/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_templates/'));
                    $xoopsMailer->setTemplate('songlist_request_created.tpl');
                    $xoopsMailer->setSubject(sprintf(_MN_SONGLIST_SUBJECT_REQUESTMADE, $rid));

                    foreach (explode('|', $GLOBALS['songlistModuleConfig']['email']) as $email) {
                        $xoopsMailer->setToEmails($email);
                    }

                    $xoopsMailer->setToEmails($obj->getVar('email'));

                    $xoopsMailer->assign('SITEURL', XOOPS_URL);
                    $xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
                    $xoopsMailer->assign('RID', $rid);
                    $xoopsMailer->assign('TITLE', $obj->getVar('title'));
                    $xoopsMailer->assign('ALBUM', $obj->getVar('album'));
                    $xoopsMailer->assign('ARTIST', $obj->getVar('artist'));
                    $xoopsMailer->assign('EMAIL', $obj->getVar('email'));
                    $xoopsMailer->assign('NAME', $obj->getVar('name'));

                    if (!$xoopsMailer->send()) {
                        xoops_error($xoopsMailer->getErrors(true), 'Email Send Error');
                    }
                } else {
                    xoops_loadLanguage('email', 'songlist');
                    $songsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
                    $artistsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
                    $albumsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
                    $genreHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');

                    $song = $songsHandler->get($obj->getVar('sid'));
                    if (is_object($song)) {
                        $sng = $genre->getVar('title');
                    }
                    $album = $albumHandler->get($song->getVar('abid'));
                    if (is_object($album)) {
                        $alb     = $genre->getVar('title');
                        $alb_img = $genre->getImage();
                    }
                    $genre = $genreHandler->get($song->getVar('abid'));
                    if (is_object($genre)) {
                        $gen = $genre->getVar('name');
                    }
                    $artists = $artistsHandler->getObjects(new \Criteria('aid', '(' . implode(',', $song->getVar('aid')) . ')', 'IN'), false);
                    $art     = '';
                    foreach ($artists as $id => $artist) {
                        $art .= $artist->getVar('name') . ($id < count($artists) - 1 ? ', ' : '');
                    }
                    $xoopsMailer = &getMailer();
                    $xoopsMailer->setHTML(true);
                    $xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('/modules/songlist/language/' . $GLOBALS['xoopsConfig']['language'] . '/mail_templates/'));
                    $xoopsMailer->setTemplate('songlist_request_updated.tpl');
                    $xoopsMailer->setSubject(sprintf(_MN_SONGLIST_SUBJECT_REQUESTFOUND, $rid));

                    $xoopsMailer->setToEmails($obj->getVar('email'));

                    $xoopsMailer->assign('SITEURL', XOOPS_URL);
                    $xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
                    $xoopsMailer->assign('RID', $rid);
                    $xoopsMailer->assign('TITLE', $obj->getVar('title'));
                    $xoopsMailer->assign('ALBUM', $obj->getVar('album'));
                    $xoopsMailer->assign('ARTIST', $obj->getVar('artist'));
                    $xoopsMailer->assign('EMAIL', $obj->getVar('email'));
                    $xoopsMailer->assign('NAME', $obj->getVar('name'));
                    $xoopsMailer->assign('SONGID', $song->getVar('songid'));
                    $xoopsMailer->assign('SONGURL', $song->getURL());
                    $xoopsMailer->assign('FOUNDTITLE', $sng);
                    $xoopsMailer->assign('FOUNDALBUM', $alb);
                    $xoopsMailer->assign('FOUNDARTIST', $art);
                    $xoopsMailer->assign('FOUNDGENRE', $gen);
                    $xoopsMailer->assign('EMAIL', $obj->getVar('email'));
                    $xoopsMailer->assign('NAME', $obj->getVar('name'));

                    if (!$xoopsMailer->send()) {
                        xoops_error($xoopsMailer->getErrors(true), 'Email Send Error');
                    }
                }
            }
        }

        return $rid;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        global $file, $op, $fct, $id, $value, $gid, $cid, $start, $limit;
        if ($GLOBALS['songlistModuleConfig']['htaccess']) {
            return XOOPS_URL . '/' . $GLOBALS['songlistModuleConfig']['baseofurl'] . '/' . $file . '/' . $op . '-' . $fct . $GLOBALS['songlistModuleConfig']['endofurl'];
        }

        return XOOPS_URL . '/modules/songlist/' . $file . '.php?op=' . $op . '&fct=' . $fct;
    }
}
