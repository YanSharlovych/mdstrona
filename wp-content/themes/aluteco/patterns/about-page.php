<?php
/**
 * Title: Complete About Us page
 * Slug: aluteco/about-page
 * Categories: aluteco-pages
 * Description: Complete editable ALUTECO company page.
 */

$hero_url      = esc_url( get_theme_file_uri( '/assets/generated/hero-about-us.png' ) );
$shipyards_url = esc_url( get_theme_file_uri( '/assets/generated/audience-shipyards.png' ) );
$builders_url  = esc_url( get_theme_file_uri( '/assets/generated/audience-yacht-builders.png' ) );
$designers_url = esc_url( get_theme_file_uri( '/assets/generated/audience-designers.png' ) );
$partners_url  = esc_url( get_theme_file_uri( '/assets/generated/audience-distributors.png' ) );
$developers_url = esc_url( get_theme_file_uri( '/assets/generated/audience-developers.png' ) );
$clients_url   = esc_url( get_theme_file_uri( '/assets/generated/audience-private-clients.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $hero_url; ?>","dimRatio":0,"minHeight":80,"minHeightUnit":"vh","align":"full","className":"hero hero-about"} -->
<div class="wp-block-cover alignfull hero hero-about" style="min-height:80vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background" alt="ALUTECO engineers reviewing a technical design" src="<?php echo $hero_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"hero-copy reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group hero-copy reveal">
		<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">About Us</h1><!-- /wp:heading -->
		<!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator -->
		<!-- wp:paragraph --><p><strong>Engineering solutions that combine innovation, precision and timeless design.</strong></p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"className":"body-copy"} --><p class="body-copy">We design and deliver premium door and glazing systems for marine and architectural projects. Combining engineering expertise with refined aesthetics, we create solutions that perform flawlessly while enhancing the visual character of every space.</p><!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->

<!-- wp:group {"className":"values shell","layout":{"type":"default"}} -->
<div class="wp-block-group values shell">
	<!-- wp:heading {"level":2,"className":"section-title reveal"} --><h2 class="wp-block-heading section-title reveal">Our values</h2><!-- /wp:heading -->
	<!-- wp:group {"className":"value-grid","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-group value-grid">
		<!-- wp:group {"className":"value-card reveal","layout":{"type":"default"}} --><div class="wp-block-group value-card reveal"><!-- wp:group {"className":"icon shield","layout":{"type":"constrained"}} --><div class="wp-block-group icon shield"></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Product quality</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Premium materials and precision manufacturing ensure long-lasting performance.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"value-card reveal","layout":{"type":"default"}} --><div class="wp-block-group value-card reveal"><!-- wp:group {"className":"icon bulb","layout":{"type":"constrained"}} --><div class="wp-block-group icon bulb"></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Innovation</h3><!-- /wp:heading --><!-- wp:paragraph --><p>We continuously improve our solutions with modern technologies.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"value-card reveal","layout":{"type":"default"}} --><div class="wp-block-group value-card reveal"><!-- wp:group {"className":"icon handshake","layout":{"type":"constrained"}} --><div class="wp-block-group icon handshake"></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Partnership</h3><!-- /wp:heading --><!-- wp:paragraph --><p>We build long-term relationships based on trust, transparency and reliability.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
		<!-- wp:group {"className":"value-card reveal","layout":{"type":"default"}} --><div class="wp-block-group value-card reveal"><!-- wp:group {"className":"icon leaf","layout":{"type":"constrained"}} --><div class="wp-block-group icon leaf"></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Responsibility</h3><!-- /wp:heading --><!-- wp:paragraph --><p>We care about the environment and design products for a sustainable future.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"audience shell","layout":{"type":"default"}} -->
<div class="wp-block-group audience shell">
	<!-- wp:heading {"level":2,"className":"section-title reveal"} --><h2 class="wp-block-heading section-title reveal">Who we work with</h2><!-- /wp:heading -->
	<!-- wp:group {"className":"audience-grid","layout":{"type":"grid","columnCount":6}} -->
	<div class="wp-block-group audience-grid">
		<!-- wp:cover {"url":"<?php echo $shipyards_url; ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Shipyard" src="<?php echo $shipyards_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p>Shipyards</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
		<!-- wp:cover {"url":"<?php echo $builders_url; ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Yacht builder" src="<?php echo $builders_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p>Yacht builders</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
		<!-- wp:cover {"url":"<?php echo $designers_url; ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Designer workspace" src="<?php echo $designers_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p>Designers</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
		<!-- wp:cover {"url":"<?php echo $partners_url; ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Distribution partner" src="<?php echo $partners_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p>Distributors</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
		<!-- wp:cover {"url":"<?php echo $developers_url; ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Contemporary development" src="<?php echo $developers_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p>Developers</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
		<!-- wp:cover {"url":"<?php echo $clients_url; ?>","dimRatio":45} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-45 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Private residence" src="<?php echo $clients_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph --><p>Private clients</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"precision-banner","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull precision-banner"><!-- wp:group {"className":"shell","layout":{"type":"default"}} --><div class="wp-block-group shell"><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Precision in<br>every detail.</h2><!-- /wp:heading --><!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator --><!-- wp:paragraph --><p>Engineered for demanding environments.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"numbers shell","layout":{"type":"default"}} -->
<div class="wp-block-group numbers shell">
	<!-- wp:heading {"level":2,"className":"section-title reveal"} --><h2 class="wp-block-heading section-title reveal">By the numbers</h2><!-- /wp:heading -->
	<!-- wp:group {"className":"number-grid","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-group number-grid">
		<!-- wp:group {"tagName":"article","layout":{"type":"default"}} --><article class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><strong>20+</strong></h3><!-- /wp:heading --><!-- wp:paragraph --><p><span>Years of engineering experience</span></p><!-- /wp:paragraph --></article><!-- /wp:group -->
		<!-- wp:group {"tagName":"article","layout":{"type":"default"}} --><article class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><strong>5</strong></h3><!-- /wp:heading --><!-- wp:paragraph --><p><span>Years warranty coverage</span></p><!-- /wp:paragraph --></article><!-- /wp:group -->
		<!-- wp:group {"tagName":"article","layout":{"type":"default"}} --><article class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><strong>5+1</strong></h3><!-- /wp:heading --><!-- wp:paragraph --><p><span>Loyalty programme</span></p><!-- /wp:paragraph --></article><!-- /wp:group -->
		<!-- wp:group {"tagName":"article","layout":{"type":"default"}} --><article class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><strong>100%</strong></h3><!-- /wp:heading --><!-- wp:paragraph --><p><span>Tailor-made solutions</span></p><!-- /wp:paragraph --></article><!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
