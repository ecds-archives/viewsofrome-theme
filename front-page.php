<?php
/**
 *
 * Front Page for Views of Rome
 *
 */

    disableAdminBar();

    wp_enqueue_script('seajax');
    wp_enqueue_script('eul-overlay-manager');
?>

<?php get_header(); ?>

<script type="text/javascript">
    window.viewer = null;
    var overlayManager;
    var $ = jQuery.noConflict();

    Seadragon.Utils.addEvent(window, "load", function() {
        overlayManager = new EUL.OverlayManager({
            map_container: "map",
            overlay_click_callback: function(overlay) {
                $.ajax({
                    url: '/vor/wp-admin/admin-ajax.php',
                    data: {
                        action: 'get_post_data',
                        id: overlay.id
                    },
                    success: function(post) {
                        var drawer = $('#mapOverlay');
                        drawer.find("#overlay-title h2").html(post.post_title);
                        drawer.find("#overlay-data").html(post.post_excerpt);
                        drawer.show('medium');
                    }
                });
            },
            open_event_callback: function() {
                $.ajax({
                    url: '/vor/wp-admin/admin-ajax.php',
                    data: {
                        action: 'get_overlay_data'
                    },
                    success: function(results) {
                        overlayManager.setData(results);
                    }
                });
            }
        });
    });
</script>
<style>
    #overlay-data {
        overflow: hidden;
    }
</style>

<div id="mapContainer">
    <div id='mapOverlayWrapper'>
        <div id="overlayDrawer">
            <div id="mapOverlay">
                <div id="overlay-title">
                    <h2>Views of Rome</h2>
                </div>
                <div id="overlay-data">
                    A look into the detailed Ligoro Map of Rome.
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="map"></div>
    <div class="clearfix"></div>
</div>

<?php get_footer(); ?>
