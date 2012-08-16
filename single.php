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
    // disableAdminBar();
    wp_enqueue_script('slides');
    wp_enqueue_script('lightbox');
 ?>
<?php get_header(); ?>
<script>
    // invoke slideshow
    // $ = jQuery.noConflict();
    window.thumbs_width = 0;
    jQuery(document).ready(function($){
        $('#slides').slides({
            width: 570,
            generatePagination: false
            // animationStart: function(current) {
            //     $('.caption').animate({bottom:-35}, 100);
            // },
            // animationEnd: function(current) {
            //     $('.caption').animate({bottom:0}, 200);
            // }
        });
        var el = $('.pagination li');

        // get remainder for back button (this is for wrapparound back button)
        window.thumbs_width = 
            ((el.length % 6 > 0) ? 6 - (el.length % 6) + el.length : el.length ) * 
            (el.width() + parseInt(el.css('margin-left')) + parseInt(el.css('margin-right')));


        $(".thumb-prev").live('click', function(eventObject) {
            eventObject.preventDefault();
            var offset = parseInt($('.pagination').css('left'));
            var newOffset = ((offset + 456) > 0) ? -(window.thumbs_width - 456) : offset + 456;

            $(".pagination").css({
                'left': newOffset
            });
        });
        
        $(".thumb-next").live('click', function(eventObject) {
            eventObject.preventDefault();
            var offset = parseInt($('.pagination').css('left'));
            // the false branch could be changed to offset to prevent wrap around scrolling
            var newOffset = ((-offset + 456) < window.thumbs_width) ? offset - 456 : 0;
            console.log(offset);
            console.log(newOffset);

            $(".pagination").css({
                'left': newOffset
            });
        });
    });
</script>
<style type="text/css">
    #widgets {
        float:right;
    }
    div[class*='post-'] {
        clear:none;
    }
</style>
<div id="content" class="grid full-width">
<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>
    
    <?php $options = get_option('responsive_theme_options'); ?>
    <?php if ($options['breadcrumb'] == 0): ?>
    <?php echo responsive_breadcrumb_lists(); ?>
    <?php endif; ?> 
      
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php get_sidebar(); ?>
            <h1><?php the_title(); ?></h1>

            <div class="post-meta">
            <?php 
                printf( __( '<span class="%1$s">Posted on</span> %2$s by %3$s', 'responsive' ),'meta-prep meta-prep-author',
                sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
                    get_permalink(),
                    esc_attr( get_the_time() ),
                    get_the_date()
                ),
                sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
                    get_author_posts_url( get_the_author_meta( 'ID' ) ),
                sprintf( esc_attr__( 'View all posts by %s', 'responsive' ), get_the_author() ),
                    get_the_author()
                    )
                );
            ?>
                <?php if ( comments_open() ) : ?>
                    <span class="comments-link">
                    <span class="mdash">&mdash;</span>
                <?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
                    </span>
                <?php endif; ?> 
            </div><!-- end of .post-meta -->
                            
            <div class="post-entry">
                <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                
                <?php if ( get_the_author_meta('description') != '' ) : ?>
                
                <div id="author-meta">
                <?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '80' ); }?>
                    <div class="about-author"><?php _e('About','responsive'); ?> <?php the_author_posts_link(); ?></div>
                    <p><?php the_author_meta('description') ?></p>
                </div><!-- end of #author-meta -->
                
                <?php endif; // no description, no author's meta ?>
                
                <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
            </div><!-- end of .post-entry -->
            
            <div class="post-data">
                <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?> 
                <?php printf(__('Posted in %s', 'responsive'), get_the_category_list(', ')); ?> 
            </div><!-- end of .post-data -->             

        <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div>             
        </div><!-- end of #post-<?php the_ID(); ?> -->
        
        <?php comments_template( '', true ); ?>
        
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
      
</div><!-- end of #content -->


<?php get_footer(); ?>