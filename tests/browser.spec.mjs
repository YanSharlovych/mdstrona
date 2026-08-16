import { expect, test } from "@playwright/test";
import path from "node:path";

const polishRoutes = [
  { route: "/pl/", heading: "Więcej niż" },
  { route: "/pl/home-systems/", heading: "Systemy domowe" },
  { route: "/pl/marine-doors/", heading: "Drzwi jachtowe" },
  { route: "/pl/about-us/", heading: "O nas" },
  { route: "/pl/contact/", heading: "Kontakt z nami" },
  { route: "/pl/news/", heading: "Aktualności" },
  { route: "/pl/news/page/2/", heading: "Aktualności" },
  { route: "/pl/news/?news_category=products", heading: "Aktualności" },
  { route: "/pl/news/?news_search=Stewarta", heading: "Aktualności" },
  { route: "/pl/privacy-policy/", heading: "Polityka prywatności" },
  { route: "/pl/cookies-policy/", heading: "Polityka plików cookie" },
  { route: "/pl/terms-conditions/", heading: "Regulamin" },
  {
    route: "/pl/new-generation-sliding-door-systems/",
    heading: "Nowa generacja systemów drzwi przesuwnych"
  },
  {
    route: "/pl/stewart-platform-marine-testing/",
    heading: "Platforma Stewarta do symulacji wymagających warunków morskich"
  },
  {
    route: "/pl/aluteco-polboat-yachting-festival-2026/",
    heading: "ALUTECO na Polboat Yachting Festival 2026"
  },
  {
    route: "/pl/thermal-performance-modern-entry-systems/",
    heading: "Izolacyjność cieplna nowoczesnych systemów wejściowych"
  },
  {
    route: "/pl/responsible-aluminium-circularity/",
    heading: "Odpowiedzialne aluminium: projektowane z myślą o obiegu zamkniętym"
  },
  {
    route: "/pl/specifying-loft-systems-quiet-interiors/",
    heading: "Jak specyfikować systemy loftowe do cichych wnętrz"
  },
  {
    route: "/pl/concept-to-sea-trial-custom-door/",
    heading: "Od koncepcji do próby morskiej: projekt drzwi na zamówienie"
  },
  {
    route: "/pl/five-details-marine-door-specification/",
    heading: "Pięć detali, które poprawiają specyfikację drzwi jachtowych"
  }
];

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
  "/new-generation-sliding-door-systems/",
  ...polishRoutes.map(({ route }) => route)
];

