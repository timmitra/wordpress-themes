<?php get_header(); ?>
<?php
$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' ); 
$large_image = $large_image[0]; 
$video_input = get_post_meta($post->ID, 'themnific_video_embed', true);
$project_url = get_post_meta($post->ID, 'themnific_project_url', true);
$project_implication = get_post_meta($post->ID, 'themnific_project_implication', true);
$project_issue = get_post_meta($post->ID, 'themnific_project_issue', true);
$project_result = get_post_meta($post->ID, 'themnific_project_result', true);
$attachments = get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );
?>

<?php the_post(); ?>
    
<div class="container container_block">
    
    <h2 class="itemtitle"><?php the_title(); ?></h2>
    
    <div class="nav_item">
        
        <?php previous_post_link('%link', '<i title="Previous Project" class="icon-double-angle-left"></i>') ?>
    
    	<a href="<?php echo stripslashes(get_option('themnific_url_portfolio'));?>"><i title="Back To Portfolio"  class="icon-double-angle-up"></i></a>
        
        <?php next_post_link('%link', '<i title="Next Project" class="icon-double-angle-right"></i>') ?>
	
    </div>
    
    <div class="hrlineB"><span></span></div>

    <div id="foliosidebar">
    
    
		<?php if($project_url) : ?>
        
        	<a class="tmnf-sc-button  silver xl" href="<?php echo $project_url; ?>"><?php _e('Visit Project','themnific');?> <i class="icon-signout"></i></a>
        
        <?php endif; ?>
        
        
       	<?php if($project_issue) : ?>
            
            <p class="meta">
    
                 <strong>Issue: </strong><br /><?php echo $project_issue; ?>
            
            </p>
            
        <?php endif; ?> 
        
       	<?php if($project_implication) : ?>
            
            <p class="meta">
    
                <strong>Implication: </strong><br /><?php echo $project_implication; ?>
            
            </p>
            
        <?php endif; ?>    
        
        <div class="meta">
             
				<strong>Solution: </strong><?php the_content(); ?>
                                
            
        </div>
   
        
       	<?php if($project_result) : ?>
            
            <p class="meta">
    
                 <strong>Result: </strong><br /><?php echo $project_result; ?>
            
            </p>
            
        <?php endif; ?>    
        
        <div class="hrline"><span></span></div>
        
        <p class="meta"><i class="icon-time"></i> <?php the_time(get_option('date_format')); ?></p>
                

        <p class="meta"><i class="icon-copy"></i> <?php $terms_of_post = get_the_term_list( $post->ID, 'categories', '',' &bull; ', ' ', '' ); echo $terms_of_post; ?></p>

        <div class="hrline"><span></span></div>
            
    </div>
    
    
    
    
    <div id="foliocontent">   
            
            <?php if($video_input) { echo ($video_input); 
			
			
            } elseif ($attachments) { echo get_template_part( '/includes/folio-types/gallery-slider' );


			} else {
				
				echo the_post_thumbnail('folio_slider' );
				
    		 }?> 
            
  
     </div>
     
</div>
        
<?php get_footer(); ?>