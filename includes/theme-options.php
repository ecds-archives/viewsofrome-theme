<?php
add_action( 'admin_init', 'vor_options_init' );


/**
 * Init plugin options to white list our options
 */
function vor_options_init() {
    wp_enqueue_style( 'vor-theme-options', get_template_directory_uri() . '/includes/theme-options.css');
    wp_enqueue_script( 'vor-theme-options', get_template_directory_uri() . '/includes/theme-options.js', array( 'jquery' ), '1.0' );
    register_setting('vor_options', 'vor_theme_options', 'vor_theme_options_validate');
    register_setting('vor_options', 'vor_category_colors', 'vor_theme_options_validate');
}

function vor_menu_options() {
    add_theme_page(
        'Map Manager Options', 
        'Map Manager Options', 
        'edit_theme_options',
        'vor-settings',
        'vor_admin_options_page'
    );
}
add_action( 'admin_menu', 'vor_menu_options');

function vor_admin_enqueue_scripts( $hook_suffix ) {
    wp_enqueue_style( 'vor-theme-options');
    wp_enqueue_script( 'vor-theme-options');
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'vor_admin_enqueue_scripts' );


function vor_admin_options_page() {
    if (!isset($_REQUEST['settings-updated']))
        $_REQUEST['settings-updated'] = false;
?>    
<div class="wrap">
    <?php 
        screen_icon();
        echo "<h2>" . __('Map Manager Theme Options', 'vor') . " - " . get_template_directory_uri() . "</h2>";
    ?>

    <?php if (false !== $_REQUEST['settings-updated']) : ?>
    <div class="updated fade"><p><strong><?php _e('Options Saved', 'responsive'); ?></strong></p></div>
    <?php endif; ?>

    <form method="post" action="options.php">
        <?php settings_fields('vor_options'); ?>
        <?php $options = get_option('vor_category_colors'); ?>
        <?php echo "<pre>" . print_r($options, true) . "</pre>"; ?>
        <div id="rwd" class="grid col-940">
            <h3 class="rwd-toggle"><a href="#"><?php _e('Category Colors', 'vor'); ?></a></h3>
            <div class="rwd-container">
                <div class="rwd-block"> 
                <?php
                    $args = array(
                        'hide_empty'    => false
                    );
                    $categories = get_categories($args);
                ?>
                <?php foreach ($categories as $category) : ?>
                    <div class="grid col-300"><?php _e($category->name, 'vor'); ?></div>
                    <div class="grid col-620 fit">
                        <?php $id = $category->cat_ID; ?>
                        <input type="text" id="vor_category_colors[<?php echo $id; ?>]" name="vor_category_colors[<?php echo $id; ?>]" value="<?php if (!empty($options[$id])) echo $options[$id]; ?>"/>
                    </div>
                <?php endforeach; ?>
                    <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Options', 'vor'); ?>" />
                    </p>
                </div><!-- end of .rwd-block -->
            </div>
        </div>
    </form>
</div>   
<?php
}

function vor_theme_options_validate($input) {
    return $input;
}
?>