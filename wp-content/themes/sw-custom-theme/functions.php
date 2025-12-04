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

// enqueue font awesome from CDN
function itg_enqueue_font_awesome() {
    wp_enqueue_style(
        'font-awesome', // unique handle for the stylesheet
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', // CDN URL for Font Awesome
        array(), // dependencies (leave empty if none)
        '6.  5.0' // version of Font Awesome
    );
} 
add_action( 'wp_enqueue_scripts', 'itg_enqueue_font_awesome' );

// enqueue block styles
add_action( 'enqueue_block_assets', function() {
    wp_enqueue_style( 
        'theme-block-styles', 
        get_template_directory_uri() . '/assets/css/block-styles.css', 
        array(), 
        filemtime( get_template_directory() . '/assets/css/block-styles.css' ) // version
    );
} );

// register a custom style for core block (fancy quote)
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

// register a custom style for core blocks (fancy h2)
add_action ( 'init', function() { // init loads before the block editor is initialized
    foreach ( [ 'core/heading', 'core/post-title' ] as $block_name ) {
        register_block_style(
            $block_name, // block name
            [
                'name'  => 'fancy-h2-style', // unique name for the style
                'label' => __( 'Fancy H2 style', 'itg-custom-theme' ), // human-readable label
                'style_handle' => 'theme-block-styles', // this should match the handle used in enqueueing the block styles
            ]
        );
    }
} );

// custom form for Contact Page 
 function itg_custom_theme_contact_form_shortcode() {
    ob_start(); // Start output buffering
    ?>
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="itg-contact-form">
        <input type="text" id="name" name="name" placeholder="Your name" required>
        <input type="email" id="email" name="email" placeholder="Your email" required>
        <textarea id="message" name="message" placeholder="Please enter your message." required></textarea>
        <input type="hidden" name="action" value="itg_custom_theme_handle_contact_form"> 
        <button type="submit">Send</button> 
    </form>
    <?php
    return ob_get_clean(); // Return the buffered content and clear the buffer 
 }
 add_shortcode( 'itg_contact_form', 'itg_custom_theme_contact_form_shortcode' ); 

 // handle form submission
 function itg_custom_theme_handle_contact_form() {
    if ( isset( $_POST['name'], $_POST['email'], $_POST['message'] ) && is_email( $_POST['email'] ) ) {
        $name = sanitize_text_field( $_POST['name'] );
        $email = sanitize_email( $_POST['email'] );
        $message = sanitize_textarea_field( $_POST['message'] );

        $subject = 'New Contact Form Submission';
        $body = "Name: $name\nEmail: $email\nMessage:\n$message";
        wp_mail( 'support@it-guy.com', $subject, $body );

        wp_safe_redirect( home_url( '/thank-you/' ) );
        exit;
    }

    wp_safe_redirect( home_url( '/error/' ) );
    exit;
 }
 add_action( 'admin_post_nopriv_itg_custom_theme_handle_contact_form', 'itg_custom_theme_handle_contact_form' ); // user not logged in
 add_action( 'admin_post_itg_custom_theme_handle_contact_form', 'itg_custom_theme_handle_contact_form' ); // user logged in

 // adding a custom block
 // register a custom block (let WordPress read `block.json`)
 function itg_custom_theme_register_blocks() {
     register_block_type( get_template_directory() . '/blocks/newsletter-form' );
 }
 add_action( 'init', 'itg_custom_theme_register_blocks' );

// handle newsletter form submission
function itg_custom_theme_newsletter_signup() {
    if ( isset( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
        $email = sanitize_email( $_POST['email'] );

        // Here you would typically add the email to your newsletter list.
        // For demonstration, we'll just send a confirmation email.

        $subject = 'New Newsletter Signup';
        $body = "Thank you for subscribing to our newsletter with the email: $email";
        wp_mail( 'support@it-guy.com', $subject, "Email: $email" );

        wp_safe_redirect( home_url( '/thank-you/' ) );
        exit;
    }
    wp_safe_redirect( home_url( '/error/' ) );
    exit;
}
add_action( 'admin_post_nopriv_itg_custom_theme_newsletter_signup', 'itg_custom_theme_newsletter_signup' ); // user not logged in
add_action( 'admin_post_itg_custom_theme_newsletter_signup', 'itg_custom_theme_newsletter_signup' ); // user logged in

// register styles and scripts for the custom block
 function itg_custom_theme_register_block_assets() {
    wp_register_script(
        'itg-newsletter-editor',
        get_template_directory_uri() . '/blocks/newsletter-form/block.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        filemtime( get_template_directory() . '/blocks/newsletter-form/block.js' ),
        true
    );

    wp_register_style(
        'itg-newsletter-editor-style',
        get_template_directory_uri() . '/blocks/newsletter-form/editor.css',
        [],
        filemtime( get_template_directory() . '/blocks/newsletter-form/editor.css' )
    );

    wp_register_style(
        'itg-newsletter-style',
        get_template_directory_uri() . '/blocks/newsletter-form/style.css',
        [],
        filemtime( get_template_directory() . '/blocks/newsletter-form/style.css' )
    );
}
 add_action( 'init', 'itg_custom_theme_register_block_assets' ); 

 // include patterns.php
require get_template_directory() . '/patterns.php';
?>