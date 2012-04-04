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
</script>

<div id='map'></div>

<?php get_footer(); ?>
