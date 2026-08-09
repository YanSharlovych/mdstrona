<?php
/**
 * Title: Product detail row
 * Slug: aluteco/product-row
 * Categories: aluteco-sections
 * Description: Editable product description, features and image.
 */

$image_url = esc_url( get_theme_file_uri( '/assets/photos/marine-doors-sliding.png' ) );
?>
<!-- wp:group {"className":"marine-row","layout":{"type":"default"}} -->
<div class="wp-block-group marine-row"><!-- wp:group {"className":"product-info","layout":{"type":"default"}} --><div class="wp-block-group product-info"><!-- wp:paragraph {"className":"number"} --><p class="number">01</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Product name</h2><!-- /wp:heading --><!-- wp:separator {"className":"blue-line small"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/><!-- /wp:separator --><!-- wp:paragraph --><p>Describe the product, its purpose and the value it brings to the project.</p><!-- /wp:paragraph --><!-- wp:group {"className":"feature-icons","layout":{"type":"grid","columnCount":2}} --><div class="wp-block-group feature-icons"><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/shuffle","align":"center"} /--><!-- wp:paragraph --><p>Tailored configuration</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/shield","align":"center"} /--><!-- wp:paragraph --><p>Premium materials</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/published","align":"center"} /--><!-- wp:paragraph --><p>Reliable performance</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/settings","align":"center"} /--><!-- wp:paragraph --><p>Installation support</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group --></div><!-- /wp:group --><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="<?php echo $image_url; ?>" alt="ALUTECO product application"/></figure><!-- /wp:image --></div>
<!-- /wp:group -->
