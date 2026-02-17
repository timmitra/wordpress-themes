<?php

/*
Plugin Name:  MTJC Speakers Plugin 
Plugin URI:   https://www.it-guy.com 
Description:  Adds speaker bubbles to posts. Requires post2post 
Version:      1.0
Author:       Tim Mitra 
Author URI:   https://www.it-guy.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

	function mtjc_speakers($content) {
	
			
		// Only do this when a single post is displayed
		if ( is_single() ) {
	
			//$content .= $content;
			
				// Find connected host pages
				$connected = new WP_Query( array(
				  'connected_type' => 'host_to_show',
				  'connected_items' => get_queried_object(),
				  'nopaging' => true,
				   'order' => 'ASC'
				) );

				// Display connected pages
				if ( $connected->have_posts() ) :
				
					$content .=	"<div class='hosts-icons'>Featuring: <table> <tr>";
					 while ( $connected->have_posts() ) : $connected->the_post(); 
					$content .=	"<td width='150'><a href='";
					$content .= the_permalink(); 
					$content .=	"><div class='hosts-avatar'>";
					if ( has_post_thumbnail() ) {
						$content .=	the_post_thumbnail( array(100,100) );
					}
					$content .='</div>';
					$content .= the_title(); 
					$content .= "'</a>&nbsp;</td>";
					 endwhile; 
					// Prevent weirdness
					wp_reset_postdata();

				endif;
			 ?>
			 
			 			<?php
				// Find connected host pages
				$connected = new WP_Query( array(
				  'connected_type' => 'guest_to_show',
				  'connected_items' => get_queried_object(),
				  'nopaging' => true,
				   'order' => 'ASC'
				) );

				// Display connected pages
				if ( $connected->have_posts() ) :
				?>
<					<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
						<td width="150">
						<a href="<?php the_permalink(); ?>">
						<div class="hosts-avatar">
						<?php 
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( array(100,100) );
								
							}
							?>
							</div>
							<?php
						 	the_title(); ?>
						 </a>&nbsp;
						 </td>
					<?php endwhile; ?>
					</tr>
					</table>
				</div>
				<?php 
				// Prevent weirdness
				wp_reset_postdata();

				endif;
				
				
		} // end is single
		
		// return the content
		return $content;

				
	} //end function mtjc_speakers
	
?>

<?php

// Hook our function to WordPress the_content filter
add_filter('the_content', 'mtjc_speakers');

?>
