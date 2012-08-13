<?php

require ('includes/map-manager-ajax.php');
require ('includes/mce-hooks.php');
require_once ( 'includes/theme-options.php');

function vor_theme_setup() {
    // override excerpt functionality from parent theme
    remove_filter('get_the_excerpt', 'responsive_custom_excerpt_more');
    remove_filter('excerpt_more', 'responsive_auto_excerpt_more');
    add_filter('excerpt_more', 'vor_excerpt_more');

    // override the default gallery shortcode from wordpress
    // allows us to use rotator w/ lightboxing effect
    remove_shortcode('gallery', 'gallery_shortcode');
    add_shortcode('gallery', 'vor_gallery_shortcode');

    // add image sizes to be used in the custom gallery
    add_image_size('gallery-big', 600, 320, false);
    add_image_size('gallery-lightbox', null, 500, false);
}
add_action('after_setup_theme', 'vor_theme_setup');


wp_register_script('seadragon', get_stylesheet_directory_uri() . '/js/seadragon-min.js', array());
wp_register_script('raphael', get_stylesheet_directory_uri() . '/js/raphael-min.js', array());
wp_register_script('seajax', get_stylesheet_directory_uri() . '/js/seajax-utils.js', array('seadragon', 'raphael'));
wp_register_script('map-manager', get_stylesheet_directory_uri() . '/js/map-manager.js', array('jquery', 'seajax'));
wp_register_script('slides', get_stylesheet_directory_uri() . '/js/slides.js', array('jquery'));
wp_register_script('lightbox', get_stylesheet_directory_uri() . '/js/lightbox.js', array('jquery'));


/**
 * @deprecated
 */
function get_excluded_pages($as_string = false) {
    $excluded_ids = Array(
        9,          // Article List
        25,         // About Us
        123,        // Recent Articles
        127,        // Sitemap
        83,        // Map Manager
    );
    
    if ($as_string)
        return implode(",", $excluded_ids);
    return $excluded_ids;
}

// custom gallery shortcode
function vor_gallery_shortcode($attr) {
    global $post;
    
    $images = get_children( array(
        'post_parent' => $post->ID, 
        'post_status' => 'inherit', 
        'post_type' => 'attachment', 
        'post_mime_type' => 'image', 
        'order' => 'ASC', 
        'orderby' => 'menu_order ID') 
    );

    $output = "<div id='slides_wrapper'>";
    $output .= "<div id='slides'>";

    foreach ($images as $imageId => $image) {
        $image_attrs = wp_get_attachment_image_src($imageId,'gallery-big', false);
        $image_full_attrs = wp_get_attachment_image_src($imageId, 'gallery-lightbox', false);

        $width = $image_attrs[1];
        $height = $image_attrs[2];
        $output .= "<div class='slide'>";
        $output .= "<a href='$image_full_attrs[0]' rel='lightbox[slides]'>";
        $output .= "<img src='$image_attrs[0]' height='300px' />";
        $output .= "</a>";
        $output .= "<div class='caption'>$image->post_title</div>";
        $output .= "</div>";
    }
    $output .= "<div class='clearfix'></div>";
    $output .= "</div><!-- /#slides -->";

    $output .= "</div><!-- /#slides_wrapper -->";
    $output .= "<div class='slides-clear'></div>";
    //

    return $output;
}

function vor_excerpt_more($more) {
    global $id;
    return ' <a href="' . get_permalink($id) . '">' . __('<div class="read-more">Read more &#8250;</div><!-- end of .read-more -->', 'responsive') . '</a>';
}

function vor_get_images($size = 'thumbnail') {
    global $post;
    
    $photos = get_children( array(
        'post_parent'       => $post->ID, 
        'post_status'       => 'inherit', 
        'post_type'         => 'attachment', 
        'post_mime_type'    => 'image', 
        'order'             => 'ASC', 
        'orderby'           => 'menu_order ID') );
    
    $results = array();

    if ($photos) {
        foreach ($photos as $photo) {
            // get the correct image html for the selected size
            $results[] = wp_get_attachment_image($photo->ID, $size);
        }
    }

    return $results;
}

function vor_get_post_image($size = 'thumbnail') {
    global $post;

    $photos = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
    
    if ($photos) {
        $photo = array_shift($photos);
        return wp_get_attachment_image($photo->ID, $size);
    }
    
    return false;
}


if (!function_exists('disableAdminBar')) {
    function disableAdminBar() {
        remove_action('wp_head', '_admin_bar_bump_cb');
        wp_deregister_script('admin-bar');
        wp_deregister_style('admin-bar');
        remove_action('wp_footer','wp_admin_bar_render',1000);    
    }
}



/*
 * Wrapper for wls_log
 *
 */
function logit($level, $text) {
    // for development side only. 
    // used to log messages instead of having to use print statements
    if(function_exists('wls_log')) {
        $current_user = wp_get_current_user();
        wls_log('testing', $text, $current_user->ID, null, null, $level);
    }
}

?>
