<div id="guest-wrap">
	<ul class="warpbox">
    
		<?php $loop = new WP_Query( array( 'post_type' => 'guest','posts_per_page' => 50) ); ?>
        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php 
        	//print_r(get_post_meta($post->ID));
			$guest_stored_meta = get_post_meta($post->ID, 'guest_stored_meta', true);
			//print_r($guest_stored_meta);
			if (is_array($guest_stored_meta) && isset($guest_stored_meta[0])) {
				$guest_position = isset($guest_stored_meta[0]['_guest-text']) ? $guest_stored_meta[0]['_guest-text'] : '';
				$guest_facebook = isset($guest_stored_meta[0]['_guest-fb']) ? $guest_stored_meta[0]['_guest-fb'] : '';
				$guest_twitter = isset($guest_stored_meta[0]['_guest-twitter']) ? $guest_stored_meta[0]['_guest-twitter'] : '';
				$guest_google = isset($guest_stored_meta[0]['_guest-go']) ? $guest_stored_meta[0]['_guest-go'] : '';
				$guest_linkedin = isset($guest_stored_meta[0]['_guest-ln']) ? $guest_stored_meta[0]['_guest-ln'] : '';
				$guest_website = isset($guest_stored_meta[0]['_guest-web']) ? $guest_stored_meta[0]['_guest-web'] : '';
				$guest_bsky = isset($guest_stored_meta[0]['_guest-bsky']) ? $guest_stored_meta[0]['_guest-bsky'] : '';
				$guest_mastodon = isset($guest_stored_meta[0]['_guest-mastodon']) ? $guest_stored_meta[0]['_guest-mastodon'] : '';
			} else {
				$guest_position = '';
				$guest_facebook = '';
				$guest_twitter = '';
				$guest_google = '';
				$guest_linkedin = '';
				$guest_website = '';
				$guest_bsky = '';
				$guest_mastodon = '';
			}
		?>
        
            <li class="guest threecol">
                
        		<div><?php the_post_thumbnail('guest'); ?></div>                
				<h3><?php the_title(  ); ?>
                <?php if ( current_user_can('edit_post', get_the_ID()) ) : ?>
                    <a href="<?php echo get_edit_post_link(get_the_ID()); ?>" style="font-size:small; margin-left:10px;">[Edit]</a>
                <?php endif; ?>
                </h3>   
                
                <?php if($guest_position) {?>
                        <p class="meta"><?php echo ($guest_position); ?></p>
                <?php } else {}?>
                       
                <?php //the_content(); ?>
                
                <ul class="guest_social">
                
                	<?php if(isset($guest_facebook) && !empty($guest_facebook)) {?>
                        <li><a class="rad mk-social-facebook" href="http://www.facebook.com/<?php echo htmlspecialchars($guest_facebook); ?>">FB</a></li>
                    <?php } ?>
                    <?php if(isset($guest_twitter) && !empty($guest_twitter)) { ?>
                        <li><a class="rad mk-social-twitter-alt" href="http://www.twitter.com/<?php echo htmlspecialchars($guest_twitter); ?>">Twitter</a></li>
                    <?php } ?>
                    <?php if(isset($guest_bsky) && !empty($guest_bsky)) { ?>
                        <?php
                        $bsky_handle = $guest_bsky;
                        if (!empty($bsky_handle) && strpos($bsky_handle, '.') === false) {
                            $bsky_handle .= '.bsky.social';
                        }
                        ?>
                        <li><a class="rad mk-social-bsky" href="https://bsky.app/profile/<?php echo htmlspecialchars($bsky_handle); ?>">Bluesky</a></li>
                    <?php } ?>
                    <?php if(isset($guest_mastodon) && !empty($guest_mastodon)) { ?>
                        <li><a class="rad mk-social-mastodon" href="<?php echo htmlspecialchars($guest_mastodon); ?>">Mastodon</a></li>
                    <?php } ?>
                    <?php if(isset($guest_google) && !empty($guest_google)) {?>
                        <li><a class="rad mk-social-googleplus" href="<?php echo htmlspecialchars($guest_google); ?>">Google+</a></li>
                    <?php } ?>
                    <?php if(isset($guest_linkedin) && !empty($guest_linkedin)) {?>
                        <li><a class="rad mk-social-linkedin" href="http://www.linkedin.com/in/<?php echo htmlspecialchars($guest_linkedin); ?>">LinkedIn</a></li>
                    <?php } ?>
                    <?php if(isset($guest_pinterest) && !empty($guest_pinterest)) {?>
                        <li><a class="rad mk-social-pinterest" href="<?php echo htmlspecialchars($guest_pinterest); ?>">Pinterest</a></li>
                    <?php } ?>
                    <?php if(isset($guest_website) && !empty($guest_website)) {?>
                        <li><a class="rad mk-social-wp" href="http://<?php echo htmlspecialchars($guest_website); ?>"><?php echo htmlspecialchars($guest_website); ?></a></li>
                    <?php } ?>
                
                </ul>
                
            </li>
        
        <?php endwhile; ?>
    
    </ul>
</div> 
<div style="clear: both;"></div>	