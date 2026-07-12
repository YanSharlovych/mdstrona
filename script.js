(function () {
  const icons = {
    arrowRight: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>',
    badgeCheck: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 4.2 12 2l4.5 2.2 5 1.1-1.2 5 .7 5.1-4.6 2.2L12 22l-4.4-4.4L3 15.4l.7-5.1-1.2-5 5-1.1Z"/><path d="m8.5 12.2 2.3 2.3 4.9-5"/></svg>',
    bulb: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M8.2 14.7a6 6 0 1 1 7.6 0c-.7.5-.8 1.4-.8 2.3H9c0-.9-.1-1.8-.8-2.3Z"/></svg>',
    chevronDown: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>',
    compass: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 21h18"/><path d="m7 21 5-15 5 15"/><path d="M9 15h6"/><path d="M12 6V3"/></svg>',
    cpu: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="8" y="8" width="8" height="8" rx="2"/><path d="M4 10h3"/><path d="M4 14h3"/><path d="M17 10h3"/><path d="M17 14h3"/><path d="M10 4v3"/><path d="M14 4v3"/><path d="M10 17v3"/><path d="M14 17v3"/></svg>',
    door: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 21V4a1 1 0 0 1 1-1h10v18"/><path d="M16 21h3"/><path d="M9 12h.01"/></svg>',
    designTools: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 20 5.4-5.4"/><path d="m14.6 9.4 5.1-5.1a2.1 2.1 0 0 0-3-3l-5.1 5.1"/><path d="m8.2 3.2 12.6 12.6a2 2 0 0 1 0 2.8l-2.2 2.2a2 2 0 0 1-2.8 0L3.2 8.2a2 2 0 0 1 0-2.8l2.2-2.2a2 2 0 0 1 2.8 0Z"/><path d="m6.5 6.5 2-2"/><path d="m9.5 9.5 2-2"/><path d="m12.5 12.5 2-2"/></svg>',
    expand: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3H3v5"/><path d="m3 3 7 7"/><path d="M16 3h5v5"/><path d="m21 3-7 7"/><path d="M8 21H3v-5"/><path d="m3 21 7-7"/><path d="M16 21h5v-5"/><path d="m21 21-7-7"/></svg>',
    facebook: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h2V5h-2c-2.8 0-4 1.7-4 4v2H8v3h2v7h3v-7h3l.5-3H13V9c0-.7.3-1 1-1Z"/></svg>',
    grid: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
    gears: '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="10" r="3"/><path d="M9 3v2M9 15v2M2 10h2M14 10h2M4 5l1.5 1.5M12.5 13.5 14 15M14 5l-1.5 1.5M5.5 13.5 4 15"/><circle cx="17.5" cy="17" r="2"/><path d="M17.5 13.5v1M17.5 19.5v1M14 17h1M20 17h1M15 14.5l.8.8M19.2 18.8l.8.8M20 14.5l-.8.8M15.8 18.8l-.8.8"/></svg>',
    handshake: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m8.5 12.5 2.2-2.2a2 2 0 0 1 2.8 0l.7.7"/><path d="m14.2 11 1.3-1.3a2 2 0 0 1 2.8 0L21 12.4"/><path d="m3 12.4 2.7-2.7a2 2 0 0 1 2.8 0l.9.9"/><path d="m7 13 4.5 4.5a2.1 2.1 0 0 0 3 0l3.5-3.5"/><path d="m9.5 15.5 1.2-1.2"/><path d="m12 18 1.2-1.2"/></svg>',
    instagram: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="3.5"/><path d="M16.7 7.3h.01"/></svg>',
    leaf: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 4c-7.4.2-12.2 3-14.3 8.4C4.4 15.7 5.8 19 9.1 20.1c5.1 1.7 9.5-3.2 10.9-16.1Z"/><path d="M5 20c3.2-4.7 6.6-7.8 10.8-9.7"/></svg>',
    linkedin: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 10v9"/><path d="M6.5 6.5h.01"/><path d="M11 19v-9"/><path d="M11 14.2c0-2.6 1.4-4.2 3.6-4.2 2.4 0 3.9 1.6 3.9 4.4V19"/></svg>',
    lock: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v2"/></svg>',
    mail: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>',
    mapPin: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>',
    messages: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a7 7 0 0 1-7 7H8l-5 3 1.7-5A7 7 0 1 1 21 12Z"/><path d="M8 11h8"/><path d="M8 14h5"/></svg>',
    move: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2v20"/><path d="m8 6 4-4 4 4"/><path d="m8 18 4 4 4-4"/><path d="M2 12h20"/><path d="m6 8-4 4 4 4"/><path d="m18 8 4 4-4 4"/></svg>',
    panel: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="1"/><path d="M8 4v16"/><path d="M16 4v16"/></svg>',
    pencil: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 20 4.3-1 11-11a2.1 2.1 0 0 0-3-3l-11 11L4 20Z"/><path d="m14.8 6.5 3 3"/><path d="M4 20h7"/></svg>',
    phone: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7A2 2 0 0 1 22 16.9Z"/></svg>',
    puzzle: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3h5v4a2 2 0 1 0 4 0V3h4v7h-4a2 2 0 1 0 0 4h4v7h-7v-4a2 2 0 1 0-4 0v4H3v-7h4a2 2 0 1 0 0-4H3V3h5Z"/></svg>',
    ruler: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m16 2 6 6L8 22l-6-6L16 2Z"/><path d="m7 15 2 2"/><path d="m10 12 2 2"/><path d="m13 9 2 2"/><path d="m16 6 2 2"/></svg>',
    shield: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m8.5 12 2.2 2.2 4.8-5"/></svg>',
    sliders: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="11" cy="18" r="2"/></svg>',
    sparkles: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 9.8 8.8 4 11l5.8 2.2L12 19l2.2-5.8L20 11l-5.8-2.2L12 3Z"/><path d="M5 3v4"/><path d="M3 5h4"/><path d="M19 17v4"/><path d="M17 19h4"/></svg>',
    smartphoneSignal: '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="2" width="10" height="20" rx="2"/><path d="M8 5h2"/><circle cx="9" cy="18" r=".8"/><path d="M17 8a4 4 0 0 1 0 8"/><path d="M19.5 5.5a7.5 7.5 0 0 1 0 13"/></svg>',
    volumeOff: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 5 6 9H3v6h3l5 4V5Z"/><path d="m17 9 4 4"/><path d="m21 9-4 4"/></svg>',
    waves: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 8c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 4-2"/><path d="M3 14c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 4-2"/><path d="M3 20c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 4-2"/></svg>',
    wrench: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.7 6.3a4.5 4.5 0 0 0-5.8 5.8L3 18l3 3 5.9-5.9a4.5 4.5 0 0 0 5.8-5.8l-3 3-3-3 3-3Z"/></svg>'
  };

  const setIcon = (element, iconName) => {
    if (!element || !icons[iconName]) return;
    element.innerHTML = icons[iconName];
    element.setAttribute("aria-hidden", "true");
  };

  const featureIconFor = (text) => {
    const value = text.toLowerCase();
    if (value.includes("pivot") || value.includes("hinged")) return "door";
    if (value.includes("lock")) return "lock";
    if (value.includes("large") || value.includes("wide")) return "expand";
    if (value.includes("custom") || value.includes("finish")) return "pencil";
    if (value.includes("slim") || value.includes("profile") || value.includes("glazing") || value.includes("glass")) return "panel";
    if (value.includes("sound")) return "volumeOff";
    if (value.includes("flexible") || value.includes("configuration")) return "grid";
    if (value.includes("style")) return "sparkles";
    if (value.includes("construction") || value.includes("durability") || value.includes("quality")) return "shield";
    if (value.includes("hinge")) return "wrench";
    if (value.includes("pop-up") || value.includes("slide") || value.includes("smooth")) return "move";
    if (value.includes("thickness")) return "ruler";
    if (value.includes("iso") || value.includes("norm")) return "badgeCheck";
    if (value.includes("marine")) return "waves";
    return "badgeCheck";
  };

  const enhanceIcons = () => {
    const classIcons = {
      shield: "shield",
      smart: "smartphoneSignal",
      puzzle: "puzzle",
      leaf: "leaf",
      bulb: "bulb",
      handshake: "handshake",
      chat: "messages",
      tools: "designTools",
      gear: "gears",
      wrench: "wrench"
    };

    Object.entries(classIcons).forEach(([className, iconName]) => {
      document.querySelectorAll(`.icon.${className}, .work-icon.${className}`).forEach((element) => setIcon(element, iconName));
    });

    document.querySelectorAll(".feature-icons span").forEach((element) => {
      const iconName = featureIconFor(element.textContent);
      element.insertAdjacentHTML("afterbegin", icons[iconName]);
    });

    document.querySelectorAll(".round-icon").forEach((element) => {
      const label = element.textContent.trim().toLowerCase();
      const iconName = label.includes("phone") ? "phone" : label.includes("pin") || label.includes("location") ? "mapPin" : "mail";
      setIcon(element, iconName);
    });

    document.querySelectorAll(".nav-dropdown > button span").forEach((element) => setIcon(element, "chevronDown"));

    document.querySelectorAll(".button span, .news-card a span").forEach((element) => {
      if (element.textContent.trim()) setIcon(element, "arrowRight");
    });

    document.querySelectorAll(".socials a").forEach((link) => {
      const label = (link.getAttribute("aria-label") || link.textContent).trim().toLowerCase();
      const iconName = label.includes("facebook") || label === "f" ? "facebook" : label.includes("instagram") || label === "ig" ? "instagram" : "linkedin";
      link.setAttribute("aria-label", iconName === "facebook" ? "Facebook" : iconName === "instagram" ? "Instagram" : "LinkedIn");
      link.innerHTML = icons[iconName];
    });
  };

  enhanceIcons();

  const header = document.querySelector("[data-header]");
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

  const contactForm = document.querySelector("[data-contact-form]");
  const formStatus = document.querySelector("[data-form-status]");
  if (contactForm && formStatus) {
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
