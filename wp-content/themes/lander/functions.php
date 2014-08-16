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

add_image_size(  'testimonial-mug', 253, 253, true);

function exclude_testimonials( $query ) {
    if ( !$query->is_category( 'testimonials' ) && $query->is_main_query() ) {
        $query->set( 'cat', '-66' );
    }
}

add_action( 'pre_get_posts', 'exclude_testimonials' );

?>