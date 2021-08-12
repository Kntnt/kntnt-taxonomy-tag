<?php


/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Taxonomy Tag
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Modifies the `post_tag` taxonomy whose terms describe different categories of content.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


namespace Kntnt\Tag;


defined( 'ABSPATH' ) && new Taxonomy;


class Taxonomy {

	public function __construct() {
		add_action( 'init', [ $this, 'run' ] );
	}

	public function run() {

		$slug = apply_filters( 'kntnt-taxonomy-tag-slug', 'post_tag' );
		$post_types = apply_filters( 'kntnt-taxonomy-tag-objects', [ 'post' ] );

		register_taxonomy( $slug, null, $this->taxonomy( $slug ) );

		foreach ( $post_types as $post_type ) {
			register_taxonomy_for_object_type( $slug, $post_type );
		}

		add_filter( 'term_updated_messages', [ $this, 'term_updated_messages' ] );

	}

	private function taxonomy() {
		return [

			// A short descriptive summary of what the taxonomy is for.
			'description' => _x( 'Tags is a taxonomy used as post metadata. Its terms describe a subject or theme. Its terms describe topics or themes of content. They are used for cross-referencing of content sharing the corresponding subject or theme.', 'Description', 'kntnt-taxonomy-tag' ),

			// Whether the taxonomy is hierarchical.
			'hierarchical' => false,

			// Whether a taxonomy is intended for use publicly either via
			// the admin interface or by front-end users.
			'public' => true,

			// Whether the taxonomy is publicly queryable.
			'publicly_queryable' => true,

			// Whether to generate and allow a UI for managing terms in this
			// taxonomy in the admin.
			'show_ui' => true,

			// Whether to show the taxonomy in the admin menu.
			'show_in_menu' => true,

			// Makes this taxonomy available for selection in navigation menus.
			'show_in_nav_menus' => true,

			// Whether to list the taxonomy in the Tag Cloud Widget controls.
			'show_tagcloud' => true,

			// Whether to show the taxonomy in the quick/bulk edit panel.
			'show_in_quick_edit' => true,

			// Whether to display a column for the taxonomy on its post
			// type listing screens.
			'show_admin_column' => true,

			// Metabox to show on edit. If a callable, it is called to render
			// the metabox. If `null` the default metabox is used. If `false`,
			// no metabox is shown.
			'meta_box_cb' => false,

			// Array of capabilities for this taxonomy.
			'capabilities' => [
				'manage_terms' => 'edit_posts',
				'edit_terms' => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			],

			// Sets the query var key for this taxonomy. Default $taxonomy key.
			// If false, a taxonomy cannot be loaded
			// at ?{query_var}={term_slug}. If a string,
			// the query ?{query_var}={term_slug} will be valid.
			'query_var' => true,

			// Triggers the handling of rewrites for this taxonomy.
			// Replace the array with false to prevent handling of rewrites.
			'rewrite' => [

				// Customize the permastruct slug.
				'slug' => 'tag',

				// Whether the permastruct should be prepended
				// with WP_Rewrite::$front.
				'with_front' => true,

				// Either hierarchical rewrite tag or not.
				'hierarchical' => false,

				// Endpoint mask to assign. If null and permalink_epmask
				// is set inherits from $permalink_epmask. If null and
				// permalink_epmask is not set, defaults to EP_PERMALINK.
				'ep_mask' => null,

			],

			// Default term to be used for the taxonomy.
			'default_term' => null,

			// An array of labels for this taxonomy.
			'labels' => [
				'name' => _x( 'Tags', 'Plural name', 'kntnt-taxonomy-tag' ),
				'singular_name' => _x( 'Tag', 'Singular name', 'kntnt-taxonomy-tag' ),
				'search_items' => _x( 'Search tags', 'Search items', 'kntnt-taxonomy-tag' ),
				'popular_items' => _x( 'Search tags', 'Search items', 'kntnt-taxonomy-tag' ),
				'all_items' => _x( 'All tags', 'All items', 'kntnt-taxonomy-tag' ),
				'parent_item' => _x( 'Parent tag', 'Parent item', 'kntnt-taxonomy-tag' ),
				'parent_item_colon' => _x( 'Parent tag colon', 'Parent item colon', 'kntnt-taxonomy-tag' ),
				'edit_item' => _x( 'Edit tag', 'Edit item', 'kntnt-taxonomy-tag' ),
				'view_item' => _x( 'View tag', 'View item', 'kntnt-taxonomy-tag' ),
				'update_item' => _x( 'Update tag', 'Update item', 'kntnt-taxonomy-tag' ),
				'add_new_item' => _x( 'Add new tag', 'Add new item', 'kntnt-taxonomy-tag' ),
				'new_item_name' => _x( 'New tag name', 'New item name', 'kntnt-taxonomy-tag' ),
				'separate_items_with_commas' => _x( 'Separate tags with commas', 'Separate items with commas', 'kntnt-taxonomy-tag' ),
				'add_or_remove_items' => _x( 'Add or remove tags', 'Add or remove items', 'kntnt-taxonomy-tag' ),
				'choose_from_most_used' => _x( 'Choose from most used', 'Choose from most used', 'kntnt-taxonomy-tag' ),
				'not_found' => _x( 'Not found', 'Not found', 'kntnt-taxonomy-tag' ),
				'no_terms' => _x( 'No terms', 'No terms', 'kntnt-taxonomy-tag' ),
				'items_list_navigation' => _x( 'Tags list navigation', 'Items list navigation', 'kntnt-taxonomy-tag' ),
				'items_list' => _x( 'Items list', 'Tags list', 'kntnt-taxonomy-tag' ),
				'most_used' => _x( 'Most used', 'Most used', 'kntnt-taxonomy-tag' ),
				'back_to_items' => _x( 'Back to tags', 'Back to items', 'kntnt-taxonomy-tag' ),
			],

		];
	}

	public function term_updated_messages( $messages ) {
		$messages['tag'] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Tag added.', 'kntnt-taxonomy-tag' ),
			2 => __( 'Tag deleted.', 'kntnt-taxonomy-tag' ),
			3 => __( 'Tag updated.', 'kntnt-taxonomy-tag' ),
			4 => __( 'Tag not added.', 'kntnt-taxonomy-tag' ),
			5 => __( 'Tag not updated.', 'kntnt-taxonomy-tag' ),
			6 => __( 'Tags deleted.', 'kntnt-taxonomy-tag' ),
		];
		return $messages;
	}

}