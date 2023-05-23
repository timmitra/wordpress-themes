<?php

/* 
 * Plugin Name: MTJC Custom Post Types and Taxonomies.
 * Description: a plug to create Host, Review, and Guest custom post types and taxonomies.
 * Version: 0.0.1
 * Author: Tim
 * License: GPL2
 */

/*  Copyright 2023  Tim Mitra  (email : tim@it-guy.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function mtjc_custom_posttypes() {

    // Guests post type
    $labels = array(
        'name'               => 'Guests',
        'singular_name'      => 'Guest',
        'menu_name'          => 'Guests',
        'name_admin_bar'     => 'Guest',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Guest',
        'new_item'           => 'New Guest',
        'edit_item'          => 'Edit Guest',
        'view_item'          => 'View Guest',
        'all_items'          => 'All Guests',
        'search_items'       => 'Search Guests',
        'parent_item_colon'  => 'Parent of Guest:',
        'not_found'          => 'No Guest found.',
        'not_found_in_trash' => 'No Guest found in Trash.',
    );
    
    $args = array(
        'labels'             => $labels, // loads $labels array above
        'public'             => true,
        'publicly_queryable' => true,
        'exclude_from_search'=> false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-groups',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'guest' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'supports'           => array( 
        	'title', 
			'editor', 
			'post-thumbnails',
			'custom-fields',
			'page-attributes',
			'author',
			'thumbnail',
			'comments' )
    );
    
    register_post_type('guest', $args);
    
    // Hosts post type
    $labels = array(
        'name'               => 'Hosts',
        'singular_name'      => 'Host',
        'menu_name'          => 'Hosts',
        'name_admin_bar'     => 'Host',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Host',
        'new_item'           => 'New Host',
        'edit_item'          => 'Edit Host',
        'view_item'          => 'View Host',
        'all_items'          => 'All Hosts',
        'search_items'       => 'Search Hosts',
        'parent_item_colon'  => 'Parent of Host:',
        'not_found'          => 'No Host found.',
        'not_found_in_trash' => 'No Host found in Trash.',
    );
    
    $args = array(
        'labels'             => $labels, // loads $labels array above
        'public'             => true,
        'publicly_queryable' => true,
        'exclude_from_search'=> false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-microphone',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'staff' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array( 
        	'title', 
			'editor', 
			'post-thumbnails',
			'custom-fields',
			'page-attributes',
			'author',
			'thumbnail',
			'comments' )
    );
    register_post_type('staff', $args);
    
        // Reviews post type
    $labels = array(
        'name'               => 'Reviews',
        'singular_name'      => 'Review',
        'menu_name'          => 'Reviews',
        'name_admin_bar'     => 'Review',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Review',
        'new_item'           => 'New Review',
        'edit_item'          => 'Edit Review',
        'view_item'          => 'View Review',
        'all_items'          => 'All Reviews',
        'search_items'       => 'Search Reviews',
        'parent_item_colon'  => 'Parent of Review:',
        'not_found'          => 'No Review found.',
        'not_found_in_trash' => 'No Review found in Trash.',
    );
    
    $args = array(
        'labels'             => $labels, // loads $labels array above
        'public'             => true,
        'publicly_queryable' => true,
        'exclude_from_search'=> false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-star-filled',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'review' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'supports'           => array( 
        	'title', 
			'editor', 
			'post-thumbnails',
			'custom-fields',
			'page-attributes',
			'author',
			'thumbnail',
			'comments' )
    );
    register_post_type('review', $args);

    
}

add_action('init', 'mtjc_custom_posttypes');


?>