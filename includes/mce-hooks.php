<?php
add_filter('mce_buttons_2', 'vor_mce_buttons_2');
function vor_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

add_filter('tiny_mce_before_init', 'vor_mce_before_init');
function vor_mce_before_init($settings) {
    $style_formats = array(
        array(
            'title'     => 'Bibliography',
            'block'     => 'div',
            'classes'   => 'bibliography',
            'wrapper'   => 'true'
        )
    );

    $settings['style_formats'] = json_encode($style_formats);
    return $settings;
}

add_action('admin_init', 'add_vor_editor_style');
function add_vor_editor_style() {
    add_editor_style();
}
?>