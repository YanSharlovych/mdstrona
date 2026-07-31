<?php
/**
 * Title: News hero
 * Slug: aluteco/news-hero
 * Categories: aluteco-sections
 * Description: Editable News page hero.
 */

$image_url = esc_url( get_theme_file_uri( '/assets/generated/hero-news.png' ) );
?>
<!-- wp:cover {"url":"<?php echo $image_url; ?>","dimRatio":0,"minHeight":80,"minHeightUnit":"vh","align":"full","className":"hero hero-news"} -->
<div class="wp-block-cover alignfull hero hero-news" style="min-height:80vh">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
	<img class="wp-block-cover__image-background" alt="" src="<?php echo $image_url; ?>" data-object-fit="cover"/>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"className":"hero-copy reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group hero-copy reveal">
			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading">News</h1>
			<!-- /wp:heading -->
			<!-- wp:separator {"className":"blue-line"} -->
			<hr class="wp-block-separator has-alpha-channel-opacity blue-line"/>
			<!-- /wp:separator -->
			<!-- wp:paragraph -->
			<p>Stay informed about what drives us forward.</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"body-copy"} -->
			<p class="body-copy">Here you will find the latest updates on our innovations, projects, partnerships and industry activities. We share insights from behind the scenes and highlight milestones that shape the future of our solutions.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
</div>
<!-- /wp:cover -->
