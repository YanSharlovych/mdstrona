<?php
/**
 * Register and render the editable ALUTECO icon block.
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the theme's icon library manifest.
 *
 * @return array<string, array{label: string, file: string}>
 */
function aluteco_custom_icon_manifest() {
	return array(
		'entry-door'           => array( 'label' => __( 'Entry door', 'aluteco' ), 'file' => 'entry-door.svg' ),
		'lock-check'           => array( 'label' => __( 'Lock check', 'aluteco' ), 'file' => 'lock-check.svg' ),
		'dimensions'           => array( 'label' => __( 'Large dimensions', 'aluteco' ), 'file' => 'dimensions.svg' ),
		'pencil-check'         => array( 'label' => __( 'Custom design', 'aluteco' ), 'file' => 'pencil-check.svg' ),
		'consultation'         => array( 'label' => __( 'Consultation', 'aluteco' ), 'file' => 'consultation.svg' ),
		'design'               => array( 'label' => __( 'Design and engineering', 'aluteco' ), 'file' => 'design.svg' ),
		'manufacturing'        => array( 'label' => __( 'Manufacturing', 'aluteco' ), 'file' => 'manufacturing.svg' ),
		'installation-support' => array( 'label' => __( 'Installation support', 'aluteco' ), 'file' => 'installation-support.svg' ),
		'transparency'         => array( 'label' => __( 'Variable transparency', 'aluteco' ), 'file' => 'transparency.svg' ),
		'sound-insulation'     => array( 'label' => __( 'Sound insulation', 'aluteco' ), 'file' => 'sound-insulation.svg' ),
		'flexible'             => array( 'label' => __( 'Flexible configurations', 'aluteco' ), 'file' => 'flexible.svg' ),
		'slim-profiles'        => array( 'label' => __( 'Slim profiles', 'aluteco' ), 'file' => 'slim-profiles.svg' ),
		'product-quality'      => array( 'label' => __( 'Product quality', 'aluteco' ), 'file' => 'product-quality.svg' ),
		'responsibility'       => array( 'label' => __( 'Responsibility', 'aluteco' ), 'file' => 'responsibility.svg' ),
		'innovation'           => array( 'label' => __( 'Innovation', 'aluteco' ), 'file' => 'innovation.svg' ),
		'partnership'          => array( 'label' => __( 'Partnership', 'aluteco' ), 'file' => 'partnership.svg' ),
	);
}

/**
 * Read a known theme icon and retain the safe SVG presentation attributes.
 *
 * @param string $icon Icon slug.
 * @return string
 */
function aluteco_get_custom_icon_svg( $icon ) {
	$manifest = aluteco_custom_icon_manifest();

	if ( ! isset( $manifest[ $icon ] ) ) {
		return '';
	}

	$path = get_theme_file_path( '/assets/icons/' . $manifest[ $icon ]['file'] );

	if ( ! is_readable( $path ) ) {
		return '';
	}

	$allowed = array(
		'svg'  => array(
			'xmlns'           => true,
			'viewbox'         => true,
			'width'           => true,
			'height'          => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'class'           => true,
			'aria-hidden'     => true,
			'aria-label'      => true,
			'role'            => true,
			'focusable'       => true,
		),
		'path' => array(
			'd'               => true,
			'fill'            => true,
			'fill-rule'       => true,
			'clip-rule'       => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'transform'       => true,
		),
	);

	return wp_kses( file_get_contents( $path ), $allowed );
}

/**
 * Render an ALUTECO icon block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function aluteco_render_custom_icon_block( $attributes ) {
	$icon = isset( $attributes['icon'] ) ? sanitize_key( $attributes['icon'] ) : 'consultation';
	$svg  = aluteco_get_custom_icon_svg( $icon );

	if ( '' === $svg ) {
		return '';
	}

	$label     = isset( $attributes['ariaLabel'] ) ? sanitize_text_field( $attributes['ariaLabel'] ) : '';
	$processor = new WP_HTML_Tag_Processor( $svg );

	if ( $processor->next_tag( 'svg' ) ) {
		$processor->set_attribute( 'focusable', 'false' );
		$processor->add_class( 'aluteco-icon-svg' );

		if ( '' !== $label ) {
			$processor->set_attribute( 'role', 'img' );
			$processor->set_attribute( 'aria-label', $label );
			$processor->remove_attribute( 'aria-hidden' );
		} else {
			$processor->set_attribute( 'aria-hidden', 'true' );
			$processor->remove_attribute( 'aria-label' );
			$processor->remove_attribute( 'role' );
		}

		$svg = $processor->get_updated_html();
	}

	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'wp-block-icon aluteco-icon aluteco-icon--' . $icon,
		)
	);

	return sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $svg );
}

/**
 * Register the custom icon block and expose the theme icon manifest to it.
 */
function aluteco_register_custom_icon_block() {
	$script_path = get_theme_file_path( '/blocks/icon/editor.js' );

	wp_register_script(
		'aluteco-icon-editor',
		get_theme_file_uri( '/blocks/icon/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : wp_get_theme()->get( 'Version' ),
		true
	);

	$icons = array();
	foreach ( aluteco_custom_icon_manifest() as $name => $properties ) {
		$icons[] = array(
			'name'  => $name,
			'label' => $properties['label'],
			'svg'   => aluteco_get_custom_icon_svg( $name ),
		);
	}

	wp_localize_script(
		'aluteco-icon-editor',
		'alutecoIconBlock',
		array( 'icons' => $icons )
	);

	register_block_type(
		get_theme_file_path( '/blocks/icon' ),
		array( 'render_callback' => 'aluteco_render_custom_icon_block' )
	);
}
add_action( 'init', 'aluteco_register_custom_icon_block' );
