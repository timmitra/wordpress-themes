<?php

	/* Template Name: App Category */

?>
<?php get_header(); the_post(); ?>
	
	<?php
	
		$categoriesCF = get_post_meta($post->ID, "categories", true);
		// eg value = "GameApp|1,GameApp|2"
		
		$allCategories = explode(",", $categoriesCF);
		// is an array of apps
		
		foreach($allCategories as $category) {
		
			$pieces = explode("|", $category);
			// category name
			
			$link = get_permalink($pieces[1]);
			echo "<div class='app-group group'>";
			echo "<h3><a href='$link'>" . $pieces[0] . "</a></h3><hr />";
			
			query_posts("posts_per_page=-1&post_type=page&post_parent=$pieces[1]");
			// -1 gets all the matching posts 
			
			while (have_posts()) : the_post(); ?>
		
			
				<div class="app-content">
				<?php if (get_post_meta($post->ID, 'app-url', true)) { ?>
					<a href="<?php echo get_post_meta($post->ID, 'app-url', true); ?>">
				<?php } ?>
					<img class="app_icon" src="<?php echo get_post_meta($post->ID, 'app-icon-image', true); ?>"/>
				</a>
		
				<span class="app_name"><?php the_title(); ?></span>
				
				<?php if (get_post_meta($post->ID, 'app-featured', true)) { ?>
					<br />
					<span class="app_featured"><?php echo get_post_meta($post->ID, 'app-featured', true); ?></span>
				<?php } ?>

				 - 
				<?php echo get_post_meta($post->ID, 'app-description', true); ?>
			
				<br />
				<?php if (get_post_meta($post->ID, 'app-url', true)) { ?>
					<a href="<?php echo get_post_meta($post->ID, 'app-url', true); ?>">Download</a> from the App Store.
				<?php } ?>
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				<g:plusone></g:plusone>
				</div>
				<br />
				<hr />
			<?php endwhile; wp_reset_query();
			
			echo "</div>";
			
			?>
		
	<?php
	
	 };
?>

<script type="text/javascript">
Ê (function() {
Ê Ê var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
Ê Ê po.src = 'https://apis.google.com/js/plusone.js';
Ê Ê var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
Ê })();
</script>


<?php get_footer(); ?>
