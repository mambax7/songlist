<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

use XoopsModules\Songlist\Common;

require __DIR__ . '/admin_header.php';

//include __DIR__ . '/header.php';

xoops_loadLanguage('admin', 'songlist');

xoops_cp_header();

$op = (!empty($_GET['op']) ? $_GET['op'] : (!empty($_POST['op']) ? $_POST['op'] : 'default'));

//switch ($op) {
//    case 'default':
//    default:

        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        $adminObject = \Xmf\Module\Admin::getInstance();

        $categoryHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Category');
        $artistsHandler  = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Artists');
        $albumsHandler   = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Albums');
        $genreHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Genre');
        $voiceHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Voice');
        $songsHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Songs');
        $requestsHandler = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Requests');
        $votesHandler    = \XoopsModules\Songlist\Helper::getInstance()->getHandler('Votes');

        $adminObject->addInfoBox(_AM_SONGLIST_COUNT);
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_CATEGORY . '</label>', $categoryHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_ARTISTS . '</label>', $artistsHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_ALBUMS . '</label>', $albumsHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_GENRE . '</label>', $genreHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_VOICE . '</label>', $voiceHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_SONGS . '</label>', $songsHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_REQUESTS . '</label>', $requestsHandler->getCount(null, true)), '', 'green');
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_SONGLIST_NUMBER_OF_VOTES . '</label>', $votesHandler->getCount(null, true)), '', 'green');


    //check for latest release
    //$newRelease = $utility->checkVerModule($helper);
    //if (!empty($newRelease)) {
    //    $adminObject->addItemButton($newRelease[0], $newRelease[1], 'download', 'style="color : Red"');
    //}

    //------------- Test Data ----------------------------

    if ($helper->getConfig('displaySampleButton')) {
        $yamlFile            = dirname(__DIR__) . '/config/admin.yml';
        $config              = loadAdminConfig($yamlFile);
        $displaySampleButton = $config['displaySampleButton'];

        if (1 == $displaySampleButton) {
            xoops_loadLanguage('admin/modulesadmin', 'system');
            require __DIR__ . '/../testdata/index.php';

            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
            //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
        } else {
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
            $displaySampleButton = $config['displaySampleButton'];
        }
        $adminObject->displayButton('left', '');
    }

    //------------- End Test Data ----------------------------


        $adminObject->displayIndex();


    echo $utility::getServerStats();

    require __DIR__ . '/admin_footer.php';



//        xoops_cp_footer();


//        break;
//}

/**
 * @param $yamlFile
 * @return array|bool
 */
function loadAdminConfig($yamlFile)
{
    $config = \Xmf\Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
    return $config;
}

/**
 * @param $yamlFile
 */
function hideButtons($yamlFile)
{
    $app['displaySampleButton'] = 0;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

/**
 * @param $yamlFile
 */
function showButtons($yamlFile)
{
    $app['displaySampleButton'] = 1;
    \Xmf\Yaml::save($app, $yamlFile);
    redirect_header('index.php', 0, '');
}

$op = \Xmf\Request::getString('op', 0, 'GET');

switch ($op) {
    case 'hide_buttons':
        hideButtons($yamlFile);
        break;
    case 'show_buttons':
        showButtons($yamlFile);
        break;
}


