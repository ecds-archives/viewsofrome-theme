<?php

function vor_theme_setup() {
    remove_filter('get_the_excerpt', 'responsive_custom_excerpt_more');
    remove_filter('excerpt_more', 'responsive_auto_excerpt_more');
    add_filter('excerpt_more', 'vor_excerpt_more');

    remove_shortcode('gallery', 'gallery_shortcode');
    add_shortcode('gallery', 'vor_gallery_shortcode');
}
add_action('after_setup_theme', 'vor_theme_setup');

wp_register_script('eul-overlay-manager', get_stylesheet_directory_uri() . '/js/map-manager.js', array('jquery'));
wp_register_script('seadragon', get_stylesheet_directory_uri() . '/js/seadragon-min.js', array());
wp_register_script('raphael', get_stylesheet_directory_uri() . '/js/raphael-min.js', array());
wp_register_script('seajax', get_stylesheet_directory_uri() . '/js/seajax-utils.js', array('seadragon', 'raphael'));

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

// custom gallery code
function vor_gallery_shortcode($attr) {
    return "";
}


if (!function_exists('disableAdminBar')) {
    function disableAdminBar() {
        remove_action('wp_head', '_admin_bar_bump_cb');
        wp_deregister_script('admin-bar');
        wp_deregister_style('admin-bar');
        remove_action('wp_footer','wp_admin_bar_render',1000);    
    }
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

/**
 * Begin ajax functions for map manager
 */
function get_overlay_data() {
    global $wpdb;
    
    $query = "select id, coords from wp_ligorio_data";

    if ($_GET["data"]["id"]) {
        $query .= " where id = " . $_GET["data"]["id"];
    }
    //echo $query;

    $results = $wpdb->get_results($query, ARRAY_A);
    
    // decode json field as assoc. array fore easy json manipulation
    foreach ($results as &$row) {
        $row["coords"] = json_decode($row["coords"], true);
        $row["category"] = get_the_tags($id = $row["id"]);
    }

    //echo "<pre>" . print_r($results, true) . "</pre>";
    header("Content-type: application/json");
    //echo $results['coords'];
    echo json_encode(Array("overlays" => $results));
    exit;
}

function delete_post_data() {
    global $wpdb;
    $wpdb->query(
        "DELETE from wp_ligorio_data where id = 76"
    );

    exit;
}

function post_overlay_data() {
    global $wpdb;
    global $page;
    $tableName = 'wp_ligorio_data';
    //if ($_POST["data"]["overwrite"] == "true") {
    // delete rows corresponding to id from wp_ligorio_data
    $query = $wpdb->prepare("delete from " . $tableName . " where id = " . $_POST['data']['id']);
    $wpdb->query($query);
    //}
    
    $inputFormat = array(
        '%d',       // ID of article
        '%s'        // json encoded points array
    );
    if (count($_POST['data']['points']) > 0) {
        foreach($_POST['data']['points'] as $overlay) {
            $inputData = array(
                //'title' => 'Collisseum',
                'id' => $_POST['data']['id'],
                'coords' => json_encode(array("points" => $overlay))
            );
            //echo json_encode($inputData);
            $wpdb->insert($tableName, $inputData, $inputFormat);
        }
    }

    // echo a success message
    
    exit;
}

function get_post_data() {
    global $wpdb;
    //global $post;
    $post_res = get_post($_GET['id']);

    // setup the post data so we can get access to the excerpt
    setup_postdata($post_res);

    $page_data = array(
        "ID"            => $post_res->ID,
        "guid"          => $post_res->guid,
        "post_title"    => $post_res->post_title,
        "post_content"  => get_the_content("Read on.."),
        "post_excerpt"  => get_the_excerpt(),
        "permalink"     => get_permalink($post_res->ID)
    );
    header("Content-type: application/json");
    echo json_encode($page_data);

    exit;
}


// register custom ajax actions
add_action('wp_ajax_post_overlay_data', 'post_overlay_data');

add_action('wp_ajax_get_overlay_data', 'get_overlay_data');
add_action('wp_ajax_nopriv_get_overlay_data', 'get_overlay_data');

add_action('wp_ajax_get_post_data', 'get_post_data');
add_action('wp_ajax_nopriv_get_post_data', 'get_post_data');

add_action('wp_ajax_delete_post_data', 'delete_post_data');
add_action('wp_ajax_nopriv_delete_post_data', 'delete_post_data');

?>
