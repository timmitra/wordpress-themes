<?php

// enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 
        'theme-styles', // unique handle for the stylesheet
        get_stylesheet_uri(), // retrieve the URI of the theme's styles.css file
        array(), // dependencies (leave empty if none)
        filemtime( get_stylesheet_directory() . '/style.css' ) // Cache busting using file modification time
    );
} );

// enqueue block styles
add_action( 'enqueue_block_assets', function() {
    wp_enqueue_style( 
        'theme-block-styles', 
        get_template_directory_uri() . '/assets/css/block-styles.css', 
        array(), 
        filemtime( get_template_directory() . '/assets/css/block-styles.css' ) // version
    );
} );

// register a custom style for core blocks
add_action ( 'init', function() { // init loads before the block editor is initialized
    register_block_style(
        'core/quote', // block name
        array(
            'name'  => 'fancy-quote', // unique name for the style
            'label' => __( 'Fancy Quote', 'itg-custom-theme' ), // human-readable label
            'style_handle' => 'theme-block-styles' // this should match the handle used in enqueueing the block styles
        )
    );
} );

?>