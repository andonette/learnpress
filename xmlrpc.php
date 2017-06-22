<?php

# fix for mozBlog and other cases where '<?xml' isn't on the very first line
$HTTP_RAW_POST_DATA = trim($HTTP_RAW_POST_DATA);

include('wp-config.php');

require_once(ABSPATH.WPINC."/class-xmlrpc.php");
require_once(ABSPATH.WPINC."/class-xmlrpcs.php");
require_once(ABSPATH.WPINC."/template-functions.php");
require_once(ABSPATH.WPINC."/functions.php");
?>
