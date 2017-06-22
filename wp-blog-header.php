<?php
$use_cache = 1; // No reason not to

/* Including config and functions files */
$curpath = dirname(__FILE__).'/';

if (!file_exists($curpath . '/wp-config.php'))
	die("There doesn't seem to be a <code>wp-config.php</code> file. I need this before we can get started. Need more help? <a href='http://wordpress.org/docs/faq/#wp-config'>We got it</a>.");

require_once ($curpath.'/wp-config.php');

?>
