<?php

if (!function_exists('_')) {
	function _($string) {
		return $string;
	}
}

if (!function_exists('floatval')) {
	function floatval($string) {
		return ((float) $string);
	}
}

/* functions... */

/***** Formatting functions *****/
function wptexturize($text) {
	$output = "";
	$textarr = preg_split("/(<.*>)/Us", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
	$stop = count($textarr); $next = true; // loop stuff
	for ($i = 0; $i < $stop; $i++) {
		$curl = $textarr[$i];
		if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Gecko')) {
			$curl = str_replace('<q>', '&#8220;', $curl);
			$curl = str_replace('</q>', '&#8221;', $curl);
		}
		if (isset($curl{0}) && '<' != $curl{0} && $next) { // If it's not a tag
			$curl = str_replace('---', '&#8212;', $curl);
			$curl = str_replace('--', '&#8211;', $curl);
			$curl = str_replace("...", '&#8230;', $curl);
			$curl = str_replace('``', '&#8220;', $curl);

			// This is a hack, look at this more later. It works pretty well though.
			$cockney = array("'tain't","'twere","'twas","'tis","'twill","'til","'bout","'nuff","'round");
			$cockneyreplace = array("&#8217;tain&#8217;t","&#8217;twere","&#8217;twas","&#8217;tis","&#8217;twill","&#8217;til","&#8217;bout","&#8217;nuff","&#8217;round");
			$curl = str_replace($cockney, $cockneyreplace, $curl);

			$curl = preg_replace("/'s/", "&#8217;s", $curl);
			$curl = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $curl);
			$curl = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $curl);
			$curl = preg_replace("/(\d+)\"/", "$1&Prime;", $curl);
			$curl = preg_replace("/(\d+)'/", "$1&prime;", $curl);
			$curl = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $curl);
			$curl = preg_replace('/"([\s.,!?;:&\']|\Z)/', '&#8221;$1', $curl);
            $curl = preg_replace('/(\s|\A)"/', '$1&#8220;', $curl);
			$curl = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $curl);
			$curl = preg_replace("/\(tm\)/i", '&#8482;', $curl);
			$curl = preg_replace("/\(c\)/i", '&#169;', $curl);
			$curl = preg_replace("/\(r\)/i", '&#174;', $curl);
			$curl = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $curl);
			$curl = str_replace("''", '&#8221;', $curl);
			
			$curl = preg_replace('/(d+)x(\d+)/', "$1&#215;$2", $curl);

		} elseif (strstr($curl, '<code') || strstr($curl, '<pre') || strstr($curl, '<kbd' || strstr($curl, '<style') || strstr($curl, '<script'))) {
			// strstr is fast
			$next = false;
		} else {
			$next = true;
		}
		$output .= $curl;
	}
	return $output;
}

function sanitize_title($title) {
    $title = strtolower($title);
	$title = preg_replace('/&.+;/', '', $title); // kill entities
    $title = preg_replace('/[^a-z0-9 -]/', '', $title);
    $title = preg_replace('/\s+/', ' ', $title);
    $title = trim($title);
    $title = str_replace(' ', '-', $title);
	return $title;
}

function popuplinks($text) {
	// Comment text in popup windows should be filtered through this.
	// Right now it's a moderately dumb function, ideally it would detect whether
	// a target or rel attribute was already there and adjust its actions accordingly.
	$text = preg_replace('/<a (.+?)>/i', "<a $1 target='_blank' rel='external'>", $text);
	return $text;
}

function autobrize($content) {
	$content = preg_replace("/<br>\n/", "\n", $content);
	$content = preg_replace("/<br \/>\n/", "\n", $content);
	$content = preg_replace("/(\015\012)|(\015)|(\012)/", "<br />\n", $content);
	return $content;
	}
function unautobrize($content) {
	$content = preg_replace("/<br>\n/", "\n", $content);   //for PHP versions before 4.0.5
	$content = preg_replace("/<br \/>\n/", "\n", $content);
	return $content;
	}


function format_to_edit($content) {
	global $autobr;
	$content = stripslashes($content);
	if ($autobr) { $content = unautobrize($content); }
	$content = htmlspecialchars($content);
	return $content;
	}
function format_to_post($content) {
	global $post_autobr,$comment_autobr;
	$content = addslashes($content);
	if ($post_autobr || $comment_autobr) { $content = autobrize($content); }
	return $content;
	}


function zeroise($number,$threshold) { // function to add leading zeros when necessary
	$l=strlen($number);
	if ($l<$threshold)
		for ($i=0; $i<($threshold-$l); $i=$i+1) { $number='0'.$number;	}
	return $number;
	}


function backslashit($string) {
	$string = preg_replace('/([a-z])/i', '\\\\\1', $string);
	return $string;
}

function mysql2date($dateformatstring, $mysqlstring, $use_b2configmonthsdays = 1) {
	global $month, $weekday;
	$m = $mysqlstring;
	if (empty($m)) {
		return false;
	}
	$i = mktime(substr($m,11,2),substr($m,14,2),substr($m,17,2),substr($m,5,2),substr($m,8,2),substr($m,0,4)); 
	if (!empty($month) && !empty($weekday) && $use_b2configmonthsdays) {
		$datemonth = $month[date('m', $i)];
		$dateweekday = $weekday[date('w', $i)];
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace("/([^\\\])D/", "\\1".backslashit(substr($dateweekday, 0, 3)), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])F/", "\\1".backslashit($datemonth), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])l/", "\\1".backslashit($dateweekday), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])M/", "\\1".backslashit(substr($datemonth, 0, 3)), $dateformatstring);
		$dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
	}
	$j = @date($dateformatstring, $i);
	if (!$j) {
	// for debug purposes
	//	echo $i." ".$mysqlstring;
	}
	return $j;
}

