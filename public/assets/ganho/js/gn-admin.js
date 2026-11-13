/* Administration GANXÔ : langue française par défaut pour DataTables (appliquée dès que le plugin se charge,
   donc avant les initialisations des pages) + petites aides d'interface. */
(function ($) {
  if (!$) { return; }
  var FR = {
    emptyTable: 'Aucune donnée disponible dans le tableau',
    info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
    infoEmpty: 'Affichage de 0 à 0 sur 0 entrée',
    infoFiltered: '(filtré à partir de _MAX_ entrées au total)',
    lengthMenu: 'Afficher _MENU_ entrées',
    loadingRecords: 'Chargement…',
    processing: 'Chargement…',
    search: 'Rechercher :',
    searchPlaceholder: 'Rechercher…',
    zeroRecords: 'Aucun résultat correspondant',
    paginate: { first: 'Premier', previous: 'Précédent', next: 'Suivant', last: 'Dernier' },
    aria: { sortAscending: ' : activer pour trier par ordre croissant', sortDescending: ' : activer pour trier par ordre décroissant' }
  };
  var dt;
  try {
    Object.defineProperty($.fn, 'dataTable', {
      configurable: true,
      get: function () { return dt; },
      set: function (v) {
        dt = v;
        if (v && v.defaults) { $.extend(true, v.defaults, { language: FR }); }
      }
    });
  } catch (e) { /* navigateur ancien : on garde l'anglais plutôt que de casser la page */ }
})(window.jQuery);

