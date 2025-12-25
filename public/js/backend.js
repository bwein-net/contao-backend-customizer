(() => {
  // Injects/upserts small header elements (custom-title, env-title) into Contao backend layouts.
  // - Reads titles from <body data-bwein-header-title> and <body data-bwein-env-title>
  // - Works for normal backend header and for the login form layout
  // - Reacts to dynamic page swaps (MutationObserver + Turbo/Turbolinks/popstate)
  const HEADER_DATA_KEY = 'bweinHeaderTitle';
  const ENV_DATA_KEY = 'bweinEnvTitle';
  const HEADER_ANCHOR_SEL = '#header h1 a';
  const LOGIN_FORM_INNER_SEL = '#main .tl_login_form .formbody';

  const debounce = (fn, wait = 120) => {
    let t = null;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), wait);
    };
  };

  const createMarkedSpan = (cls, text) => {
    const el = document.createElement('span');
    el.className = cls;
    el.dataset.bwein = '1';
    el.textContent = text;
    return el;
  };

  // Create or update a marked span inside parent; optionally insert before `before` node.
  const upsert = (parent, cls, text, before = null) => {
    if (!parent) return null;
    const sel = `.${cls}[data-bwein="1"]`;
    const existing = parent.querySelector(sel);
    if (existing) {
      existing.textContent = text;
      return existing;
    }
    const el = createMarkedSpan(cls, text);
    if (before && parent.contains(before)) parent.insertBefore(el, before);
    else parent.appendChild(el);
    return el;
  };

  const removeIfExists = (parent, cls) => {
    if (!parent) return;
    const el = parent.querySelector(`.${cls}[data-bwein="1"]`);
    if (el) el.remove();
  };

  // Ensure a .app-title wrapper exists inside the header anchor.
  const ensureAppTitle = (anchor) => {
    if (!anchor) return;
    let app = anchor.querySelector('.app-title');
    if (!app) {
      app = document.createElement('span');
      app.className = 'app-title';
      while (anchor.firstChild) app.appendChild(anchor.firstChild);
      anchor.appendChild(app);
    }
  };

  const init = () => {
    const body = document.body;
    if (!body) return;

    const headerTitle = body.dataset[HEADER_DATA_KEY] ?? '';
    const envTitle = body.dataset[ENV_DATA_KEY] ?? '';

    const headerAnchor = document.querySelector(HEADER_ANCHOR_SEL);
    const loginFormInner = document.querySelector(LOGIN_FORM_INNER_SEL);
    const loginForm = loginFormInner?.parentElement ?? null;

    if (headerAnchor) {
      ensureAppTitle(headerAnchor);

      // Move previously created env-title into header if necessary
      const strayEnv = document.querySelector('.env-title[data-bwein="1"]');
      if (strayEnv && !headerAnchor.contains(strayEnv)) headerAnchor.appendChild(strayEnv);

      if (headerTitle) upsert(headerAnchor, 'custom-title', headerTitle);
      else removeIfExists(headerAnchor, 'custom-title');

      if (envTitle) upsert(headerAnchor, 'env-title', envTitle);
      else removeIfExists(headerAnchor, 'env-title');

      // remove empty custom-header if present
      const customHeader = document.querySelector('.custom-header[data-bwein="1"]');
      if (customHeader && customHeader.childElementCount === 0) customHeader.remove();
      return;
    }

    if (loginForm && loginFormInner) {
      let customHeader = document.querySelector('.custom-header[data-bwein="1"]');
      if (!customHeader) {
        customHeader = document.createElement('div');
        customHeader.className = 'custom-header';
        customHeader.dataset.bwein = '1';
        loginForm.insertBefore(customHeader, loginFormInner);
      } else if (!loginForm.contains(customHeader)) {
        loginForm.insertBefore(customHeader, loginFormInner);
      }

      if (headerTitle) upsert(customHeader, 'custom-title', headerTitle);
      else removeIfExists(customHeader, 'custom-title');

      if (envTitle) {
        const headline = loginFormInner.querySelector('h1');
        if (!upsert(loginFormInner, 'env-title', envTitle, headline || null)) {
          upsert(customHeader, 'env-title', envTitle);
        }
      } else {
        removeIfExists(loginFormInner, 'env-title');
        removeIfExists(customHeader, 'env-title');
      }
      return;
    }

    // No suitable target: remove any previously inserted artifacts
    const any = document.querySelector('.custom-title[data-bwein="1"], .env-title[data-bwein="1"], .custom-header[data-bwein="1"]');
    if (any) any.remove();
  };

  const debouncedInit = debounce(init, 100);

  // Observe DOM changes to react to Contao's AJAX/navigation swaps
  const observer = new MutationObserver(debouncedInit);
  const startObserver = () => {
    const root = document.body || document.documentElement;
    if (!root) return;
    try {
      observer.observe(root, { childList: true, subtree: true, attributes: true });
    } catch (e) {
      // nothing to do if observe fails
    }
  };

  const start = () => {
    init();
    startObserver();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start, { once: true });
  } else {
    start();
  }

  // React to SPA-style navigation events when available
  document.addEventListener('turbo:load', debouncedInit);
  document.addEventListener('turbolinks:load', debouncedInit);
  window.addEventListener('popstate', debouncedInit);
})();