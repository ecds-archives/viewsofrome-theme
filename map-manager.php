<?php
/**
 * Map Manager Template
 *
 * Template Name: Map Manager
 * @file        map-manager.php
 * @package     ViewsOfRome
 * @filesource  wp-content/themes/viewsofrome-theme/map-manager.php
 */

 wp_enqueue_script('eul-overlay-manager');

 wp_enqueue_script('seajax');
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
        /*var PRECISION = 5; // number of decimal places
        var viewer = null;
        var points = null;
        
        function init() {
            viewer = new Seadragon.Viewer("mapcontainer");
            viewer.openDzi("/images/map/GeneratedImages/dzc_output.xml");
            
            // listeners to print data to screen
            viewer.addEventListener("open", showViewport);
            viewer.addEventListener("animation", showViewport);
            
            // listener to add click points to img
            viewer.tracker.clickHandler = function(tracker, position) {
                var pixel = Seadragon.Utils.getMousePosition(event).minus(Seadragon.Utils.getElementPosition(viewer.elmt));
                var point = viewer.viewport.pointFromPixel(pixel);
                if (!points) points = new Array();
                points.push(new No5.Seajax.toImageCoordinates(viewer, point.x, point.y));
            };
            
            //callback to update mouse position for screen printing
            Seadragon.Utils.addEvent(viewer.elmt, "mousemove", showMouse);
        }
        
        function addOverlayToDZI() {
            // add overlay, then clear temp points array
            addOverlay(points);
            console.log(points);
            points = new Array();
        }
        
        function addOverlay(points) {
            var polygon = new No5.Seajax.Shapes.Polygon(points);
            
            polygon.getElement().attr({"fill":"#000000", "fill-opacity":0.5});
            polygon.getElement().node.onmouseover = function() {
                polygon.getElement().attr({'fill': '#fff'});
                //$(polygon.div).attr({'z-index' : '20001'});
            }
            polygon.getElement().node.onmouseout = function() {
                polygon.getElement().attr({'fill': '#000'});
                //$(polygon.div).attr({'z-index' : '10000'});
                console.log(polygon.getElement());
            }
            polygon.attachTo(viewer);
            
            setTimeout(function() {
                polygon.redraw(viewer);
            }, 500);
        }
        
        function addOverlays() {
            points = [
                new No5.Seajax.toImageCoordinates(viewer, .25, .28),
                new No5.Seajax.toImageCoordinates(viewer, .50, .28),
                new No5.Seajax.toImageCoordinates(viewer, .50, .62),
                new No5.Seajax.toImageCoordinates(viewer, .25, .62)             
            ];
            console.log(points);
            
            var polygon = new No5.Seajax.Shapes.Polygon(points);
            
            polygon.getElement().attr({"fill":"#000000", "fill-opacity":0.5});
            polygon.attachTo(viewer);
            
            setTimeout(function() {
                polygon.redraw(viewer);
            }, 500);
        }

        function showMouse(event) {
            var pixel = Seadragon.Utils.getMousePosition(event).minus(Seadragon.Utils.getElementPosition(viewer.elmt));

            //$("#mousePixels").html(toString(pixel, true));    

            if (!viewer.isOpen()) {
                return;
            }
        
            var point = viewer.viewport.pointFromPixel(pixel);
            // $("#mousePoints").html(toString(point, true) + ":mp");
        }

        function showViewport(viewer) {
            if (!viewer.isOpen())
                return;
            
            var sizePoints = viewer.viewport.getBounds().getSize();
            var sizePixels = viewer.viewport.getContainerSize();

            // $("#viewportSizePoints").html(toString(sizePoints, false));
            // $("#viewportSizePixels").html(toString(sizePixels, false));
        }

        function toString(point, useParens) {
            var x = point.x;
            var y = point.y;

            if (x % 1 || y % 1) {
                x = x.toFixed(PRECISION);
                y = y.toFixed(PRECISION);
            }

            if (useParens) {
                return "(" + x + ", " + y + ")";
            }

            return x + " x " + y;
        }*/
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
