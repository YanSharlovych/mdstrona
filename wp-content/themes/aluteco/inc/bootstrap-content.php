<?php
/**
 * Idempotent local demo-content bootstrap for WP-CLI.
 *
 * Run with:
 * wp eval-file wp-content/themes/aluteco/inc/bootstrap-content.php --allow-root
 *
 * @package Aluteco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

/**
 * Render a PHP block-pattern file into reusable post content.
 *
 * @param string $slug Pattern filename without extension.
 * @return string
 */
function aluteco_seed_render_pattern( $slug ) {
	$path = get_theme_file_path( '/patterns/' . sanitize_file_name( $slug ) . '.php' );

	if ( ! file_exists( $path ) ) {
		return '';
	}

	ob_start();
	include $path;

	return trim( (string) ob_get_clean() );
}

/**
 * Find the local administrator used as the author of seeded content.
 *
 * @return int
 */
function aluteco_seed_author_id() {
	$login = sanitize_user( (string) getenv( 'WP_ADMIN_USER' ) );
	$user  = $login ? get_user_by( 'login', $login ) : false;

	if ( ! $user instanceof WP_User ) {
		$administrators = get_users(
			array(
				'role'    => 'administrator',
				'number'  => 1,
				'orderby' => 'ID',
				'order'   => 'ASC',
			)
		);
		$user           = $administrators ? $administrators[0] : false;
	}

	return $user instanceof WP_User ? (int) $user->ID : 0;
}

/**
 * Insert a page only when its path does not already exist.
 *
 * Existing content is intentionally preserved so setup remains idempotent after
 * editors start working in Gutenberg.
 *
 * @param string $title   Page title.
 * @param string $slug    Page slug.
 * @param string $content Block markup.
 * @return int
 */
function aluteco_seed_page( $title, $slug, $content = '' ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );
	$author_id = aluteco_seed_author_id();

	if ( $existing instanceof WP_Post ) {
		$updates = array( 'ID' => $existing->ID );

		if ( 'publish' !== $existing->post_status ) {
			$updates['post_status'] = 'publish';
		}

		if ( 0 === (int) $existing->post_author && $author_id ) {
			$updates['post_author'] = $author_id;
		}

		if ( count( $updates ) > 1 ) {
			wp_update_post( $updates );
		}

		return (int) $existing->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => $author_id,
		),
		true
	);

	if ( is_wp_error( $page_id ) ) {
		WP_CLI::warning( sprintf( 'Could not create page "%s": %s', $title, $page_id->get_error_message() ) );
		return 0;
	}

	WP_CLI::log( sprintf( 'Created page: %s', $title ) );

	return (int) $page_id;
}

/**
 * Add a trusted theme image to the Media Library once.
 *
 * @param string $relative_path Path relative to the theme root.
 * @param string $title         Attachment title.
 * @return int
 */
function aluteco_seed_attachment( $relative_path, $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_key'       => '_aluteco_seed_source',
			'meta_value'     => $relative_path,
			'fields'         => 'ids',
		)
	);

	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = get_theme_file_path( '/' . ltrim( $relative_path, '/' ) );

	if ( ! file_exists( $source ) ) {
		WP_CLI::warning( sprintf( 'Seed image not found: %s', $relative_path ) );
		return 0;
	}

	$upload = wp_upload_dir();

	if ( ! empty( $upload['error'] ) ) {
		WP_CLI::warning( $upload['error'] );
		return 0;
	}

	$directory = trailingslashit( $upload['basedir'] ) . 'aluteco-seed';
	wp_mkdir_p( $directory );

	$filename    = wp_unique_filename( $directory, wp_basename( $source ) );
	$destination = trailingslashit( $directory ) . $filename;

	if ( ! copy( $source, $destination ) ) {
		WP_CLI::warning( sprintf( 'Could not copy seed image: %s', $relative_path ) );
		return 0;
	}

	$filetype = wp_check_filetype( $filename );
	$mime     = $filetype['type'];

	if ( 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		$mime = 'image/svg+xml';
	}

	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => trailingslashit( $upload['baseurl'] ) . 'aluteco-seed/' . $filename,
			'post_mime_type' => $mime ? $mime : 'application/octet-stream',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$destination,
		0,
		true
	);

	if ( is_wp_error( $attachment_id ) ) {
		WP_CLI::warning( sprintf( 'Could not import %s: %s', $relative_path, $attachment_id->get_error_message() ) );
		return 0;
	}

	update_post_meta( $attachment_id, '_aluteco_seed_source', $relative_path );

	if ( 'image/svg+xml' !== $mime ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$metadata = wp_generate_attachment_metadata( $attachment_id, $destination );

		if ( $metadata ) {
			wp_update_attachment_metadata( $attachment_id, $metadata );
		}
	} else {
		update_post_meta(
			$attachment_id,
			'_wp_attachment_metadata',
			array(
				'width'  => 640,
				'height' => 126,
				'file'   => 'aluteco-seed/' . $filename,
			)
		);
	}

	WP_CLI::log( sprintf( 'Imported media: %s', $title ) );

	return (int) $attachment_id;
}

