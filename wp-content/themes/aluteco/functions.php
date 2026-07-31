<?php
/**
 * ALUTECO theme functions.
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( '/inc/contact-form.php' );

/**
 * Configure theme supports and block editor behavior.
 */
function aluteco_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'style.css' );

	register_block_pattern_category(
		'aluteco-pages',
		array(
			'label' => __( 'ALUTECO pages', 'aluteco' ),
		)
	);

	register_block_pattern_category(
		'aluteco-sections',
		array(
			'label' => __( 'ALUTECO sections', 'aluteco' ),
		)
	);
}
add_action( 'after_setup_theme', 'aluteco_setup' );

/**
 * Enqueue frontend assets.
 */
function aluteco_enqueue_assets() {
	$stylesheet_path = get_theme_file_path( '/style.css' );
	$script_path     = get_theme_file_path( '/assets/js/site.js' );

	wp_enqueue_style(
		'aluteco-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'aluteco-style',
		get_stylesheet_uri(),
		array( 'aluteco-fonts' ),
		file_exists( $stylesheet_path ) ? (string) filemtime( $stylesheet_path ) : wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'aluteco-site',
		get_theme_file_uri( '/assets/js/site.js' ),
		array(),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : wp_get_theme()->get( 'Version' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aluteco_enqueue_assets' );

/**
 * Load the web fonts in the block editor.
 */
function aluteco_enqueue_editor_assets() {
	wp_enqueue_style(
		'aluteco-editor-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap',
		array(),
		null
	);
}
add_action( 'enqueue_block_editor_assets', 'aluteco_enqueue_editor_assets' );

/**
 * Add page-specific classes used by the original visual system.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function aluteco_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'page-home';
	}

	if ( is_home() ) {
		$classes[] = 'page-news';
	}

	if ( is_page( array( 'home-systems', 'marine-doors' ) ) ) {
		$classes[] = 'page-product';
	}

	return array_unique( $classes );
}
add_filter( 'body_class', 'aluteco_body_classes' );

/**
 * Provide a visual fallback when a post does not have a featured image.
 *
 * @param string $content Rendered featured-image block.
 * @return string
 */
function aluteco_featured_image_fallback( $content ) {
	if ( '' !== trim( $content ) || is_admin() ) {
		return $content;
	}

	return '<figure class="wp-block-post-featured-image aluteco-featured-fallback" aria-label="' .
		esc_attr__( 'ALUTECO article image placeholder', 'aluteco' ) .
		'"></figure>';
}
add_filter( 'render_block_core/post-featured-image', 'aluteco_featured_image_fallback' );

/**
 * Render an automatically updated copyright notice.
 *
 * @return string
 */
function aluteco_copyright_shortcode() {
	return sprintf(
		'&copy; %1$s %2$s',
		esc_html( wp_date( 'Y' ) ),
		esc_html__( 'ALUTECO. All rights reserved.', 'aluteco' )
	);
}
add_shortcode( 'aluteco_copyright', 'aluteco_copyright_shortcode' );