/* Recherche globale de l'administration : combobox ARIA (↑ ↓ Entrée Échap, « / » pour focaliser). */
(function () {
  var root = document.querySelector('[data-gn-asearch]');
  if (!root) { return; }
  var input = root.querySelector('.gn-asearch__input');
  var list = root.querySelector('.gn-asearch__list');
  var status = root.querySelector('[data-gn-asearch-status]');
  var toggle = root.querySelector('[data-gn-asearch-toggle]');
  var url = root.getAttribute('data-url');
  var timer = null, ctrl = null, opts = [], active = -1, last = '';

  function el(tag, cls, text) { var e = document.createElement(tag); if (cls) { e.className = cls; } if (text != null) { e.textContent = text; } return e; }
  function close() { list.hidden = true; input.setAttribute('aria-expanded', 'false'); input.removeAttribute('aria-activedescendant'); active = -1; }
  function open() { list.hidden = false; input.setAttribute('aria-expanded', 'true'); }
  function setActive(i) {
    if (!opts.length) { return; }
    if (active > -1) { opts[active].setAttribute('aria-selected', 'false'); }
    active = (i + opts.length) % opts.length;
    var o = opts[active];
    o.setAttribute('aria-selected', 'true');
    input.setAttribute('aria-activedescendant', o.id);
    o.scrollIntoView({ block: 'nearest' });
  }
  function render(data) {
    list.textContent = ''; opts = []; active = -1;
    var n = 0;
    (data.groups || []).forEach(function (g, gi) {
      var wrap = el('div'); wrap.setAttribute('role', 'group'); wrap.setAttribute('aria-labelledby', 'gn-asg-' + gi);
      var h = el('div', 'gn-asearch__g', g.label); h.id = 'gn-asg-' + gi; wrap.appendChild(h);
      g.items.forEach(function (it) {
        var a = el('a', 'gn-asearch__o'); a.href = it.url; a.id = 'gn-aso-' + (n++); a.setAttribute('role', 'option'); a.setAttribute('aria-selected', 'false');
        a.appendChild(el('b', null, it.label));
        if (it.hint) { a.appendChild(el('small', null, it.hint)); }
        a.addEventListener('mousemove', function () { var i = opts.indexOf(a); if (i !== active) { setActive(i); } });
        wrap.appendChild(a); opts.push(a);
      });
      list.appendChild(wrap);
    });
    if (!n) { list.appendChild(el('div', 'gn-asearch__empty', 'Aucun résultat pour « ' + data.query + ' ».')); }
    status.textContent = n ? (n + (n > 1 ? ' résultats disponibles.' : ' résultat disponible.')) : 'Aucun résultat.';
    open();
  }
  function run() {
    var q = input.value.trim();
    if (q.length < 2) { close(); status.textContent = ''; last = ''; return; }
    if (q === last) { open(); return; }
    last = q;
    if (ctrl) { ctrl.abort(); }
    ctrl = window.AbortController ? new AbortController() : null;
    fetch(url + '?q=' + encodeURIComponent(q), { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: ctrl ? ctrl.signal : undefined })
      .then(function (r) { if (!r.ok) { throw new Error(r.status); } return r.json(); })
      .then(function (d) { if (input.value.trim() === d.query) { render(d); } })
      .catch(function (e) { if (e && e.name === 'AbortError') { return; } list.textContent = ''; list.appendChild(el('div', 'gn-asearch__empty', 'La recherche est momentanément indisponible.')); open(); });
  }
  input.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(run, 220); });
  input.addEventListener('focus', function () { if (input.value.trim().length >= 2 && opts.length) { open(); } });
  input.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowDown') { e.preventDefault(); if (list.hidden && opts.length) { open(); } setActive(active + 1); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); if (list.hidden && opts.length) { open(); } setActive(active < 0 ? opts.length - 1 : active - 1); }
    else if (e.key === 'Enter') { if (active > -1 && !list.hidden) { e.preventDefault(); window.location.href = opts[active].href; } }
    else if (e.key === 'Escape') { if (!list.hidden) { e.preventDefault(); close(); } else if (input.value) { input.value = ''; last = ''; } else if (root.classList.contains('is-open')) { root.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); toggle.focus(); } }
    else if (e.key === 'Tab') { close(); }
  });
  document.addEventListener('click', function (e) { if (!root.contains(e.target)) { close(); if (root.classList.contains('is-open')) { root.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); } } });
  document.addEventListener('keydown', function (e) {
    if (e.key !== '/' || e.ctrlKey || e.metaKey || e.altKey) { return; }
    var t = e.target, tag = t && t.tagName;
    if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || (t && t.isContentEditable)) { return; }
    e.preventDefault();
    if (window.getComputedStyle(toggle).display !== 'none') { toggle.click(); } else { input.focus(); }
  });
  toggle.addEventListener('click', function () {
    var o = root.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', o ? 'true' : 'false');
    toggle.setAttribute('aria-label', o ? 'Fermer la recherche' : 'Ouvrir la recherche');
    if (o) { input.focus(); } else { close(); }
  });
})();

/* Accessibilité de l'administration : noms accessibles manquants (icônes, cases à cocher, champs, bascules du menu).
   Le gabarit d'origine n'en fournit pas ; on les complète au chargement et quand les tableaux se redessinent. */
