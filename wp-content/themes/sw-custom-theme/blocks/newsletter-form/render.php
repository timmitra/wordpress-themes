<?php
//  server-side rendering for newsletter form block
?>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="newsletter-form">
    <input type="email" name="email" placeholder="Enter your email" required />
    <input type="hidden" name="action" value="sw_custom_theme_newsletter_signup">
    <button type="submit">Sign Up</button>
</form>
 