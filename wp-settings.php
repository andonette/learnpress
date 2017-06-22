<?php
$HTTP_HOST = getenv('HTTP_HOST');  /* domain name */
$REMOTE_ADDR = getenv('REMOTE_ADDR'); /* visitor's IP */
$HTTP_USER_AGENT = getenv('HTTP_USER_AGENT'); /* visitor's browser */

// Change to E_ALL for development/debugging
error_reporting(E_ALL ^ E_NOTICE);

// Table names
$tableposts               = $table_prefix . 'posts';
$tableusers               = $table_prefix . 'users';
$tablesettings            = $table_prefix . 'settings'; // only used during upgrade
$tablecategories          = $table_prefix . 'categories';
$tablepost2cat            = $table_prefix . 'post2cat';
$tablecomments            = $table_prefix . 'comments';
$tablelinks               = $table_prefix . 'links';
$tablelinkcategories      = $table_prefix . 'linkcategories';
$tableoptions             = $table_prefix . 'options';
$tableoptiontypes         = $table_prefix . 'optiontypes';
$tableoptionvalues        = $table_prefix . 'optionvalues';
$tableoptiongroups        = $table_prefix . 'optiongroups';
$tableoptiongroup_options = $table_prefix . 'optiongroup_options';

define('WPINC', 'wp-includes');
require (ABSPATH . WPINC . '/wp-db.php');

$wpdb->hide_errors();
if (!$wpdb->get_row("SELECT * FROM $tableoptions LIMIT 1") && !strstr($HTTP_SERVER_VARS['REQUEST_URI'], 'install.php')) {
	die("It doesn't look like you've installed WP yet. Try running <a href='wp-admin/install.php'>install.php</a>.");
}
$wpdb->show_errors();

// This is the name of the include directory. No "/" allowed.

require (ABSPATH . WPINC . '/functions.php');
require (ABSPATH . 'wp-config-extra.php');

?>