const editableIconPages = [
  { slug: "home", route: "/", count: 8 },
  { slug: "home-systems", route: "/home-systems/", count: 8 },
  { slug: "marine-doors", route: "/marine-doors/", count: 16 },
  { slug: "about-us", route: "/about-us/", count: 4 },
  { slug: "contact", route: "/contact/", count: 3 }
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

    const polishRoute = polishRoutes.find((entry) => entry.route === route);
    if (polishRoute) {
      await expect(page.locator("html")).toHaveAttribute("lang", "pl-PL");
      await expect(page.locator("h1")).toContainText(polishRoute.heading);
    }

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

test("language switcher preserves the current page in both languages", async ({
  page
}) => {
  await page.goto("/pl/home-systems/", { waitUntil: "networkidle" });

  const switcher = page.locator(".site-header .language-switcher");
  const english = switcher.getByRole("link", { name: "View site in English" });
  const polish = switcher.getByRole("link", {
    name: "Wyświetl witrynę po polsku"
  });

  await expect(page.locator("#trp-floater-ls")).toHaveCount(0);
  await expect(polish).toHaveAttribute("aria-current", "page");
  await expect(polish).toHaveAttribute("href", /\/pl\/home-systems\//);
  await expect(english).toHaveAttribute("href", /\/home-systems\//);

  await english.click();
  await expect(page).toHaveURL(/\/home-systems\/(?:\?switcher-check=1)?$/);
  await expect(
    page
      .locator(".site-header .language-switcher")
      .getByRole("link", { name: "View site in English" })
  ).toHaveAttribute("aria-current", "page");
});

test("Polish document titles are translated", async ({ page }) => {
  await page.goto("/pl/", { waitUntil: "networkidle" });
  await expect(page).toHaveTitle("ALUTECO – Drzwi jachtowe i systemy domowe");

  await page.goto("/pl/?s=marine", { waitUntil: "networkidle" });
  await expect(page).toHaveTitle(/Wyniki wyszukiwania dla: marine/);
});

test("Polish 404 page is translated", async ({ page }) => {
  const response = await page.goto("/pl/missing-page/", {
    waitUntil: "networkidle"
  });

  expect(response?.status()).toBe(404);
  await expect(page.locator("html")).toHaveAttribute("lang", "pl-PL");
  await expect(page.locator("main#main")).toContainText("Nie znaleziono strony");
  await expect(page.locator("main#main")).toContainText("Wróć na stronę główną");
});

test("content icon containers are controlled by Gutenberg blocks", async ({
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
    <div id="empty-icon" class="work-icon tools"></div>
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
  await expect(page.locator("#empty-icon svg")).toHaveCount(0);
  await expect(page.locator("#empty-icon")).not.toHaveAttribute("aria-hidden");
});

test("all page-section icons render from editable Gutenberg blocks", async ({
  page
}) => {
  for (const iconPage of editableIconPages) {
    await page.goto(iconPage.route, { waitUntil: "networkidle" });
    const editableIcons = page.locator(
      "main .wp-block-icon, main .wp-block-aluteco-icon"
    );

    await expect(editableIcons).toHaveCount(iconPage.count);
    await expect(editableIcons.locator("svg")).toHaveCount(iconPage.count);
    await expect(
      page.locator(
        "main .icon:empty, main .work-icon:empty, main .feature-icon-item:not(:has(.wp-block-icon, .wp-block-aluteco-icon)), main .round-icon:not(:has(.wp-block-icon, .wp-block-aluteco-icon))"
      )
    ).toHaveCount(0);
  }
});

test("Global Styles typography overrides theme presentation defaults", async ({
  page
}) => {
  const typography = {
    family: '"Times New Roman", serif',
    size: "31px",
    style: "italic",
    weight: "300",
    letterSpacing: "2px",
    lineHeight: "43.4px",
    transform: "none"
  };

  const assertTypography = async (route, selectors) => {
    await page.goto(route, { waitUntil: "networkidle" });
    await page.evaluate(() => {
      const themeStylesheet = document.querySelector("#aluteco-style-css");
      const globalStylesTest = document.createElement("style");

      globalStylesTest.textContent = `
        body {
          font-family: "Courier New", monospace;
          font-size: 19px;
          line-height: 2;
        }
        h1, h2, h3, h4, h5, h6 {
          font-family: "Times New Roman", serif;
          font-size: 31px;
          font-style: italic;
          font-weight: 300;
          letter-spacing: 2px;
          line-height: 1.4;
          text-transform: none;
        }
        .wp-block-navigation,
        .wp-element-button,
        .wp-block-button__link {
          font-family: "Times New Roman", serif;
          font-size: 31px;
          font-style: italic;
          font-weight: 300;
          letter-spacing: 2px;
          line-height: 1.4;
          text-transform: none;
        }
      `;

      themeStylesheet.before(globalStylesTest);
    });

    await expect(page.locator("body")).toHaveCSS(
      "font-family",
      '"Courier New", monospace'
    );
    await expect(page.locator("body")).toHaveCSS("font-size", "19px");
    await expect(page.locator("body")).toHaveCSS("line-height", "38px");

    for (const selector of selectors) {
      const element = page.locator(selector).first();
      await expect(element).toHaveCSS("font-family", typography.family);
      await expect(element).toHaveCSS("font-size", typography.size);
      await expect(element).toHaveCSS("font-style", typography.style);
      await expect(element).toHaveCSS("font-weight", typography.weight);
      await expect(element).toHaveCSS(
        "letter-spacing",
        typography.letterSpacing
      );
      await expect(element).toHaveCSS("line-height", typography.lineHeight);
      await expect(element).toHaveCSS("text-transform", typography.transform);
    }
  };

  await assertTypography("/", [
    ".hero h1",
    ".product-tile h2",
    ".icon-card h3",
    ".work .section-title",
    ".site-header .wp-block-navigation",
    ".site-header .wp-block-navigation-item__content",
    ".site-footer h2",
    ".site-footer h3",
    ".site-footer .wp-block-button__link"
  ]);
  await assertTypography("/contact/", [
    ".contact-form-panel > h2",
    ".message-form .wp-element-button"
  ]);
  await assertTypography("/news/", [".news-query .wp-block-post-title"]);
  await assertTypography("/new-generation-sliding-door-systems/", [
    ".single-news .wp-block-post-title"
  ]);
  await assertTypography("/missing-page/", [".error-page h1"]);
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

test("Polish contact form exposes translated labels", async ({ page }) => {
  await page.goto("/pl/contact/", { waitUntil: "networkidle" });

  await expect(page.getByLabel("Imię i nazwisko")).toHaveAttribute("required", "");
  await expect(page.getByLabel("Adres e-mail")).toHaveAttribute("required", "");
  await expect(page.getByLabel("Numer telefonu")).not.toHaveAttribute("required");
  await expect(page.getByLabel("Wiadomość")).toHaveAttribute("required", "");
  await expect(page.getByRole("button", { name: "Wyślij wiadomość" })).toBeVisible();
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

test("Polish news categories and search update in the same document", async ({
  page
}) => {
  await page.goto("/pl/news/", { waitUntil: "networkidle" });
  await page.evaluate(() => {
    window.__alutecoPolishNewsDocumentMarker = "same-document";
  });

  const productsResponse = page.waitForResponse(
    (response) =>
      response.url().includes("news_category=products") && response.status() === 200
  );
  await page
    .locator(".news-category-controls")
    .getByRole("link", { name: "Produkty", exact: true })
    .click();
  await productsResponse;

  await expect(page).toHaveURL(/\/pl\/news\/\?news_category=products$/);
  await expect(page.locator(".news-category-controls a.is-active")).toHaveText(
    "Produkty"
  );
  expect(
    await page.evaluate(() => window.__alutecoPolishNewsDocumentMarker)
  ).toBe("same-document");

  const allNewsResponse = page.waitForResponse(
    (response) => response.url().endsWith("/pl/news/") && response.status() === 200
  );
  await page
    .locator(".news-category-controls")
    .getByRole("link", { name: "Wszystkie aktualności", exact: true })
    .click();
  await allNewsResponse;

  const searchResponse = page.waitForResponse(
    (response) =>
      response.url().includes("news_search=Stewarta") && response.status() === 200
  );
  await page.getByPlaceholder("Szukaj aktualności...").fill("Stewarta");
  await searchResponse;

  await expect(page).toHaveURL(/\/pl\/news\/\?news_search=Stewarta$/);
  await expect(page.locator(".news-query .wp-block-post-title")).toHaveText(
    "Platforma Stewarta do symulacji wymagających warunków morskich"
  );
  await expect(page.locator(".news-query .wp-block-post")).toHaveCount(1);
  await expect(page.locator(".news-results-status")).toHaveText(
    "Wyświetlono 1 aktualność."
  );
  expect(
    await page.evaluate(() => window.__alutecoPolishNewsDocumentMarker)
  ).toBe("same-document");

  const emptyResponse = page.waitForResponse(
    (response) =>
      response.url().includes("news_search=brakwynikow") && response.status() === 200
  );
  await page.getByPlaceholder("Szukaj aktualności...").fill("brakwynikow");
  await emptyResponse;
  await expect(page.locator(".news-query")).toContainText(
    "Nie znaleziono aktualności pasujących do zapytania."
  );
  await expect(page.locator(".news-results-status")).toHaveText(
    "Wyświetlono 0 aktualności."
  );
});

test("Gutenberg page editor and Site Editor load cleanly", async ({
  page,
  request
}) => {
  test.setTimeout(90_000);

  const username = process.env.WP_ADMIN_USER || "aluteco_admin";
  const password =
    process.env.WP_ADMIN_PASSWORD || "local-only-change-me";
  const editorPages = [];

  for (const iconPage of editableIconPages) {
    const response = await request.get(
      `/wp-json/wp/v2/pages?slug=${iconPage.slug}&_fields=id,slug`
    );
    const [editorPage] = await response.json();

    expect(editorPage?.id).toBeTruthy();
    editorPages.push({ ...iconPage, id: editorPage.id });
  }

  await page.goto("/wp-login.php");
  await page.locator("#user_login").fill(username);
  await page.locator("#user_pass").fill(password);
  await page.locator("#wp-submit").click();
  await expect(page).toHaveURL(/wp-admin/);

  for (const editorPage of editorPages) {
    await page.goto(`/wp-admin/post.php?post=${editorPage.id}&action=edit`);
    await expect(page.locator("body")).not.toContainText(
      "There has been a critical error"
    );
    await expect(page.locator(".notice-error:visible")).toHaveCount(0);
    await expect(page.locator(".block-editor-warning")).toHaveCount(0);

    const editor = page.frameLocator('iframe[name="editor-canvas"]');
    const editableIcons = editor.locator(
      '[data-type="core/icon"], [data-type="aluteco/icon"]'
    );

    await expect(editableIcons).toHaveCount(editorPage.count);

    if ("home" === editorPage.slug) {
      await expect(editor.locator("body")).toContainText(
        "Door and window systems engineered"
      );
    }

    if ("marine-doors" === editorPage.slug) {
      await editableIcons.first().click();
      await expect(
        page.getByRole("button", { name: /Replace/i }).first()
      ).toBeVisible();
    }
  }

  await page.goto("/wp-admin/site-editor.php");
  await expect(page.locator("body")).not.toContainText(
    "There has been a critical error"
  );
  await expect(page.locator(".notice-error:visible")).toHaveCount(0);
  await expect(page.locator("body")).toContainText(/Design|Styles|Navigation/);
});