(function () {
  var ICONS = [
    ['fe-edit', 'Modifier'], ['fe-edit-2', 'Modifier'], ['fa-edit', 'Modifier'], ['fa-pencil', 'Modifier'], ['fe-trash', 'Supprimer'], ['fe-trash-2', 'Supprimer'], ['fa-trash', 'Supprimer'],
    ['fe-eye', 'Voir le détail'], ['fa-eye', 'Voir le détail'], ['fe-plus', 'Ajouter'], ['fe-download', 'Télécharger'], ['fe-user', 'Profil'], ['fe-user-plus', 'Attribuer'], ['fe-user-check', 'Attribuer'],
    ['fe-x', 'Fermer'], ['fe-check', 'Valider'], ['fe-copy', 'Copier'], ['fe-refresh-cw', 'Actualiser'], ['fe-settings', 'Réglages'], ['fe-more-vertical', 'Plus d’actions'], ['fe-more-horizontal', 'Plus d’actions'],
    ['fe-lock', 'Verrouiller'], ['fe-unlock', 'Déverrouiller'], ['fe-mail', 'E-mail'], ['fe-send', 'Envoyer'], ['fe-paperclip', 'Pièce jointe'], ['fe-search', 'Rechercher'], ['fe-bell', 'Notifications'],
    ['fe-log-in', 'Se connecter en tant que'], ['fe-file-text', 'Document'], ['fe-file', 'Fichier'], ['fe-clock', 'Historique']
  ];
  function iconName(el) {
    var i = el.querySelector('i[class*="fe-"], i[class*="fa-"], i[class*="feather-"], span[class*="fe-"]');
    if (!i) { return ''; }
    var c = ' ' + String(i.className).replace(/feather-/g, 'fe-') + ' ';
    for (var k = 0; k < ICONS.length; k++) { if (c.indexOf(' ' + ICONS[k][0] + ' ') > -1) { return ICONS[k][1]; } }
    return '';
  }
  function hasName(el) {
    return (el.textContent || '').trim() || el.getAttribute('aria-label') || el.getAttribute('aria-labelledby') || el.getAttribute('title') || el.getAttribute('data-bs-original-title') || el.getAttribute('data-original-title');
  }
  var uid = 0;
  function pass() {
    // bascules du menu et retour en haut
    document.querySelectorAll('.open-toggle').forEach(function (a) { if (!hasName(a)) { a.setAttribute('aria-label', 'Ouvrir le menu'); } });
    document.querySelectorAll('.close-toggle').forEach(function (a) { if (!hasName(a)) { a.setAttribute('aria-label', 'Fermer le menu'); } });
    document.querySelectorAll('#back-to-top, .back-to-top').forEach(function (a) { if (!hasName(a)) { a.setAttribute('aria-label', 'Retour en haut de la page'); a.setAttribute('title', 'Retour en haut de la page'); } });
    // liens et boutons-icônes
    document.querySelectorAll('a[href], button').forEach(function (el) {
      if (hasName(el)) { return; }
      var n = iconName(el);
      if (n) { el.setAttribute('aria-label', n); if (!el.getAttribute('title')) { el.setAttribute('title', n); } }
    });
    // cases à cocher de tableaux (sélection de ligne / tout sélectionner)
    document.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
      if (cb.getAttribute('aria-label') || cb.getAttribute('aria-labelledby') || cb.closest('label') || (cb.id && document.querySelector('label[for="' + cb.id + '"]'))) { return; }
      var th = cb.closest('th');
      cb.setAttribute('aria-label', th ? 'Tout sélectionner' : (cb.classList.contains('onoffswitch2-checkbox') || cb.classList.contains('custom-switch-input') ? 'Activer ou désactiver' : 'Sélectionner la ligne'));
    });
    // champs sans étiquette associée : on relie le <label> voisin, sinon le placeholder
    document.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]), select, textarea').forEach(function (f) {
      if (f.getAttribute('aria-label') || f.getAttribute('aria-labelledby') || f.getAttribute('title') || f.closest('label') || (f.id && document.querySelector('label[for="' + f.id + '"]'))) { return; }
      if (f.closest('[aria-hidden="true"]') || f.tabIndex === -1 && f.offsetParent === null) { return; }
      var lab = null, p = f.parentElement, hops = 0;
      while (p && hops < 4 && !lab) { lab = p.querySelector('label'); p = p.parentElement; hops++; }
      if (lab && (lab.textContent || '').trim()) {
        if (!lab.id) { lab.id = 'gn-lb-' + (++uid); }
        f.setAttribute('aria-labelledby', lab.id);
      } else if (f.getAttribute('placeholder')) {
        f.setAttribute('aria-label', f.getAttribute('placeholder'));
      } else if (f.name) {
        f.setAttribute('aria-label', f.name.replace(/[\[\]_]+/g, ' ').trim());
      }
    });
  }
  var t;
  function later() { clearTimeout(t); t = setTimeout(pass, 250); }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', pass); } else { pass(); }
  window.addEventListener('load', pass);
  if (window.MutationObserver) { new MutationObserver(later).observe(document.body || document.documentElement, { childList: true, subtree: true }); }
})();
