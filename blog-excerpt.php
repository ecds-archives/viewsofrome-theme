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
    query_posts(array(
        'showposts' => $limit,
        'paged'     => $paged
    ));
?>
    <div id="content-blog" class="grid col-620">
<?php if (have_posts()) : ?>
    <?php while(have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h1>
            <div class="post-entry">
                <?php if ( has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>
                <?php the_excerpt(); ?>
                <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
            </div><!-- end of .post-entry -->
        </div>
    <?php endwhile; ?>
<?php endif; ?>
    </div>
    <!-- /content-blog -->
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
