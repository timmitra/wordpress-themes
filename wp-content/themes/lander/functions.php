<?php

/* 
 * functions for the lander child theme
 */

function lander_scripts() {
    
    if ( is_front_page() ) {
        
        wp_enqueue_style('lander-styles', get_stylesheet_directory_uri() . '/lander-styles.css');
    }
}

add_action( 'wp_enqueue_scripts', 'lander_scripts');

?>