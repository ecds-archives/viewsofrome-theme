<?php

wp_register_script('seadragon', get_stylesheet_directory_uri() . '/js/seadragon-min.js', array());
wp_register_script('raphael', get_stylesheet_directory_uri() . '/js/raphael-min.js', array());
wp_register_script('seajax', get_stylesheet_directory_uri() . '/js/seajax-utils.js', array('seadragon', 'raphael'));


function get_excluded_pages($as_string = false) {
	$excluded_ids = Array(
		9,		// Article List
		25, 	// About Us
		98,		// Recent Articles
		101		// Sitemap
	);
	
	if ($as_string)
		return implode(",", $excluded_ids);
	return $excluded_ids;
}

?>