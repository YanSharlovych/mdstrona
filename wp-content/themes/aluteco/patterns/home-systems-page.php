<?php
/**
 * Title: Complete Home Systems page
 * Slug: aluteco/home-systems-page
 * Categories: aluteco-pages
 * Description: Editable Home Systems product page.
 */

$hero_url  = esc_url( get_theme_file_uri( '/assets/generated/hero-home-systems.png' ) );
$entry_url = esc_url( get_theme_file_uri( '/assets/photos/home-systems-entry-doors.png' ) );
$loft_url  = esc_url( get_theme_file_uri( '/assets/photos/home-systems-loft-systems.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $hero_url; ?>","dimRatio":0,"minHeight":80,"minHeightUnit":"vh","align":"full","className":"hero hero-product hero-home-systems"} -->
<div class="wp-block-cover alignfull hero hero-product hero-home-systems" style="min-height:80vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Contemporary home with premium aluminium systems" src="<?php echo $hero_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"hero-copy reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group hero-copy reveal"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Home Systems</h1><!-- /wp:heading --><!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator --><!-- wp:paragraph --><p>Aluminium solutions<br>for modern architecture</p><!-- /wp:paragraph --></div>
	<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->

<!-- wp:group {"className":"product-tabs shell","layout":{"type":"grid","columnCount":2}} -->
<div class="wp-block-group product-tabs shell"><!-- wp:paragraph --><p><a href="#entry">Entry Doors</a></p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#loft">Loft Systems</a></p><!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"entry","className":"product-section split-right","layout":{"type":"default"}} -->
<div id="entry" class="wp-block-group product-section split-right">
	<!-- wp:group {"className":"product-info reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group product-info reveal"><!-- wp:paragraph {"className":"number"} --><p class="number">01</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Entry Doors</h2><!-- /wp:heading --><!-- wp:separator {"className":"blue-line small"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/><!-- /wp:separator --><!-- wp:paragraph --><p>Elegant and secure entrance solutions that combine modern design with outstanding performance.</p><!-- /wp:paragraph -->
		<!-- wp:group {"className":"feature-icons","layout":{"type":"grid","columnCount":2}} -->
		<div class="wp-block-group feature-icons"><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/home","align":"center"} /--><!-- wp:paragraph --><p>Pivot or hinged door mechanism</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/key","align":"center"} /--><!-- wp:paragraph --><p>Multipoint locking system</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/chevron-up-down","align":"center"} /--><!-- wp:paragraph --><p>Extremely large width and height</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/pencil","align":"center"} /--><!-- wp:paragraph --><p>Customizable design and finishes</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
	<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"entry-photo"} -->
	<figure class="wp-block-image size-full entry-photo"><img src="<?php echo $entry_url; ?>" alt="Open timber entry door in a modern glazed facade"/></figure>
	<!-- /wp:image -->
</div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"loft","className":"product-section split-left","layout":{"type":"default"}} -->
<div id="loft" class="wp-block-group product-section split-left">
	<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"loft-photo"} -->
	<figure class="wp-block-image size-full loft-photo"><img src="<?php echo $loft_url; ?>" alt="Minimal black-framed loft glazing in a bright living room"/></figure>
	<!-- /wp:image -->
	<!-- wp:group {"className":"product-info reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group product-info reveal"><!-- wp:paragraph {"className":"number"} --><p class="number">02</p><!-- /wp:paragraph --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Loft Systems</h2><!-- /wp:heading --><!-- wp:separator {"className":"blue-line small"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/><!-- /wp:separator --><!-- wp:paragraph --><p>Sleek and versatile glass wall systems that define spaces while maintaining openness and natural light.</p><!-- /wp:paragraph -->
		<!-- wp:group {"className":"feature-icons","layout":{"type":"grid","columnCount":2}} -->
		<div class="wp-block-group feature-icons"><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/table","align":"center"} /--><!-- wp:paragraph --><p>Slim profiles for minimalist design</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/audio","align":"center"} /--><!-- wp:paragraph --><p>Excellent sound insulation</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/shuffle","align":"center"} /--><!-- wp:paragraph --><p>Flexible configurations and finishes</p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:group {"className":"feature-icon-item","layout":{"type":"default"}} --><div class="wp-block-group feature-icon-item"><!-- wp:icon {"icon":"core/table","align":"center"} /--><!-- wp:paragraph --><p>Glass with variable transparency</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
