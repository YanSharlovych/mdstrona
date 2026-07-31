<?php
/**
 * Title: News query
 * Slug: aluteco/news-query
 * Categories: aluteco-sections
 * Description: Responsive news cards with metadata and pagination.
 * Inserter: no
 */
?>
<!-- wp:query {"queryId":12,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"news-query","layout":{"type":"default"}} -->
<div class="wp-block-query news-query">
	<!-- wp:post-template -->
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /-->
		<!-- wp:group {"className":"news-card-content","layout":{"type":"default"}} -->
		<div class="wp-block-group news-card-content">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">
				<!-- wp:post-date {"format":"j M Y"} /-->
				<!-- wp:post-terms {"term":"category"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:post-title {"isLink":true,"level":2} /-->
			<!-- wp:post-excerpt {"moreText":"","excerptLength":24} /-->
			<!-- wp:read-more {"content":"Read more"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p>No news matched your request.</p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->

	<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:query-pagination-previous {"label":"Previous"} /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next {"label":"Next"} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
