# ALUTECO WordPress implementation plan

## Architecture

The static ALUTECO site will be converted into a native WordPress block theme
named `aluteco`, located at `wp-content/themes/aluteco`.

A full block theme is the best fit because it keeps the header, navigation,
footer, global styles, page layouts, and post templates editable through the
WordPress Site Editor while allowing the existing visual system to remain in a
dedicated theme stylesheet. Core Gutenberg blocks and registered patterns will
be used instead of a page builder or custom React blocks.

The original HTML, CSS, JavaScript, and image files remain in the repository as
the visual reference and as the currently published static build.

## Docker environment

Docker Compose will provide:

- WordPress with Apache and PHP;
- MariaDB with a persistent database volume;
- a persistent WordPress files volume;
- WP-CLI for idempotent installation and content seeding;
- Mailpit for local contact-form delivery testing.

The local theme directory is bind-mounted into WordPress. Runtime data, uploads,
database files, credentials, and logs are not committed.

## Theme templates

- `templates/front-page.html`: header, editable page content, footer;
- `templates/page.html`: standard editable Gutenberg pages;
- `templates/home.html`: news hero, category/search controls, Query Loop, pagination;
- `templates/archive.html`: category and tag archives;
- `templates/search.html`: search results;
- `templates/single-post.html`: individual news article;
- `templates/single.html`: generic single-content fallback;
- `templates/index.html`: global fallback;
- `templates/404.html`: branded not-found page;
- `parts/header.html`: Site Logo, Navigation, CTA, language link;
- `parts/footer.html`: editable CTA, contacts, menus, social links, legal links.

## Gutenberg patterns

Page compositions:

- full front page;
- Marine Doors page;
- Home Systems page;
- About Us page;
- Contact page.

Reusable patterns:

- hero;
- inner-page header;
- text with image;
- product/service cards;
- feature cards;
- CTA;
- audience/partner cards;
- contact details;
- latest news Query Loop;
- product detail row.

Patterns use core blocks and preserve the original CSS class names so the
existing responsive design and interaction model can be reused. Structure is
locked only where accidental reordering would break the composition; text,
images, links, and buttons remain editable.

## News

Standard WordPress posts are used. The News page is the posts page and its block
template contains a responsive Query Loop with featured image, categories,
date, title, excerpt, and read-more link. Category, tag, search, archive,
pagination, and single-post templates are included. Setup creates polished
demonstration posts and enough items to exercise pagination.

## Assets and behavior

- Theme CSS is derived from the existing `styles.css`.
- Theme JavaScript is derived from the existing `script.js`.
- Images and SVG logos are copied into the theme assets directory.
- CSS and JavaScript are enqueued through WordPress APIs with file modification
  times as versions.
- Google Fonts are enqueued with the existing Manrope and Sora families.
- Header state, mobile menu, dropdowns, reveal animations, filters, icons, and
  reduced-motion behavior are retained.
- Contact form submission is handled server-side with nonce validation,
  sanitization, validation, a honeypot, and Mailpit-backed local delivery.

## Initial content

An idempotent WP-CLI setup script will:

- install WordPress when needed;
- activate the ALUTECO theme;
- create Home, News, Marine Doors, Home Systems, About Us, and Contact pages;
- seed each page with its Gutenberg composition;
- assign the static front page and posts page;
- set post-name permalinks;
- create categories and demonstration posts;
- import featured images;
- configure the initial site logo and flush rewrite rules.

## Verification

- `docker compose config` validation;
- container health and persistence after restart;
- PHP syntax checks for every theme PHP file;
- WordPress debug and Docker log inspection;
- HTTP checks for home, pages, news, archives, a single post, REST API, and 404;
- link and static-resource checks;
- Playwright smoke tests for desktop and mobile navigation, form behavior,
  pagination, console errors, and missing assets;
- editor and Site Editor checks;
- screenshots at 1440, 1024, 768, and 390 pixels;
- visual comparison against the original static pages.

