<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'resource_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function resource_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['resource_metabox'] = array(
		'id'         => 'resource_metabox',
		'title'      => __( '<i class="fa fa-archive"></i> Resource Details', 'cmb' ),
		'pages'      => array( 'resources', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name' => __( 'Resource URL', 'cmb' ),
				'desc' => __( 'Where can the resurce be found?', 'cmb' ),
				'id'   => $prefix . 'url',
				'type' => 'text_url',
				// 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
				// 'repeatable' => true,
			),
			array(
				'name' => __( 'Price', 'cmb' ),
				'desc' => __( 'How much does the resource cost?', 'cmb' ),
				'id'   => $prefix . 'price',
				'type' => 'text_money',
				'before'     => '£',
				// 'repeatable' => true,
			),
			array(
				'name'     => __( 'Media Type', 'cmb' ),
				'desc'     => __( 'The format of resource', 'cmb' ),
				'id'       => $prefix . 'test_multitaxonomy',
				'type'     => 'taxonomy_multicheck',
				'taxonomy' => 'media-type', // Taxonomy Slug
				'inline'  => true, 
			),
		),
	);
	/**
	 * Repeatable Field Groups
	 */
	$meta_boxes['field_group'] = array(
		'id'         => 'field_group',
		'title'      => __( '<i class="fa fa-star"></i> Review Categories', 'cmb' ),
		'pages'      => array( 'resources', ),
		'fields'     => array(
			array(
				'id'          => $prefix . 'repeat_group',
				'type'        => 'group',
				'description' => __( 'Add categories to review the resource on. Each star rating calculates an overall rating based on each category.', 'cmb' ),
				'options'     => array(
					'group_title'   => __( 'Category {#}', 'cmb' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Category', 'cmb' ),
					'remove_button' => __( 'Remove Category', 'cmb' ),
					'sortable'      => true, // beta
				),
				// Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
				'fields'      => array(
					array(
						'name' => 'Review Category Title',
						'id'   => 'title',
						'type' => 'text',
						// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
					),
					array(
						'name' => 'Review Category Text',
						'description' => 'Write a short review for this category',
						'id'   => 'description',
						'type' => 'textarea_small',
					),
					array(
						'name' => 'Review Category Image',
						'id'   => 'image',
						'type' => 'file',
					),
					array(
						'name'    => __( 'Category Star Rating', 'cmb' ),
						'desc'    => __( 'The star rating for this category', 'cmb' ),
						'id'      => 'rating',
						'type'    => 'radio_inline',
						'options' => array(
							'1' => __( 'One <i class="fa fa-star"></i>', 'cmb' ),
							'2'   => __( 'Two <i class="fa fa-star"></i>', 'cmb' ),
							'3'     => __( 'Three <i class="fa fa-star"></i>', 'cmb' ),
							'4'     => __( 'Four <i class="fa fa-star"></i>', 'cmb' ),
							'5'     => __( 'Five <i class="fa fa-star"></i>', 'cmb' ),
						),
					),
				),
			),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'initialize_resource_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function initialize_resource_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
