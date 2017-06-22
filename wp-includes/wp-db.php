<?php
	// ==================================================================
	//  Author: Justin Vincent (justin@visunet.ie)
	//	Web: 	http://php.justinvincent.com
	//	Name: 	ezSQL
	// 	Desc: 	Class to make it very easy to deal with mySQL database connections.
	//  WordPress is using this class to make the code cleaner and faster.
	//  We highly recommend it.
	//  We have modified the HTML it returns slightly.

	define('EZSQL_VERSION', '1.21');
	define('OBJECT', 'OBJECT', true);
	define('ARRAY_A', 'ARRAY_A', true);
	define('ARRAY_N', 'ARRAY_N', true);
	define('SAVEQUERIES', true);

	//	The Main Class, renamed to avoid conflicts.

	class wpdb {

		var $debug_called;
		var $vardump_called;
		var $show_errors = true;
		var $querycount;

		// ==================================================================
		//	DB Constructor - connects to the server and selects a database

		function wpdb($dbuser, $dbpassword, $dbname, $dbhost) {
			
		}
	} 
?>