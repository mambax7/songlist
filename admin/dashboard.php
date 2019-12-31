<?php
// $Id: directory.php 5204 2010-09-06 20:10:52Z mageg $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: XOOPS Foundation                                                  //
// URL: http://www.xoops.org/                                                //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

	include ('header.php');
	xoops_loadLanguage('admin', 'songlist');
	
	xoops_cp_header();		

	$op = (!empty($_GET['op']) ? $_GET['op'] : (!empty($_POST['op']) ? $_POST['op'] : "default"));
	
	switch ($op) {
	    case "default":
	    default:
    	
			$indexAdmin = new ModuleAdmin();
			echo $indexAdmin->addNavigation(basename(__FILE__));
			
			$indexAdmin = new ModuleAdmin();	
			
			$category_handler = xoops_getmodulehandler('category', 'songlist');
			$artists_handler = xoops_getmodulehandler('artists', 'songlist');
			$albums_handler = xoops_getmodulehandler('albums', 'songlist');
			$genre_handler = xoops_getmodulehandler('genre', 'songlist');
			$voice_handler = xoops_getmodulehandler('voice', 'songlist');			
			$songs_handler = xoops_getmodulehandler('songs', 'songlist');
			$requests_handler = xoops_getmodulehandler('requests', 'songlist');
			$votes_handler = xoops_getmodulehandler('votes', 'songlist');
			
		    $indexAdmin->addInfoBox(_AM_SONGLIST_COUNT);
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_CATEGORY."</label>", $category_handler->getCount(NULL, true), 'green');
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_ARTISTS."</label>", $artists_handler->getCount(NULL, true), 'green');
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_ALBUMS."</label>", $albums_handler->getCount(NULL, true), 'green');
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_GENRE."</label>", $genre_handler->getCount(NULL, true), 'green');
			$indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_VOICE."</label>", $voice_handler->getCount(NULL, true), 'green');
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_SONGS."</label>", $songs_handler->getCount(NULL, true), 'green');
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_REQUESTS."</label>", $requests_handler->getCount(NULL, true), 'green');
		    $indexAdmin->addInfoBoxLine(_AM_SONGLIST_COUNT, "<label>"._AM_SONGLIST_NUMBER_OF_VOTES."</label>", $votes_handler->getCount(NULL, true), 'green');
    		echo $indexAdmin->renderIndex();

	        xoops_cp_footer();
	        break;
	}

?>