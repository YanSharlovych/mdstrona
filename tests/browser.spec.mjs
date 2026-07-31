import { expect, test } from "@playwright/test";
import path from "node:path";

const publicRoutes = [
  "/",
  "/home-systems/",
  "/marine-doors/",
  "/about-us/",
  "/contact/",
  "/news/",
  "/news/?news_category=products",
  "/news/?news_search=Stewart",
  "/news/page/2/",
  "/category/products/",
  "/?s=marine",
  "/new-generation-sliding-door-systems/"
];

async function revealWholePage(page) {
  await page.emulateMedia({ reducedMotion: "reduce" });
  await page.evaluate(async () => {
    const step = Math.max(window.innerHeight * 0.8, 500);

    for (let y = 0; y < document.documentElement.scrollHeight; y += step) {
      window.scrollTo(0, y);
      await new Promise((resolve) => setTimeout(resolve, 20));
    }

    window.scrollTo(0, 0);
  });
}

for (const route of publicRoutes) {
  test(`${route} renders without structural or asset errors`, async ({ page }) => {
    const browserErrors = [];

    page.on("pageerror", (error) => browserErrors.push(error.message));
    page.on("console", (message) => {
      if (message.type() === "error") {
        browserErrors.push(message.text());
      }
    });

    const response = await page.goto(route, { waitUntil: "networkidle" });

    expect(response?.ok()).toBeTruthy();
    await revealWholePage(page);
    await expect(page.locator("main#main")).toHaveCount(1);
    await expect(page.locator("h1")).toHaveCount(1);

    const structure = await page.evaluate(() => {
      const headings = [...document.querySelectorAll("h1,h2,h3,h4,h5,h6")];
      const levels = headings.map((heading) => Number(heading.tagName.slice(1)));
      const duplicateIds = [...document.querySelectorAll("[id]")]
        .map((element) => element.id)
        .filter((id, index, ids) => ids.indexOf(id) !== index);

      return {
        horizontalOverflow:
          document.documentElement.scrollWidth > window.innerWidth + 1,
        headingJumps: levels
          .slice(1)
          .filter((level, index) => level > levels[index] + 1).length,
        duplicateIds: duplicateIds.length,
        unlabeledLinks: [...document.querySelectorAll("a")].filter(
          (link) =>
            !link.getAttribute("aria-label") &&
            !link.textContent.trim() &&
            !link.querySelector('img[alt]:not([alt=""])')
        ).length,
        unlabeledButtons: [...document.querySelectorAll("button")].filter(
          (button) =>
            !button.getAttribute("aria-label") &&
            !button.getAttribute("title") &&
            !button.textContent.trim()
        ).length,
        imagesWithoutAlt: [...document.images].filter(
          (image) => !image.hasAttribute("alt")
        ).length
      };
    });

    expect(structure).toEqual({
      horizontalOverflow: false,
      headingJumps: 0,
      duplicateIds: 0,
      unlabeledLinks: 0,
      unlabeledButtons: 0,
      imagesWithoutAlt: 0
    });

    const images = page.locator("img");
    for (let index = 0; index < (await images.count()); index += 1) {
      const image = images.nth(index);
      await image.scrollIntoViewIfNeeded();
      await expect
        .poll(() =>
          image.evaluate(
            (element) => element.complete && element.naturalWidth > 0
          )
        )
        .toBe(true);
    }

    expect(browserErrors).toEqual([]);
  });
}

test("skip link and mobile navigation work with the keyboard", async ({
  page
}) => {
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto("/", { waitUntil: "networkidle" });

  await page.keyboard.press("Tab");
  await expect(page.locator(":focus")).toHaveText("Skip to content");
  await expect(page.locator(":focus")).toHaveAttribute("href", "#main");

  await page.getByRole("button", { name: /open menu/i }).click();
  const mobileMenu = page.locator(
    ".wp-block-navigation__responsive-container.is-menu-open"
  );

  await expect(mobileMenu).toBeVisible();
  await expect(mobileMenu.getByRole("link", { name: "Marine Doors" })).toBeVisible();
  await expect(mobileMenu.getByRole("link", { name: "Home Systems" })).toBeVisible();
  await page.keyboard.press("Escape");
  await expect(mobileMenu).not.toBeVisible();
});

test("Gutenberg icon and image blocks are not replaced by fallbacks", async ({
  page
}) => {
  await page.setContent(`
    <div id="custom-icon" class="work-icon chat">
      <div class="wp-block-icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="12" cy="12" r="8"></circle>
        </svg>
      </div>
    </div>
    <div id="custom-image" class="icon shield">
      <figure class="wp-block-image">
        <img
          alt="Custom quality icon"
          src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
        >
      </figure>
    </div>
    <div id="fallback-icon" class="work-icon tools"></div>
  `);

  await page.addScriptTag({
    path: path.join(
      process.cwd(),
      "wp-content",
      "themes",
      "aluteco",
      "assets",
      "js",
      "site.js"
    )
  });

  await expect(page.locator("#custom-icon .wp-block-icon svg")).toHaveCount(1);
  await expect(page.locator("#custom-icon")).not.toHaveAttribute("aria-hidden");
  await expect(page.locator("#custom-image img")).toHaveCount(1);
  await expect(page.locator("#custom-image svg")).toHaveCount(0);
  await expect(page.locator("#custom-image")).not.toHaveAttribute("aria-hidden");
  await expect(page.locator("#fallback-icon svg")).toHaveCount(1);
  await expect(page.locator("#fallback-icon")).toHaveAttribute(
    "aria-hidden",
    "true"
  );
});

