<?php

/* 
 * functions for the mtjc child theme
 */

function mtjc_scripts() {
    
    if ( is_front_page() ) {
        
        wp_enqueue_style('mtjc-styles', get_stylesheet_directory_uri() . '/mtjc-styles.css');
        
        wp_enqueue_script( 'mtjc-script', get_stylesheet_directory_uri() . '/js/mtjcscripts.js', array( 'jquery' ), '20140816' );
    }
}

add_action( 'wp_enqueue_scripts', 'mtjc_scripts');

add_image_size(  'testimonial-mug', 253, 253, true);

function exclude_testimonials( $query ) {
    if ( !$query->is_category( 'testimonials' ) && $query->is_main_query() ) {
        $query->set( 'cat', '-66' );
    }
}

add_action( 'pre_get_posts', 'exclude_testimonials' );

?>