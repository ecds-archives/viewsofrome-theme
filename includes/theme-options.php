<?php
add_action( 'admin_init', 'vor_options_init' );


/**
 * Init plugin options to white list our options
 */
function vor_options_init() {
    wp_enqueue_style( 'vor-theme-options', get_template_directory_uri() . '/includes/theme-options.css');
    wp_enqueue_script( 'vor-theme-options', get_template_directory_uri() . '/includes/theme-options.js', array( 'jquery' ), '1.0' );
    register_setting('vor_options', 'vor_theme_options', 'vor_theme_options_validate');
    register_setting('vor_category_colors', 'vor_category_colors', 'vor_theme_options_validate');
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
        echo "<h2>" . __('Map Manager Theme Options', 'vor') . "</h2>";
    ?>

    <?php if (false !== $_REQUEST['settings-updated']) : ?>
    <div class="updated fade"><p><strong><?php _e('Options Saved', 'responsive'); ?></strong></p></div>
    <?php endif; ?>

    <form method="post" action="options.php">
        <?php settings_fields('vor_options'); ?>
        <?php $options = get_option('vor_category_colors'); ?>
        <?php 
            $errors = get_settings_errors(); 
            $errors_list = array();
            if (sizeof($errors) > 0) {
                foreach ($errors as $error) {
                    $errors_list[$error['code']] = $error['message'];
                }
            }
        ?>
        <div id="rwd" class="grid col-940">
            <h3 class="rwd-toggle active"><a href="#"><?php _e('Category Colors', 'vor'); ?></a></h3>
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
                        <?php if (isset($errors_list[$id])) : ?>
                        <div style="color: red; margin-left: 5px;">
                            <?php echo $errors_list[$id]; ?>
                        </div>
                        <?php endif; ?>
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
    foreach ($input as $id => $color_code) {
        //echo "$id => $color_code<br />";
        if (!preg_match("/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", $color_code)) {
            add_settings_error('vor_category_colors', $id, "Code provided is not a valid hex color code", 'error');
        }
    }
    return $input;
}
?>