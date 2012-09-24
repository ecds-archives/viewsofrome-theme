<?php
/**
 *
 * Front Page for Views of Rome
 *
 */

    disableAdminBar();

    wp_enqueue_script('seajax');
    wp_enqueue_script('map-manager');
    $category_colors = get_option('vor_category_colors');
    $options = get_option('responsive_theme_options');
    //echo "<pre>".print_r($category_colors)."<pre>";
?>

<?php get_header(); ?>
<script type="text/javascript">
    window.viewer = null;
    var overlayManager;
    var $ = jQuery.noConflict();
    

    Seadragon.Utils.addEvent(window, "load", function() {
        window.colors = <?php echo json_encode($category_colors); ?>;
        overlayManager = new EUL.OverlayManager({
            map_container: "map",
            dzi_path: "/viewsofrome/images/map/GeneratedImages/dzc_output.xml",
            overlay_click_callback: function(overlay) {
                $.ajax({
                    url: '/vor/wp-admin/admin-ajax.php',
                    data: {
                        action: 'vor_get_post_data',
                        id: overlay.id
                    },
                    success: function(post, textStatus, jqXHR) {
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
                        action: 'vor_get_overlay_data'
                    },
                    success: function(results) {
                        console.log(results);
                        overlayManager.setData(results);
                    }
                });
            },
            set_full_page_callback: function(fullPage) {
                if (fullPage) {
                    overlayManager.hideAll();
                } else {
                    $('.checkbox').each(function(index, elmt) {
                        if ($(elmt).val() != 'all'&& $(elmt).attr('checked') == 'checked') {
                            overlayManager.showCategory($(elmt).val());
                        }
                    });
                }
            }
        });
        $('.checkbox').change(function() {
            var el = $(this);

            if (el.val() == 'all') {
                $('.checkbox').not(this).each(function(index, elmt) {
                    if (el.attr('checked') == 'checked') {
                        overlayManager.showAll();
                        $('.checkbox').attr('checked', 'checked');
                    } else {
                        overlayManager.hideAll();
                        $('.checkbox').removeAttr('checked');
                    }
                });
                return;
            }

            if (el.attr('checked') == 'checked') {
                overlayManager.showCategory(el.val());
            } else {
                overlayManager.hideCategory(el.val());
            }
        })
    });
</script>
<style>
    #overlay-data {
        overflow: hidden;
        height: 170px;
    }
</style>

<div id="mapContainer">
    <div id='mapOverlayWrapper'>
        <div id="overlayDrawer">
            <div id="mapOverlay">
                <div id="overlay-title">
                    <h2><?php echo $options['home_headline']; ?></h2>
                </div>
                <div id="overlay-data">
                    <?php echo $options['home_content_area']; ?>
                </div>
                <?php get_template_part('includes/legend'); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="map"></div>
    <div class="clearfix"></div>
</div>

<?php get_footer(); ?>
