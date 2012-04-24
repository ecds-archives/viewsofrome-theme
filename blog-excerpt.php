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
        'order'     => 'ASC',               // sort order, through admin?
        'orderby'  => 'title',              // sort field, through admin?
        'showposts' => $limit,
        'paged'     => $paged,
        'post_type' => 'page',              // limit to page types
        'post__not_in'   => array(9, 25)    // look to do this through admin?
        );
?>

<?php $page_title = "Articles"; ?>
<div id="content-blog" class="grid col-620">
    <?php include 'includes/breadcrumbs.php'; ?>
    <h1><?php the_title(); ?></h1>

    <?php include 'includes/list-article.php'; ?>
</div>

<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
