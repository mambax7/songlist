<?php

namespace XoopsModules\Songlist;

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
 * @author         XOOPS Development Team, Wishcraft
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class Visibility
 * @package XoopsModules\Songlist
 */
class Visibility extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT);
        $this->initVar('user_group', XOBJ_DTYPE_INT);
        $this->initVar('profile_group', XOBJ_DTYPE_INT);
    }
}
