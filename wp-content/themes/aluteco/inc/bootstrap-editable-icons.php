<?php
/**
 * Convert legacy frontend-only icon placeholders into editable Icon blocks.
 *
 * This migration is intentionally conservative: it only replaces empty icon
 * containers and the legacy text-only feature items used by the ALUTECO page
 * patterns. Existing Icon and Image blocks are always preserved.
 *
 * Run with:
 * wp eval-file wp-content/themes/aluteco/inc/bootstrap-editable-icons.php --allow-root
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

if ( ! WP_Block_Type_Registry::get_instance()->is_registered( 'core/icon' ) ) {
	WP_CLI::warning( 'The editable icon migration requires WordPress 7.0 or newer.' );
	return;
}

/**
 * Return a block's additional CSS classes as individual tokens.
 *
 * @param array $block Parsed block.
 * @return string[]
 */
function aluteco_editable_icons_classes( $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	return array_values( array_filter( preg_split( '/\s+/', trim( $class_name ) ) ) );
}

/**
 * Create a parsed Gutenberg Icon block.
 *
 * @param string $icon Registered WordPress icon name.
 * @return array
 */
function aluteco_editable_icons_block( $icon ) {
	$markup = sprintf(
		'<!-- wp:icon %s /-->',
		wp_json_encode(
			array(
				'icon'  => $icon,
				'align' => 'center',
			)
		)
	);
	$blocks = parse_blocks( $markup );

	return $blocks[0];
}

/**
 * Determine whether a container already holds an editor-controlled icon.
 *
 * @param array $block Parsed block.
 * @return bool
 */
