<?php 
/**
 * Begin ajax functions for map manager
 */
function vor_get_overlay_data() {
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
        $row["tags"] = get_the_tags($id = $row["id"]);
        $row["categories"] = array();

        $i = 0;
        $categories = get_the_category($id = $row["id"]);
        foreach ($categories as $category) {
            $row["categories"][$i] = $category->cat_ID;
            $i++;
        }
    }

    //echo "<pre>" . print_r($results, true) . "</pre>";
    header("Content-type: application/json");
    //echo $results['coords'];
    echo json_encode(Array("overlays" => $results));
    exit;
}
add_action('wp_ajax_vor_get_overlay_data', 'vor_get_overlay_data');
add_action('wp_ajax_nopriv_vor_get_overlay_data', 'vor_get_overlay_data');


function vor_post_overlay_data() {
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
add_action('wp_ajax_vor_post_overlay_data', 'vor_post_overlay_data');

/**
 *
 *
 */
function vor_get_post_data() {
    global $wpdb;

    // setup args for the loop
    $args = array(
        "p" => $_GET['id'] // returns a single post with the given id
    );

    query_posts($args);

    $page_data = array();

    // this is because we have to have the loop to populate the excerpt, 
    // not ideal but works and is the wordpress way
    while (have_posts()) : the_post();
        $page_data = array(
            "ID"            => get_the_ID(),
            "guid"          => get_the_guid(),
            "post_title"    => get_the_title(),
            "post_content"  => get_the_content("Read on.."),
            "post_excerpt"  => get_the_excerpt(),
            "permalink"     => get_permalink(get_the_ID())
        );
    endwhile;

    header("Content-type: application/json");
    echo json_encode($page_data);

    exit;
}
add_action('wp_ajax_vor_get_post_data', 'vor_get_post_data');
add_action('wp_ajax_nopriv_vor_get_post_data', 'vor_get_post_data');
?>