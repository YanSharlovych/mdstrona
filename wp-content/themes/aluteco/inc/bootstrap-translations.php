<?php
/**
 * Idempotent TranslatePress configuration and Polish translation seed.
 *
 * Run after activating TranslatePress:
 * wp eval-file wp-content/themes/aluteco/inc/bootstrap-translations.php --allow-root
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

if ( ! class_exists( 'TRP_Translate_Press' ) ) {
	WP_CLI::error( 'TranslatePress must be active before seeding translations.' );
}

$settings                              = get_option( 'trp_settings', array() );
$settings['default-language']          = 'en_US';
$settings['translation-languages']     = array( 'en_US', 'pl_PL' );
$settings['publish-languages']         = array( 'en_US', 'pl_PL' );
$settings['native_or_english_name']    = 'native_name';
$settings['url-slugs']                 = array(
	'en_US' => 'en',
	'pl_PL' => 'pl',
);
$settings['add-subdirectory-to-default-language'] = 'no';
$settings['force-language-to-custom-links']       = 'yes';
$settings['trp-ls-floater']                       = 'no';
$settings['trp-ls-show-poweredby']                = 'no';
$settings['shortcode-options']                    = 'short-names';
update_option( 'trp_settings', $settings );

$switcher_settings = get_option( 'trp_language_switcher_settings', array() );

if ( ! is_array( $switcher_settings ) ) {
	$switcher_settings = array();
}

if ( ! isset( $switcher_settings['floater'] ) || ! is_array( $switcher_settings['floater'] ) ) {
	$switcher_settings['floater'] = array();
}

$switcher_settings['floater']['enabled'] = false;
update_option( 'trp_language_switcher_settings', $switcher_settings );

// Keep headings as one translatable phrase while CSS controls visual wrapping.
$content_migrations = array(
	'home-systems' => array(
		'Home<br>Systems' => 'Home Systems',
		'Entry<br>Doors' => 'Entry Doors',
		'Loft<br>Systems' => 'Loft Systems',
	),
	'marine-doors' => array(
		'Marine<br>Doors' => 'Marine Doors',
	),
	'contact' => array(
		'Contact<br>Us' => 'Contact<br> Us',
	),
);

foreach ( $content_migrations as $slug => $replacements ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );

	if ( ! $page instanceof WP_Post ) {
		continue;
	}

	$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $page->post_content );

	if ( $content !== $page->post_content ) {
		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_content' => wp_slash( $content ),
			)
		);
		WP_CLI::log( sprintf( 'Prepared translatable headings on: %s', $page->post_title ) );
	}
}

// WordPress may create its default Privacy Policy before the demo content runs.
$privacy_page = get_page_by_path( 'privacy-policy', OBJECT, 'page' );

if ( $privacy_page instanceof WP_Post && ! preg_match( '/<h1\b/i', $privacy_page->post_content ) ) {
	$privacy_header = '<!-- wp:group {"align":"full","className":"inner-page-header","layout":{"type":"default"}} -->' .
		'<div class="wp-block-group alignfull inner-page-header"><!-- wp:group {"className":"shell","layout":{"type":"default"}} -->' .
		'<div class="wp-block-group shell"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Privacy Policy</h1><!-- /wp:heading -->' .
		'<!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator -->' .
		'<!-- wp:paragraph --><p>This privacy notice explains how ALUTECO processes contact details submitted through this website and how you may exercise your data protection rights.</p><!-- /wp:paragraph -->' .
		'</div><!-- /wp:group --></div><!-- /wp:group -->';

	wp_update_post(
		array(
			'ID'           => $privacy_page->ID,
			'post_content' => wp_slash( $privacy_header . "\n\n" . $privacy_page->post_content ),
		)
	);
	WP_CLI::log( 'Added the missing Privacy Policy H1.' );
}

$dictionary = require get_theme_file_path( '/inc/translations-pl.php' );
$regular    = isset( $dictionary['regular'] ) && is_array( $dictionary['regular'] ) ? $dictionary['regular'] : array();
$gettext    = isset( $dictionary['gettext'] ) && is_array( $dictionary['gettext'] ) ? $dictionary['gettext'] : array();
$trp        = TRP_Translate_Press::get_trp_instance();
$query      = $trp->get_component( 'query' );

$query->check_original_table();
$query->check_original_meta_table();
$query->check_table( 'en_US', 'pl_PL' );

$originals = array_keys( $regular );
$existing  = $query->get_string_ids( $originals, 'pl_PL', OBJECT_K );
$missing   = array_values( array_diff( $originals, array_keys( $existing ) ) );

if ( $missing ) {
	$query->insert_strings( $missing, 'pl_PL' );
}

$string_ids = $query->get_string_ids( $originals, 'pl_PL', OBJECT_K );
$updates    = array();

foreach ( $regular as $original => $translated ) {
	if ( empty( $string_ids[ $original ]->id ) ) {
		continue;
	}

	$updates[] = array(
		'id'         => (int) $string_ids[ $original ]->id,
		'translated' => $translated,
		'status'     => 2,
	);
}

if ( $updates ) {
	$query->update_strings( $updates, 'pl_PL', array( 'id', 'translated', 'status' ) );
}

$gettext_tables = $query->get_query_component( 'gettext_table_creation' );
$gettext_writer = $query->get_query_component( 'gettext_insert_update' );
$gettext_tables->check_gettext_original_table();
$gettext_tables->check_gettext_original_meta_table();
$gettext_tables->check_gettext_table( 'pl_PL' );

$prepared_gettext = array();

foreach ( $gettext as $entry ) {
	$prepared_gettext[] = array(
		'original'        => $entry['original'],
		'translated'      => $entry['translated'],
		'domain'          => $entry['domain'],
		'context'         => isset( $entry['context'] ) ? $entry['context'] : null,
		'original_plural' => '',
		'plural_form'     => 0,
		'status'          => 2,
	);
}

$gettext_ids = $gettext_writer->gettext_original_strings_sync( $prepared_gettext );

global $wpdb;
$gettext_table = $wpdb->prefix . 'trp_gettext_pl_pl';

foreach ( $prepared_gettext as $index => $entry ) {
	if ( empty( $gettext_ids[ $index ] ) ) {
		continue;
	}

	$wpdb->query(
		$wpdb->prepare(
			"INSERT INTO `{$gettext_table}` (original, translated, domain, status, original_id, plural_form)
			VALUES (%s, %s, %s, 2, %d, 0)
			ON DUPLICATE KEY UPDATE original = VALUES(original), translated = VALUES(translated), domain = VALUES(domain), status = 2",
			$entry['original'],
			$entry['translated'],
			$entry['domain'],
			(int) $gettext_ids[ $index ]
		)
	);
}

update_option( 'aluteco_translation_seed_version', '1.0.0' );
delete_option( 'trp_db_errors' );
wp_cache_flush();
flush_rewrite_rules();

WP_CLI::success(
	sprintf(
		'Polish translations seeded: %1$d page strings and %2$d interface strings.',
		count( $updates ),
		count( $prepared_gettext )
	)
);
