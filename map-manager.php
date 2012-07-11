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
        'post__not_in'   => get_excluded_pages()    // look to do this through admin?
    ));
?>
<script type="text/javascript">
    var overlayManager;

    $(document).ready(function() {
        overlayManager = new EUL.OverlayManager({
            edit_mode: true
        });
        
        Seadragon.Utils.addEvent(overlayManager.getViewer().elmt, "mousemove", showMouse);


        // on change listener to load overlays for selected article
        $("#post").change(function() {
            var self = this;
            if ($(self).val() != "none") {
                $.ajax({
                    url: '/vor/wp-admin/admin-ajax.php',
                    data: {
                        action: 'get_overlay_data',
                        data: {
                            id: $(self).val()
                        }
                    },
                    success: function(results) {
                        // set the data on overlay manager which draws the current 
                        //polygons for the selected article
                        overlayManager.setData(results);
                    }
                });
            }
        });
    });

    // fixes event propagation bug in ff/ie
    function showMouse(event ) {
        overlayManager.event = event;
    }

    // TODO: add loading icon for saving
    function saveOverlays() {
        $.ajax({
            url: '/vor/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'post_overlay_data',
                data: {
                    id: $('#post').val(),
                    points: overlayManager.serializeOverlays(),
                    overwrite: ($("#overwrite").attr("checked") == "checked") ? true : false
                }
            },
            success: function(data, textStatus, jqXHR) {
                console.log("jqXHR:");
                console.log(jqXHR);
                if (textStatus == "success") {
                    // hide loader
                    // alert success
                }
            }
        });
    }
    var data;

    // TODO: can we remove this? was this only for testing w/out reload?
    function getOverlays() {
        $.ajax({
            url: '/vor/wp-admin/admin-ajax.php',
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
        margin-top: 10px;
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
        margin: 10px 0px;
    }

    #overlays-container h2 {
        margin: 10px;
    }

    #overlay-staging {
        /*min-height: 50px;*/
        border-top: 1px solid #ccc;
    }

    #post-select {
        /*border-top: 1px solid #ccc;*/
    }

    #post {
        width: 90%;
        margin: 15px 5%;
    }
</style>

<div id="mapManager">
    <div class="information">
        Explanation of usage goes here
    </div>
    <div id="mapcontainer"></div>
    <div id="map-manager-controls">
        <div id="overlays-container">
            <h2>Overlays</h2>
            <!-- staging area for non saved overlays-->
            <div id="overlay-staging">
            </div>
            <?php if (have_posts()) : the_post(); ?>
            <div id="post-select">
                <select id="post">
                    <option value="none">Select an article</option>
                    <?php while (have_posts()) : the_post(); ?>
                    <option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="clearfix"></div>
            </div>
            <?php endif; ?>
            <div style="margin:0 10px 10px 10px;">
                Clear existing overlays for this article? <input id="overwrite" type="checkbox">
            </div>
            <div class="clearfix"></div>
        </div>
        <input type="button" onclick="javascript:overlayManager._addOverlayToDZI();" value="Add Shape" />
        <input type="button" onclick="javascript:saveOverlays();" value="Save Overlays" />
        <div class="clearfix"></div>
    </div>
</div>


<?php get_footer(); ?>
