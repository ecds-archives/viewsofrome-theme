<?php
/**
 * Map Manager Template
 *
 * Template Name: Map Manager
 * @file        map-manager.php
 * @package     ViewsOfRome
 * @filesource  wp-content/themes/viewsofrome-theme/map-manager.php
 */

    // to remove the admin bar which effects the positioning of the overlays
    disableAdminBar();

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
    //function init() {
        $(document).ready(function() {
            overlayManager = new EUL.OverlayManager({
                edit_mode: true
            });
            //console.log(overlayManager.getViewer());
            Seadragon.Utils.addEvent(overlayManager.getViewer().elmt, "mousemove", showMouse);
        });
   // }
    function showMouse(event ) {
        overlayManager.event = event;
    }

    function saveOverlays() {
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'post_overlay_data',
                data: {
                    id: 28,
                    points: overlayManager.serializeOverlays()
                }
            },
            success: function(results) {
                console.log(results);
            }
        });
    }
    var data;
    function getOverlays() {

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'get_overlay_data'
            },
            success: function(results) {
                console.log(results);
            }
        });
    }

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
        padding: 15px;
        border-bottom: 1px solid #ccc;
    }
    
    #mousePixels, #mousePoints,
    #viewportSizePixels, #viewportSizePoints {
        
    }

    #overlays-container {
        border: 1px solid #ccc;
    }
    #overlays-container {
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        margin-top: 10px;
    }

    #overlays-container h2 {
        margin: 10px;
    }

    #overlay-staging {
        min-height: 50px;
        border-top: 1px solid #ccc;
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
        <input type="button" onclick="javascript:void(0);" value="Save Overlays" />
        <div class="clearfix"></div>
    </div>
</div>


<?php get_footer(); ?>
