 <?php

// register a pattern category for the theme
function sw_custom_theme_register_block_patterns() {
    $base = get_template_directory() . '/patterns/';

    register_block_pattern (
        'sw_custom_theme/recipe-details',
        array(
            'title'       => __( 'Recipe Details', 'sw_custom_theme' ),
            'description' => __( 'A pattern to display recipe details like ingredients and instructions.', 'sw_custom_theme' ),
            'categories'  => array( 'text', 'layout' ),
            'content'     => file_get_contents( $base . 'recipe-details.html' ),
        ) 
    );

    register_block_pattern (
        'sw_custom_theme/2-col-layout',
        array(
            'title'       => __( '2 column layout', 'sw_custom_theme' ),
            'description' => __( 'A pattern to display two columns side by side.', 'sw_custom_theme' ),
            'categories'  => array( 'text', 'layout' ),
            'content'     => file_get_contents( $base . '2-col-layout.html' ),
        ) 
    );

    register_block_pattern (
        'sw_custom_theme/3-col-layout',
        array(
            'title'       => __( '3 column layout', 'sw_custom_theme' ),
            'description' => __( 'A pattern to display three columns side by side.', 'sw_custom_theme' ),
            'categories'  => array( 'text', 'layout' ),
            'content'     => file_get_contents( $base . '3-col-layout.html' ),
        ) 
    );
}    

add_action( 'init', 'sw_custom_theme_register_block_patterns' ); 

?>