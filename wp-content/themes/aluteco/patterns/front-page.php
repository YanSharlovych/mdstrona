<?php
/**
 * Title: Complete front page
 * Slug: aluteco/front-page
 * Categories: aluteco-pages
 * Description: Complete editable ALUTECO front page.
 */

$hero_url        = esc_url( get_theme_file_uri( '/assets/generated/home-hero-profile.png' ) );
$marine_card_url = esc_url( get_theme_file_uri( '/assets/photos/home-card-marine-doors.png' ) );
$home_card_url   = esc_url( get_theme_file_uri( '/assets/photos/home-card-home-systems.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $hero_url; ?>","dimRatio":0,"minHeight":80,"minHeightUnit":"vh","align":"full","className":"hero hero-home hero-dark"} -->
<div class="wp-block-cover alignfull hero hero-home hero-dark" style="min-height:80vh">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="Precision aluminium sliding-door profile" src="<?php echo $hero_url; ?>" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"className":"hero-copy reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group hero-copy reveal">
			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading">Beyond the<br>frame</h1>
			<!-- /wp:heading -->
			<!-- wp:separator {"className":"blue-line"} -->
			<hr class="wp-block-separator has-alpha-channel-opacity blue-line"/>
			<!-- /wp:separator -->
			<!-- wp:paragraph -->
			<p>Door and window systems engineered to open up new possibilities</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
</div>
<!-- /wp:cover -->

<!-- wp:group {"className":"product-cards shell","layout":{"type":"grid","columnCount":2}} -->
<div class="wp-block-group product-cards shell">
	<!-- wp:cover {"url":"<?php echo $marine_card_url; ?>","dimRatio":0,"className":"product-tile marine-tile reveal"} -->
	<div class="wp-block-cover product-tile marine-tile reveal">
		<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
		<img class="wp-block-cover__image-background" alt="Marine sliding doors on a yacht" src="<?php echo $marine_card_url; ?>" data-object-fit="cover"/>
		<div class="wp-block-cover__inner-container">
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:heading {"level":2} -->
				<h2 class="wp-block-heading">Marine Doors</h2>
				<!-- /wp:heading -->
				<!-- wp:separator {"className":"blue-line small"} -->
				<hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/>
				<!-- /wp:separator -->
				<!-- wp:paragraph -->
				<p>High performance door systems for marine environments.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/marine-doors/">Explore &rarr;</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
	</div>
	<!-- /wp:cover -->

	<!-- wp:cover {"url":"<?php echo $home_card_url; ?>","dimRatio":0,"className":"product-tile home-tile reveal"} -->
	<div class="wp-block-cover product-tile home-tile reveal">
		<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
		<img class="wp-block-cover__image-background" alt="Premium entry door for modern architecture" src="<?php echo $home_card_url; ?>" data-object-fit="cover"/>
		<div class="wp-block-cover__inner-container">
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:heading {"level":2} -->
				<h2 class="wp-block-heading">Home Systems</h2>
				<!-- /wp:heading -->
				<!-- wp:separator {"className":"blue-line small"} -->
				<hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/>
				<!-- /wp:separator -->
				<!-- wp:paragraph -->
				<p>Aluminium solutions for modern architecture.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/home-systems/">Explore &rarr;</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
	</div>
	<!-- /wp:cover -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"precision shell","layout":{"type":"default"}} -->
<div class="wp-block-group precision shell">
	<!-- wp:group {"className":"section-intro reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group section-intro reveal">
		<!-- wp:paragraph {"className":"eyebrow"} -->
		<p class="eyebrow">Engineered to perform</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading">Precision<br>in every detail</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p>We combine innovation, engineering and quality materials to deliver systems that perform today and for years to come.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"icon-grid","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-group icon-grid">
		<!-- wp:group {"className":"icon-card reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group icon-card reveal">
			<!-- wp:group {"className":"icon shield","layout":{"type":"constrained"}} --><div class="wp-block-group icon shield"><!-- wp:icon {"icon":"core/shield","align":"center"} /--></div><!-- /wp:group -->
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Premium quality</h3><!-- /wp:heading -->
			<!-- wp:paragraph --><p>High-end materials and components for long-lasting performance.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"icon-card reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group icon-card reveal">
			<!-- wp:group {"className":"icon smart","layout":{"type":"constrained"}} --><div class="wp-block-group icon smart"><!-- wp:icon {"icon":"core/mobile","align":"center"} /--></div><!-- /wp:group -->
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Smart door solutions</h3><!-- /wp:heading -->
			<!-- wp:paragraph --><p>Intelligent systems for seamless and reliable operation.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"icon-card reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group icon-card reveal">
			<!-- wp:group {"className":"icon puzzle","layout":{"type":"constrained"}} --><div class="wp-block-group icon puzzle"><!-- wp:icon {"icon":"core/shuffle","align":"center"} /--></div><!-- /wp:group -->
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Flexible</h3><!-- /wp:heading -->
			<!-- wp:paragraph --><p>Modular solutions tailored to your project requirements.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"icon-card reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group icon-card reveal">
			<!-- wp:group {"className":"icon leaf","layout":{"type":"constrained"}} --><div class="wp-block-group icon leaf"><!-- wp:icon {"icon":"core/star-empty","align":"center"} /--></div><!-- /wp:group -->
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Sustainable</h3><!-- /wp:heading -->
			<!-- wp:paragraph --><p>Responsible design for a better future.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"tech-banner","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull tech-banner">
	<!-- wp:group {"className":"shell tech-grid","layout":{"type":"default"}} -->
	<div class="wp-block-group shell tech-grid">
		<!-- wp:group {"className":"reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group reveal">
			<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Designed to inspire</p><!-- /wp:paragraph -->
			<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Technology<br>meets design</h2><!-- /wp:heading -->
			<!-- wp:paragraph --><p>From first sketch to final product, we design and engineer advanced systems that make a difference in every project.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"work shell","layout":{"type":"default"}} -->
<div class="wp-block-group work shell">
	<!-- wp:heading {"level":2,"className":"section-title reveal"} --><h2 class="wp-block-heading section-title reveal">How we work</h2><!-- /wp:heading -->
	<!-- wp:group {"className":"work-steps","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-group work-steps">
		<!-- wp:group {"className":"reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group reveal"><!-- wp:group {"className":"work-icon chat","layout":{"type":"constrained"}} --><div class="wp-block-group work-icon chat"><!-- wp:icon {"icon":"core/comment","align":"center"} /--></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Consultation</h3><!-- /wp:heading --><!-- wp:paragraph --><p>We listen to your needs and understand your project.</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group reveal"><!-- wp:group {"className":"work-icon tools","layout":{"type":"constrained"}} --><div class="wp-block-group work-icon tools"><!-- wp:icon {"icon":"core/pencil","align":"center"} /--></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Design &amp; Engineering</h3><!-- /wp:heading --><!-- wp:paragraph --><p>We design and engineer tailored solutions with precision.</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group reveal"><!-- wp:group {"className":"work-icon gear","layout":{"type":"constrained"}} --><div class="wp-block-group work-icon gear"><!-- wp:icon {"icon":"core/settings","align":"center"} /--></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Manufacturing</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Advanced production ensures high quality and durability.</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group reveal"><!-- wp:group {"className":"work-icon wrench","layout":{"type":"constrained"}} --><div class="wp-block-group work-icon wrench"><!-- wp:icon {"icon":"core/settings","align":"center"} /--></div><!-- /wp:group --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Installation support</h3><!-- /wp:heading --><!-- wp:paragraph --><p>We provide guidance and support for smooth installation.</p><!-- /wp:paragraph --></div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
