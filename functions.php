<?php

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
        132,        // Map Manager
    );
    
    if ($as_string)
        return implode(",", $excluded_ids);
    return $excluded_ids;
}

if (!function_exists('disableAdminBar')) {
    function disableAdminBar() {
        remove_action('wp_head', '_admin_bar_bump_cb');
        wp_deregister_script('admin-bar');
        wp_deregister_style('admin-bar');
        remove_action('wp_footer','wp_admin_bar_render',1000);    
    }
}


/**
 * Begin ajax functions for map manager
 */
function get_overlay_data() {
    global $wpdb;
    
    $query = "select id, coords from wp_ligorio_data;";

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

//TODO: 
function post_overlay_data() {
    global $wpdb;
    //set up varables to be used for insert
    //echo "<pre>" . print_r($_POST, true) . "</pre>";

    $tableName = 'wp_ligorio_data';
    if ($_POST["data"]["overwrite"] == "true") {
        // delete rows corresponding to id from wp_ligorio_data
        $wpdb->query(
            $wpdb->prepare(
                "DELETE from $tableName
                  WHERE id = %d",
                $_POST["data"]["id"]
            )
        );
    } 
    
    $inputFormat = array(
        '%d',
        '%s'
    );
    foreach($_POST['data']['points'] as $overlay) {
        $inputData = array(
            //'title' => 'Collisseum',
            'id' => $_POST['data']['id'],
            'coords' => json_encode(array("points" => $overlay))
        );
        //echo json_encode($inputData);
        $wpdb->insert($tableName, $inputData, $inputFormat);
    }
    
    exit;
}

function get_post_data() {
    global $wpdb;

    $page = get_page($_GET['id']);
    $page_data = array(
        "ID"            => $page->ID,
        "guid"          => $page->guid,
        "post_title"    => $page->post_title,
        "post_content"  => $page->post_content,
        "post_excerpt"  => apply_filters('the_excerpt', $page->post_content),
    );
    header("Content-type: application/json");
    echo json_encode($page_data);

    exit;
}

add_action('wp_ajax_post_overlay_data', 'post_overlay_data');

add_action('wp_ajax_get_overlay_data', 'get_overlay_data');
add_action('wp_ajax_nopriv_get_overlay_data', 'get_overlay_data');

add_action('wp_ajax_get_post_data', 'get_post_data');
add_action('wp_ajax_nopriv_get_post_data', 'get_post_data');

?>
