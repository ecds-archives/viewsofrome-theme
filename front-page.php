<?php
/**
 *
 * Front Page for Views of Rome
 *
 */

 wp_enqueue_script('seajax');
?>

<?php get_header(); ?>

<script type="text/javascript">
  var viewer = null;
  function init() {
    viewer = new Seadragon.Viewer('map');
    viewer.openDzi('/images/map/GeneratedImages/dzc_output.xml');
  }

  Seadragon.Utils.addEvent(window, 'load', init);
  var $ = jQuery.noConflict();
  $(document).ready(function() {
    $('#hide').live('click', function() {
        $('#mapOverlay').hide();
        $('#overlayDrawer').show();
    });
    $('#showOverlay').live('click', function() {
        $('#overlayDrawer').hide();
        $('#mapOverlay').show();
    });
    $('#load').live('click', function() {
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'GET',
            data: {action: 'get_overlay_data'},
            success: function(results) {
                alert(results);
            }
        });
    });
  });
</script>

<div id="mapContainer">
    <div id='map'></div>
    <div id='mapOverlayWrapper'>
        <div id="mapOverlay">
            <div id="overlay-title">
                <h2>The Colosseum</h2>
                <?php
                    $arr = array(
                        'category'  => "Arena",
                        'pageId'    => '/colosseum/'
                    );
    
                    echo json_encode($arr);
                ?>
            </div>
            <div>
                <a id="hide" href="#">Hide</a><br />
                <a id="load" href="#">Load</a>
            </div>
        </div>
        <div id="overlayDrawer">
            <a id="showOverlay" href="#">>></a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