/**
 * Get or create a taxonomy term.
 *
 * @param string $name     Term name.
 * @param string $taxonomy Taxonomy name.
 * @return int
 */
function aluteco_seed_term( $name, $taxonomy ) {
	$term = term_exists( $name, $taxonomy );

	if ( $term ) {
		return (int) ( is_array( $term ) ? $term['term_id'] : $term );
	}

	$created = wp_insert_term( $name, $taxonomy );

	if ( is_wp_error( $created ) ) {
		WP_CLI::warning( sprintf( 'Could not create term "%s": %s', $name, $created->get_error_message() ) );
		return 0;
	}

	return (int) $created['term_id'];
}

/**
 * Build polished editable article body blocks.
 *
 * @param string   $lead       Introductory paragraph.
 * @param string   $heading    Secondary heading.
 * @param string   $body       Main paragraph.
 * @param string[] $highlights Article highlights.
 * @return string
 */
function aluteco_seed_article_content( $lead, $heading, $body, $highlights ) {
	$list_items = '';

	foreach ( $highlights as $highlight ) {
		$list_items .= '<li>' . esc_html( $highlight ) . '</li>';
	}

	return sprintf(
		'<!-- wp:paragraph {"fontSize":"large"} --><p class="has-large-font-size">%1$s</p><!-- /wp:paragraph -->' .
		'<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">%2$s</h2><!-- /wp:heading -->' .
		'<!-- wp:paragraph --><p>%3$s</p><!-- /wp:paragraph -->' .
		'<!-- wp:list --><ul class="wp-block-list">%4$s</ul><!-- /wp:list -->' .
		'<!-- wp:paragraph --><p>To discuss the right ALUTECO configuration for your project, contact our engineering team.</p><!-- /wp:paragraph -->',
		esc_html( $lead ),
		esc_html( $heading ),
		esc_html( $body ),
		$list_items
	);
}

/**
 * Insert a demo post once and attach its taxonomy and featured image.
 *
 * @param array<string,mixed> $post Seed post definition.
 * @param array<string,int>   $categories Category map.
 */
