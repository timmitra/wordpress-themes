<?php

// HOSTS

/**
 * 1. Adds a meta box to the post editing screen
 */
function staff_custom_meta() {
   // add_meta_box( 'sscore_meta', __( 'Meta Box Title', 'sscore-textdomain' ), 'sscore_meta_callback', 'post' );
    add_meta_box( 'staff_meta', __( 'Host Member Info', 'staff-textdomain' ), 'staff_meta_callback', 'staff', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'staff_custom_meta' );

/**
 * 2. Outputs the content of the meta box
 */
function staff_meta_callback( $post ) {
    
    wp_nonce_field( basename( __FILE__ ), 'staff_nonce' );
    
    $staff_stored_meta = get_post_meta($post->ID, 'staff_stored_meta', true);
    // print_r($staff_stored_meta);
    // sscore_stored_meta instead of $repeatable_fields
    //$sscore_stored_meta = get_post_meta( $post->ID );
    
    if (is_array( $staff_stored_meta ) ) {
    
		foreach ( $staff_stored_meta as $field ) {
			//echo($field['_staff-tex']);
		?>
    
    	<span>
			<p>
				<label for="_staff-text" class="staff-row-title"><?php _e( 'Staff Title ', 'staff-textdomain' )?></label>
                <input type="text" name="_staff-text[]" id="_staff-text" value="<?php if ( isset ( $field['_staff-text'] ) ) { echo $field['_staff-text']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-fb"><?php _e( 'Facebook URL ', 'staff-fbdomain' )?></label>
                <input type="text" name="_staff-fb[]" id="_staff-fb" value="<?php if ( isset ( $field['_staff-fb'] ) ) { echo $field['_staff-fb']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-tw"><?php _e( 'Twitter URL ', 'staff-twdomain' )?></label>
                <input type="text" name="_staff-tw[]" id="_staff-tw" value="<?php if ( isset ( $field['_staff-tw'] ) ) { echo $field['_staff-tw']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-ln"><?php _e( 'LinkedIn URL ', 'staff-lndomain' )?></label>
                <input type="text" name="_staff-ln[]" id="_staff-ln" value="<?php if ( isset ( $field['_staff-ln'] ) ) { echo $field['_staff-ln']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-web"><?php _e( 'Web Site: ', 'staff-webdomain' )?></label>
                <input type="text" name="_staff-web[]" id="_staff-web" value="<?php if ( isset ( $field['_staff-web'] ) ) { echo $field['_staff-web']; } ?>" />
			</p>

		</span>

<?php 
		}
	} else {
		// empty fields 
		?>
    	<span>
			<p>
				<label for="_staff-text" class="staff-row-title"><?php _e( 'Staff Title ', 'staff-textdomain' )?></label>
                <input type="text" name="_staff-text[]" id="_staff-text" value="<?php if ( isset ( $field['_staff-text'] ) ) { echo $field['_staff-text']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-fb"><?php _e( 'Facebook URL ', 'staff-fbdomain' )?></label>
                <input type="text" name="_staff-fb[]" id="_staff-fb" value="<?php if ( isset ( $field['_staff-fb'] ) ) { echo $field['_staff-fb']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-tw"><?php _e( 'Twitter URL ', 'staff-twdomain' )?></label>
                <input type="text" name="_staff-tw[]" id="_staff-tw" value="<?php if ( isset ( $field['_staff-tw'] ) ) { echo $field['_staff-tw']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-ln"><?php _e( 'LinkedIn URL ', 'staff-lndomain' )?></label>
                <input type="text" name="_staff-ln[]" id="_staff-ln" value="<?php if ( isset ( $field['_staff-ln'] ) ) { echo $field['_staff-ln']; } ?>" />
			</p>
			<p>
				<label for="_staff-text" class="staff-row-web"><?php _e( 'Web Site: ', 'staff-webdomain' )?></label>
                <input type="text" name="_staff-web[]" id="_staff-web" value="<?php if ( isset ( $field['_staff-web'] ) ) { echo $field['_staff-web']; } ?>" />
			</p>

		</span>

		<?php
	}

} 



/**
 * Saves the custom meta input
 */
function staff_meta_save( $post_id ) {

 	if ( ! isset( $_POST['staff_nonce'] ) || !wp_verify_nonce( $_POST['staff_nonce'], basename( __FILE__ ) ) ) {
		return;
	}
		
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'staff_nonce' ] ) && wp_verify_nonce( $_POST[ 'staff_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 	//echo $is_valid_nonce;
 	
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    $old = get_post_meta($post_id, 'staff_stored_meta', true);
	$new = array();

	//print_r($old);
	//print_r($new);
	
	$meta_text_array = $_POST['_staff-text'];
	$meta_fb_array = $_POST['_staff-fb'];
	$meta_tw_array = $_POST['_staff-tw'];
	$meta_ln_array = $_POST['_staff-ln'];
	$meta_web_array = $_POST['_staff-web'];
	
	$count = count( $meta_text_array );
	//echo $count;
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $meta_text_array[$i] != '' ) :
			$new[$i]['_staff-text'] = sanitize_text_field( $meta_text_array[$i]); // position
			$new[$i]['_staff-fb'] = sanitize_text_field( $meta_fb_array[$i] ); // facebook
			$new[$i]['_staff-tw'] = sanitize_text_field( $meta_tw_array[$i] ); // twitter
			$new[$i]['_staff-ln'] = sanitize_text_field( $meta_ln_array[$i] ); // linked in
			$new[$i]['_staff-web'] = sanitize_text_field( $meta_web_array[$i] ); // web site
		endif;
	}

	if ( !empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'staff_stored_meta', $new );
	} elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'staff_stored_meta', $old );
	}

