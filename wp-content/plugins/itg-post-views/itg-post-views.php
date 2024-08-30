<?php

/*
Plugin Name: ITG Post Views
Plugin URI: http://www.it-guy.com/
Description: Keep track of how often posts are viewed. add itg_set_post_views(get_the_ID()); to single posts.
Author: Tim Mitra
Version: 0.0.1
Author URI: http://www.it-guy.com/

https://www.wpbeginner.com/wp-tutorials/how-to-track-popular-posts-by-views-in-wordpress-without-a-plugin/

*/

function itg_set_post_views($postID) {
    $count_key = 'itg_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function itg_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    itg_set_post_views($post_id);
}
add_action( 'wp_head', 'itg_track_post_views');

function itg_get_post_views($postID){
    $count_key = 'itg_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

?>