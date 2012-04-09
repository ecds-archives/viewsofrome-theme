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
    var viewer = null;
    var points = null;
    function init() {
        viewer = new Seadragon.Viewer('map');
        viewer.openDzi('/images/map/GeneratedImages/dzc_output.xml');
        // listener to add click points to img
        viewer.tracker.clickHandler = function(tracker, position) {
            var pixel = Seadragon.Utils.getMousePosition(event).minus(Seadragon.Utils.getElementPosition(viewer.elmt));
            var point = viewer.viewport.pointFromPixel(pixel);
            if (!points) 
                points = new Array();
            points.push(new No5.Seajax.toImageCoordinates(viewer, point.x, point.y));
        };
    }
    Seadragon.Utils.addEvent(window, 'load', init);
    
    var polygon = null;
    function addOverlay(points) {
        polygon = new No5.Seajax.Shapes.Polygon(points);
        
        polygon.getElement().attr({"fill":"#000000", "fill-opacity":0.5});
        polygon.getElement().node.onmouseover = function() {
            polygon.getElement().attr({'fill': '#fff'});
        }
        polygon.getElement().node.onmouseout = function() {
            polygon.getElement().attr({'fill': '#000'});
        }
        polygon.attachTo(viewer);
        
        setTimeout(function() {
            polygon.redraw(viewer);
        }, 100);
    }
    
    var $ = jQuery.noConflict();
    $(document).ready(function() {
        $('#add_overlay').live('click', function() {
            addOverlay(points);
        });
        
        $('#cancel').live('click', function() {
            points = null;
            viewer.drawer.removeOverlay(polygon.div);
            polygon = null;
        });
        
        $('#serialize').live('click', function() {
            var result = {
                id: $("#post").val(),
                title: $("#post :selected").text(),
                data: []
            };
            
            for (i in points) {
                result.data.push({x: points[i].x, y: points[i].y});
            }
            console.log(result);
            // post point to the db
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'post_overlay_data', 
                    data: result
                },
                success: function(results) {
                    console.log(results);
                    $('#serialized').html(results);
                }
            });
        });
    });
</script>

<div id="mapContainer">
    <div id="controller">
        <input type="button" value="Add Overlay" id="add_overlay" />
        <input type="button" value="Cancel" id="cancel" />
        <input type="button" value="Serialize" id="serialize"/>
        <?php if (have_posts()) : the_post; ?>
        <select id="post">
            <?php while(have_posts()) : the_post(); ?>
                <option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
            <?php endwhile; ?> 
        <?php endif; ?>
        </select>
        <div id="serialized"></div>
    </div>
    <div id='map'></div>
</div>

<?php get_footer(); ?>
