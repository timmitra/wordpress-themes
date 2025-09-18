<?php
// disable all the default block patterns
remove_theme_support( 'core-block-patterns' );


function my_block_theme_enqueue_styles() {
    wp_enqueue_style(
        'my-block-theme-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version')
    );
}

add_action( 'wp_enqueue_scripts', 'my_block_theme_enqueue_styles' );

?>