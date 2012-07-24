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
    $paged = 1;
    $args = array(
        'order'         => 'DESC',
        'orderby'       => 'post_modified',
        'numberposts'   => $limit
        //'paged'         => $paged
    );
?>

<?php $page_title = get_the_title(); ?>

<?php include 'includes/list-article.php'; ?>

<?php get_footer(); ?>
