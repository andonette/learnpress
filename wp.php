<?php 

$blog=1;
require_once("wp-blog-header.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml/DTD/xhtml-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php bloginfo('name') ?><?php single_post_title(' :: ') ?><?php single_cat_title(' :: ') ?><?php single_month_title(' :: ') ?></title>
  <!-- Change charset if needed(?)  But please do not remove this metatag -->
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="generator" content="WordPress <?php $wp_version ?>" /> <!-- leave this for stats -->
  <meta http-equiv="reply-to" content="you@somewhere.zzz" />
  <link rel="alternate" type="text/xml" title="RDF" href="<?php bloginfo('rdf_url'); ?>" />
  <link rel="alternate" type="text/xml" title="RSS" href="<?php bloginfo('rss2_url'); ?>" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
</head>

<body>
<h1 id="header"><a href="<?php echo $siteurl; ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>

?>