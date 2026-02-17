<?php

	/* Template Name: App Page */

?>
<?php get_header(); the_post(); ?>

	<div class="container container_block">
		<div id="content" class="eightcol">
			<?php edit_post_link(__('Edit', 'inove'), '<span class="editpost">', '</span>'); ?>
			<?php if (get_post_meta($post->ID, 'app-url', true)) { ?>
				<a href="<?php echo get_post_meta($post->ID, 'app-url', true); ?>">
			<?php } else { ?>
				<a href="#" >
			<?php } ?>
				<img class="app_icon" src="<?php echo get_post_meta($post->ID, 'app-icon-image', true); ?>"/>
			</a>
		
			<p><span class="app_name"><?php the_title(); ?></span>
			 - 
			<?php if (get_post_meta($post->ID, 'app-featured', true)) { ?>
				<br />
				<span class="app_featured"><?php echo get_post_meta($post->ID, 'app-featured', true); ?></span>
			<?php } ?>
			</p>
			
			<p><?php echo get_post_meta($post->ID, 'app-description', true); ?></p>
		
			<br />
			<?php if (get_post_meta($post->ID, 'app-url', true)) { ?>
				<a href="<?php echo get_post_meta($post->ID, 'app-url', true); ?>">Download</a> from the App Store.
			<?php } ?>
		</div>

	</div>

<br />
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<g:plusone></g:plusone>
<script type="text/javascript">
Ê (function() {
Ê Ê var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
Ê Ê po.src = 'https://apis.google.com/js/plusone.js';
Ê Ê var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
Ê })();
</script>

<?php get_footer(); ?>
