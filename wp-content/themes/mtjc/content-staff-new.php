<div id="staff-wrap">
	<ul class="archivepost">
	<!--ul class="warpbox"-->
    
		<?php $loop = new WP_Query( array( 'post_type' => 'staff','posts_per_page' => 50) ); ?>
        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php 
        	//print_r(get_post_meta($post->ID));
			$staff_stored_meta = get_post_meta($post->ID, 'staff_stored_meta', true);
			//print_r($staff_stored_meta);
			$staff_position = $staff_stored_meta[0]['_staff-text'];
			$staff_facebook = $staff_stored_meta[0]['_staff-fb'];
			$staff_twitter = $staff_stored_meta[0]['_staff-tw'];
			$staff_google = $staff_stored_meta[0]['_staff-go'];
			$staff_linkedin = $staff_stored_meta[0]['_staff-ln'];
			$staff_website = $staff_stored_meta[0]['_staff-web'];
		?>
        
            <!--li class="staff threecol"-->
            <li>
                
        		<div style="float:left;height:100%;">
        			<?php the_post_thumbnail('staff'); ?>
        		</div>
                
				<div style="float:left;">
					<h3 class="leading"><?php the_title(  ); ?></h3>   
                
                <?php if($staff_position) {?>
                        <p class="meta"><?php echo ($staff_position); ?></p>
                <?php } else {}?>
                       
                <?php the_content(); ?>
                
                <ul class="staff_social">
                
                	<?php if($staff_facebook) {?><li><a class="rad mk-social-facebook" href="http://www.facebook.com/<?php echo ($staff_facebook); ?>">FB</a></li><?php } else {}?>
                	<?php if($staff_twitter) {?><li><a class="rad mk-social-twitter-alt" href="http://www.twitter.com/<?php echo ($staff_twitter); ?>">Twitter</a></li><?php } else {}?>
                	<?php if($staff_google) {?><li><a class="rad mk-social-googleplus" href="<?php echo ($staff_google); ?>">Google+</a></li><?php } else {}?>
                	<?php if($staff_linkedin) {?><li><a class="rad mk-social-linkedin" href="http://www.linkedin.com/in/<?php echo ($staff_linkedin); ?>">LinkedIn</a></li><?php } else {}?>
                	<?php if($staff_pinterest) {?><li><a class="rad mk-social-pinterest" href="<?php echo ($staff_pinterest); ?>">Pinterest</a></li><?php } else {}?>
                	<?php if($staff_website) {?><li><a class="rad mk-social-wp" href="http://<?php echo ($staff_website); ?>"><?php echo ($staff_website); ?></a></li><?php } else {}?>
                
                </ul>
                </div>
            </li>
                <hr />
        
        <?php endwhile; ?>
    
    </ul>
</div> 
<div style="clear: both;"></div>	