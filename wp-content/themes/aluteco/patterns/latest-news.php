<?php
/**
 * Title: Latest news
 * Slug: aluteco/latest-news
 * Categories: aluteco-sections
 * Description: Three latest posts with featured images and excerpts.
 */
?>
<!-- wp:group {"className":"latest-news shell","layout":{"type":"default"}} -->
<div class="wp-block-group latest-news shell"><!-- wp:group {"className":"latest-news-heading","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} --><div class="wp-block-group latest-news-heading"><!-- wp:heading {"level":2,"className":"section-title"} --><h2 class="wp-block-heading section-title">Latest news</h2><!-- /wp:heading --><!-- wp:paragraph --><p><a href="/news/">View all news</a></p><!-- /wp:paragraph --></div><!-- /wp:group --><!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"news-query"} --><div class="wp-block-query news-query"><!-- wp:post-template --><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /--><!-- wp:group {"className":"news-card-content","layout":{"type":"default"}} --><div class="wp-block-group news-card-content"><!-- wp:post-date /--><!-- wp:post-title {"isLink":true} /--><!-- wp:post-excerpt {"moreText":"","excerptLength":22} /--><!-- wp:read-more {"content":"Read more"} /--></div><!-- /wp:group --><!-- /wp:post-template --></div><!-- /wp:query --></div>
<!-- /wp:group -->
