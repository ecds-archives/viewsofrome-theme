<?php
/**
 * Recent Articles Template
 *
 * Template Name: Recent Articles
 * @file        recent-articles.php
 * @package     ViewsOfRome
 * @author      Kyle Bock
 * @filesource  wp-content/themes/viewsofrome-theme/recent-articles.php
 */
?>

<?php get_header(); ?>
<?php
    $limit = 10;
    $paged = 0;
    $args = array(
        'order'         => 'DESC',
        'orderby'       => 'post_modified',
        'showposts'     => $limit,
        'paged'         => $paged,
        'post_type'     => 'page',
        'post__not_in'  => array(9, 25, get_the_ID())
    );
?>

<?php $page_title = "Recent Articles"; ?>
<div id="content-blog" class="grid col-620">
    <?php include 'includes/breadcrumbs.php'; ?>
    <h1><?php the_title(); ?></h1>
    <?php include 'includes/list-article.php'; ?>
</div>
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