//     if( isset( $_POST[ '_meta-text' ] ) ) {
//         update_post_meta( $post_id, '_meta-text', sanitize_text_field( $_POST[ '_meta-text' ] ) );
//     }

}

add_action( 'save_post', 'staff_meta_save' );

/* REVIEWS */

/**
 * 1. Adds a meta box to the post editing screen
 */
function review_custom_meta() {
   // add_meta_box( 'sscore_meta', __( 'Meta Box Title', 'sscore-textdomain' ), 'sscore_meta_callback', 'post' );
    add_meta_box( 'review_meta', __( 'Review Info', 'staff-textdomain' ), 'review_meta_callback', 'review', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'review_custom_meta' );

/**
 * 2. Outputs the content of the meta box
 */
function review_meta_callback( $post ) {
    
    wp_nonce_field( basename( __FILE__ ), 'review_nonce' );
    
    $review_stored_meta = get_post_meta($post->ID, 'review_stored_meta', true);
    // print_r($review_stored_meta);
    // sscore_stored_meta instead of $repeatable_fields
    //$sscore_stored_meta = get_post_meta( $post->ID );
    
    if (is_array( $review_stored_meta ) ) {
    
		foreach ( $review_stored_meta as $field ) {
			//echo($field['_review-tex']);
		?>
    
    	<span>
			<p>
				<label for="_review-text" class="staff-row-title"><?php _e( 'Review Title ', 'review-textdomain' )?></label>
                <input type="text" name="_review-text[]" id="_review-text" value="<?php if ( isset ( $field['_review-text'] ) ) { echo $field['_review-text']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-author"><?php _e( 'Review Author ', 'review-author' )?></label>
                <input type="text" name="_review-author[]" id="_review-author" value="<?php if ( isset ( $field['_review-author'] ) ) { echo $field['_review-author']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-stars"><?php _e( 'Stars ', 'review-stars' )?></label>
                <input type="text" name="_review-stars[]" id="_review-stars" value="<?php if ( isset ( $field['_review-stars'] ) ) { echo $field['_review-stars']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-country"><?php _e( 'Country ', 'review-country' )?></label>
                <input type="text" name="_review-country[]" id="_review-country" value="<?php if ( isset ( $field['_review-country'] ) ) { echo $field['_review-country']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-web"><?php _e( 'Web Site: ', 'review-webdomain' )?></label>
                <input type="text" name="_review-web[]" id="_review-web" value="<?php if ( isset ( $field['_review-web'] ) ) { echo $field['_review-web']; } ?>" />
			</p>

		</span>

<?php 
		}
	} else {
		// empty fields 
		?>
    	<span>
			<p>
				<label for="_review-text" class="review-row-title"><?php _e( 'Review Title ', 'review-textdomain' )?></label>
                <input type="text" name="_review-text[]" id="_review-text" value="<?php if ( isset ( $field['_review-text'] ) ) { echo $field['_review-text']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-author"><?php _e( 'Review Author ', 'review-author' )?></label>
                <input type="text" name="_review-author[]" id="_review-author" value="<?php if ( isset ( $field['_review-author'] ) ) { echo $field['_review-author']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-stars"><?php _e( 'Stars ', 'review-stars' )?></label>
                <input type="text" name="_review-stars[]" id="_review-stars" value="<?php if ( isset ( $field['_review-stars'] ) ) { echo $field['_review-stars']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-country"><?php _e( 'Country ', 'review-country' )?></label>
                <input type="text" name="_review-country[]" id="_review-country" value="<?php if ( isset ( $field['_review-country'] ) ) { echo $field['_review-country']; } ?>" />
			</p>
			<p>
				<label for="_review-text" class="review-row-web"><?php _e( 'Web Site: ', 'review-webdomain' )?></label>
                <input type="text" name="_review-web[]" id="_review-web" value="<?php if ( isset ( $field['_review-web'] ) ) { echo $field['_review-web']; } ?>" />
			</p>

		</span>

		<?php
	}

} 



/**
 * Saves the custom meta input
 */
function review_meta_save( $post_id ) {

 	if ( ! isset( $_POST['review_nonce'] ) || !wp_verify_nonce( $_POST['review_nonce'], basename( __FILE__ ) ) ) {
		return;
	}
		
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'review_nonce' ] ) && wp_verify_nonce( $_POST[ 'review_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 	//echo $is_valid_nonce;
 	
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    $old = get_post_meta($post_id, 'review_stored_meta', true);
	$new = array();

	//print_r($old);
	//print_r($new);
	
	$meta_text_array = $_POST['_review-text'];
	$meta_twitter_array = $_POST['_review-author'];
	$meta_stars_array = $_POST['_review-stars'];
	$meta_country_array = $_POST['_review-country'];
	$meta_web_array = $_POST['_review-web'];
	
	$count = count( $meta_text_array );
	//echo $count;
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $meta_text_array[$i] != '' ) :
			$new[$i]['_review-text'] = sanitize_text_field( $meta_text_array[$i]); // title
			$new[$i]['_review-author'] = sanitize_text_field( $meta_twitter_array[$i] ); // author
			$new[$i]['_review-stars'] = sanitize_text_field( $meta_stars_array[$i] ); // stars
			$new[$i]['_review-country'] = sanitize_text_field( $meta_country_array[$i] ); // linked in
			$new[$i]['_review-web'] = sanitize_text_field( $meta_web_array[$i] ); // web site
		endif;
	}

	if ( !empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'review_stored_meta', $new );
	} elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'review_stored_meta', $old );
	}