function aluteco_seed_post( $post, $categories ) {
	$existing = get_page_by_path( $post['slug'], OBJECT, 'post' );
	$author_id = aluteco_seed_author_id();

	if ( $existing instanceof WP_Post ) {
		if ( 0 === (int) $existing->post_author && $author_id ) {
			wp_update_post(
				array(
					'ID'          => $existing->ID,
					'post_author' => $author_id,
				)
			);
		}

		if ( ! has_post_thumbnail( $existing->ID ) ) {
			$image_id = aluteco_seed_attachment( $post['image'], $post['title'] );

			if ( $image_id ) {
				set_post_thumbnail( $existing->ID, $image_id );
			}
		}

		return;
	}

	$post_id = wp_insert_post(
		array(
			'post_title'   => $post['title'],
			'post_name'    => $post['slug'],
			'post_content' => aluteco_seed_article_content(
				$post['lead'],
				$post['heading'],
				$post['body'],
				$post['highlights']
			),
			'post_excerpt' => $post['excerpt'],
			'post_status'  => 'publish',
			'post_type'    => 'post',
			'post_date'    => $post['date'],
			'post_author'  => $author_id,
			'post_category' => array( $categories[ $post['category'] ] ),
			'tags_input'   => $post['tags'],
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		WP_CLI::warning( sprintf( 'Could not create post "%s": %s', $post['title'], $post_id->get_error_message() ) );
		return;
	}

	$image_id = aluteco_seed_attachment( $post['image'], $post['title'] );

	if ( $image_id ) {
		set_post_thumbnail( $post_id, $image_id );
	}

	WP_CLI::log( sprintf( 'Created post: %s', $post['title'] ) );
}

$home_id = aluteco_seed_page( 'Home', 'home', aluteco_seed_render_pattern( 'front-page' ) );
$news_id = aluteco_seed_page( 'News', 'news' );
$marine_id = aluteco_seed_page( 'Marine Doors', 'marine-doors', aluteco_seed_render_pattern( 'marine-doors-page' ) );
$systems_id = aluteco_seed_page( 'Home Systems', 'home-systems', aluteco_seed_render_pattern( 'home-systems-page' ) );
$about_id = aluteco_seed_page( 'About Us', 'about-us', aluteco_seed_render_pattern( 'about-page' ) );
$contact_id = aluteco_seed_page( 'Contact', 'contact', aluteco_seed_render_pattern( 'contact-page' ) );

$legal_content = array(
	'privacy-policy' => array(
		'title' => 'Privacy Policy',
		'copy'  => 'This privacy notice explains how ALUTECO processes contact details submitted through this website and how you may exercise your data protection rights.',
	),
	'cookies-policy' => array(
		'title' => 'Cookies Policy',
		'copy'  => 'This website uses essential technical cookies required for reliable operation. Any optional analytics or marketing cookies should be documented here before they are enabled.',
	),
	'terms-conditions' => array(
		'title' => 'Terms & Conditions',
		'copy'  => 'Information on this website is provided for general product guidance. Final specifications, scope and commercial terms are confirmed individually for each project.',
	),
);

foreach ( $legal_content as $slug => $page ) {
	$content = sprintf(
		'<!-- wp:group {"align":"full","className":"inner-page-header","layout":{"type":"default"}} --><div class="wp-block-group alignfull inner-page-header"><!-- wp:group {"className":"shell","layout":{"type":"default"}} --><div class="wp-block-group shell"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">%1$s</h1><!-- /wp:heading --><!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator --><!-- wp:paragraph --><p>%2$s</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group --><!-- wp:group {"className":"legal-copy shell","layout":{"type":"constrained"}} --><div class="wp-block-group legal-copy"><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">About this document</h2><!-- /wp:heading --><!-- wp:paragraph --><p>This editable placeholder must be reviewed and replaced with legal text appropriate to the company, services and jurisdictions before production launch.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		esc_html( $page['title'] ),
		esc_html( $page['copy'] )
	);
	$page_id = aluteco_seed_page( $page['title'], $slug, $content );

	if ( 'privacy-policy' === $slug && $page_id ) {
		update_option( 'wp_page_for_privacy_policy', $page_id );
	}
}

if ( $home_id && $news_id ) {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $news_id );
}

update_option( 'blogname', 'ALUTECO' );
update_option( 'blogdescription', 'Marine Doors & Home Systems' );
update_option( 'timezone_string', 'Europe/Warsaw' );
update_option( 'posts_per_page', 6 );
update_option( 'default_comment_status', 'closed' );
update_option( 'default_ping_status', 'closed' );

$logo_id = aluteco_seed_attachment( 'assets/aluteco-logo-dark.svg', 'ALUTECO logo' );

if ( $logo_id ) {
	update_option( 'site_logo', $logo_id );
	set_theme_mod( 'custom_logo', $logo_id );
}

$category_names = array( 'Products', 'Technology', 'Events', 'Sustainability' );
$categories     = array();

foreach ( $category_names as $category_name ) {
	$categories[ $category_name ] = aluteco_seed_term( $category_name, 'category' );
}

