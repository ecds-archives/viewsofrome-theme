<?php
/**
 * Blog Template
 *
 * Template Name: Blog Excerpt (summary)
 * @file            blog-excerpt.php
 * @package         ViewsOfRome
 * @author          Kyle Bock
 * @filesource      wp-content/themes/viewsofrome-theme/blog-excerpt.php
 *
 */
?>
<?php get_header(); ?>
<?php
    $limit = get_option('posts_per_page');
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'orderby'       => 'title',
        'order'         => 'ASC',
        'numberposts'   => $limit,
        'paged'         => $paged
    );
?>

<?php $page_title = "Articles"; ?>

<?php include 'includes/list-article.php'; ?>

<?php get_footer(); ?>
