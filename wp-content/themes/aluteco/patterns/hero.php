<?php
/**
 * Title: ALUTECO hero
 * Slug: aluteco/hero
 * Categories: aluteco-sections
 * Description: Large premium hero with editable image, title and copy.
 */

$image_url = esc_url( get_theme_file_uri( '/assets/generated/home-hero-profile.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $image_url; ?>","dimRatio":45,"overlayColor":"navy","minHeight":80,"minHeightUnit":"vh","align":"full","className":"hero hero-dark"} -->
<div class="wp-block-cover alignfull hero hero-dark" style="min-height:80vh"><span aria-hidden="true" class="wp-block-cover__background has-navy-background-color has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="ALUTECO aluminium system" src="<?php echo $image_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"className":"hero-copy","layout":{"type":"default"}} --><div class="wp-block-group hero-copy"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Engineering beyond the frame</h1><!-- /wp:heading --><!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator --><!-- wp:paragraph --><p>Premium aluminium solutions shaped around ambitious architecture and marine design.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/contact/">Start a project</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div>
<!-- /wp:cover -->