$posts = array(
	array(
		'title'      => 'New Generation of Sliding Door Systems',
		'slug'       => 'new-generation-sliding-door-systems',
		'date'       => '2026-07-22 09:00:00',
		'category'   => 'Products',
		'tags'       => array( 'Marine doors', 'Sliding systems' ),
		'excerpt'    => 'A refined sliding platform engineered for greater performance, durability and ease of use.',
		'lead'       => 'The newest ALUTECO sliding platform combines compact profiles, dependable movement and broad configuration freedom.',
		'heading'    => 'Performance shaped around the vessel',
		'body'       => 'The system was developed for demanding marine projects where limited space, exposure and visual continuity all matter. Its modular architecture helps designers coordinate large glazed openings without compromising day-to-day operation.',
		'highlights' => array( 'Optimised profile geometry', 'Smooth and quiet movement', 'Flexible panel configurations' ),
		'image'      => 'assets/generated/news-product-detail.png',
	),
	array(
		'title'      => 'Stewart Platform for Simulating Heavy Marine Conditions',
		'slug'       => 'stewart-platform-marine-testing',
		'date'       => '2026-07-10 10:30:00',
		'category'   => 'Technology',
		'tags'       => array( 'Testing', 'Engineering' ),
		'excerpt'    => 'Expanded testing capability lets our engineers reproduce complex vessel motion before installation.',
		'lead'       => 'A new multi-axis Stewart platform strengthens the way ALUTECO validates moving systems for real marine conditions.',
		'heading'    => 'Evidence before installation',
		'body'       => 'Controlled motion testing gives the engineering team repeatable data about deflection, alignment and operating forces. Findings can be fed back into profile geometry, hardware selection and installation tolerances early in the project.',
		'highlights' => array( 'Repeatable multi-axis motion', 'Project-specific test programmes', 'Measured operating-force data' ),
		'image'      => 'assets/generated/news-technology-lab.png',
	),
	array(
		'title'      => 'ALUTECO at Polboat Yachting Festival 2026',
		'slug'       => 'aluteco-polboat-yachting-festival-2026',
		'date'       => '2026-06-26 08:00:00',
		'category'   => 'Events',
		'tags'       => array( 'Yachting', 'Polboat' ),
		'excerpt'    => "Meet our team and explore the latest ALUTECO marine systems at this year's festival.",
		'lead'       => 'ALUTECO will join shipyards, designers and marine suppliers at the 2026 Polboat Yachting Festival.',
		'heading'    => 'A meeting point for new projects',
		'body'       => 'The event is an opportunity to discuss new-build and refit challenges directly with our team. We will present current sliding, folding and custom engineering capabilities for premium vessels.',
		'highlights' => array( 'Product consultations', 'Current marine portfolio', 'Project engineering discussions' ),
		'image'      => 'assets/generated/news-marina-event.png',
	),
	array(
		'title'      => 'Thermal Performance in Modern Entry Systems',
		'slug'       => 'thermal-performance-modern-entry-systems',
		'date'       => '2026-06-14 11:00:00',
		'category'   => 'Technology',
		'tags'       => array( 'Home systems', 'Entry doors' ),
		'excerpt'    => 'How profile design, glazing and installation details work together in a premium entrance.',
		'lead'       => 'An entrance system should make an architectural statement without becoming a weak point in the building envelope.',
		'heading'    => 'A complete-system approach',
		'body'       => 'Thermal performance depends on more than one component. Profiles, thresholds, infill panels, perimeter interfaces and installation quality must be coordinated as one assembly.',
		'highlights' => array( 'Insulated aluminium profiles', 'Continuity at the threshold', 'Project-specific glass and panel selection' ),
		'image'      => 'assets/generated/card-home-systems.png',
	),
	array(
		'title'      => 'Responsible Aluminium: Designed for Circularity',
		'slug'       => 'responsible-aluminium-circularity',
		'date'       => '2026-05-29 09:15:00',
		'category'   => 'Sustainability',
		'tags'       => array( 'Materials', 'Sustainability' ),
		'excerpt'    => 'Durability, repairability and material recovery are considered from the earliest design decisions.',
		'lead'       => 'Long product life is the first step toward reducing the environmental impact of an aluminium system.',
		'heading'    => 'Designed for a longer horizon',
		'body'       => 'ALUTECO favours solutions that can be maintained, adjusted and repaired rather than prematurely replaced. Clear material separation also supports more effective recovery at the end of service life.',
		'highlights' => array( 'Durable replaceable hardware', 'Maintainable system architecture', 'Recoverable aluminium content' ),
		'image'      => 'assets/generated/about-precision-banner.png',
	),
	array(
		'title'      => 'Specifying Loft Systems for Quiet Interiors',
		'slug'       => 'specifying-loft-systems-quiet-interiors',
		'date'       => '2026-05-12 12:00:00',
		'category'   => 'Products',
		'tags'       => array( 'Loft systems', 'Architecture' ),
		'excerpt'    => 'A practical guide to balancing transparency, acoustic comfort and slim-profile design.',
		'lead'       => 'Internal glazing can preserve visual openness while giving work, living and hospitality spaces clearer acoustic boundaries.',
		'heading'    => 'Transparency with purpose',
		'body'       => 'The right specification considers glass build-up, perimeter seals, door interfaces and the acoustic character of adjacent finishes. Each detail contributes to the perceived comfort of the completed space.',
		'highlights' => array( 'Slim architectural sightlines', 'Flexible glass specifications', 'Coordinated seals and interfaces' ),
		'image'      => 'assets/generated/product-loft-system.png',
	),
	array(
		'title'      => 'From Concept to Sea Trial: A Custom Door Project',
		'slug'       => 'concept-to-sea-trial-custom-door',
		'date'       => '2026-04-25 10:00:00',
		'category'   => 'Products',
		'tags'       => array( 'Custom doors', 'Case study' ),
		'excerpt'    => 'A look at the engineering process behind a one-off curved marine opening.',
		'lead'       => "Custom marine systems begin with constraints: geometry, structure, weather exposure, movement and the owner's visual expectations.",
		'heading'    => 'One coordinated engineering path',
		'body'       => 'ALUTECO translates those constraints into a testable concept, develops the interface with the shipyard and supports installation through commissioning. Early coordination helps protect both appearance and operational reliability.',
		'highlights' => array( 'Project-specific kinematics', 'Shipyard interface coordination', 'Commissioning and sea-trial support' ),
		'image'      => 'assets/generated/marine-custom-doors.png',
	),
	array(
		'title'      => 'Five Details That Improve a Marine Door Specification',
		'slug'       => 'five-details-marine-door-specification',
		'date'       => '2026-04-08 08:45:00',
		'category'   => 'Technology',
		'tags'       => array( 'Specification', 'Marine doors' ),
		'excerpt'    => 'Small decisions around drainage, access and interfaces can make a major difference in service.',
		'lead'       => 'Reliable performance is often secured by details that receive little attention in the first visual concept.',
		'heading'    => 'Specify the interfaces, not only the opening',
		'body'       => 'Drainage routes, maintenance access, cable management, structural tolerances and finish transitions should be resolved together. Documenting them early reduces uncertainty during production and installation.',
		'highlights' => array( 'Accessible drainage paths', 'Defined structural tolerances', 'Service access for moving components' ),
		'image'      => 'assets/generated/marine-sliding-doors.png',
	),
);

foreach ( $posts as $post ) {
	aluteco_seed_post( $post, $categories );
}

$default_post = get_page_by_path( 'hello-world', OBJECT, 'post' );

if ( $default_post instanceof WP_Post && 'Hello world!' === $default_post->post_title ) {
	wp_delete_post( $default_post->ID, true );
}

$default_page = get_page_by_path( 'sample-page', OBJECT, 'page' );

if ( $default_page instanceof WP_Post && 'Sample Page' === $default_page->post_title ) {
	wp_delete_post( $default_page->ID, true );
}

update_option( 'aluteco_seed_version', '1.0.0' );
flush_rewrite_rules();

WP_CLI::success( 'ALUTECO content bootstrap is complete.' );
