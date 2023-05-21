<div id="review-wrap">
	
	<ul class="archivepost">
	<!--ul class="warpbox"-->
    
		<?php $loop = new WP_Query( array( 'post_type' => 'review','posts_per_page' => 50) ); ?>
        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php 
        	//print_r(get_post_meta($post->ID));
			$review_stored_meta = get_post_meta($post->ID, 'review_stored_meta', true);
			//print_r($review_stored_meta);
			$review_position = $review_stored_meta[0]['_review-text'];
			$review_author = $review_stored_meta[0]['_review-author'];
			$review_stars = $review_stored_meta[0]['_review-stars'];
			//$review_google = $review_stored_meta[0]['_review-go'];
			$review_country = $review_stored_meta[0]['_review-country'];
			//$review_website = $review_stored_meta[0]['_review-web']; 
		?>
        
            <!--li class="review threecol"-->
            <li>
                
        		<div style="float:left;height:100%;">
        			<?php the_post_thumbnail('review'); ?>
        		</div>
                
				<div style="float:left;">
					<h3 class="leading"><?php the_title(  ); ?></h3>
				
				<?php if($review_stars) {?>
					<p class="meta"><img src="/wp-content/uploads/2016/10/<?php echo ($review_stars); ?>-stars.png" style="height:15px;width:83px;"/><br /></p>
                <?php } else {}?>
                
                <?php if($review_position) {?>
                        <p class="meta">by: <?php echo ($review_author); ?></p>
                <?php } else {}?>
                       
                <?php if($review_country) {?>
                        <p class="meta"><i class="famfamfam-flag-<?php echo ($review_country); ?>"></i><br /></p>
                <?php } else {}?>
                       
                <?php the_content(); ?>
                
                <!--ul class="review_social">
                
                	<?php if($review_author) {?><li><a class="rad mk-social-author" href="http://www.author.com/<?php echo ($review_author); ?>">Author</a></li><?php } else {}?>
                	<?php if($review_stars) {?><li><a class="rad mk-social-stars-alt" href="http://www.stars.com/<?php echo ($review_stars); ?>">stars</a></li><?php } else {}?>
                	<?php if($review_google) {?><li><a class="rad mk-social-googleplus" href="<?php echo ($review_google); ?>">Google+</a></li><?php } else {}?>
                	<?php if($review_linkedin) {?><li><a class="rad mk-social-linkedin" href="http://www.linkedin.com/in/<?php echo ($review_linkedin); ?>">LinkedIn</a></li><?php } else {}?>
                	<?php if($review_pinterest) {?><li><a class="rad mk-social-pinterest" href="<?php echo ($review_pinterest); ?>">Pinterest</a></li><?php } else {}?>
                	<?php if($review_website) {?><li><a class="rad mk-social-wp" href="http://<?php echo ($review_website); ?>"><?php echo ($review_website); ?></a></li><?php } else {}?>
                
                </ul-->
                </div>
            </li>
                <hr />
        
        <?php endwhile; ?>
    
    </ul>
</div> 
<div style="clear: both;"></div>	