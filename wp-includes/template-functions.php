<?php 

/* new and improved ! now with more querystring stuff ! */

if (!isset($querystring_start)) {
	$querystring_start = '?';
	$querystring_equal = '=';
	$querystring_separator = '&amp;';
}

/* template functions... */


// @@@ These are template tags, you can edit them if you know what you're doing...



/***** About-the-blog tags *****/
/* Note: these tags go anywhere in the template */

function bloginfo($show='') {
	$info = get_bloginfo($show);
	$info = convert_bbcode($info);
	$info = convert_gmcode($info);
	$info = convert_smilies($info);
	$info = apply_filters('bloginfo', $info);
	echo convert_chars($info, 'html');
}

function bloginfo_rss($show='') {
	$info = strip_tags(get_bloginfo($show));
	echo convert_chars($info, 'unicode');
}

function bloginfo_unicode($show='') {
	$info = get_bloginfo($show);
	echo convert_chars($info, 'unicode');
}

?>