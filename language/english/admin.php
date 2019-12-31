<?php

	// Dashboard
	define('_AM_SONGLIST_COUNT', 'Number of items counted');
	define('_AM_SONGLIST_NUMBER_OF_CATEGORY', 'Number of Categories: %s');
	define('_AM_SONGLIST_NUMBER_OF_ARTISTS', 'Number of Artists: %s');
	define('_AM_SONGLIST_NUMBER_OF_ALBUMS', 'Number of Albums: %s');
	define('_AM_SONGLIST_NUMBER_OF_GENRE', 'Number of Genre: %s');
	define('_AM_SONGLIST_NUMBER_OF_VOICE', 'Number of Voice: %s');
	define('_AM_SONGLIST_NUMBER_OF_SONGS', 'Number of Songs: %s');
	define('_AM_SONGLIST_NUMBER_OF_REQUESTS', 'Number of Request: %s');
	define('_AM_SONGLIST_NUMBER_OF_VOTES', 'Number of Votes: %s');
	
	// Table Headers
	define('_AM_SONGLIST_TH_GID', 'Genre ID');
	define('_AM_SONGLIST_TH_CID', 'Category ID');
	define('_AM_SONGLIST_TH_PID', 'Parent ID');
	define('_AM_SONGLIST_TH_NAME', 'Name');
	define('_AM_SONGLIST_TH_ARTISTS', 'Artists');
	define('_AM_SONGLIST_TH_ALBUMS', 'Album\'s');
	define('_AM_SONGLIST_TH_SONGS', 'Song\'s');
	define('_AM_SONGLIST_TH_HITS', 'Hit\'s');
	define('_AM_SONGLIST_TH_RANK', 'Rank');
	define('_AM_SONGLIST_TH_CREATED', 'Created');
	define('_AM_SONGLIST_TH_UPDATED', 'Updated');
	define('_AM_SONGLIST_TH_ACTIONS', 'Actions');
	define('_AM_SONGLIST_TH_SINGER', 'Singer');
	define('_AM_SONGLIST_TH_AID', 'Artist ID');
	define('_AM_SONGLIST_TH_SID', 'Song ID');
	define('_AM_SONGLIST_TH_ABID', 'Album');
	define('_AM_SONGLIST_TH_SONGID', 'Song Ref.');
	define('_AM_SONGLIST_TH_TITLE', 'Title');
	define('_AM_SONGLIST_TH_VID', 'Vote ID');
	define('_AM_SONGLIST_TH_UID', 'User');
	define('_AM_SONGLIST_TH_IP', 'IP Address');
	define('_AM_SONGLIST_TH_NETADDY', 'NetBIOS Address');
	define('_AM_SONGLIST_TH_RID', 'Request ID');
	define('_AM_SONGLIST_TH_ARTIST', 'Artist');
	define('_AM_SONGLIST_TH_ALBUM', 'Album');
	define('_AM_SONGLIST_TH_LYRICS', 'Lyrics');
	define('_AM_SONGLIST_TH_EMAIL', 'Email');
	define('_AM_SONGLIST_TH_UTFID', 'Map ID');
	define('_AM_SONGLIST_TH_FROM', 'From');
	define('_AM_SONGLIST_TH_TO', 'To');
	define('_AM_SONGLIST_TH_VCID', 'Voice ID');
	define('_AM_SONGLIST_TH_MP3', 'MP3');
			
	// Category
	define('_AM_SONGLIST_CATEGORY_H1', 'Categories');
	define('_AM_SONGLIST_CATEGORY_P', 'These are the categories and child categories you have on the system.');
	define('_AM_SONGLIST_NEW_CATEGORY_H1', 'New Category');
	define('_AM_SONGLIST_NEW_CATEGORY_P', 'Create a new Category from here');
	define('_AM_SONGLIST_EDIT_CATEGORY_H1', 'Edit Category');
	define('_AM_SONGLIST_EDIT_CATEGORY_P', 'Edit an existing Category from here');
	
	// Category Redirection Messages
	define('_AM_SONGLIST_MSG_CATEGORY_SAVEDOKEY', 'Category Saved Okey!');
	define('_AM_SONGLIST_MSG_CATEGORY_FAILEDTOSAVE', 'Category Failed to Save!');
	define('_AM_SONGLIST_MSG_CATEGORY_FAILEDTODELETE', 'Category Failed to Delete!');
	define('_AM_SONGLIST_MSG_CATEGORY_DELETED', 'Category Deleted Fine!');
	define('_AM_SONGLIST_MSG_CATEGORY_DELETE', 'Are you sure you wish to delete the Category "%s"?');
	
	// Artist
	define('_AM_SONGLIST_ARTISTS_H1', 'Artists');
	define('_AM_SONGLIST_ARTISTS_P', 'These are the artists you have on the system.');
	define('_AM_SONGLIST_NEW_ARTISTS_H1', 'New Artist');
	define('_AM_SONGLIST_NEW_ARTISTS_P', 'Create a new Artist from here');
	define('_AM_SONGLIST_EDIT_ARTISTS_H1', 'Edit Artist');
	define('_AM_SONGLIST_EDIT_ARTISTS_P', 'Edit an existing Artist from here');
	
	// Artist Redirection Messages
	define('_AM_SONGLIST_MSG_ARTISTS_SAVEDOKEY', 'Artist Saved Okey!');
	define('_AM_SONGLIST_MSG_ARTISTS_FAILEDTOSAVE', 'Artist Failed to Save!');
	define('_AM_SONGLIST_MSG_ARTISTS_FAILEDTODELETE', 'Artist Failed to Delete!');
	define('_AM_SONGLIST_MSG_ARTISTS_DELETED', 'Artist Deleted Fine!');
	define('_AM_SONGLIST_MSG_ARTISTS_DELETE', 'Are you sure you wish to delete the Artist "%s"?');
	
	// Album
	define('_AM_SONGLIST_ALBUMS_H1', 'Albums');
	define('_AM_SONGLIST_ALBUMS_P', 'These are the albums you have on the system.');
	define('_AM_SONGLIST_NEW_ALBUMS_H1', 'New Album');
	define('_AM_SONGLIST_NEW_ALBUMS_P', 'Create a new Album from here');
	define('_AM_SONGLIST_EDIT_ALBUMS_H1', 'Edit Album');
	define('_AM_SONGLIST_EDIT_ALBUMS_P', 'Edit an existing Album from here');
	
	// Album Redirection Messages
	define('_AM_SONGLIST_MSG_ALBUMS_SAVEDOKEY', 'Album Saved Okey!');
	define('_AM_SONGLIST_MSG_ALBUMS_FAILEDTOSAVE', 'Album Failed to Save!');
	define('_AM_SONGLIST_MSG_ALBUMS_FAILEDTODELETE', 'Album Failed to Delete!');
	define('_AM_SONGLIST_MSG_ALBUMS_DELETED', 'Album Deleted Fine!');
	define('_AM_SONGLIST_MSG_ALBUMS_DELETE', 'Are you sure you wish to delete the Album "%s"?');
	
	// Genre
	define('_AM_SONGLIST_GENRE_H1', 'Genres');
	define('_AM_SONGLIST_GENRE_P', 'These are the genre you have on the system.');
	define('_AM_SONGLIST_NEW_GENRE_H1', 'New Genre');
	define('_AM_SONGLIST_NEW_GENRE_P', 'Create a new Genre from here');
	define('_AM_SONGLIST_EDIT_GENRE_H1', 'Edit Genre');
	define('_AM_SONGLIST_EDIT_GENRE_P', 'Edit an existing Genre from here');
	
	// Voice
	define('_AM_SONGLIST_VOICE_H1', 'Voices');
	define('_AM_SONGLIST_VOICE_P', 'These are the Voice you have on the system.');
	define('_AM_SONGLIST_NEW_VOICE_H1', 'New Voice');
	define('_AM_SONGLIST_NEW_VOICE_P', 'Create a new Voice from here');
	define('_AM_SONGLIST_EDIT_VOICE_H1', 'Edit voice');
	define('_AM_SONGLIST_EDIT_VOICE_P', 'Edit an existing Voice from here');
	
		
	// Genre Redirection Messages
	define('_AM_SONGLIST_MSG_GENRE_SAVEDOKEY', 'Genre Saved Okey!');
	define('_AM_SONGLIST_MSG_GENRE_FAILEDTOSAVE', 'Genre Failed to Save!');
	define('_AM_SONGLIST_MSG_GENRE_FAILEDTODELETE', 'Genre Failed to Delete!');
	define('_AM_SONGLIST_MSG_GENRE_DELETED', 'Genre Deleted Fine!');
	define('_AM_SONGLIST_MSG_GENRE_DELETE', 'Are you sure you wish to delete the Genre "%s"?');

	// Voice Redirection Messages
	define('_AM_SONGLIST_MSG_VOICE_SAVEDOKEY', 'Voice Saved Okey!');
	define('_AM_SONGLIST_MSG_VOICE_FAILEDTOSAVE', 'Voice Failed to Save!');
	define('_AM_SONGLIST_MSG_VOICE_FAILEDTODELETE', 'Voice Failed to Delete!');
	define('_AM_SONGLIST_MSG_VOICE_DELETED', 'Voice Deleted Fine!');
	define('_AM_SONGLIST_MSG_VOICE_DELETE', 'Are you sure you wish to delete the Voice "%s"?');
	
	// Request
	define('_AM_SONGLIST_REQUESTS_H1', 'Requests');
	define('_AM_SONGLIST_REQUESTS_P', 'These are the user requests you have on the system.');
	define('_AM_SONGLIST_NEW_REQUESTS_H1', 'New Request');
	define('_AM_SONGLIST_NEW_REQUESTS_P', 'Create a new Request from here');
	define('_AM_SONGLIST_EDIT_REQUESTS_H1', 'Edit Request');
	define('_AM_SONGLIST_EDIT_REQUESTS_P', 'Edit an existing Request from here');
	
	// Request Redirection Messages
	define('_AM_SONGLIST_MSG_REQUESTS_SAVEDOKEY', 'Request Saved Okey!');
	define('_AM_SONGLIST_MSG_REQUESTS_FAILEDTOSAVE', 'Request Failed to Save!');
	define('_AM_SONGLIST_MSG_REQUESTS_FAILEDTODELETE', 'Request Failed to Delete!');
	define('_AM_SONGLIST_MSG_REQUESTS_DELETED', 'Request Deleted Fine!');
	define('_AM_SONGLIST_MSG_REQUESTS_DELETE', 'Are you sure you wish to delete the Request "%s"?');
	
	// Song
	define('_AM_SONGLIST_SONGS_H1', 'Songs');
	define('_AM_SONGLIST_SONGS_P', 'These are the songs you have on the system.');
	define('_AM_SONGLIST_NEW_SONGS_H1', 'New Song');
	define('_AM_SONGLIST_NEW_SONGS_P', 'Create a new Song from here');
	define('_AM_SONGLIST_EDIT_SONGS_H1', 'Edit Song');
	define('_AM_SONGLIST_EDIT_SONGS_P', 'Edit an existing Song from here');
	
	// Song Redirection Messages
	define('_AM_SONGLIST_MSG_SONGS_SAVEDOKEY', 'Song Saved Okey!');
	define('_AM_SONGLIST_MSG_SONGS_FAILEDTOSAVE', 'Song Failed to Save!');
	define('_AM_SONGLIST_MSG_SONGS_FAILEDTODELETE', 'Song Failed to Delete!');
	define('_AM_SONGLIST_MSG_SONGS_DELETED', 'Song Deleted Fine!');
	define('_AM_SONGLIST_MSG_SONGS_DELETE', 'Are you sure you wish to delete the Song "%s"?');
	
	// Character Conversion Mapping
	define('_AM_SONGLIST_UTF8MAP_H1', 'Character Conversion Mappings');
	define('_AM_SONGLIST_UTF8MAP_P', 'These are the character conversion mapping for search and keyboards you have on the system.');
	define('_AM_SONGLIST_NEW_UTF8MAP_H1', 'New Character Conversion Mapping');
	define('_AM_SONGLIST_NEW_UTF8MAP_P', 'Create a new Character Conversion Mapping from here');
	define('_AM_SONGLIST_EDIT_UTF8MAP_H1', 'Edit Character Conversion Mapping ');
	define('_AM_SONGLIST_EDIT_UTF8MAP_P', 'Edit an existing Character Conversion Mapping from here');
	
	// Character Conversion Mapping Redirection Messages
	define('_AM_SONGLIST_MSG_UTF8MAP_SAVEDOKEY', 'Character Conversion Mapping Saved Okey!');
	define('_AM_SONGLIST_MSG_UTF8MAP_FAILEDTOSAVE', 'Character Conversion Mapping Failed to Save!');
	define('_AM_SONGLIST_MSG_UTF8MAP_FAILEDTODELETE', 'Character Conversion Mapping Failed to Delete!');
	define('_AM_SONGLIST_MSG_UTF8MAP_DELETED', 'Character Conversion Mapping Deleted Fine!');
	define('_AM_SONGLIST_MSG_UTF8MAP_DELETE', 'Are you sure you wish to delete the Character Conversion Mapping "%s"?');
	
	// Vote
	define('_AM_SONGLIST_VOTES_H1', 'Votes');
	define('_AM_SONGLIST_VOTES_P', 'These are the votes you have on the system.');
	define('_AM_SONGLIST_NEW_VOTES_H1', 'New Vote');
	define('_AM_SONGLIST_NEW_VOTES_P', 'Create a new Vote from here');
	define('_AM_SONGLIST_EDIT_VOTES_H1', 'Edit Vote');
	define('_AM_SONGLIST_EDIT_VOTES_P', 'Edit an existing Vote from here');
		
	// Vote Redirection Messages
	define('_AM_SONGLIST_MSG_VOTES_SAVEDOKEY', 'Vote Saved Okey!');
	define('_AM_SONGLIST_MSG_VOTES_FAILEDTOSAVE', 'Vote Failed to Save!');
	define('_AM_SONGLIST_MSG_VOTES_FAILEDTODELETE', 'Vote Failed to Delete!');
	define('_AM_SONGLIST_MSG_VOTES_DELETED', 'Vote Deleted Fine!');
	define('_AM_SONGLIST_MSG_VOTES_DELETE', 'Are you sure you wish to delete the Vote "%s"?');

	// Fields
	define('_AM_SONGLIST_FIELDS_FIELDS', 'Fields');
	define('_AM_SONGLIST_FIELDS_FIELD', 'Field');
	define('_AM_SONGLIST_FIELDS_NAME', 'Name');
	define('_AM_SONGLIST_FIELDS_TITLE', 'Title');
	define('_AM_SONGLIST_FIELDS_DESCRIPTION', 'Description');
	define('_AM_SONGLIST_FIELDS_TYPE', 'Type');
	define('_AM_SONGLIST_FIELDS_CATEGORIES', 'Categories');
	define('_AM_SONGLIST_FIELDS_WEIGHT', 'Weight');
	define('_AM_SONGLIST_FIELDS_CATEGORY', 'Category');
	define('_AM_SONGLIST_FIELDS_DEFAULT', 'Default');
	define('_AM_SONGLIST_FIELDS_ADD', 'Add Field');
	define('_AM_SONGLIST_FIELDS_EDIT', 'Edit Field');
	define('_AM_SONGLIST_FIELDS_PROF_EDITABLE', 'Songs Field Editable?');
	
	//Field Redirection Messages
	define('_AM_SONGLIST_FIELDS_FIELDNOTCONFIGURABLE', 'Field not Configurable!');
	define('_AM_SONGLIST_FIELDS_SAVEDSUCCESS', '%s Saved Successfully!');
	define('_AM_SONGLIST_FIELDS_DELETEDSUCCESS', '%s Deleted Sucessfully!');
	
	//Field value types
	define('_AM_SONGLIST_FIELDS_ARRAY', 'Array');
	define('_AM_SONGLIST_FIELDS_EMAIL', 'eMail');
	define('_AM_SONGLIST_FIELDS_INT', 'Integer');
	define('_AM_SONGLIST_FIELDS_TXTAREA', 'Text Area');
	define('_AM_SONGLIST_FIELDS_TXTBOX', 'Text-box');
	define('_AM_SONGLIST_FIELDS_URL', 'URL');
	define('_AM_SONGLIST_FIELDS_OTHER', 'Other');
	define('_AM_SONGLIST_FIELDS_DATE', 'Date');
	
	//Field element types
	define('_AM_SONGLIST_FIELDS_CHECKBOX', 'Checkboxes');
	define('_AM_SONGLIST_FIELDS_GROUP', 'Single Groups');
	define('_AM_SONGLIST_FIELDS_GROUPMULTI', 'Multiple Groups');
	define('_AM_SONGLIST_FIELDS_LANGUAGE', 'Language Select');
	define('_AM_SONGLIST_FIELDS_RADIO', 'Radio Buttons');
	define('_AM_SONGLIST_FIELDS_SELECT', 'Single Select');
	define('_AM_SONGLIST_FIELDS_SELECTMULTI', 'Multiple Select');
	define('_AM_SONGLIST_FIELDS_TEXTAREA', 'Text Area');
	define('_AM_SONGLIST_FIELDS_DHTMLTEXTAREA', 'DHTML Text Editor');
	define('_AM_SONGLIST_FIELDS_EDITOR', 'Editor');
	define('_AM_SONGLIST_FIELDS_TEXTBOX', 'Text-box');
	define('_AM_SONGLIST_FIELDS_TIMEZONE', 'Timezone Select');
	define('_AM_SONGLIST_FIELDS_YESNO', 'Yes/No Radio');
	define('_AM_SONGLIST_FIELDS_DATETIME', 'Date Time');
	define('_AM_SONGLIST_FIELDS_LONGDATE', 'Long Date');
	define('_AM_SONGLIST_FIELDS_THEME', 'Theme Select');
	define('_AM_SONGLIST_FIELDS_AUTOTEXT', 'Autotext');
	define('_AM_SONGLIST_FIELDS_RANK', 'Rank');
	
	// Field Visibility
	define('_AM_SONGLIST_FIELDNOTVISIBLE', '--&nbsp;Field Not Visible&nbsp;--');
	define('_AM_SONGLIST_FIELDVISIBLEFOR', '&nbsp;Visible For&nbsp;');
	define('_AM_SONGLIST_FIELDVISIBLEON', '&nbsp;On&nbsp;');
	define('_AM_SONGLIST_FIELDVISIBLE', 'Field Visible:&nbsp;');
	
	// Field Permissions
	define('_AM_SONGLIST_PROF_VISIBLE', 'Field Visiblity');
	define('_AM_SONGLIST_PROF_EDITABLE', 'Field Editable');
	define('_AM_SONGLIST_PROF_POST', 'Field Post');
	define('_AM_SONGLIST_PROF_SEARCH', 'Field Searchable');

	// Import Wizards
	define('_AM_SONGLIST_IMPORT_NOFILE', 'No File Specified!');
	define('_AM_SONGLIST_IMPORT_ACTIONA_H1', 'Upload XML File to Import');
	define('_AM_SONGLIST_IMPORT_ACTIONA_P', 'Select an XML File you have exported from Excel or other platform to import.');
	define('_AM_SONGLIST_IMPORT_ACTIONB_H1', 'Specify Element Names');
	define('_AM_SONGLIST_IMPORT_ACTIONB_P', 'You have to specify from the example the elements that exists in this XML file to import, may vary from file to file and also if you specify no element name the field type is ignored..');
	define('_AM_SONGLIST_XMLFILE_COMPLETE', 'XML File Import and Comparison Complete!');
	define('_AM_SONGLIST_XMLFILE_COPIED', 'The source XML File has been copied for importing!');
		
	// About Box
	define('_AM_SONGLIST_ABOUT_MAKEDONATE', 'Make Song List Better Donate Today!');
	
?>