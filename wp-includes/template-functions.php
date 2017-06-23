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

function get_bloginfo($show='') {
	global $siteurl, $blogfilename, $blogname, $blogdescription, $siteurl, $admin_email;
	switch($show) {
		case "url":
			$output = $siteurl."/".$blogfilename;
			break;
		case "description":
			$output = $blogdescription;
			break;
		case "rdf_url":
			$output = $siteurl.'/wp-rdf.php';
			break;
		case "rss_url":
			$output = $siteurl.'/wp-rss.php';
			break;
		case "rss2_url":
			$output = $siteurl.'/wp-rss2.php';
			break;
		case "atom_url":
			$output = $siteurl.'/wp-atom.php';
			break;		
		case "comments_rss2_url":
			$output = $siteurl.'/wp-commentsrss2.php';
			break;
		case "pingback_url":
			$output = $siteurl.'/xmlrpc.php';
			break;
		case "admin_email":
			$output = $admin_email;
			break;
		case "name":
		default:
			$output = $blogname;
			break;
	}
	return $output;
}

function wp_title($sep = '&raquo;', $display = true) {
	global $wpdb, $tableposts, $tablecategories;
	global $year, $monthnum, $day, $cat, $p, $name;

	// If there's a category
	if(!empty($cat)) {
		$title = stripslashes(get_the_category_by_ID($cat));
	}
	if (!empty($category_name)) {
		$title = stripslashes($wpdb->get_var("SELECT cat_name FROM $tablecategories WHERE category_nicename = '$category_name'"));
	}

	// If there's a month
	if(!empty($m)) {
		$my_year = substr($m, 0, 4);
		$my_month = $month[substr($m, 4, 2)];
		$title = "$my_year $sep $my_month";

	}
	if (!empty($year)) {
		$title = $year;
		if (!empty($monthnum)) {
			$title .= "$sep $monthnum";
		}
		if (!empty($day)) {
			$title .= " $sep $day";
		}
	}

	// If there's a post
	if (intval($p) || '' != $name) {
		if (!$p) {
		if ($year != '') {
			$year = '' . intval($year);
			$where .= ' AND YEAR(post_date)=' . $year;
		}
		
		if ($monthnum != '') {
			$monthnum = '' . intval($monthnum);
			$where .= ' AND MONTH(post_date)=' . $monthnum;
		}
		
		if ($day != '') {
			$hay = '' . intval($day);
			$where .= ' AND DAYOFMONTH(post_date)=' . $day;
		}
			$p = $wpdb->get_var("SELECT ID FROM $tableposts WHERE post_name = '$name' $where");
		}
		$post_data = get_postdata($p);
		$title = strip_tags(stripslashes($post_data['Title']));
		$title = apply_filters('single_post_title', $title);
	}

	// Send it out
	if ($display && isset($title)) {
		echo " $sep $title";
	} elseif (!$display && isset($title)) {
		return " $sep $title";
	}
}

function single_post_title($prefix = '', $display = true) {
	global $p, $name, $wpdb, $tableposts;
	if (intval($p) || '' != $name) {
		if (!$p) {
			$p = $wpdb->get_var("SELECT ID FROM $tableposts WHERE post_name = '$name'");
		}
		$post_data = get_postdata($p);
		$title = $post_data['Title'];
		$title = apply_filters('single_post_title', $title);
		if ($display) {
			echo $prefix.strip_tags(stripslashes($title));
		} else {
			return strip_tags(stripslashes($title));
		}
	}
}

function single_cat_title($prefix = '', $display = true ) {
	global $cat;
	if(!empty($cat) && !(strtoupper($cat) == 'ALL')) {
		$my_cat_name = get_the_category_by_ID($cat);
		if(!empty($my_cat_name)) {
			if ($display)
				echo $prefix.strip_tags(stripslashes($my_cat_name));
			else
				return strip_tags(stripslashes($my_cat_name));
		}
	}
}

function single_month_title($prefix = '', $display = true ) {
	global $m, $month;
	if(!empty($m)) {
		$my_year = substr($m,0,4);
		$my_month = $month[substr($m,4,2)];
		if ($display)
			echo $prefix.$my_month.$prefix.$my_year;
		else
			return $m;
	}
}

?>