<?php
/**
 * Sitemap Template
 *
 * Template Name: Sitemap
 *
 * @file           sitemap.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/sitemap.php
 * @link           http://codex.wordpress.org/Templates
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

        <div id="content-sitemap" class="grid col-940">
        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
        <?php $options = get_option('responsive_theme_options'); ?>
		<?php if ($options['breadcrumb'] == 0): ?>
		<?php echo responsive_breadcrumb_lists(); ?>
        <?php endif; ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1> 
                
                <div class="post-entry">
                <div id="widgets">
                    <div class="grid col-300">
                          <div class="widget-title"><?php _e('Articles', 'responsive'); ?></div>
                            <ul><?php wp_list_pages("title_li=&exclude=".get_excluded_pages(true) ); ?></ul>               
                    </div><!-- end of .col-300 fit -->
					
					<div class="grid col-300 fit">
                        <div class="widget-title"><?php _e('Tags', 'responsive'); ?></div>
							<ul>
								<?php
									$tags = get_tags( array('orderby' => 'count', 'order' => 'DESC') );
									foreach ( (array) $tags as $tag ) {
										echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . ucwords($tag->name) . ' (' . $tag->count . ') </a></li>';
									}
								?>
							</ul>
					</div><!-- end of .col-300 -->
                </div><!-- end of #widgets --> 
                <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>     
                </div><!-- end of .post-entry -->             
            
            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div>  
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?> 

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
        <h6><?php _e( 'You can return', 'responsive' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'Home', 'responsive' ); ?>"><?php _e( '&#9166; Home', 'responsive' ); ?></a> <?php _e( 'or search for the page you were looking for', 'responsive' ); ?></h6>
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-sitemap -->

<?php get_footer(); ?>