//     if( isset( $_POST[ '_meta-text' ] ) ) {
//         update_post_meta( $post_id, '_meta-text', sanitize_text_field( $_POST[ '_meta-text' ] ) );
//     }

}

add_action( 'save_post', 'review_meta_save' );

// Load scripts
// -----------------------------------------------------------------------------
if (!is_admin()) {
        wp_register_style('style-famfamfam-flags', get_bloginfo( 'stylesheet_directory' )."/css/famfamfam-flags.css");
        wp_enqueue_style( 'style-famfamfam-flags' );
}

/* GUESTS */


/**
 * 1. Adds a meta box to the post editing screen
 */
function guest_custom_meta() {
   // add_meta_box( 'sscore_meta', __( 'Meta Box Title', 'sscore-textdomain' ), 'sscore_meta_callback', 'post' );
    add_meta_box( 'guest_meta', __( 'Guest Info', 'staff-textdomain' ), 'guest_meta_callback', 'guest', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'guest_custom_meta' );

/**
 * 2. Outputs the content of the meta box
 */
function guest_meta_callback( $post ) {
    
    wp_nonce_field( basename( __FILE__ ), 'guest_nonce' );
    
    $guest_stored_meta = get_post_meta($post->ID, 'guest_stored_meta', true);
    
    if (is_array( $guest_stored_meta ) ) {
    
		foreach ( $guest_stored_meta as $field ) {
			//echo($field['_guest-tex']);
		?>
    
    	<span>
			<p>
				<label for="_guest-text" class="guest-row-twitter"><?php _e( 'Twitter URL ', 'guest-twitter' )?></label>
                <input type="text" name="_guest-twitter[]" id="_guest-twitter" value="<?php if ( isset ( $field['_guest-twitter'] ) ) { echo $field['_guest-twitter']; } ?>" />
			</p>
			<p>
				<label for="_guest-bsky" class="guest-row-bsky"><?php _e( 'Bluesky Handle (e.g. user.bsky.social)', 'guest-bsky' )?></label>
                <input type="text" name="_guest-bsky[]" id="_guest-bsky" value="<?php if ( isset ( $field['_guest-bsky'] ) ) { echo $field['_guest-bsky']; } ?>" />
			</p>
			<p>
				<label for="_guest-mastodon" class="guest-row-mastodon"><?php _e( 'Mastodon URL (full URL)', 'guest-mastodon' )?></label>
                <input type="text" name="_guest-mastodon[]" id="_guest-mastodon" value="<?php if ( isset ( $field['_guest-mastodon'] ) ) { echo $field['_guest-mastodon']; } ?>" />
			</p>
		</span>

<?php 
		}
	} else {
		// empty fields 
		?>
    	<span>
			<p>
				<label for="_guest-text" class="guest-row-twitter"><?php _e( 'Twitter URL ', 'guest-twitter' )?></label>
                <input type="text" name="_guest-twitter[]" id="_guest-twitter" value="" />
			</p>
			<p>
				<label for="_guest-bsky" class="guest-row-bsky"><?php _e( 'Bluesky Handle (e.g. user.bsky.social)', 'guest-bsky' )?></label>
                <input type="text" name="_guest-bsky[]" id="_guest-bsky" value="" />
			</p>
			<p>
				<label for="_guest-mastodon" class="guest-row-mastodon"><?php _e( 'Mastodon URL (full URL)', 'guest-mastodon' )?></label>
                <input type="text" name="_guest-mastodon[]" id="_guest-mastodon" value="" />
			</p>
		</span>

		<?php
	}

} 



/**
 * Saves the custom meta input
 */
function guest_meta_save( $post_id ) {

 	if ( ! isset( $_POST['guest_nonce'] ) || !wp_verify_nonce( $_POST['guest_nonce'], basename( __FILE__ ) ) ) {
		return;
	}
		
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'guest_nonce' ] ) && wp_verify_nonce( $_POST[ 'guest_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 	//echo $is_valid_nonce;
 	
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    $old = get_post_meta($post_id, 'guest_stored_meta', true);
	$new = array();

	$meta_twitter_array = $_POST['_guest-twitter'];
	$meta_bsky_array = isset($_POST['_guest-bsky']) ? $_POST['_guest-bsky'] : array();
	$meta_mastodon_array = isset($_POST['_guest-mastodon']) ? $_POST['_guest-mastodon'] : array();
	
	$count = count( $meta_twitter_array );
	//echo $count;
	
	for ( $i = 0; $i < $count; $i++ ) {
		// check if twitter is missing
		if ( $meta_twitter_array[$i] != '' ) :
			$new[$i]['_guest-twitter'] = sanitize_text_field( $meta_twitter_array[$i] ); // twitter
		endif;
		if ( isset($meta_bsky_array[$i]) && $meta_bsky_array[$i] != '' ) :
			$new[$i]['_guest-bsky'] = sanitize_text_field( $meta_bsky_array[$i] ); // bsky
		endif;
		if ( isset($meta_mastodon_array[$i]) && $meta_mastodon_array[$i] != '' ) :
			$new[$i]['_guest-mastodon'] = sanitize_text_field( $meta_mastodon_array[$i] ); // mastodon
		endif;
	}

	if ( !empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'guest_stored_meta', $new );
	} elseif ( empty($new) && $old ) {
		delete_post_meta( $post_id, 'guest_stored_meta', $old );
	}
}

add_action( 'save_post', 'guest_meta_save' );

?>