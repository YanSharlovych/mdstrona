<?php
/**
 * ALUTECO theme functions.
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( '/inc/multilingual.php' );
require_once get_theme_file_path( '/inc/contact-form.php' );
require_once get_theme_file_path( '/inc/custom-icons.php' );

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

	if ( is_page( array( 'privacy-policy', 'cookies-policy', 'terms-conditions' ) ) ) {
		$classes[] = 'page-legal';
	}

	return array_unique( $classes );
}
add_filter( 'body_class', 'aluteco_body_classes' );

/**
 * Find news posts by either their English source copy or Polish translation.
 *
 * @param string $search Search phrase entered by the visitor.
 * @return int[]
 */
function aluteco_polish_news_search_ids( $search ) {
	$needle = strtolower( remove_accents( $search ) );

	if ( '' === $needle ) {
		return array();
	}

	$posts = get_posts(
		array(
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'suppress_filters' => true,
		)
	);
	$matches = array();

	foreach ( $posts as $post ) {
		$source = implode(
			' ',
			array(
				$post->post_title,
				$post->post_excerpt,
				wp_strip_all_tags( $post->post_content ),
			)
		);
		$translated = aluteco_translate_polish_fragment( $source );
		$haystack   = strtolower( remove_accents( $source . ' ' . $translated ) );

		if ( false !== strpos( $haystack, $needle ) ) {
			$matches[] = (int) $post->ID;
		}
	}

	return $matches;
}

/**
 * Apply in-place News filters to the main posts-page query.
 *
 * Custom query parameters keep filtered results on the News page instead of
 * switching WordPress to its category archive or global search template.
 *
 * @param WP_Query $query Current WordPress query.
 */
function aluteco_filter_news_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
		return;
	}

	if ( isset( $_GET['news_category'] ) ) {
		$category = sanitize_title( wp_unslash( $_GET['news_category'] ) );

		if ( $category && term_exists( $category, 'category' ) ) {
			$query->set( 'category_name', $category );
		}
	}

	if ( isset( $_GET['news_search'] ) ) {
		$search = sanitize_text_field( wp_unslash( $_GET['news_search'] ) );

		if ( '' !== $search ) {
			if ( aluteco_is_polish() ) {
				$matches = aluteco_polish_news_search_ids( $search );
				$query->set( 'post__in', $matches ? $matches : array( 0 ) );
			} else {
				$query->set( 's', $search );
			}
		}
	}

	if ( isset( $_GET['news_page'] ) ) {
		$query->set( 'paged', max( 1, absint( $_GET['news_page'] ) ) );
	}
}
add_action( 'pre_get_posts', 'aluteco_filter_news_query' );

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
