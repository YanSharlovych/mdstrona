(function () {
  document.documentElement.classList.add("js");

  const icons = {
    arrowRight: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>',
    chevronDown: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>'
  };

  const hasEditableIcon = (element) =>
    Boolean(element?.querySelector("img, picture, svg, .wp-block-image"));

  const setIcon = (element, iconName) => {
    if (!element || !icons[iconName]) return;
    if (hasEditableIcon(element)) {
      element.removeAttribute("aria-hidden");
      return;
    }

    element.innerHTML = icons[iconName];
    element.setAttribute("aria-hidden", "true");
  };

  const enhanceInterfaceIcons = () => {
    document.querySelectorAll(".nav-dropdown > button span").forEach((element) => setIcon(element, "chevronDown"));

    document.querySelectorAll(".button span, .news-card a span").forEach((element) => {
      if (element.textContent.trim()) setIcon(element, "arrowRight");
    });
  };

  enhanceInterfaceIcons();

  const header = document.querySelector("[data-header], .site-header");
  const navToggle = document.querySelector("[data-nav-toggle]");
  const navMenu = document.querySelector("[data-nav-menu]");
  const dropdownButtons = document.querySelectorAll("[data-dropdown]");
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const setHeader = () => {
    if (header) header.classList.toggle("is-scrolled", window.scrollY > 8);
  };
  setHeader();
  window.addEventListener("scroll", setHeader, { passive: true });

  if (navToggle && navMenu) {
    const closeMenu = () => {
      navToggle.setAttribute("aria-expanded", "false");
      navMenu.classList.remove("is-open");
      document.body.classList.remove("menu-open");
    };

    navToggle.addEventListener("click", () => {
      const open = navToggle.getAttribute("aria-expanded") !== "true";
      navToggle.setAttribute("aria-expanded", String(open));
      navMenu.classList.toggle("is-open", open);
      document.body.classList.toggle("menu-open", open);
    });

    navMenu.addEventListener("click", (event) => {
      if (event.target.closest("a")) closeMenu();
    });

    window.addEventListener("keydown", (event) => {
      if (event.key === "Escape") closeMenu();
    });
  }

  dropdownButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const parent = button.closest(".nav-dropdown");
      if (parent) parent.classList.toggle("is-open");
    });
  });

  if (!reducedMotion && "IntersectionObserver" in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.14, rootMargin: "0px 0px -30px 0px" });

    document.querySelectorAll(".reveal").forEach((el, index) => {
      el.style.transitionDelay = `${Math.min(index % 4, 3) * 45}ms`;
      observer.observe(el);
    });
  } else {
    document.querySelectorAll(".reveal").forEach((el) => el.classList.add("is-visible"));
  }

  const filterTabs = document.querySelector("[data-filter-tabs]");
  const searchInput = document.querySelector("[data-news-search]");
  const newsCards = document.querySelectorAll("[data-category]");

  const filterNews = () => {
    if (!newsCards.length) return;
    const active = filterTabs ? filterTabs.querySelector(".active") : null;
    const category = active ? active.dataset.filter : "all";
    const query = searchInput ? searchInput.value.trim().toLowerCase() : "";

    newsCards.forEach((card) => {
      const text = card.textContent.toLowerCase();
      const categoryMatch = category === "all" || card.dataset.category === category;
      const searchMatch = !query || text.includes(query);
      card.classList.toggle("is-hidden", !(categoryMatch && searchMatch));
    });
  };

  if (filterTabs) {
    filterTabs.addEventListener("click", (event) => {
      const button = event.target.closest("button");
      if (!button) return;
      filterTabs.querySelectorAll("button").forEach((item) => item.classList.remove("active"));
      button.classList.add("active");
      filterNews();
    });
  }
  if (searchInput) searchInput.addEventListener("input", filterNews);

  const newsFilterBar = document.querySelector(".news-filter-bar");
  const wordpressNewsQuery = document.querySelector(".news-query");

  if (newsFilterBar && wordpressNewsQuery) {
    const categoryControls = newsFilterBar.querySelector(".news-category-controls");
    const newsSearchForm = newsFilterBar.querySelector("form.wp-block-search");
    const newsSearchInput = newsFilterBar.querySelector(".wp-block-search__input");
    const allNewsLink = newsFilterBar.querySelector(".news-all-link a");
    const isPolish = document.documentElement.lang.toLowerCase().startsWith("pl");
    const newsCopy = isPolish
      ? {
          loading: "Ładowanie aktualności...",
          error: "Nie udało się zaktualizować aktualności. Spróbuj ponownie.",
          count: (count) =>
            count === 1
              ? "Wyświetlono 1 aktualność."
              : `Wyświetlono ${count} aktualności.`
        }
      : {
          loading: "Loading news...",
          error: "News could not be updated. Please try again.",
          count: (count) =>
            count === 1 ? "1 news item shown." : `${count} news items shown.`
        };
    const newsStatus = document.createElement("p");
    let searchTimer;
    let requestController;

    newsStatus.className = "news-results-status";
    newsStatus.setAttribute("role", "status");
    newsStatus.setAttribute("aria-live", "polite");
    newsFilterBar.append(newsStatus);

    const categoryFromLink = (link) => {
      if (!link || link === allNewsLink) return "";
      const url = new URL(link.href, window.location.origin);
      const match = url.pathname.match(/\/category\/([^/]+)\/?$/);
      return match ? decodeURIComponent(match[1]) : "";
    };

    const stateFromUrl = () => {
      const params = new URLSearchParams(window.location.search);
      return {
        category: params.get("news_category") || "",
        search: params.get("news_search") || "",
        page: Math.max(1, Number.parseInt(params.get("news_page") || "1", 10) || 1)
      };
    };

    const buildNewsUrl = (state) => {
      const url = new URL(allNewsLink?.href || "/news/", window.location.origin);

      if (state.category) url.searchParams.set("news_category", state.category);
      if (state.search) url.searchParams.set("news_search", state.search);
      if (state.page > 1) url.searchParams.set("news_page", String(state.page));

      return url;
    };

    const updateNewsControls = (state, options = {}) => {
      const { syncSearch = true } = options;

      if (syncSearch && newsSearchInput && newsSearchInput.value !== state.search) {
        newsSearchInput.value = state.search;
      }

      categoryControls?.querySelectorAll("a").forEach((link) => {
        const isActive = categoryFromLink(link) === state.category;
        link.classList.toggle("is-active", isActive);
        if (isActive) link.setAttribute("aria-current", "page");
        else link.removeAttribute("aria-current");
      });
    };

    const updateNewsStatus = () => {
      const count = document.querySelectorAll(".news-query .wp-block-post").length;
      newsStatus.textContent = newsCopy.count(count);
    };

    const pageFromPaginationLink = (link) => {
      const url = new URL(link.href, window.location.origin);
      const pathMatch = url.pathname.match(/\/page\/(\d+)\/?$/);
      if (pathMatch) return Number.parseInt(pathMatch[1], 10);

      for (const [key, value] of url.searchParams.entries()) {
        if (/^query-\d+-page$/.test(key)) return Number.parseInt(value, 10) || 1;
      }

      return 1;
    };

    const loadNews = async (state, options = {}) => {
      const { historyMode = "push", focusResults = false } = options;
      const url = buildNewsUrl(state);

      requestController?.abort();
      const controller = new AbortController();
      requestController = controller;
      newsFilterBar.setAttribute("aria-busy", "true");
      document.querySelector(".news-query")?.classList.add("is-loading");
      newsStatus.textContent = newsCopy.loading;

      try {
        const response = await fetch(url, {
          headers: { "X-Requested-With": "XMLHttpRequest" },
          signal: controller.signal
        });

        if (!response.ok) throw new Error(`News request failed: ${response.status}`);

        const pageDocument = new DOMParser().parseFromString(await response.text(), "text/html");
        const nextQuery = pageDocument.querySelector(".news-query");
        const currentQuery = document.querySelector(".news-query");

        if (!nextQuery || !currentQuery) throw new Error("News results were not found.");

        nextQuery.classList.remove("is-loading");
        currentQuery.replaceWith(nextQuery);
        nextQuery.querySelectorAll(".reveal").forEach((item) => item.classList.add("is-visible"));

        if (historyMode === "push") window.history.pushState({ alutecoNews: true }, "", url);
        if (historyMode === "replace") window.history.replaceState({ alutecoNews: true }, "", url);

        updateNewsControls(state, { syncSearch: historyMode === "none" });
        updateNewsStatus();

        if (focusResults) {
          nextQuery.setAttribute("tabindex", "-1");
          nextQuery.focus({ preventScroll: true });
          nextQuery.scrollIntoView({ behavior: reducedMotion ? "auto" : "smooth", block: "start" });
        }
      } catch (error) {
        if (error?.name === "AbortError") return;
        newsStatus.textContent = newsCopy.error;
        document.querySelector(".news-query")?.classList.remove("is-loading");
      } finally {
        if (requestController === controller) {
          newsFilterBar.removeAttribute("aria-busy");
        }
      }
    };

    const isPlainClick = (event) =>
      event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;

    categoryControls?.addEventListener("click", (event) => {
      const link = event.target.closest("a");
      if (!link || !isPlainClick(event)) return;
      event.preventDefault();
      loadNews({
        category: categoryFromLink(link),
        search: newsSearchInput?.value.trim() || "",
        page: 1
      });
    });

    newsSearchForm?.addEventListener("submit", (event) => {
      event.preventDefault();
      loadNews({
        category: stateFromUrl().category,
        search: newsSearchInput?.value.trim() || "",
        page: 1
      });
    });

    newsSearchInput?.addEventListener("input", () => {
      window.clearTimeout(searchTimer);
      searchTimer = window.setTimeout(() => {
        loadNews(
          {
            category: stateFromUrl().category,
            search: newsSearchInput.value.trim(),
            page: 1
          },
          { historyMode: "replace" }
        );
      }, 350);
    });

    document.addEventListener("click", (event) => {
      const paginationLink = event.target.closest(".news-query .wp-block-query-pagination a");
      const cardCategoryLink = event.target.closest(".news-query .wp-block-post-terms a");

      if (paginationLink && isPlainClick(event)) {
        event.preventDefault();
        const currentState = stateFromUrl();
        loadNews(
          { ...currentState, page: pageFromPaginationLink(paginationLink) },
          { focusResults: true }
        );
      } else if (cardCategoryLink && isPlainClick(event)) {
        event.preventDefault();
        loadNews({
          category: categoryFromLink(cardCategoryLink),
          search: newsSearchInput?.value.trim() || "",
          page: 1
        });
      }
    });

    window.addEventListener("popstate", () => {
      loadNews(stateFromUrl(), { historyMode: "none" });
    });

    updateNewsControls(stateFromUrl());
    updateNewsStatus();
  }

  const contactForm = document.querySelector("[data-contact-form]");
  const formStatus = document.querySelector("[data-form-status]");
  if (contactForm && formStatus && contactForm.dataset.ajaxDemo === "true") {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault();
      const button = contactForm.querySelector("button[type='submit']");
      if (!button) return;
      const label = button.innerHTML;
      button.disabled = true;
      button.textContent = "Sending...";
      window.setTimeout(() => {
        button.disabled = false;
        button.innerHTML = label;
        formStatus.textContent = "Thank you. Your message is ready for review.";
        contactForm.reset();
      }, reducedMotion ? 80 : 650);
    });
  }
})();
