<?php
/**
 *
 *
 */
    global $post;

    $images = get_children(array(
        'post_parent'       => $post->ID,
        'post_status'       => 'inherit',
        'post_type'         => 'attachment',
        'post_mime_type'    => 'image',
        'order'             => 'ASC',
        'orderby'           => 'menu_order ID'
    ));
?>
<div id="slides_wrapper">
    <div id="slides">
        <div class='slides_container'>

        <?php foreach ($images as $imageID => $image) : ?>
            <div class="slide">
                <?php
                $image_attrs = wp_get_attachment_image_src($imageID,'gallery-big', false);
                $image_full_attrs = wp_get_attachment_image_src($imageID, 'gallery-lightbox', false);
                $lb_caption = $image->post_title . "<br /><span class='lb-caption-text'>" . $image->post_excerpt . "</span>";
                ?>
                <a href="<?php echo $image_full_attrs[0]; ?>" rel="lightbox[slides]" title="<?php echo $lb_caption; ?>"><img src="<?php echo $image_attrs[0]; ?>" /></a>
                <div class="caption">
                    <a href="<?php echo get_attachment_link($imageID); ?>"><?php _e($image->post_title); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        <a href="#" class="prev"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slides/arrow-prev.png" /></a>
        <a href="#" class="next"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slides/arrow-next.png" /></a>
        <div id="thumb_wrapper">
            <div class="thumb-prev"><a href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slides/arrow-prev.png" /></a></div>
            <div class="thumb-window">
                <ul class="pagination">
                    <?php $index = 0; ?>
                    <?php foreach ($images as $imageID => $image): ?>
                        <?php $image_attrs = wp_get_attachment_image_src($imageID, $size='thumbnail', $icon = false); ?>
                        <li><a class="foo" href="#<?php echo $index; ?>"><img src="<?php echo $image_attrs[0]; ?>" width="64px" /></a></li>
                        <?php $index++; ?>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="thumb-next"><a href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slides/arrow-next.png" /></a></div>
        </div>
    </div>
</div>