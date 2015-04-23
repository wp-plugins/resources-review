<?php 
/*
Plugin Name: Resource Review
Plugin URI: https://nathanhensher.com/plugins/resource-review
Description: Adds a resource custom post type with the option to add multiple fields to review the resource.
Version: 1.1
Author: Nathan Hensher
Author URI: https://nathanhensher.com/
*/

/*  Copyright 2015 Nathan Hensher  (email : me@nathanhensher.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

$plugin_url = plugins_url(). "/resource-review/";

if (is_admin()) {
	add_action( 'init', 'cmb_showcase_load_metabox' );
}

function cmb_showcase_load_metabox() {
	require_once( 'includes/metabox/cmb-functions.php' );
	require_once( 'includes/metabox/init.php' );
}

//Setting up Resource post type

function resources_post_type() {

	$labels = array(
		'name'                => _x( 'Resources', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Resource', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Resources', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Resources', 'text_domain' ),
		'view_item'           => __( 'View Resource', 'text_domain' ),
		'add_new_item'        => __( 'Add New Resource', 'text_domain' ),
		'add_new'             => __( 'Add Resource', 'text_domain' ),
		'edit_item'           => __( 'Edit Resource', 'text_domain' ),
		'update_item'         => __( 'Update Resource', 'text_domain' ),
		'search_items'        => __( 'Search Resource', 'text_domain' ),
		'not_found'           => __( 'Resource Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                => 'resource',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$capabilities = array(
		'edit_post'           => 'edit_post',
		'read_post'           => 'read_post',
		'delete_post'         => 'delete_post',
		'edit_posts'          => 'edit_posts',
		'edit_others_posts'   => 'edit_others_posts',
		'publish_posts'       => 'publish_posts',
		'read_private_posts'  => 'read_private_posts', 
	);
	$args = array(
		'label'               => __( 'resources', 'text_domain' ),
		'description'         => __( 'The Resource posts containing reviews for each resource.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'map_meta_cap'		  => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'register_meta_box_cb' => 'move_activitypost_box',
		'rewrite'             => $rewrite,
		'capabilities'        => $capabilities,
	);
	register_post_type( 'resources', $args );
	flush_rewrite_rules();
}

// Hook into the 'init' action
add_action( 'init', 'resources_post_type', 0 );

//--------------------------------------------------------------------------------//	
								//Taxonomies//
//--------------------------------------------------------------------------------//	

// Register Media Type Taxonomy
function media_type_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Media Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Media Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Media Type', 'text_domain' ),
		'all_items'                  => __( 'All Media Types', 'text_domain' ),
		'parent_item'                => __( 'Parent Media Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Media Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Media Type', 'text_domain' ),
		'add_new_item'               => __( 'Add New Media Type', 'text_domain' ),
		'edit_item'                  => __( 'Edit Media Type', 'text_domain' ),
		'update_item'                => __( 'Update Media Type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'search_items'               => __( 'Search Media Types', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Media Types', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Media Types', 'text_domain' ),
		'not_found'                  => __( 'Media Type Not Found', 'text_domain' ),
	);
	
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_in_menu'				 => true,
		'show_tagcloud'              => true,
	
	);
	register_taxonomy( 'media-type', array( 'resources' ), $args );

}


// Hook into the 'init' action
add_action( 'init', 'media_type_taxonomy', 0 );

function insert_terms() {
	$terms = array('Audio','CD','Download','DVD','Publication','Website');
	foreach( $terms as $term ) {
		   //intert the term
		   wp_insert_term(
				$term, // the term 
				'media-type' // the taxonomy
			);
	}
}

add_action( 'init', 'insert_terms' );

add_action('do_meta_boxes', 'move_media_type_post_box');


function move_media_type_post_box() {
	remove_meta_box( 'media-typediv', 'resources', 'side' );
}


//--------------------------------------------------------------------------------//	
						//Overall Rating Meta box//
//--------------------------------------------------------------------------------//	

add_action( 'add_meta_boxes', 'add_rating_box' );
function add_rating_box( $post ) {
 
        add_meta_box(
                'rating', // ID, should be a string
                '<i class="fa fa-check-square-o"></i> Overall Rating', // Meta Box Title
                'rating_meta_box_content', // Your call back function, this is where your form field will go
                'resources', // The post type you want this to show up on, can be post, page, or custom post type
                'side', // The placement of your meta box, can be normal or side
                'high' // The priority in which this will be displayed
            );
 
    }
function rating_meta_box_content( $post ) {
 		global $post;
		$entries = get_post_meta( $post->ID, '_cmb_repeat_group', true ); 
		$items = array();
			foreach ( (array) $entries as $key => $entry ) {
			
				if ( isset( $entry['rating'] ) )
					$items[] = esc_html( $entry['rating'] );
			}
		
		$average = array_sum($items) / count($items);

		echo 'Rating: '.$average.'/5'; 
    }

//--------------------------------------------------------------------------------//	
					// Add Resource Review Shortcode //
//--------------------------------------------------------------------------------//	

function resources_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'show' => '10',
		), $atts )
	);

	// Code
	$args = array(
		'post_type' 			=> 'resources',
		'posts_per_page' 	=> $atts['show'],
	);
	echo '<div id="resources">';
	$query = new WP_Query( $args );
	while ($query->have_posts()) : $query->the_post(); 
	
	require_once( plugin_dir_path( __FILE__ ) .'/resource-template.php' );

	endwhile;
	echo '</div>';
}
add_shortcode( 'resources', 'resources_shortcode' );



//--------------------------------------------------------------------------------//	
		/* Filter the single_template with our custom function*/
//--------------------------------------------------------------------------------//	

add_filter('single_template', 'resources_template');

function resources_template($single) {
    global $wp_query, $post;

/* Checks for single template by post type */
if ($post->post_type == "resources"){
    if(file_exists(plugin_dir_path( __FILE__ ) .'single-resource.php'))
        return plugin_dir_path( __FILE__ ) .'single-resource.php';
}
    return $single;
}

//--------------------------------------------------------------------------------//	
							/* Register Files */
//--------------------------------------------------------------------------------//	

function resource_includes()
{
    // Register the style for this plugin
    wp_register_style( 'resource-style', plugins_url( '/includes/css/resource-style.css', __FILE__ ), array(), '20120208', 'all' );
	// Register the js for plugin
	wp_register_script( 'resource-script', plugins_url( '/includes/js/resource.js', __FILE__ ) );

	wp_enqueue_style( 'resource-style' );
	
	wp_enqueue_script( 'resource-script' );
}
add_action( 'wp_enqueue_scripts', 'resource_includes' ); 
