<?php
/*
Plugin Name: CF WWW fix
Plugin URI: http://crowdfavorite.com
Description: Adds www. to domains without editing WordPress database entries.  Works in WordPress 2.6-2.7 and WPMU 2.6+.
Version: 1.0
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

//ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

function cf_www_fix($home) {
	if(is_feed() || is_trackback() || is_404() || is_search() || is_tax() || is_home() || is_attachment() || is_single() || is_page() || is_category() || is_tag() || is_author() || is_date() || is_archive() || is_comments_popup() || is_paged()) {
		if (strpos($home,'www.') === false) {
			return str_replace('http://', 'http://www.', $home);
		}
	}
	return $home;
}
add_filter('option_home','cf_www_fix');
add_filter('option_url','cf_www_fix');
add_filter('option_siteurl','cf_www_fix');
add_filter('template_directory_uri','cf_www_fix');
add_filter('stylesheet_directory_uri','cf_www_fix');
add_filter('stylesheet_uri','cf_www_fix');
add_filter('wp_get_attachment_url','cf_www_fix');

/**
 * Catch $_SERVER['SERVER_NAME'] in the content without www. and fix it.
 * All media gallery files inserted with this plugin enabled will have the www from the above fixes, but legacy content, 
 * or mistakenly missing www. will not
 *
 * @param string $content 
 * @return string
 */
function cf_www_content_fix($content) {
	if(is_feed() || is_trackback() || is_404() || is_search() || is_tax() || is_home() || is_attachment() || is_single() || is_page() || is_category() || is_tag() || is_author() || is_date() || is_archive() || is_comments_popup() || is_paged()) {
		$find = 'http://'.str_replace('www.','',$_SERVER['SERVER_NAME']);
		$replace = 'http://'. (strpos($_SERVER['SERVER_NAME'],'www') !== 0 ? 'www.' : null) .$_SERVER['SERVER_NAME'];
		$content = str_replace($find,$replace,$content);
	}
	return $content;
}
add_filter('the_content','cf_www_content_fix');
?>