function aluteco_editable_icons_has_media( $block ) {
	if ( 'core/image' === $block['blockName'] ) {
		return true;
	}

	if ( 'core/icon' === $block['blockName'] && ! empty( $block['attrs']['icon'] ) ) {
		return true;
	}

	foreach ( $block['innerBlocks'] as $inner_block ) {
		if ( aluteco_editable_icons_has_media( $inner_block ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Check that an icon container contains no meaningful editor content.
 *
 * @param array $block Parsed container block.
 * @return bool
 */
function aluteco_editable_icons_is_replaceable( $block ) {
	foreach ( $block['innerBlocks'] as $inner_block ) {
		if ( 'core/icon' === $inner_block['blockName'] && empty( $inner_block['attrs']['icon'] ) ) {
			continue;
		}

		if ( null === $inner_block['blockName'] && '' === trim( (string) $inner_block['innerHTML'] ) ) {
			continue;
		}

		return false;
	}

	return true;
}

/**
 * Insert one block into an otherwise empty Group wrapper.
 *
 * @param array $container Parsed Group block.
 * @param array $child     Parsed child block.
 * @return array
 */
function aluteco_editable_icons_set_group_child( $container, $child ) {
	if ( ! preg_match( '/^\s*(<(?P<tag>[a-z][a-z0-9]*)\b[^>]*>).*?(<\/(?P=tag)>)\s*$/is', (string) $container['innerHTML'], $matches ) ) {
		return $container;
	}

	$container['innerBlocks']  = array( $child );
	$container['innerHTML']    = $matches[1] . $matches[3];
	$container['innerContent'] = array( $matches[1], null, $matches[3] );

	return $container;
}

/**
 * Match legacy semantic classes to editable WordPress icons.
 *
 * @param string[] $classes CSS class tokens.
 * @return string
 */
function aluteco_editable_icons_for_container( $classes ) {
	$icons = array(
		'shield'    => 'core/shield',
		'smart'     => 'core/mobile',
		'puzzle'    => 'core/shuffle',
		'leaf'      => 'core/star-empty',
		'bulb'      => 'core/tip',
		'handshake' => 'core/people',
		'chat'      => 'core/comment',
		'tools'     => 'core/pencil',
		'gear'      => 'core/settings',
		'wrench'    => 'core/settings',
	);

	foreach ( $icons as $class_name => $icon ) {
		if ( in_array( $class_name, $classes, true ) ) {
			return $icon;
		}
	}

	return 'core/check';
}

/**
 * Select an editable icon for a product feature label.
 *
 * @param string $text Feature text.
 * @return string
 */
function aluteco_editable_icons_for_feature( $text ) {
	$value = strtolower( remove_accents( wp_strip_all_tags( $text ) ) );

	if ( false !== strpos( $value, 'pivot' ) || false !== strpos( $value, 'hinged door' ) ) {
		return 'core/home';
	}
	if ( false !== strpos( $value, 'lock' ) ) {
		return 'core/key';
	}
	if ( false !== strpos( $value, 'large' ) || false !== strpos( $value, 'wide' ) ) {
		return 'core/chevron-up-down';
	}
	if ( false !== strpos( $value, 'custom' ) || false !== strpos( $value, 'finish' ) ) {
		return 'core/pencil';
	}
	if ( false !== strpos( $value, 'sound' ) ) {
		return 'core/audio';
	}
	if ( false !== strpos( $value, 'flexible' ) || false !== strpos( $value, 'configuration' ) ) {
		return 'core/shuffle';
	}
	if ( false !== strpos( $value, 'style' ) ) {
		return 'core/star-empty';
	}
	if ( false !== strpos( $value, 'construction' ) || false !== strpos( $value, 'durability' ) || false !== strpos( $value, 'quality' ) ) {
		return 'core/shield';
	}
	if ( false !== strpos( $value, 'hinge' ) ) {
		return 'core/settings';
	}
	if ( false !== strpos( $value, 'pop-up' ) || false !== strpos( $value, 'slide' ) || false !== strpos( $value, 'smooth' ) ) {
		return 'core/chevron-up-down';
	}
	if ( false !== strpos( $value, 'thickness' ) ) {
		return 'core/block-table';
	}
	if ( false !== strpos( $value, 'iso' ) || false !== strpos( $value, 'norm' ) ) {
		return 'core/published';
	}
	if ( false !== strpos( $value, 'marine' ) ) {
		return 'core/rss';
	}
	if ( false !== strpos( $value, 'profile' ) || false !== strpos( $value, 'glazing' ) || false !== strpos( $value, 'glass' ) ) {
		return 'core/table';
	}

	return 'core/check';
}

/**
 * Wrap a legacy feature paragraph with its own editable Icon block.
 *
 * @param array $paragraph Parsed Paragraph block.
 * @return array
 */
function aluteco_editable_icons_feature_item( $paragraph ) {
	$icon   = aluteco_editable_icons_for_feature( serialize_block( $paragraph ) );
	$markup = '<!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} -->' .
		'<div class="wp-block-group feature-icon-item">' .
		serialize_block( aluteco_editable_icons_block( $icon ) ) .
		serialize_block( $paragraph ) .
		'</div><!-- /wp:group -->';
	$blocks = parse_blocks( $markup );

	return $blocks[0];
}

/**
 * Convert a legacy contact-label paragraph to an editable icon container.
 *
 * @param array $paragraph Parsed Paragraph block.
 * @return array
 */
function aluteco_editable_icons_contact_item( $paragraph ) {
	$text = strtolower( wp_strip_all_tags( serialize_block( $paragraph ) ) );
	$icon = false !== strpos( $text, 'phone' ) ? 'core/mobile' : ( false !== strpos( $text, 'pin' ) || false !== strpos( $text, 'location' ) ? 'core/map-marker' : 'core/envelope' );
	$markup = '<!-- wp:group {"className":"round-icon","layout":{"type":"constrained"}} -->' .
		'<div class="wp-block-group round-icon">' . serialize_block( aluteco_editable_icons_block( $icon ) ) . '</div>' .
		'<!-- /wp:group -->';
	$blocks = parse_blocks( $markup );

	return $blocks[0];
}

/**
 * Recursively migrate supported icon placeholders.
 *
 * @param array[] $blocks Parsed blocks.
 * @return array[]
 */
function aluteco_editable_icons_migrate_blocks( $blocks ) {
	foreach ( $blocks as $index => $block ) {
		$classes = aluteco_editable_icons_classes( $block );

		if ( 'core/icon' === $block['blockName'] && 'core/arrow-up-down' === ( $block['attrs']['icon'] ?? '' ) ) {
			$block['attrs']['icon'] = 'core/chevron-up-down';
			$blocks[ $index ]       = $block;
			continue;
		}

		if ( 'core/paragraph' === $block['blockName'] && in_array( 'round-icon', $classes, true ) ) {
			$blocks[ $index ] = aluteco_editable_icons_contact_item( $block );
			continue;
		}

		if ( ! empty( $block['innerBlocks'] ) ) {
			$block['innerBlocks'] = aluteco_editable_icons_migrate_blocks( $block['innerBlocks'] );
		}

		$is_icon_container = in_array( 'icon', $classes, true ) || in_array( 'work-icon', $classes, true );

		if ( $is_icon_container && ! aluteco_editable_icons_has_media( $block ) && aluteco_editable_icons_is_replaceable( $block ) ) {
			$block = aluteco_editable_icons_set_group_child(
				$block,
				aluteco_editable_icons_block( aluteco_editable_icons_for_container( $classes ) )
			);
		}

		if ( in_array( 'feature-icons', $classes, true ) ) {
			foreach ( $block['innerBlocks'] as $child_index => $child ) {
				if ( 'core/paragraph' === $child['blockName'] ) {
					$block['innerBlocks'][ $child_index ] = aluteco_editable_icons_feature_item( $child );
				}
			}
		}

		$blocks[ $index ] = $block;
	}

	return $blocks;
}

$pages = get_posts(
	array(
		'post_type'        => 'page',
		'post_status'      => array( 'publish', 'draft', 'private', 'pending' ),
		'posts_per_page'   => -1,
		'suppress_filters' => true,
	)
);
$updated_pages = 0;

foreach ( $pages as $page ) {
	$original = (string) $page->post_content;
	$updated  = serialize_blocks( aluteco_editable_icons_migrate_blocks( parse_blocks( $original ) ) );

	if ( $updated === $original ) {
		continue;
	}

	$result = wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_content' => wp_slash( $updated ),
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		WP_CLI::warning( sprintf( 'Could not migrate icons on "%s": %s', $page->post_title, $result->get_error_message() ) );
		continue;
	}

	++$updated_pages;
	WP_CLI::log( sprintf( 'Added editable icons to: %s', $page->post_title ) );
}

update_option( 'aluteco_editable_icons_version', '1.0.0' );
WP_CLI::success( sprintf( 'Editable icon migration complete. Updated %d page(s).', $updated_pages ) );
