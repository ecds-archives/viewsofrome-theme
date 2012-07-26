<?php
 /*
  * Article List include
  * Utilized by blog-excerpt and tag.php
  * 
  */
?>
<div id="content-blog" class="grid col-620">
    <?php $options = get_option('responsive_theme_options'); ?>
    <?php if ($options['breadcrumb'] ==0): ?>
        <?php echo responsive_breadcrumb_lists(); ?>
    <?php endif; ?>
    <h1><?php echo $page_title; ?></h1>
    <?php 
    $posts;
    //echo "<pre>" . print_r($args, true) . "</pre>";
    if (!isset($execute_query) || $execute_query) // needed to prevent execution of query in tag.php
        $posts = query_posts($args);
    ?>
<?php if (have_posts()) : ?>
    <?php foreach ( $posts as $post ) : setup_postdata($post); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h4>
            <div class="post-entry">
                <?php if ( has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>
                <div class="page-excerpt <?php echo (has_post_thumbnail()) ? 'list-right-col' : '';?>">
                <?php the_excerpt(); ?>
                </div>
                <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
            </div><!-- end of .post-entry -->
            <div class="clearfix"></div>
        </div>
    <?php endforeach; ?>
    <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
            <div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
        </div><!-- end of .navigation -->
    <?php endif; ?>
<?php endif; ?>
</div>
<?php get_sidebar('right'); ?>
