<?php

	// XOOPS Version
	define('_MI_SONGLIST_NAME','Songlist');
	define('_MI_SONGLIST_DESC','Karioki Songlist - a module for keeping karioki song lists and ranking of them.');
	define('_MI_SONGLIST_DIRNAME','songlist');
	
	// Form langauges
	define('_MI_SONGLIST_NONE','None');
	define('_MI_SONGLIST_ALL','All');
	define('_MI_SONGLIST_OFTEN',' of 10.00');
	
	// Javascripts and style sheets
	define('_MI_SONGLIST_JQUERY','/browse.php?Frameworks/jquery/jquery.js');
	define('_MI_SONGLIST_STYLESHEET','/modules/songlist/css/songlist.css');
	
	// Admin Menus
	define('_MI_SONGLIST_ADMENU_DASHBOARD','Dashboard');
	define('_MI_SONGLIST_ADMENU_CATEGORY','Category\'s');
	define('_MI_SONGLIST_ADMENU_ALBUMS','Albums');
	define('_MI_SONGLIST_ADMENU_ARTISTS','Artists');
	define('_MI_SONGLIST_ADMENU_SONGS','Songs');
	define('_MI_SONGLIST_ADMENU_GENRE','Genre');
	define('_MI_SONGLIST_ADMENU_VOICE','Voice');
	define('_MI_SONGLIST_ADMENU_VOTE','Votes');
	define('_MI_SONGLIST_ADMENU_PERMISSIONS','Permissions');
	define('_MI_SONGLIST_ADMENU_REQUESTS','Requests');
	define('_MI_SONGLIST_ADMENU_UTF8MAP','UTF8 Map');
	define('_MI_SONGLIST_ADMENU_IMPORT','Import');
	define('_MI_SONGLIST_ADMENU_FIELDS','Fields');
	define('_MI_SONGLIST_ADMENU_FIELDSPERMS','Field Permissions');
	define('_MI_SONGLIST_ADMENU_ABOUT','About Songlist');
	
	//User Menus
	define('_MI_SONGLIST_MENU_ARTISTS','Browse Artists');
	define('_MI_SONGLIST_MENU_ALBUMS','Browse Albums');
	define('_MI_SONGLIST_MENU_SEARCH','Search for a song');
	define('_MI_SONGLIST_MENU_REQUEST','Request a Listing');
	
	// Blocks
	define('_MI_SONGLIST_BLOCK_TOP_ARTIST','Top Artist');
	define('_MI_SONGLIST_BLOCK_POPULAR_ARTISTS','Top Popular Artists');
	define('_MI_SONGLIST_BLOCK_TOP_ALBUM','Top Album');
	define('_MI_SONGLIST_BLOCK_POPULAR_ALBUMS','Top Popular Albums');
	define('_MI_SONGLIST_BLOCK_TOP_GENRE','Top Genre');
	define('_MI_SONGLIST_BLOCK_POPULAR_GENRES','Top Popular Genre\'s');
	define('_MI_SONGLIST_BLOCK_TOP_SONG','Top Song');
	define('_MI_SONGLIST_BLOCK_POPULAR_SONGS','Top Popular Songs');
	
	// Preferences
	define('_MI_SONGLIST_EDITORS','Editor to Use');
	define('_MI_SONGLIST_EDITORS_DESC','');
	define('_MI_SONGLIST_SALT','Encryption Salt');
	define('_MI_SONGLIST_SALT_DESC','');
	define('_MI_SONGLIST_EMAIL','Email to send requests to');
	define('_MI_SONGLIST_EMAIL_DESC','Seperated with a pipe symbol ie. <em>email@address1.com|email@address2.com|email@address3.com</em>');
	define('_MI_SONGLIST_FILESIZEUPLD','Filesize Upload Allowed');
	define('_MI_SONGLIST_FILESIZEUPLD_DESC','');
	define('_MI_SONGLIST_ALLOWEDMIMETYPE','File mimetypes Allowed');
	define('_MI_SONGLIST_ALLOWEDMIMETYPE_DESC','');
	define('_MI_SONGLIST_ALLOWEDEXTENSIONS','File Extensions Allowed');
	define('_MI_SONGLIST_ALLOWEDEXTENSIONS_DESC','');
	define('_MI_SONGLIST_UPLOADAREAS','Upload path');
	define('_MI_SONGLIST_UPLOADAREAS_DESC','');
	define('_MI_SONGLIST_ALBUM','Support Albums?');
	define('_MI_SONGLIST_ALBUM_DESC','');
	define('_MI_SONGLIST_GENRE','Support Genre?');
	define('_MI_SONGLIST_GENRE_DESC','');
	define('_MI_SONGLIST_VOICE','Support Voice?');
	define('_MI_SONGLIST_VOICE_DESC','');	
	define('_MI_SONGLIST_SINGER','Allow Singer Type in Search?');
	define('_MI_SONGLIST_SINGER_DESC','');
	define('_MI_SONGLIST_HTACCESS','SEO Supported (see doc/.htaccess)');
	define('_MI_SONGLIST_HTACCESS_DESC','');
	define('_MI_SONGLIST_HTACCESS_BASEOFURL','Base of SEO URL');
	define('_MI_SONGLIST_HTACCESS_BASEOFURL_DESC','');
	define('_MI_SONGLIST_HTACCESS_ENDOFURL','End of SEO URL');
	define('_MI_SONGLIST_HTACCESS_ENDOFURL_DESC','');
	define('_MI_SONGLIST_TAGS','Support Tag 2.3 Module?');
	define('_MI_SONGLIST_TAGS_DESC','');
	define('_MI_SONGLIST_FORCE_JQUERY','For JQuery Loading');
	define('_MI_SONGLIST_FORCE_JQUERY_DESC','For themes without JQuery!');
	define('_MI_SONGLIST_COLS','Columns in table');
	define('_MI_SONGLIST_COLS_DESC','Columns in table before pagination.');
	define('_MI_SONGLIST_ROWS','Rows in table');
	define('_MI_SONGLIST_ROWS_DESC','Rows in table before pagination.');
	
	// Version 1.11
	// Preferences
	define('_MI_SONGLIST_MEMORY_ADMIN','Memory Limit for Admin');
	define('_MI_SONGLIST_MEMORY_ADMIN_DESC','This is the memory limit in megabytes for the admin of songlist, you may have to increase this over a certain amount of records.');
	define('_MI_SONGLIST_MEMORY_USER','Memory Limit for User Interface');
	define('_MI_SONGLIST_MEMORY_USER_DESC','This is the memory limit in megabytes for the user interface of songlist, you may have to increase this over a certain amount of records.');
	define('_MI_SONGLIST_TIME_ADMIN','Second Limit of Execution for Admin');
	define('_MI_SONGLIST_TIME_ADMIN_DESC','This is the time limit in megabytes for the admin of songlist, you may have to increase this over a certain amount of records.');
	define('_MI_SONGLIST_TIME_USER','Second Limit of Execution for User Interface');
	define('_MI_SONGLIST_TIME_USER_DESC','This is the time limit in megabytes for the user interface of songlist, you may have to increase this over a certain amount of records.');
	
	// version 1.12
	define('_MI_SONGLIST_LYRICS','Display Lyrics in Results?');
	define('_MI_SONGLIST_LYRICS_DESC','If you enable this the lyrics will be displayed in the search results!');

?>
