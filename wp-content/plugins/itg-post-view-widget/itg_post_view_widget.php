<?php
/*
Plugin Name: ITG Post View Widget
Plugin URI: http://www.it-guy.com/
Description: Show posts by view.
Author: Tim Mitra
Version: 0.0.3
Author URI: http://www.it-guy.com/
*/

// Creating the widget 
class itg_post_view_widget extends WP_Widget {
 
// The construct part  
function __construct() {
	parent::__construct(
  
	// Base ID of your widget
	'itg_post_view_widget', 
  
	// Widget name will appear in UI
	__('ITG Post View Widget', 'itg_post_view_widgte_domain'), 
  
	// Widget description
	array( 'description' => __( 'Show posts by views', 'itg_post_view_widget_domain' ), ) 
	);
}
  
	// Creating widget front-end
	public function get_post_view_Listings($numberOfListings) { //html
		global $post;
		$args = array('post_type' => 'post',
						'orderby' => 'itg_post_views_count',
						'orderby' => 'meta_value meta_value_num',
						'order' => 'DESC',
						'posts_per_page' => $numberOfListings );
		$listings = new WP_Query($args);
		add_image_size( 'post_view_widget_size', 85, 45);

		if($listings->found_posts > 0) {
			echo '<ul class="post_view__widget">';
				while ($listings->have_posts()) {
					$listings->the_post();
					$image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, array(85,70) ) : '<div class="noThumb"></div>'; 
					$listItem = '<li>' . $image; 
					$listItem .= '<a href="' . get_permalink() . '">';
					$listItem .= get_the_title() . '</a>';
					$listItem .= '<br />' . itg_get_post_views(get_the_ID());
					$listItem .= '<span>Added ' . get_the_date() . '</span></li>'; 
					echo $listItem; 
				}
			echo '</ul>';
			wp_reset_postdata(); 
		}else{
			echo '<p style="padding:25px;">No listing found</p>';
		} 
	}
          
/* This is the method that actually outputs our widget. 
  	This method accepts a couple of arguments – one of them is our $instance array. */
	public function widget( $args, $instance ) {
		?>
		<style>
			.noThumb{
			  width:67px; 
			  height:45px; 
			  background-color:#10416f;
			  float:left; 
			  margin:0 12px 0 0;
  
			}
			ul.post_view__widget {
			  margin-top:10px;
			  text-transform: uppercase;
  
			}
			ul.post_view__widget li {
			  margin:0 0 15px 0; 
			  list-style:none;
			  min-height:45px;
			  line-height:19px;
  
			}
			ul.post_view__widget li img{
			  float:left;
			  margin:0 12px 0 0;
  
			}
			ul.post_view__widget li a {
			  font-weight:bold;
  
			}
			ul.post_view__widget li span {
			  display:block; 
			  font-size:11px;
  
			}
		</style>
		<?php
		$title = apply_filters( 'widget_title', $instance['title'] );
  		$numberOfListings = $instance['numberOfListings'];
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( $title ) {
			echo "<span style=color:black>".$args['before_title'] . $title . $args['after_title']."</span>";
		}
		$this->get_post_view_Listings($numberOfListings);
		echo $args['after_widget'];
	}
	
         
	// Widget Backend 
	public function form( $instance ) {
		if( $instance) {
			$title = esc_attr($instance['title']);
			$numberOfListings = esc_attr($instance['numberOfListings']);
		} else {
			$title = '';
			$numberOfListings = '';
		} 
		// Widget admin form
		?>
		<p>
		<h3>post_view_s</h3>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'post_view__widget'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Number of Listings:', 'post_view__widget'); ?></label>		
		<select id="<?php echo $this->get_field_id('numberOfListings'); ?>"  name="<?php echo $this->get_field_name('numberOfListings'); ?>">
			<?php for($x=1;$x<=10;$x++): ?>
			<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
			<?php endfor;?>
		</select>
		</p>
		<?php 
	}
      
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
		return $instance;
	}
 
// Class itg_post_view_widget ends here
} 

// Register and load the widget
function itg_post_view_load_widget() {
    register_widget( 'itg_post_view_widget' );
}
add_action( 'widgets_init', 'itg_post_view_load_widget' );

?>