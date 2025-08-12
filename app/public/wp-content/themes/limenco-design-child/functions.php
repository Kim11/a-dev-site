<?php
/**
 * Limenco Design Child Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package limenco-design-child
 */

add_action( 'wp_enqueue_scripts', 'limenco_design_child_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function limenco_design_child_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'siteorigin-north-style', get_template_directory_uri() . '/style.css', array(), '0.1.0' );
	wp_enqueue_style(
		'limenco-design-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'siteorigin-north-style' ),
		'0.1.0'
	);
}
// Enqueue parent style only 
add_action('wp_enqueue_scripts', function() { 
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css'); 
}); 
