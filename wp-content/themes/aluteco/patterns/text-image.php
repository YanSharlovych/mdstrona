<?php
/**
 * Title: Text and image section
 * Slug: aluteco/text-image
 * Categories: aluteco-sections
 * Description: Premium two-column editorial section.
 */

$image_url = esc_url( get_theme_file_uri( '/assets/generated/about-precision-banner.png' ) );
?>
<!-- wp:group {"className":"text-image-section shell","layout":{"type":"default"}} -->
<div class="wp-block-group text-image-section shell"><!-- wp:group {"className":"text-image-copy","layout":{"type":"default"}} --><div class="wp-block-group text-image-copy"><!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Designed with purpose</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Precision in every detail</h2><!-- /wp:heading --><!-- wp:separator {"className":"blue-line small"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/><!-- /wp:separator --><!-- wp:paragraph --><p>From initial specification to final installation, every ALUTECO solution balances refined aesthetics with dependable engineering.</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="<?php echo $image_url; ?>" alt="ALUTECO precision engineering"/></figure><!-- /wp:image --></div>
<!-- /wp:group -->
