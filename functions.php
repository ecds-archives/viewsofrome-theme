<?php

wp_register_script('seadragon', get_stylesheet_directory_uri() . '/js/seadragon-min.js', array());
wp_register_script('raphael', get_stylesheet_directory_uri() . '/js/raphael-min.js', array());
wp_register_script('seajax', get_stylesheet_directory_uri() . '/js/seajax-utils.js', array('seadragon', 'raphael'));

function get_overlay_data() {
    if (isset($_GET['data'])) {
        $output = "";

        //validate somehow?
        $output .= json_encode($_GET['data']);
            
        echo $output;
    }
    die(0);
//    return $output;
}

function post_overlay_data() {
    echo $_POST['data'];
    if(isset($_POST['data'])) {
        $data = $_POST['data'];
        $output = "";
        
        $output .= "Title: " . $data['title'] . "<br>";
        $output .= "ID: " . $data['id'] . "<br>";

        echo $output;
    }

    return 0;
}

add_action('wp_ajax_post_overlay_data', 'post_overlay_data');
add_action('wp_ajax_get_overlay_data', 'get_overlay_data');
add_action('wp_ajax_nopriv_get_overlay_data', 'get_overlay_data');
?>
