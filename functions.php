<?php

wp_register_script('eul-overlay-manager', get_stylesheet_directory_uri() . '/js/map-manager.js', array('jquery'));
wp_register_script('seadragon', get_stylesheet_directory_uri() . '/js/seadragon-min.js', array());
wp_register_script('raphael', get_stylesheet_directory_uri() . '/js/raphael-min.js', array());
wp_register_script('seajax', get_stylesheet_directory_uri() . '/js/seajax-utils.js', array('seadragon', 'raphael'));

function get_overlay_data() {
    global $wpdb;
    
    $query = "select title, id, coords from wp_ligorio_data;";

    $results = $wpdb->get_results($query, ARRAY_A);
    
    echo json_encode($results);
    
    return 0;
}

function post_overlay_data() {
    global $wpdb;
    echo $_POST['data'];
    if(isset($_POST['data'])) {
        $data = $_POST['data'];

        //set up varables to be used for insert
        $tableName = 'wp_ligorio_data';
        $inputData = array(
            'title' => $data['title'],
            'id'    => $data['id'],
            'coords'=> json_encode($data['data']) //serialize, could possibly json_encoded
        );
        $inputFormat = array(
            '%s',
            '%d',
            '%s'
        );

        $wpdb->insert($tableName, $inputData, $coords);

        echo 'Inserted Data';
    }

    return 0;
}

add_action('wp_ajax_post_overlay_data', 'post_overlay_data');
add_action('wp_ajax_get_overlay_data', 'get_overlay_data');
add_action('wp_ajax_nopriv_get_overlay_data', 'get_overlay_data');
?>
