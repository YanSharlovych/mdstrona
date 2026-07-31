<?php
/**
 * Title: Complete Contact page
 * Slug: aluteco/contact-page
 * Categories: aluteco-pages
 * Description: Complete editable contact page with a secure form.
 */

$hero_url = esc_url( get_theme_file_uri( '/assets/generated/hero-contact.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $hero_url; ?>","dimRatio":0,"minHeight":80,"minHeightUnit":"vh","align":"full","className":"hero hero-contact"} -->
<div class="wp-block-cover alignfull hero hero-contact" style="min-height:80vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background" alt="Precision aluminium profile on an engineering drawing" src="<?php echo $hero_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"hero-copy reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group hero-copy reveal"><!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Contact<br>Us</h1><!-- /wp:heading --><!-- wp:separator {"className":"blue-line"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line"/><!-- /wp:separator --><!-- wp:paragraph --><p>Let's discuss your project and find the right aluminium system solution.</p><!-- /wp:paragraph --></div>
	<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->

<!-- wp:group {"className":"contact-main shell","layout":{"type":"default"}} -->
<div class="wp-block-group contact-main shell">
	<!-- wp:group {"className":"contact-form-panel reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group contact-form-panel reveal">
		<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Send us a message</h2><!-- /wp:heading -->
		<!-- wp:separator {"className":"blue-line small"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/><!-- /wp:separator -->
		<!-- wp:shortcode -->[aluteco_contact_form]<!-- /wp:shortcode -->
		<!-- wp:paragraph {"className":"response-note"} --><p class="response-note">We typically respond within 1 business day.</p><!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"tagName":"aside","className":"contact-details reveal","layout":{"type":"default"}} -->
	<aside class="wp-block-group contact-details reveal">
		<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Contact details</h2><!-- /wp:heading -->
		<!-- wp:separator {"className":"blue-line small"} --><hr class="wp-block-separator has-alpha-channel-opacity blue-line small"/><!-- /wp:separator -->
		<!-- wp:group {"tagName":"article","layout":{"type":"flex","flexWrap":"nowrap"}} --><article class="wp-block-group"><!-- wp:paragraph {"className":"round-icon"} --><p class="round-icon">Email</p><!-- /wp:paragraph --><!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Email</h3><!-- /wp:heading --><!-- wp:paragraph --><p><a href="mailto:info@md-concepts.com">info@md-concepts.com</a></p><!-- /wp:paragraph --></div><!-- /wp:group --></article><!-- /wp:group -->
		<!-- wp:group {"tagName":"article","layout":{"type":"flex","flexWrap":"nowrap"}} --><article class="wp-block-group"><!-- wp:paragraph {"className":"round-icon"} --><p class="round-icon">Phone</p><!-- /wp:paragraph --><!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Phone</h3><!-- /wp:heading --><!-- wp:paragraph --><p><a href="tel:+48123456789">+48 123 456 789</a></p><!-- /wp:paragraph --></div><!-- /wp:group --></article><!-- /wp:group -->
		<!-- wp:group {"tagName":"article","layout":{"type":"flex","flexWrap":"nowrap"}} --><article class="wp-block-group"><!-- wp:paragraph {"className":"round-icon"} --><p class="round-icon">Pin</p><!-- /wp:paragraph --><!-- wp:group {"layout":{"type":"default"}} --><div class="wp-block-group"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Location</h3><!-- /wp:heading --><!-- wp:paragraph --><p>ul. Aluminium 1<br>00-000 Warsaw, Poland</p><!-- /wp:paragraph --></div><!-- /wp:group --></article><!-- /wp:group -->
	</aside>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
