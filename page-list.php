<?php
/**
 * Template for displaying list of pages/articles
 * 
 *
 * @package WordPress
 * @subpackage ViewsofRome-Theme
 */
 $DEBUG = true;
 $args = array(
    'sort_order' => 'asc',
    'exclude' => array(9, 25)
 );

 get_header();
?>
<ul>
    <?php 
    $pages = get_pages($args);
    foreach ( $pages as $page ) {
        $output  = '<li>';
        $output .= '<a href="'. get_page_link($page->ID) . '">' . $page->post_title . '</a>';
        $output .= '</li>';
        echo $output;
    }
    ?>
</ul>

<?php if ($DEBUG) { ?>
<pre>
<?php var_dump($pages);?>
</pre>
<?php } ?>
<?php get_footer(); ?>
