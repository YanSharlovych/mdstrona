<?php
/**
 * Title: Partner and client audience
 * Slug: aluteco/audience
 * Categories: aluteco-sections
 * Description: Editable audience tiles for ALUTECO partners and clients.
 */

$images = array(
	'audience-shipyards.png',
	'audience-yacht-builders.png',
	'audience-designers.png',
	'audience-distributors.png',
	'audience-developers.png',
	'audience-private-clients.png',
);

$labels = array( 'Shipyards', 'Yacht builders', 'Designers', 'Distributors', 'Developers', 'Private clients' );
?>
<!-- wp:group {"className":"audience shell","layout":{"type":"default"}} -->
<div class="wp-block-group audience shell"><!-- wp:heading {"level":2,"className":"section-title"} --><h2 class="wp-block-heading section-title">Who we work with</h2><!-- /wp:heading --><!-- wp:group {"className":"audience-grid","layout":{"type":"grid","columnCount":6}} --><div class="wp-block-group audience-grid">
<?php foreach ( $images as $index => $image ) : ?>
	<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( '/assets/generated/' . $image ) ); ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="<?php echo esc_attr( $labels[ $index ] ); ?>" src="<?php echo esc_url( get_theme_file_uri( '/assets/generated/' . $image ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p><?php echo esc_html( $labels[ $index ] ); ?></p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
<?php endforeach; ?>
</div><!-- /wp:group --></div>
<!-- /wp:group -->