test("contact form exposes usable labels and validation", async ({ page }) => {
  await page.goto("/contact/", { waitUntil: "networkidle" });

  await expect(page.getByLabel("Full Name")).toHaveAttribute("required", "");
  await expect(page.getByLabel("Email Address")).toHaveAttribute("required", "");
  await expect(page.getByLabel("Phone Number")).not.toHaveAttribute("required");
  await expect(page.getByLabel("Message")).toHaveAttribute("required", "");
  await expect(page.locator('input[name="aluteco_contact_nonce"]')).toHaveCount(1);
  await expect(page.locator('input[name="company"]')).toHaveCount(1);
});

test("news is dynamic and paginated", async ({ page, request }) => {
  const postsResponse = await request.get(
    "/wp-json/wp/v2/posts?per_page=100&_fields=id,author"
  );
  const posts = await postsResponse.json();

  expect(postsResponse.ok()).toBeTruthy();
  expect(Number(postsResponse.headers()["x-wp-total"])).toBeGreaterThanOrEqual(8);
  expect(posts.every((post) => post.author > 0)).toBe(true);

  await page.goto("/news/", { waitUntil: "networkidle" });
  await expect(
    page.getByRole("link", { name: "2", exact: true })
  ).toHaveAttribute("href", /\/news\/page\/2\/$/);
});

test("news categories and live search update without leaving the page", async ({
  page
}) => {
  await page.goto("/news/", { waitUntil: "networkidle" });
  await page.evaluate(() => {
    window.__alutecoNewsDocumentMarker = "same-document";
  });

  const productsResponse = page.waitForResponse(
    (response) =>
      response.url().includes("news_category=products") && response.status() === 200
  );
  await page
    .locator(".news-category-controls")
    .getByRole("link", { name: "Products", exact: true })
    .click();
  await productsResponse;

  await expect(page).toHaveURL(/\/news\/\?news_category=products$/);
  await expect(
    page.locator(".news-category-controls a.is-active")
  ).toHaveText("Products");
  const productCards = page.locator(".news-query .wp-block-post");
  const productTerms = await page
    .locator(".news-query .wp-block-post-terms")
    .allTextContents();
  expect(await productCards.count()).toBeGreaterThan(0);
  expect(productTerms.every((terms) => terms.includes("Products"))).toBe(true);
  expect(
    await page.evaluate(() => window.__alutecoNewsDocumentMarker)
  ).toBe("same-document");

  const allNewsResponse = page.waitForResponse(
    (response) => response.url().endsWith("/news/") && response.status() === 200
  );
  await page
    .locator(".news-category-controls")
    .getByRole("link", { name: "All news", exact: true })
    .click();
  await allNewsResponse;

  const searchResponse = page.waitForResponse(
    (response) =>
      response.url().includes("news_search=Stewart") && response.status() === 200
  );
  await page.getByPlaceholder("Search news...").fill("Stewart");
  await searchResponse;

  await expect(page).toHaveURL(/news_search=Stewart/);
  await expect(page.locator(".news-query .wp-block-post-title")).toHaveText(
    "Stewart Platform for Simulating Heavy Marine Conditions"
  );
  await expect(page.locator(".news-query .wp-block-post")).toHaveCount(1);
  expect(
    await page.evaluate(() => window.__alutecoNewsDocumentMarker)
  ).toBe("same-document");
});

test("Gutenberg page editor and Site Editor load cleanly", async ({
  page,
  request
}) => {
  const username = process.env.WP_ADMIN_USER || "aluteco_admin";
  const password =
    process.env.WP_ADMIN_PASSWORD || "local-only-change-me";
  const homeResponse = await request.get(
    "/wp-json/wp/v2/pages?slug=home&_fields=id"
  );
  const [home] = await homeResponse.json();

  expect(home?.id).toBeTruthy();

  await page.goto("/wp-login.php");
  await page.locator("#user_login").fill(username);
  await page.locator("#user_pass").fill(password);
  await page.locator("#wp-submit").click();
  await expect(page).toHaveURL(/wp-admin/);

  await page.goto(`/wp-admin/post.php?post=${home.id}&action=edit`);
  await expect(page.locator("body")).not.toContainText(
    "There has been a critical error"
  );
  await expect(page.locator(".notice-error:visible")).toHaveCount(0);
  await expect(page.locator(".block-editor-warning")).toHaveCount(0);
  await expect(
    page.frameLocator('iframe[name="editor-canvas"]').locator("body")
  ).toContainText("Door and window systems engineered");

  await page.goto("/wp-admin/site-editor.php");
  await expect(page.locator("body")).not.toContainText(
    "There has been a critical error"
  );
  await expect(page.locator(".notice-error:visible")).toHaveCount(0);
  await expect(page.locator("body")).toContainText(/Design|Styles|Navigation/);
});
