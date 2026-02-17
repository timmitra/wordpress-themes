<div id="footer" class="body3">

	<div class="container">

        <div id="copyright">
                
            <div class="fl">
				<p style="width:400px;"><?php wp_tag_cloud('number=40'); ?></p>
            </div>
        
        
            <div class="fl">
            
				<?php if(get_option('themnific_footer_left') == 'true'){
                    
                    echo do_shortcode(get_option('themnific_footer_left_text'));
                    
                } else { ?>
        
        			<p>&copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></p>
                    
                <?php } ?>
                    
            </div>
        
            <div class="fr">
            
				<?php if(get_option('themnific_footer_right') == 'true'){
                    
                    echo do_shortcode(get_option('themnific_footer_right_text'));
                    
                } else { ?>
                
                    <p><?php _e('Powered by','themnific');?> <a href="http://www.wordpress.org">Wordpress</a>. <?php _e('Designed by','themnific');?> <a href="http://themnific.com">Themnific&trade;</a></p>
                    
                <?php } ?>
                
				<p>This site has been visited at least
					<SCRIPT LANGUAGE ="JavaScript" src="https://www.it-guy.com/www.it-guy.com/thecount.js" ></SCRIPT>
					<SCRIPT LANGUAGE ="php" src="https://www.it-guy.com/www.it-guy.com/jscount.php" ></SCRIPT>
					<!--SCRIPT LANGUAGE ="Javascript" document.write(' offline ') ></SCRIPT-->
					 times since March 2, 2000.
				</p>
           </div>
                  
			<br />
        </div> 
    
	</div>
        
</div><!-- /#footer  -->
    
<?php themnific_foot(); ?>
<?php wp_footer(); ?>



</body>
</html>