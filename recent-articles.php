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
    apply_filters('post_limits', 10);
    $no_pagination = false; //True means no pagination, False means pagination
    $args = array(
        'order'             => 'DESC',
        'orderby'           => 'post_modified',
        'posts_per_page'    => 10,
        'nopaging'          => false
    );
?>

<?php $page_title = get_the_title(); ?>

<?php include 'includes/list-article.php'; ?>

<?php get_footer(); ?>
