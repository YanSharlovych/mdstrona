<?php
/**
 * TranslatePress integration for the bilingual ALUTECO website.
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the language selected by TranslatePress.
 *
 * @return string WordPress locale such as en_US or pl_PL.
 */
function aluteco_current_language() {
	global $TRP_LANGUAGE;

	if ( is_string( $TRP_LANGUAGE ) && '' !== $TRP_LANGUAGE ) {
		return $TRP_LANGUAGE;
	}

	$settings = get_option( 'trp_settings', array() );

	return isset( $settings['default-language'] )
		? sanitize_text_field( $settings['default-language'] )
		: 'en_US';
}

/**
 * Determine whether the current frontend request is Polish.
 *
 * @return bool
 */
function aluteco_is_polish() {
	return 0 === strpos( strtolower( aluteco_current_language() ), 'pl' );
}

/**
 * Load the manually reviewed Polish source dictionary.
 *
 * @return array<string,mixed>
 */
function aluteco_polish_dictionary() {
	static $dictionary = null;

	if ( null === $dictionary ) {
		$path       = get_theme_file_path( '/inc/translations-pl.php' );
		$dictionary = file_exists( $path ) ? require $path : array();
	}

	return is_array( $dictionary ) ? $dictionary : array();
}

/**
 * Look up a reviewed Polish translation used outside TranslatePress' free scope.
 *
 * @param string $source English source string.
 * @return string
 */
function aluteco_polish_translation( $source ) {
	$dictionary   = aluteco_polish_dictionary();
	$translations = isset( $dictionary['regular'] ) && is_array( $dictionary['regular'] )
		? $dictionary['regular']
		: array();

	return isset( $translations[ $source ] ) ? $translations[ $source ] : $source;
}

/**
 * Translate known phrases inside generated block fragments such as excerpts.
 *
 * @param string $content Rendered block markup.
 * @return string
 */
function aluteco_translate_polish_fragment( $content ) {
	if ( ! aluteco_is_polish() || '' === $content ) {
		return $content;
	}

	static $translations = null;

	if ( null === $translations ) {
		$dictionary   = aluteco_polish_dictionary();
		$translations = isset( $dictionary['regular'] ) && is_array( $dictionary['regular'] )
			? $dictionary['regular']
			: array();

		uksort(
			$translations,
			static function ( $left, $right ) {
				return strlen( $right ) <=> strlen( $left );
			}
		);
	}

	foreach ( $translations as $source => $translated ) {
		$content = str_replace( $source, $translated, $content );
	}

	return $content;
}

/**
 * Render an accessible two-language switcher using TranslatePress URLs.
 *
 * @return string
 */
function aluteco_language_switcher_markup() {
	if ( ! function_exists( 'trp_custom_language_switcher' ) ) {
		return '';
	}

	$languages = trp_custom_language_switcher();
	$current   = aluteco_current_language();
	$labels    = array(
		'en_US' => 'ENG',
		'pl_PL' => 'PL',
	);
	$names     = array(
		'en_US' => 'View site in English',
		'pl_PL' => 'Wyświetl witrynę po polsku',
	);
	$links     = array();

	foreach ( $labels as $code => $label ) {
		if ( empty( $languages[ $code ]['current_page_url'] ) ) {
			continue;
		}

		$is_current = $current === $code;
		$links[]    = sprintf(
			'<a class="language-switcher__link%1$s" href="%2$s" lang="%3$s" hreflang="%3$s" aria-label="%4$s"%5$s>%6$s</a>',
			$is_current ? ' is-current' : '',
			esc_url( $languages[ $code ]['current_page_url'] ),
			esc_attr( strtolower( substr( $code, 0, 2 ) ) ),
			esc_attr( $names[ $code ] ),
			$is_current ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}

	if ( count( $links ) < 2 ) {
		return '';
	}

	return '<li class="wp-block-navigation-item language language-switcher trp-language-switcher-container" data-no-translation>' .
		implode( '<span class="language-switcher__separator" aria-hidden="true">/</span>', $links ) .
		'</li>';
}

/**
 * Replace the editable navigation placeholder with the live switcher.
 *
 * @param string $block_content Rendered navigation-link markup.
 * @param array  $block         Parsed block data.
 * @return string
 */
function aluteco_render_language_switcher( $block_content, $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
	$label      = isset( $block['attrs']['label'] ) ? html_entity_decode( (string) $block['attrs']['label'] ) : '';

	if ( ! in_array( 'language', preg_split( '/\s+/', trim( $class_name ) ), true ) && 'ENG / PL' !== $label && 'PL / ENG' !== $label ) {
		return $block_content;
	}

	$switcher = aluteco_language_switcher_markup();

	return '' !== $switcher ? $switcher : $block_content;
}
add_filter( 'render_block_core/navigation-link', 'aluteco_render_language_switcher', 10, 2 );

/**
 * Translate browser titles in the free TranslatePress configuration.
 *
 * @param array<string,string> $parts Document title parts.
 * @return array<string,string>
 */
function aluteco_translate_polish_document_title( $parts ) {
	if ( ! aluteco_is_polish() || empty( $parts['title'] ) ) {
		return $parts;
	}

	if ( is_search() ) {
		$parts['title'] = sprintf(
			/* translators: %s is the search phrase. */
			__( 'Wyniki wyszukiwania dla: %s', 'aluteco' ),
			get_search_query()
		);
	}

	foreach ( $parts as $key => $part ) {
		if ( is_string( $part ) ) {
			$parts[ $key ] = aluteco_polish_translation( html_entity_decode( $part, ENT_QUOTES, 'UTF-8' ) );
		}
	}

	return $parts;
}
add_filter( 'document_title_parts', 'aluteco_translate_polish_document_title', 20 );

/**
 * Translate image alternative text, which is outside TranslatePress Free SEO scope.
 *
 * @param string $block_content Rendered image block.
 * @return string
 */
function aluteco_translate_polish_image_alt( $block_content ) {
	if ( ! aluteco_is_polish() || false === strpos( $block_content, ' alt=' ) ) {
		return $block_content;
	}

	return (string) preg_replace_callback(
		'/\salt=("|\')(.*?)\1/i',
		static function ( $matches ) {
			$source      = html_entity_decode( $matches[2], ENT_QUOTES, 'UTF-8' );
			$translation = aluteco_polish_translation( $source );

			return ' alt=' . $matches[1] . esc_attr( $translation ) . $matches[1];
		},
		$block_content
	);
}
add_filter( 'render_block_core/image', 'aluteco_translate_polish_image_alt' );
add_filter( 'render_block_core/cover', 'aluteco_translate_polish_image_alt' );
add_filter( 'render_block_core/post-featured-image', 'aluteco_translate_polish_image_alt' );
add_filter( 'render_block_core/post-excerpt', 'aluteco_translate_polish_fragment' );
add_filter( 'render_block_core/read-more', 'aluteco_translate_polish_fragment' );
add_filter( 'render_block_core/query-title', 'aluteco_translate_polish_fragment' );
