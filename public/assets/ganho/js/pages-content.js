/* Ganho — pages de contenu : recherche d'articles (POST /search) et avis « utile / pas utile ». Sans dépendance. */
(function () {
  'use strict';
  var meta = document.querySelector('meta[name="csrf-token"]');
  var token = meta ? meta.getAttribute('content') : '';

  /* ---- Recherche ---- */
  var box = document.getElementById('gc-search');
  if (box) {
    var input = box.querySelector('input[type="search"]');
    var list = box.querySelector('.gc-search__list');
    var form = box.querySelector('form');
    var timer = null, seq = 0;
    var searchUrl = box.getAttribute('data-url');
    var hide = function () { list.hidden = true; list.innerHTML = ''; input.setAttribute('aria-expanded', 'false'); };
    var run = function () {
      var q = input.value.trim();
      if (q.length < 2) { hide(); return; }
      var mine = ++seq;
      var body = new FormData();
      body.append('data', q);
      body.append('_token', token);
      fetch(searchUrl, { method: 'POST', body: body, credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } })
        .then(function (r) { return r.ok ? r.json() : ''; })
        .then(function (html) {
          if (mine !== seq || typeof html !== 'string' || !html) { return; }
          list.innerHTML = html;
          var empty = list.querySelector('a[href="#"]');
          if (empty) { empty.outerHTML = '<span class="gn-hint" style="display:block;padding:12px 16px">Aucun article ne correspond à votre recherche.</span>'; }
          list.removeAttribute('style');
          list.hidden = false;
          input.setAttribute('aria-expanded', 'true');
        })
        .catch(function () {});
    };
    input.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(run, 250); });
    form.addEventListener('submit', function (e) { e.preventDefault(); clearTimeout(timer); run(); });
    document.addEventListener('click', function (e) { if (!box.contains(e.target)) { hide(); } });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !list.hidden) { hide(); input.focus(); } });
  }

  /* ---- Avis sur l'article (connecté) ---- */
  var rate = document.getElementById('gc-rate');
  if (rate) {
    var msg = rate.querySelector('[role="status"]');
    Array.prototype.forEach.call(rate.querySelectorAll('button[data-value]'), function (btn) {
      btn.addEventListener('click', function () {
        var body = new FormData();
        body.append('dataname', rate.getAttribute('data-article'));
        body.append('datavalue', btn.getAttribute('data-value'));
        body.append('_token', token);
        btn.disabled = true;
        fetch(rate.getAttribute('data-url'), { method: 'POST', body: body, credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (d) {
            if (d && d.data === 'success') { window.location.reload(); return; }
            btn.disabled = false;
            if (msg) { msg.textContent = 'Votre avis n’a pas pu être enregistré. Réessayez plus tard.'; }
          })
          .catch(function () {
            btn.disabled = false;
            if (msg) { msg.textContent = 'Votre avis n’a pas pu être enregistré. Réessayez plus tard.'; }
          });
      });
    });
  }
})();

/* Copier le lien de l'article */
(function () {
  document.querySelectorAll('[data-copy-link]').forEach(function (b) {
    b.addEventListener('click', function () {
      var url = (b.closest('.gc-share') || {}).dataset ? b.closest('.gc-share').dataset.url : location.href;
      var done = function () { var t = b.textContent; b.textContent = 'Lien copié'; setTimeout(function () { b.textContent = t; }, 2000); };
      if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(url).then(done, function () {}); }
    });
  });
})();
