/* Ganho SPA : navigation sans rechargement de page (routeur maison, sans dépendance).
   Trois zones (meta gn-zone) : public, client, admin. Le contenu seul est remplacé ; en-tête, barres latérales, chat, SOS,
   invitation PWA et bandeau cookies restent vivants. Au moindre doute (autre zone, erreur HTTP, exception) : navigation classique.
   Désactiver : GN_SPA=false dans .env (php artisan config:clear), ou localStorage.gn_spa_off = '1' dans le navigateur. */
(function () {
  'use strict';
  if (window.__gnSpa) { return; }
  var D = document, W = window, root = D.documentElement;
  try { if (localStorage.getItem('gn_spa_off') === '1') { return; } } catch (e) {}

  function zoneOf(doc) { var m = doc.querySelector('meta[name="gn-zone"]'); return m ? (m.getAttribute('content') || '') : ''; }
  var ZONE = zoneOf(D);
  if (!ZONE || !W.fetch || !W.DOMParser || !W.URL || !W.AbortController || !history.pushState) { return; }
  var ZONES = {
    'public': { regions: ['#main', '#gn-flash'], sync: ['.gh-nav'], prefetch: true },
    'client': { regions: ['#gcx-main', '#gn-spa-modal'], sync: ['.gcx-side', '.gcx-top'], prefetch: false },
    'admin':  { regions: ['#gn-spa-region', '#gn-spa-modal'], sync: ['.app-header'], prefetch: false }
  };
  var CFG = ZONES[ZONE];
  if (!CFG) { return; }
  W.__gnSpa = { version: 1, zone: ZONE, navigations: 0, go: function (u) { return navigate(u, {}); } };

  /* ------------------------------------------------------------------ règles de périmètre */
  var DENY_LINK = /(logout|deconnexion|\/likes?\/|\/dislikes?\/|\/language\/|marquer|delete|destroy|supprim|download|telecharg|export|imageurl|imagedownload|\/storage\/|\/uploads\/)/i;
  var DENY_FORM = /(logout|deconnexion|login|connexion|inscription|register|password|mot-de-passe|oauth|export|download|telecharg)/i;
  var FILE_EXT = /\.(pdf|zip|rar|7z|docx?|xlsx?|pptx?|csv|png|jpe?g|gif|webp|svg|ico|mp4|mp3|wav|txt|xml|json|webmanifest)$/i;
  var RERUN = /\/assets\/ganho\/js\//;
  var NEVER_RERUN = /gn-(assistant|install|spa|libs-fr|admin|customer)\.js|ganho-home\.js|\/assets\/js\/custom\.js/;

  function zoneOfPath(p) {
    /* /admin et /customer sont d'anciennes adresses redirigées (301) vers /administration et /espace-client */
    if (/^\/(administration|admin)(\/|$)/.test(p)) { return 'admin'; }
    if (/^\/(espace-client|customer)(\/|$)/.test(p)) { return 'client'; }
    if (/^\/(installer|update|api|broadcasting|storage|uploads)(\/|$)/.test(p)) { return 'other'; }
    return 'public';
  }
  function classic(url) { W.location.assign(url); return Promise.resolve(false); }
  function key(src) { try { return new URL(src, location.href).pathname; } catch (e) { return src; } }

  /* ------------------------------------------------------------------ gestionnaires hérités : pas d'empilement */
  var origAdd = EventTarget.prototype.addEventListener;
  var origRemove = EventTarget.prototype.removeEventListener;
  var execCtx = { active: false, intervals: [] };
  var listReg = new WeakMap();
  function isGlobalTarget(t) { return t === D || t === W || t === root || (D.body && t === D.body); }
  EventTarget.prototype.addEventListener = function (type, listener, opts) {
    var t = this;
    try {
      if (typeof listener === 'function' && isGlobalTarget(t)) {
        if (execCtx.active && ((t === D && type === 'DOMContentLoaded') || (t === W && type === 'load'))) {
          setTimeout(function () { try { listener.call(t, new Event(type)); } catch (e) { console.error(e); } }, 0);
          return;
        }
        var src = Function.prototype.toString.call(listener);
        if (src.indexOf('[native code]') === -1) {
          var cap = (opts === true) || (opts && opts.capture === true);
          var k = type + '|' + cap + '|' + src;
          var m = listReg.get(t); if (!m) { m = {}; listReg.set(t, m); }
          if (m[k]) { origRemove.call(t, type, m[k].l, m[k].o); }
          m[k] = { l: listener, o: opts };
        }
      }
    } catch (e) {}
    return origAdd.call(this, type, listener, opts);
  };
  var origSetInterval = W.setInterval;
  W.setInterval = function () { var id = origSetInterval.apply(W, arguments); if (execCtx.active) { execCtx.intervals.push(id); } return id; };

  function patchJQuery($) {
    if (!$ || !$.fn || $.fn.__gnSpa) { return; }
    $.fn.__gnSpa = true;
    var origOn = $.fn.on, origOff = $.fn.off, reg = new WeakMap();
    $.fn.on = function (types, selector, data, fn, one) {
      if (one || typeof types !== 'string') { return origOn.apply(this, arguments); }
      var sel = selector, d = data, f = fn;
      if (d == null && f == null) { f = selector; d = undefined; sel = undefined; }
      else if (f == null) { if (typeof selector === 'string') { f = data; d = undefined; } else { f = data; d = selector; sel = undefined; } }
      if (typeof f !== 'function') { return origOn.apply(this, arguments); }
      var src = Function.prototype.toString.call(f);
      if (src.indexOf('[native code]') !== -1) { return origOn.apply(this, arguments); }
      var k = types + '|' + (sel || '') + '|' + src;
      this.each(function () {
        var el = this;
        if (isGlobalTarget(el)) {
          var m = reg.get(el); if (!m) { m = {}; reg.set(el, m); }
          if (m[k]) { origOff.call($(el), m[k].t, m[k].s, m[k].f); }
          m[k] = { t: types, s: sel, f: f };
        }
        origOn.call($(el), types, sel, d, f);
      });
      return this;
    };
  }
  (function hookJQuery() {
    if (W.jQuery) { patchJQuery(W.jQuery); return; }
    var holder;
    try {
      Object.defineProperty(W, 'jQuery', {
        configurable: true, enumerable: true,
        get: function () { return holder; },
        set: function (v) { holder = v; patchJQuery(v); }
      });
    } catch (e) {}
  })();

  /* ------------------------------------------------------------------ interface : barre de progression, annonce vocale */
  var bar, live, barTimer, barW = 0;
  function ui() {
    if (bar) { return; }
    var st = D.createElement('style');
    st.textContent = '#gn-spa-bar{position:fixed;left:0;top:0;height:3px;width:0;z-index:2147483000;background:linear-gradient(90deg,#5B2BB8,#2B33C9,#0A66E8,#0F8B6D,#3F9A1A,#FFBE05,#F66100,#E8381A);opacity:0;transition:width .25s ease,opacity .25s ease;pointer-events:none}' +
      '#gn-spa-live{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}' +
      '@media (prefers-reduced-motion:reduce){#gn-spa-bar{transition:none}}';
    D.head.appendChild(st);
    bar = D.createElement('div'); bar.id = 'gn-spa-bar'; bar.setAttribute('aria-hidden', 'true'); D.body.appendChild(bar);
    live = D.createElement('div'); live.id = 'gn-spa-live'; live.setAttribute('aria-live', 'polite'); live.setAttribute('role', 'status'); D.body.appendChild(live);
  }
  function barStart() {
    ui(); clearInterval(barTimer); barW = 8; bar.style.opacity = '1'; bar.style.width = '8%';
    barTimer = origSetInterval(function () { barW += (90 - barW) * 0.12; bar.style.width = barW + '%'; }, 200);
  }
  function barEnd() {
    if (!bar) { return; }
    clearInterval(barTimer); bar.style.width = '100%';
    setTimeout(function () { bar.style.opacity = '0'; setTimeout(function () { bar.style.width = '0'; }, 260); }, 180);
  }

  /* ------------------------------------------------------------------ marqueurs <!--gn:name--> ... <!--/gn:name--> */
  function between(doc, name) {
    var out = [], on = false, w = doc.createTreeWalker(doc.documentElement, NodeFilter.SHOW_ALL, null), n;
    while ((n = w.nextNode())) {
      if (n.nodeType === 8) {
        if (n.data.trim() === 'gn:' + name) { on = true; }
        else if (n.data.trim() === '/gn:' + name) { on = false; }
      } else if (on && n.nodeType === 1 && /^(SCRIPT|LINK|STYLE)$/.test(n.tagName)) { out.push(n); }
    }
    return out;
  }
  function hash(s) { var h = 5381, i = s.length; while (i) { h = (h * 33) ^ s.charCodeAt(--i); } return (h >>> 0).toString(36); }
  function nodeKey(n) { return n.tagName === 'LINK' ? 'L' + key(n.getAttribute('href') || '') : (n.tagName === 'SCRIPT' ? 'S' + (n.getAttribute('src') ? key(n.getAttribute('src')) : hash(n.textContent)) : 'Y' + hash(n.textContent)); }

  var loadedScripts = null, currentUrl = location.href.split('#')[0], lastBody = '', navId = 0, ctl = null, reinitN = 0;
  function initState() {
    if (loadedScripts) { return; }
    loadedScripts = {};
    [].forEach.call(D.scripts, function (s) { if (s.src) { loadedScripts[key(s.src)] = 1; } });
    between(D, 'page-head').forEach(function (n) { n.setAttribute('data-gn-page', '1'); });
    between(D, 'page-scripts').forEach(function (n) { n.setAttribute('data-gn-pagescript', '1'); });
    lastBody = D.body.className;
  }

  /* ------------------------------------------------------------------ chargement des pages */
  var cache = {};
  function fetchPage(url, init) {
    init = init || {}; init.credentials = 'same-origin'; init.redirect = 'follow';
    init.headers = Object.assign({ 'X-GN-SPA': '1', 'Accept': 'text/html,application/xhtml+xml' }, init.headers || {});
    return fetch(url, init).then(function (res) {
      var ct = res.headers.get('Content-Type') || '';
      return res.text().then(function (text) { return { res: res, text: text, html: /text\/html/.test(ct), url: res.url || url }; });
    });
  }
  function getPage(u, signal) {
    var k = u.href.split('#')[0], c = cache[k];
    if (c && Date.now() - c.t < 30000) { delete cache[k]; return c.p; }
    return fetchPage(u.href, { signal: signal });
  }
  function prefetch(u) {
    var k = u.href.split('#')[0];
    if (cache[k] && Date.now() - cache[k].t < 30000) { return; }
    var keys = Object.keys(cache); if (keys.length > 20) { delete cache[keys[0]]; }
    cache[k] = { t: Date.now(), p: fetchPage(u.href).catch(function () { delete cache[k]; return { res: { ok: false }, text: '', html: false, url: k }; }) };
  }

  function loadScript(attrs, text) {
    return new Promise(function (resolve) {
      var s = D.createElement('script');
      [].forEach.call(attrs, function (a) { if (a.name !== 'defer' && a.name !== 'async') { s.setAttribute(a.name, a.value); } });
      s.async = false;
      if (s.src) { s.onload = s.onerror = function () { s.remove(); resolve(); }; D.body.appendChild(s); }
      else {
        /* un script en ligne rejoué peut redéclarer un const/let global : erreur de syntaxe AVANT toute exécution,
           on le rejoue alors avec var (sans effet de bord puisque rien n'a tourné) */
        if (attrs && attrs.length) { [].forEach.call(attrs, function (a) { if (a.name === 'type') { s.setAttribute('type', a.value); } }); }
        var redeclared = false;
        function onErr(ev) { if (/has already been declared/.test(ev.message || '')) { redeclared = true; ev.preventDefault(); } }
        origAdd.call(W, 'error', onErr);
        s.textContent = text; D.body.appendChild(s); D.body.removeChild(s);
        origRemove.call(W, 'error', onErr);
        if (redeclared) {
          var s2 = D.createElement('script');
          s2.textContent = text.replace(/(^|[\n;{}])(\s*)(const|let)(\s+[A-Za-z_$])/g, '$1$2var$4');
          D.body.appendChild(s2); D.body.removeChild(s2);
        }
        resolve();
      }
    });
  }
  function runScripts(list) {
    execCtx.active = true;
    var p = Promise.resolve();
    list.forEach(function (n) {
      p = p.then(function () { return Promise.resolve().then(function () {
        var src = n.getAttribute('src');
        if (src) {
          var k = key(src);
          var again = RERUN.test(k) && !NEVER_RERUN.test(k);
          if (loadedScripts[k] && !again) { return; }
          loadedScripts[k] = 1;
          var attrs = [].slice.call(n.attributes);
          if (again) { attrs = attrs.map(function (a) { return a.name === 'src' ? { name: 'src', value: a.value + (a.value.indexOf('?') > -1 ? '&' : '?') + 'gnre=' + (++reinitN) } : a; }); }
          return loadScript(attrs);
        }
        if (n.type && !/javascript|module|^$/.test(n.type)) { return; }
        return loadScript(n.type === 'module' ? [{ name: 'type', value: 'module' }] : [], n.textContent);
      }).catch(function (e) { console.warn('[gn-spa] script de page en erreur :', e); }); });
    });
    return p.then(function () { return new Promise(function (r) { setTimeout(r, 0); }); }, function () {}).then(function () { execCtx.active = false; });
  }

  function cleanupBeforeSwap() {
    execCtx.intervals.forEach(function (id) { clearInterval(id); }); execCtx.intervals = [];
    [].forEach.call(D.querySelectorAll('.modal.show'), function (m) { try { var inst = W.bootstrap && W.bootstrap.Modal.getInstance(m); if (inst) { inst.hide(); } } catch (e) {} });
    [].forEach.call(D.querySelectorAll('.modal-backdrop, body > .select2-container, body > .select2-dropdown, .tooltip, .popover, .note-popover, .daterangepicker'), function (n) { n.remove(); });
    D.body.classList.remove('modal-open'); D.body.style.removeProperty('overflow'); D.body.style.removeProperty('padding-right');
  }

  function syncTree(o, n) {
    var oc = o.children, nc = n.children;
    if (oc.length !== nc.length || o.tagName !== n.tagName) { o.innerHTML = n.innerHTML; return; }
    ['class', 'aria-current', 'aria-label', 'src', 'title'].forEach(function (a) {
      var v = n.getAttribute(a);
      if (v === null) { if (o.hasAttribute(a) && a !== 'class') { o.removeAttribute(a); } } else if (o.getAttribute(a) !== v) { o.setAttribute(a, v); }
    });
    if (!nc.length) { if (o.textContent !== n.textContent) { o.textContent = n.textContent; } return; }
    for (var i = 0; i < nc.length; i++) { syncTree(oc[i], nc[i]); }
  }
  function syncMeta(doc) {
    var sel = 'meta[name="description"],meta[name="robots"],link[rel="canonical"],meta[property^="og:"],meta[name^="twitter:"],script[type="application/ld+json"]';
    [].forEach.call(D.head.querySelectorAll(sel), function (n) { n.remove(); });
    [].forEach.call(doc.head.querySelectorAll(sel), function (n) {
      var c = D.createElement(n.tagName.toLowerCase());
      [].forEach.call(n.attributes, function (a) { c.setAttribute(a.name, a.value); });
      if (n.tagName === 'SCRIPT') { c.textContent = n.textContent; }
      D.head.appendChild(c);
    });
  }
  function syncBodyClass(doc) {
    var prev = lastBody.split(/\s+/).filter(Boolean), next = doc.body.className.split(/\s+/).filter(Boolean);
    prev.forEach(function (c) { if (next.indexOf(c) === -1) { D.body.classList.remove(c); } });
    next.forEach(function (c) { if (prev.indexOf(c) === -1) { D.body.classList.add(c); } });
    lastBody = doc.body.className;
  }

  /* feuilles de style propres à la page : ajoutées avant l'échange, anciennes retirées après */
  function prepHead(doc) {
    var wanted = between(doc, 'page-head'), wantedKeys = {}, waits = [];
    wanted.forEach(function (n) { wantedKeys[nodeKey(n)] = n; });
    var existing = {}; [].forEach.call(D.head.querySelectorAll('link[rel="stylesheet"],style'), function (n) { existing[nodeKey(n)] = n; });
    wanted.forEach(function (n) {
      var k = nodeKey(n);
      if (existing[k]) { existing[k].setAttribute('data-gn-keep', '1'); return; }
      if (n.tagName === 'SCRIPT') { return; }
      var c = D.createElement(n.tagName.toLowerCase());
      [].forEach.call(n.attributes, function (a) { c.setAttribute(a.name, a.value); });
      if (n.tagName === 'STYLE') { c.textContent = n.textContent; }
      c.setAttribute('data-gn-page', '1'); c.setAttribute('data-gn-keep', '1');
      if (n.tagName === 'LINK') { waits.push(new Promise(function (r) { c.onload = c.onerror = r; setTimeout(r, 3000); })); }
      D.head.appendChild(c);
    });
    return Promise.all(waits);
  }
  function pruneHead() {
    [].forEach.call(D.head.querySelectorAll('[data-gn-page]'), function (n) { if (n.getAttribute('data-gn-keep')) { n.removeAttribute('data-gn-keep'); } else { n.remove(); } });
    [].forEach.call(D.head.querySelectorAll('[data-gn-keep]'), function (n) { n.removeAttribute('data-gn-keep'); });
  }


  /* menu admin (Spruko) : sidemenu.js ne marque l'entrée active qu'au chargement initial, par égalité stricte d'URL.
     On recalcule après chaque navigation, en tenant compte des anciennes adresses /admin -> /administration. */
  function adminActive() {
    var sb = D.querySelector('.app-sidebar');
    if (!sb) { return; }
    [].forEach.call(sb.querySelectorAll('.active,.is-expanded,.open'), function (e) { e.classList.remove('active', 'is-expanded', 'open'); });
    var cur = location.pathname.replace(/\/$/, ''), found = null;
    [].some.call(sb.querySelectorAll('li a[href]'), function (a) {
      var raw = a.getAttribute('href');
      if (!raw || raw.charAt(0) === '#' || /^javascript:/i.test(raw)) { return false; }
      var u; try { u = new URL(raw, location.href); } catch (e) { return false; }
      var p = u.pathname.replace(/\/$/, '');
      if (p === cur || p.replace(/^\/admin(?=\/|$)/, '/administration') === cur) { found = a; return true; }
      return false;
    });
    if (!found) { return; }
    var li = found.parentElement; found.classList.add('active');
    if (li) { li.classList.add('is-expanded'); }
    var ul = li && li.parentElement;
    if (ul && ul !== sb && ul.tagName === 'UL') {
      ul.classList.add('open');
      if (ul.previousElementSibling) { ul.previousElementSibling.classList.add('active', 'is-expanded'); }
      if (ul.parentElement) { ul.parentElement.classList.add('is-expanded'); }
    }
  }

  /* contenu neuf : ce que custom.js (Spruko) ne fait qu'au chargement initial */
  function initContent() {
    if (ZONE === 'admin') { adminActive(); }
    var regs = CFG.regions.map(function (sel) { return D.querySelector(sel); }).filter(Boolean);
    regs.forEach(function (r) {
      try {
        if (W.bootstrap) {
          [].forEach.call(r.querySelectorAll('[data-bs-toggle="tooltip"]'), function (el) { if (W.bootstrap.Tooltip) { W.bootstrap.Tooltip.getOrCreateInstance(el); } });
          [].forEach.call(r.querySelectorAll('[data-bs-toggle="popover"]'), function (el) { if (W.bootstrap.Popover) { W.bootstrap.Popover.getOrCreateInstance(el); } });
        }
        [].forEach.call(r.querySelectorAll('.cover-image[data-bs-image-src]'), function (el) { el.style.background = 'url(' + el.getAttribute('data-bs-image-src') + ') center center'; });
      } catch (e) {}
    });
  }

  function announce(title) { ui(); live.textContent = ''; setTimeout(function () { live.textContent = 'Page chargée : ' + title; }, 60); }
  function focusTop() {
    var r = D.querySelector(CFG.regions[0]); if (!r) { return; }
    var t = r.querySelector('h1') || r;
    if (!t.hasAttribute('tabindex')) { t.setAttribute('tabindex', '-1'); t.addEventListener('blur', function f() { t.removeAttribute('tabindex'); t.removeEventListener('blur', f); }); }
    try { t.focus({ preventScroll: true }); } catch (e) {}
  }

  function render(pg, o) {
    var doc = new DOMParser().parseFromString(pg.text, 'text/html');
    var primary = CFG.regions[0];
    if (zoneOf(doc) !== ZONE || !doc.querySelector(primary) || !D.querySelector(primary)) { return classic(pg.url); }
    var finalU = new URL(pg.url, location.href);
    if (zoneOfPath(finalU.pathname) !== ZONE) { return classic(pg.url); }
    initState();
    var regionScripts = [];
    cleanupBeforeSwap();
    return prepHead(doc).then(function () {
      if (o.push !== false) {
        try { history.replaceState(Object.assign({}, history.state, { gn: 1, gnScroll: [W.scrollX, W.scrollY] }), ''); } catch (e) {}
        history.pushState({ gn: 1 }, '', pg.url);
      }
      currentUrl = pg.url.split('#')[0];
      function applySync() {
        D.title = doc.title;
        syncMeta(doc);
        CFG.regions.forEach(function (sel) {
          var a = D.querySelector(sel), b = doc.querySelector(sel);
          if (a && b) { a.innerHTML = b.innerHTML; [].forEach.call(b.querySelectorAll('script'), function (s) { regionScripts.push(s); }); }
        });
        CFG.sync.forEach(function (sel) { var a = D.querySelector(sel), b = doc.querySelector(sel); if (a && b) { syncTree(a, b); } });
        syncBodyClass(doc);
        pruneHead();
      }
      var reduced = W.matchMedia && W.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (D.startViewTransition && !reduced) {
        var vt = D.startViewTransition(applySync);
        return vt.updateCallbackDone.catch(function () {});
      }
      applySync();
    }).then(function () {
      var scripts = [];
      /* les scripts de la région remplacée (clones : ceux insérés par innerHTML sont inertes) */
      regionScripts.forEach(function (s) { scripts.push(s); });
      between(doc, 'page-scripts').forEach(function (s) { if (s.tagName === 'SCRIPT') { scripts.push(s); } });
      /* les scripts de base (menu, SOS, DataTables FR, a11y, custom.js…) ne sont JAMAIS rejoués : ils s'attachent à des éléments qui persistent */
      var seen = {};
      /* un même script ne tourne qu'une fois par navigation */
      var uniq = [], dup = {};
      scripts.forEach(function (s) { var k = s.getAttribute('src') ? 'src:' + key(s.getAttribute('src')) : 'inl:' + hash(s.textContent); if (!dup[k]) { dup[k] = 1; uniq.push(s); } });
      return runScripts(uniq);
    }).then(function () {
      initContent();
      if (W.Dropzone && W.Dropzone.discover) { try { W.Dropzone.discover(); } catch (e) {} }
      W.dispatchEvent(new CustomEvent('gn:page-load', { detail: { url: pg.url } }));
      W.__gnSpa.navigations++;
      var h = finalU.hash;
      if (o.scroll) { W.scrollTo(o.scroll[0], o.scroll[1]); }
      else if (h && D.getElementById(decodeURIComponent(h.slice(1)))) { D.getElementById(decodeURIComponent(h.slice(1))).scrollIntoView(); }
      else { W.scrollTo(0, 0); }
      focusTop(); announce(D.title);
      return true;
    });
  }

  function navigate(url, o) {
    o = o || {};
    var u;
    try { u = new URL(url, location.href); } catch (e) { return classic(url); }
    if (u.origin !== location.origin || zoneOfPath(u.pathname) !== ZONE) { return classic(u.href); }
    if (ctl) { ctl.abort(); }
    ctl = new AbortController();
    var me = ++navId, sig = ctl.signal;
    barStart();
    return getPage(u, sig).then(function (pg) {
      if (me !== navId) { return false; }
      if (!pg.res.ok || !pg.html) { return classic(u.href); }
      return render(pg, o);
    }).then(function (r) { if (me === navId) { barEnd(); } return r; }, function (err) {
      if (err && err.name === 'AbortError') { return false; }
      console.warn('[gn-spa] repli en navigation classique :', err);
      return classic(u.href);
    });
  }

  function submitForm(f, sub, action, method) {
    var fd = new FormData(f);
    if (sub && sub.name) { fd.append(sub.name, sub.value); }
    if (method === 'GET') {
      var p = new URLSearchParams(); fd.forEach(function (v, k) { if (typeof v === 'string') { p.append(k, v); } });
      action.search = p.toString();
      return navigate(action.href, {});
    }
    if (ctl) { ctl.abort(); }
    ctl = new AbortController();
    var me = ++navId;
    barStart();
    return fetchPage(action.href, { method: 'POST', body: fd, signal: ctl.signal }).then(function (pg) {
      if (me !== navId) { return false; }
      if (pg.res.status === 419 || pg.res.status === 0) { f.__gnBypass = true; barEnd(); f.submit(); return false; }
      if (!pg.html) { barEnd(); return pg.res.ok ? navigate(currentUrl, { push: false }) : (f.__gnBypass = true, f.submit(), false); }
      var doc = new DOMParser().parseFromString(pg.text, 'text/html');
      if (!pg.res.ok || zoneOf(doc) !== ZONE) {
        /* page d'erreur ou autre zone : affichage tel quel */
        if (!pg.res.ok && !zoneOf(doc)) { D.open(); D.write(pg.text); D.close(); barEnd(); return false; }
        if (zoneOf(doc) !== ZONE) { return classic(pg.url); }
      }
      return render(pg, {});
    }).then(function (r) { if (me === navId) { barEnd(); } return r; }, function (err) {
      if (err && err.name === 'AbortError') { return false; }
      console.warn('[gn-spa] envoi classique du formulaire :', err);
      f.__gnBypass = true; barEnd(); f.submit(); return false;
    });
  }

  /* ------------------------------------------------------------------ écouteurs */
  function eligibleLink(a, e) {
    if (!a || !a.getAttribute) { return null; }
    var href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#' || /^(mailto:|tel:|javascript:|whatsapp:|sms:)/i.test(href)) { return null; }
    if (a.hasAttribute('download') || a.hasAttribute('data-no-spa') || a.hasAttribute('data-bs-toggle') || a.hasAttribute('data-toggle')) { return null; }
    if ((a.target && a.target !== '_self') || /(^|\s)external(\s|$)/.test(a.rel || '')) { return null; }
    if (e && (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey)) { return null; }
    var u; try { u = new URL(a.href, location.href); } catch (er) { return null; }
    if (u.origin !== location.origin || zoneOfPath(u.pathname) !== ZONE) { return null; }
    if (u.pathname === location.pathname && u.search === location.search && u.hash) { return null; }
    if (DENY_LINK.test(u.pathname + u.search) || FILE_EXT.test(u.pathname)) { return null; }
    return u;
  }
  W.addEventListener('click', function (e) {
    if (e.defaultPrevented) { return; }
    var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
    var u = eligibleLink(a, e);
    if (!u) { return; }
    e.preventDefault();
    navigate(u.href, {});
  }, false);

  W.addEventListener('submit', function (e) {
    if (e.defaultPrevented) { return; }
    var f = e.target;
    if (!(f instanceof HTMLFormElement) || f.hasAttribute('data-no-spa') || f.__gnBypass) { return; }
    var sub = e.submitter;
    var target = (sub && sub.getAttribute('formtarget')) || f.getAttribute('target');
    if (target && target !== '_self') { return; }
    var action;
    try { action = new URL((sub && sub.getAttribute('formaction')) || f.getAttribute('action') || location.href, location.href); } catch (er) { return; }
    var method = ((sub && sub.getAttribute('formmethod')) || f.getAttribute('method') || 'get').toUpperCase();
    if (action.origin !== location.origin || zoneOfPath(action.pathname) !== ZONE || DENY_FORM.test(action.pathname)) { return; }
    if (f.querySelector('input[type=file]') && f.enctype !== 'multipart/form-data' && method !== 'GET') { /* envoi de fichiers : multipart géré par FormData */ }
    e.preventDefault();
    submitForm(f, sub, action, method);
  }, false);

  W.addEventListener('popstate', function (e) {
    var href = location.href.split('#')[0];
    if (href === currentUrl) { return; }
    if (zoneOfPath(location.pathname) !== ZONE) { W.location.reload(); return; }
    navigate(location.href, { push: false, scroll: e.state && e.state.gnScroll });
  });

  if (CFG.prefetch) {
    var hoverT;
    D.addEventListener('mouseover', function (e) {
      var a = e.target && e.target.closest ? e.target.closest('a[href]') : null, u = eligibleLink(a, null);
      clearTimeout(hoverT);
      if (u) { hoverT = setTimeout(function () { prefetch(u); }, 80); }
    }, { passive: true });
    D.addEventListener('touchstart', function (e) {
      var a = e.target && e.target.closest ? e.target.closest('a[href]') : null, u = eligibleLink(a, null);
      if (u) { prefetch(u); }
    }, { passive: true });
  }

  function boot() { initState(); ui(); try { history.replaceState(Object.assign({}, history.state, { gn: 1 }), ''); } catch (e) {} }
  if (D.readyState === 'loading') { D.addEventListener('DOMContentLoaded', boot); } else { boot(); }
})();
