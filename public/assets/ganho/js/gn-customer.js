/* Espace client Ganho : menu mobile, filtre des listes, suppression confirmée, annonces. Sans dépendance.
   Idempotent (navigation SPA) : tout est délégué sur document, jamais lié à un élément qui persiste. */
(function () {
  'use strict';
  if (window.__gcxInit) { return; }
  window.__gcxInit = true;
  var d = document;

  /* Menu latéral (mobile) */
  function setMenu(open) {
    var side = d.getElementById('gcx-side'), scrim = d.querySelector('.gcx-scrim'), burger = d.querySelector('.gcx-top__menu');
    if (!side) { return; }
    side.classList.toggle('is-open', open);
    if (scrim) { scrim.hidden = !open; }
    if (burger) { burger.setAttribute('aria-expanded', open ? 'true' : 'false'); }
    d.body.classList.toggle('gcx-lock', open);
    if (open) { var f = side.querySelector('a,button'); if (f) { f.focus(); } } else if (burger && d.activeElement && side.contains(d.activeElement)) { burger.focus(); }
  }
  d.addEventListener('click', function (e) {
    var t = e.target && e.target.closest ? e.target : null;
    if (!t) { return; }
    var side = d.getElementById('gcx-side');
    if (t.closest('[data-gcx-menu]')) { setMenu(!(side && side.classList.contains('is-open'))); return; }
    /* un clic sur un lien du menu referme le menu mobile (la navigation SPA ne recharge pas la page) */
    if (side && side.classList.contains('is-open') && t.closest('#gcx-side a[href]')) { setMenu(false); }
  });
  d.addEventListener('keydown', function (e) { var side = d.getElementById('gcx-side'); if (e.key === 'Escape' && side && side.classList.contains('is-open')) { setMenu(false); } });

  /* Filtre de liste */
  d.addEventListener('input', function (e) {
    var inp = e.target;
    if (!inp || !inp.hasAttribute || !inp.hasAttribute('data-gcx-filter')) { return; }
    var list = d.querySelector(inp.getAttribute('data-gcx-filter'));
    var none = d.querySelector('[data-gcx-nomatch]');
    if (!list) { return; }
    var q = inp.value.trim().toLowerCase(), n = 0;
    list.querySelectorAll('li[data-search]').forEach(function (li) {
      var ok = li.getAttribute('data-search').indexOf(q) !== -1;
      li.hidden = !ok; if (ok) { n++; }
    });
    if (none) { none.hidden = n > 0; }
  });

  /* Suppression confirmée (boîte de dialogue native) */
  var pending = null;
  d.addEventListener('click', function (e) {
    var b = e.target && e.target.closest ? e.target.closest('[data-gcx-del]') : null;
    var dlg = d.getElementById('gcx-del-dialog');
    if (!b || !dlg || typeof dlg.showModal !== 'function') { return; }
    pending = b;
    dlg.querySelector('[data-gcx-del-label]').textContent = b.getAttribute('data-gcx-del-name') || '';
    dlg.showModal();
  });
  /* l'événement « close » d'une boîte de dialogue ne remonte pas : on l'écoute en phase de capture */
  d.addEventListener('close', function (e) {
    var dlg = e.target;
    if (!dlg || dlg.id !== 'gcx-del-dialog') { return; }
    if (dlg.returnValue !== 'ok' || !pending) { pending = null; return; }
    var btn = pending, li = btn.closest('li'); pending = null;
    btn.disabled = true;
    fetch(btn.getAttribute('data-gcx-del'), { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (r) { if (!r.ok) { throw new Error('http'); } return r.json(); })
      .then(function () {
        if (li) { li.remove(); }
        if (window.__gnSpa && window.__gnSpa.go) { window.__gnSpa.go(location.href); } else { location.reload(); }
      })
      .catch(function () { btn.disabled = false; if (window.toastr) { toastr.error('La suppression a échoué. Réessayez dans un instant.'); } });
  }, true);

  /* Annonces : fermeture mémorisée 30 minutes (initialisation à chaque page) */
  function getCookie(n) { var m = d.cookie.match(new RegExp('(?:^|; )' + n + '=([^;]*)')); return m ? m[1] : ''; }
  function announces() {
    d.querySelectorAll('[data-gcx-announce]').forEach(function (a) {
      var key = 'gcx_ann_' + a.getAttribute('data-gcx-announce');
      if (getCookie(key)) { a.remove(); }
    });
  }
  d.addEventListener('click', function (e) {
    var c = e.target && e.target.closest ? e.target.closest('[data-gcx-close]') : null;
    var a = c && c.closest('[data-gcx-announce]');
    if (a) { d.cookie = 'gcx_ann_' + a.getAttribute('data-gcx-announce') + '=1;max-age=1800;path=/'; a.remove(); }
  });
  announces();
  window.addEventListener('gn:page-load', announces);
})();


/* Libellés restants injectés par des bibliothèques tierces (Dropzone 5, Summernote) */
(function () {
  'use strict';
  var map = { 'Drop files here to upload': 'Déposez vos fichiers ici ou cliquez pour les choisir', 'Select': 'Choisir', 'Project': 'Projet Summernote' };
  function fix(root) {
    root.querySelectorAll('.dz-button,.note-color-select,a[href*="summernote"]').forEach(function (el) {
      var t = el.textContent.trim();
      if (map[t]) { el.textContent = map[t]; }
    });
  }
  function run() { fix(document); new MutationObserver(function () { fix(document); }).observe(document.body, { childList: true, subtree: true }); }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', run); } else { run(); }
})();
