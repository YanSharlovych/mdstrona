<?php
/**
 * Plugin Name: ALUTECO Icon
 * Description: Visual ALUTECO SVG icon library with secure custom SVG uploads for the block editor.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: ALUTECO
 * License: GPL-2.0-or-later
 * Text Domain: aluteco-icon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Aluteco_Icon_Plugin {
	const VERSION      = '1.0.0';
	const MAX_SVG_SIZE = 102400;

	/**
	 * Return the bundled icon manifest.
	 *
	 * @return array<string, array{label: string, file: string}>
	 */
	private static function icon_manifest() {
		return array(
			'entry-door'           => array( 'label' => __( 'Entry door', 'aluteco-icon' ), 'file' => 'entry-door.svg' ),
			'lock-check'           => array( 'label' => __( 'Lock check', 'aluteco-icon' ), 'file' => 'lock-check.svg' ),
			'dimensions'           => array( 'label' => __( 'Large dimensions', 'aluteco-icon' ), 'file' => 'dimensions.svg' ),
			'pencil-check'         => array( 'label' => __( 'Custom design', 'aluteco-icon' ), 'file' => 'pencil-check.svg' ),
			'consultation'         => array( 'label' => __( 'Consultation', 'aluteco-icon' ), 'file' => 'consultation.svg' ),
			'design'               => array( 'label' => __( 'Design and engineering', 'aluteco-icon' ), 'file' => 'design.svg' ),
			'manufacturing'        => array( 'label' => __( 'Manufacturing', 'aluteco-icon' ), 'file' => 'manufacturing.svg' ),
			'installation-support' => array( 'label' => __( 'Installation support', 'aluteco-icon' ), 'file' => 'installation-support.svg' ),
			'transparency'         => array( 'label' => __( 'Variable transparency', 'aluteco-icon' ), 'file' => 'transparency.svg' ),
			'sound-insulation'     => array( 'label' => __( 'Sound insulation', 'aluteco-icon' ), 'file' => 'sound-insulation.svg' ),
			'flexible'             => array( 'label' => __( 'Flexible configurations', 'aluteco-icon' ), 'file' => 'flexible.svg' ),
			'slim-profiles'        => array( 'label' => __( 'Slim profiles', 'aluteco-icon' ), 'file' => 'slim-profiles.svg' ),
			'product-quality'      => array( 'label' => __( 'Product quality', 'aluteco-icon' ), 'file' => 'product-quality.svg' ),
			'responsibility'       => array( 'label' => __( 'Responsibility', 'aluteco-icon' ), 'file' => 'responsibility.svg' ),
			'innovation'           => array( 'label' => __( 'Innovation', 'aluteco-icon' ), 'file' => 'innovation.svg' ),
			'partnership'          => array( 'label' => __( 'Partnership', 'aluteco-icon' ), 'file' => 'partnership.svg' ),
		);
	}

	/**
	 * Sanitize SVG markup used by the dynamic block renderer.
	 *
	 * @param string $svg Raw SVG markup.
	 * @return string
	 */
	public static function sanitize_svg( $svg ) {
		if ( ! is_string( $svg ) || '' === trim( $svg ) || strlen( $svg ) > self::MAX_SVG_SIZE ) {
			return '';
		}

		$shape_attributes = array(
			'class'             => true,
			'd'                 => true,
			'cx'                => true,
			'cy'                => true,
			'r'                 => true,
			'rx'                => true,
			'ry'                => true,
			'x'                 => true,
			'y'                 => true,
			'x1'                => true,
			'y1'                => true,
			'x2'                => true,
			'y2'                => true,
			'width'             => true,
			'height'            => true,
			'points'            => true,
			'fill'              => true,
			'fill-rule'         => true,
			'clip-rule'         => true,
			'stroke'            => true,
			'stroke-width'      => true,
			'stroke-linecap'    => true,
			'stroke-linejoin'   => true,
			'stroke-miterlimit' => true,
			'transform'         => true,
			'opacity'           => true,
		);
		$allowed = array(
			'svg'      => array(
				'xmlns'              => true,
				'viewbox'            => true,
				'width'              => true,
				'height'             => true,
				'fill'               => true,
				'stroke'             => true,
				'stroke-width'       => true,
				'stroke-linecap'     => true,
				'stroke-linejoin'    => true,
				'stroke-miterlimit'  => true,
				'opacity'            => true,
				'class'              => true,
				'aria-hidden'        => true,
				'aria-label'         => true,
				'role'               => true,
				'focusable'          => true,
			),
			'g'        => $shape_attributes,
			'path'     => $shape_attributes,
			'polygon'  => $shape_attributes,
			'polyline' => $shape_attributes,
			'line'     => $shape_attributes,
			'rect'     => $shape_attributes,
			'circle'   => $shape_attributes,
			'ellipse'  => $shape_attributes,
		);
		$sanitized = wp_kses( $svg, $allowed );

		if ( ! preg_match( '/^\s*<svg\b[^>]*>.*<\/svg>\s*$/is', $sanitized ) ) {
			return '';
		}

		if ( ! preg_match( '/<(?:path|polygon|polyline|line|rect|circle|ellipse)\b/i', $sanitized ) ) {
			return '';
		}

		return $sanitized;
	}

	/**
	 * Read and sanitize a bundled icon.
	 *
	 * @param string $icon Icon slug.
	 * @return string
	 */
	private static function get_icon_svg( $icon ) {
		$manifest = self::icon_manifest();

		if ( ! isset( $manifest[ $icon ] ) ) {
			return '';
		}

		$path = plugin_dir_path( __FILE__ ) . 'assets/icons/' . $manifest[ $icon ]['file'];

		if ( ! is_readable( $path ) ) {
			return '';
		}

		return self::sanitize_svg( file_get_contents( $path ) );
	}

	/**
	 * Render the dynamic ALUTECO Icon block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		$icon = isset( $attributes['icon'] ) ? sanitize_key( $attributes['icon'] ) : 'consultation';
		$svg  = 'custom' === $icon && ! empty( $attributes['customSvg'] )
			? self::sanitize_svg( $attributes['customSvg'] )
			: self::get_icon_svg( $icon );

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
			array( 'class' => 'aluteco-icon aluteco-icon--' . $icon )
		);

		return sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $svg );
	}

	/**
	 * Register the block, editor assets and visual icon manifest.
	 */
	public static function register_block() {
		if ( WP_Block_Type_Registry::get_instance()->is_registered( 'aluteco/icon' ) ) {
			return;
		}

		$script_path = plugin_dir_path( __FILE__ ) . 'blocks/icon/editor.js';
		wp_register_script(
			'aluteco-icon-plugin-editor',
			plugins_url( 'blocks/icon/editor.js', __FILE__ ),
			array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
			file_exists( $script_path ) ? (string) filemtime( $script_path ) : self::VERSION,
			true
		);

		$icons = array();
		foreach ( self::icon_manifest() as $name => $properties ) {
			$icons[] = array(
				'name'  => $name,
				'label' => $properties['label'],
				'svg'   => self::get_icon_svg( $name ),
			);
		}

		wp_localize_script(
			'aluteco-icon-plugin-editor',
			'alutecoIconBlock',
			array( 'icons' => $icons )
		);

		register_block_type(
			plugin_dir_path( __FILE__ ) . 'blocks/icon',
			array( 'render_callback' => array( __CLASS__, 'render_block' ) )
		);
	}
}

add_action( 'init', array( 'Aluteco_Icon_Plugin', 'register_block' ), 5 );
