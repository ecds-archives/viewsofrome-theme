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
  var viewer = null;

  var $ = jQuery.noConflict();
  $(document).ready(function() {
    overlayManager = new EUL.OverlayManager({
        map_container: "map",
        overlay_click_callback: function(overlay) {
            console.log(overlay.id);
            $.ajax({
                url: '/vor/wp-admin/admin-ajax.php',
                data: {
                    action: 'get_post_data',
                    id: overlay.id
                },
                success: function(post) {
                    console.log(post);
                    var drawer = $('#mapOverlay');
                    drawer.find("#overlay-title h2").html(post.post_title);
                    drawer.find("#overlay-data").html(post.post_excerpt);
                    drawer.show('medium');
                }
            });
        }
    });

    $.ajax({
        url: '/vor/wp-admin/admin-ajax.php',
        data: {
            action: 'get_overlay_data'
        },
        success: function(results) {
            overlayManager.setData(results);
        }
    });

    $('#hide').live('click', function() {
        $('#mapOverlay').hide("medium");
    });
    $('#showOverlay').live('click', function() {
        $('#mapOverlay').show("medium");
    });
    $('#load').live('click', function() {
        $.ajax({
            url: '/vor/wp-admin/admin-ajax.php',
            type: 'GET',
            data: {action: 'get_overlay_data'},
            success: function(results) {
                alert(results);
            }
        });
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
                    <h2>Vews of Rome</h2>
                </div>
                <div id="overlay-data">
                    A look into the detailed Ligoro Map of Rome.
                </div>
            </div>
            <!-- <div id="drawerHandle">
                <a id="showOverlay">>></a>
            </div> -->
        </div>
        <div class="clearfix"></div>
    </div>
    <div id='map'></div>
    <div class="clearfix"></div>
</div>

<?php get_footer(); ?>
