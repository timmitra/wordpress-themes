<div id="guest-wrap">
	<ul class="warpbox">
    
		<?php $loop = new WP_Query( array( 'post_type' => 'guest','posts_per_page' => 50) ); ?>
        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php 
        	//print_r(get_post_meta($post->ID));
			$guest_stored_meta = get_post_meta($post->ID, 'guest_stored_meta', true);
			//print_r($guest_stored_meta);
			$guest_position = $guest_stored_meta[0]['_guest-text'];
			$guest_facebook = $guest_stored_meta[0]['_guest-fb'];
			$guest_twitter = $guest_stored_meta[0]['_guest-twitter'];
			$guest_google = $guest_stored_meta[0]['_guest-go'];
			$guest_linkedin = $guest_stored_meta[0]['_guest-ln'];
			$guest_website = $guest_stored_meta[0]['_guest-web'];
		?>
        
            <li class="guest threecol">
                
        		<div><?php the_post_thumbnail('guest'); ?></div>                
				<h3><?php the_title(  ); ?></h3>   
                
                <?php if($guest_position) {?>
                        <p class="meta"><?php echo ($guest_position); ?></p>
                <?php } else {}?>
                       
                <?php the_content(); ?>
                
                <ul class="guest_social">
                
                	<?php if($guest_facebook) {?><li><a class="rad mk-social-facebook" href="http://www.facebook.com/<?php echo ($guest_facebook); ?>">FB</a></li><?php } else {}?>
                	<?php if($guest_twitter) {?><li><a class="rad mk-social-twitter-alt" href="http://www.twitter.com/<?php echo ($guest_twitter); ?>">Twitter</a></li><?php } else {}?>
                	<?php if($guest_google) {?><li><a class="rad mk-social-googleplus" href="<?php echo ($guest_google); ?>">Google+</a></li><?php } else {}?>
                	<?php if($guest_linkedin) {?><li><a class="rad mk-social-linkedin" href="http://www.linkedin.com/in/<?php echo ($guest_linkedin); ?>">LinkedIn</a></li><?php } else {}?>
                	<?php if($guest_pinterest) {?><li><a class="rad mk-social-pinterest" href="<?php echo ($guest_pinterest); ?>">Pinterest</a></li><?php } else {}?>
                	<?php if($guest_website) {?><li><a class="rad mk-social-wp" href="http://<?php echo ($guest_website); ?>"><?php echo ($guest_website); ?></a></li><?php } else {}?>
                
                </ul>
                
            </li>
        
        <?php endwhile; ?>
    
    </ul>
</div> 
<div style="clear: both;"></div>	