function current_time($type) {
	$time_difference = get_settings('time_difference');
	switch ($type) {
		case 'mysql':
			return date('Y-m-d H:i:s', (time() + ($time_difference * 3600) ) );
			break;
		case 'timestamp':
			return (time() + ($time_difference * 3600) );
			break;
	}
}

function addslashes_gpc($gpc) {
	if (!get_magic_quotes_gpc()) {
		$gpc = addslashes($gpc);
	}
	return $gpc;
}

function date_i18n($dateformatstring, $unixtimestamp) {
	global $month, $weekday;
	$i = $unixtimestamp; 
	if ((!empty($month)) && (!empty($weekday))) {
		$datemonth = $month[date('m', $i)];
		$dateweekday = $weekday[date('w', $i)];
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace("/([^\\\])D/", "\\1".backslashit(substr($dateweekday, 0, 3)), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])F/", "\\1".backslashit($datemonth), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])l/", "\\1".backslashit($dateweekday), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])M/", "\\1".backslashit(substr($datemonth, 0, 3)), $dateformatstring);
		$dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
	}
	$j = @date($dateformatstring, $i);
	return $j;
	}



function get_weekstartend($mysqlstring, $start_of_week) {
	$my = substr($mysqlstring,0,4);
	$mm = substr($mysqlstring,8,2);
	$md = substr($mysqlstring,5,2);
	$day = mktime(0,0,0, $md, $mm, $my);
	$weekday = date('w',$day);
	$i = 86400;
	while ($weekday > $start_of_week) {
		$weekday = date('w',$day);
		$day = $day - 86400;
		$i = 0;
	}
	$week['start'] = $day + 86400 - $i;
	$week['end']   = $day + 691199;
	return $week;
}

unction convert_chars($content,$flag='obsolete attribute left there for backwards compatibility') { // html/unicode entities output

	global $use_htmltrans, $wp_htmltrans, $wp_htmltranswinuni;

	// removes metadata tags
	$content = preg_replace('/<title>(.+?)<\/title>/','',$content);
	$content = preg_replace('/<category>(.+?)<\/category>/','',$content);
	
	if ($use_htmltrans) {

		// converts lone & characters into &#38; (a.k.a. &amp;)
		$content = preg_replace('/&[^#](?![a-z]*;)/ie', '"&#38;".substr("\0",1)', $content);

		// converts HTML-entities to their display values in order to convert them again later
		$content = preg_replace('/['.chr(127).'-'.chr(255).']/e', '"&#".ord(\'\0\').";"', $content );
		$content = strtr($content, $wp_htmltrans);

		// now converting: Windows CP1252 => Unicode (valid HTML)
		// (if you've ever pasted text from MSWord, you'll understand)

		$content = strtr($content, $wp_htmltranswinuni);

	}

	// you can delete these 2 lines if you don't like <br /> and <hr />
	$content = str_replace("<br>","<br />",$content);
	$content = str_replace("<hr>","<hr />",$content);

	return $content;

}

function convert_bbcode($content) {
	global $wp_bbcode, $use_bbcode;
	if ($use_bbcode) {
		$content = preg_replace($wp_bbcode["in"], $wp_bbcode["out"], $content);
	}
	$content = convert_bbcode_email($content);
	return $content;
}

function convert_bbcode_email($content) {
	global $use_bbcode;
	$bbcode_email["in"] = array(
		'#\[email](.+?)\[/email]#eis',
		'#\[email=(.+?)](.+?)\[/email]#eis'
	);
	$bbcode_email["out"] = array(
		"'<a href=\"mailto:'.antispambot('\\1').'\">'.antispambot('\\1').'</a>'",		// E-mail
		"'<a href=\"mailto:'.antispambot('\\1').'\">\\2</a>'"
	);

	$content = preg_replace($bbcode_email["in"], $bbcode_email["out"], $content);
	return $content;
}

function convert_gmcode($content) {
	global $wp_gmcode, $use_gmcode;
	if ($use_gmcode) {
		$content = preg_replace($wp_gmcode["in"], $wp_gmcode["out"], $content);
	}
	return $content;
}

function convert_smilies($text) {
	global $smilies_directory, $use_smilies;
	global $wp_smiliessearch, $wp_smiliesreplace;
    $output = '';
	if ($use_smilies) {
		// HTML loop taken from texturize function, could possible be consolidated
		$textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
		$stop = count($textarr);// loop stuff
		for ($i = 0; $i < $stop; $i++) {
			$content = $textarr[$i];
			if ((strlen($content) > 0) && ('<' != $content{0})) { // If it's not a tag
				$content = str_replace($wp_smiliessearch, $wp_smiliesreplace, $content);
			}
			$output .= $content;
		}
	} else {
		// return default text.
		$output = $text;
	}
	return $output;
}

function antispambot($emailaddy, $mailto=0) {
	$emailNOSPAMaddy = '';
	srand ((float) microtime() * 1000000);
	for ($i = 0; $i < strlen($emailaddy); $i = $i + 1) {
		$j = floor(rand(0, 1+$mailto));
		if ($j==0) {
			$emailNOSPAMaddy .= '&#'.ord(substr($emailaddy,$i,1)).';';
		} elseif ($j==1) {
			$emailNOSPAMaddy .= substr($emailaddy,$i,1);
		} elseif ($j==2) {
			$emailNOSPAMaddy .= '%'.zeroise(dechex(ord(substr($emailaddy, $i, 1))), 2);
		}
	}
	$emailNOSPAMaddy = str_replace('@','&#64;',$emailNOSPAMaddy);
	return $emailNOSPAMaddy;
}




?>