<?php
/**
 * Map Manager Template
 *
 * Template Name: Map Manager
 * @file        map-manager.php
 * @package     ViewsOfRome
 * @filesource  wp-content/themes/viewsofrome-theme/map-manager.php
 */



    wp_enqueue_script('seajax');
    wp_enqueue_script('eul-overlay-manager');
?>
<?php get_header(); ?>
<?php
    $limit = get_option('posts_per_page');
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts(array(
        'order'     => 'ASC',               // sort order, through admin?
        'orderby'  => 'title',              // sort field, through admin?
        'showposts' => $limit,
        'paged'     => $paged,
        'post_type' => 'page',              // limit to page types
        'post__not_in'   => array(9, 25, 83)    // look to do this through admin?
    ));
?>
<script type="text/javascript">
        
    var overlayManager;
    function init() {
        $(document).ready(function() {
            overlayManager = new EUL.OverlayManager();
        })
    }
    Seadragon.Utils.addEvent(window, "load", init);
</script>
<style>
    #mapcontainer {
        width: 600px;
        height: 500px;
        background-color: black;
        border: 1px solid black;
        color: white;
        float: left;
    }

    #map-manager-controls {
        width: 300px;
        float: right;
    }
    .remove-link {
        margin: 10px;
        padding: 5px;
        border-radius: 5px;
    }
    
    #mousePixels, #mousePoints,
    #viewportSizePixels, #viewportSizePoints {
        
    }
</style>

<div id="mapManager">
    <div id="mapcontainer"></div>
    <div id="map-manager-controls">
        <div id="overlays-container">
            <h2>Overlays</h2>
            <!-- staging area for non saved overlays-->
            <div id="overlay-staging">
            </div>
        </div>
        <input type="button" onclick="javascript:overlayManager._addOverlayToDZI();" value="Add Shape" />
        <div class="clearfix"></div>
    </div>
</div>


<?php get_footer(); ?>
