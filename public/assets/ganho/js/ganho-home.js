/* Ganho — menu mobile et SOS avec GPS. L'assistant et sa fenêtre de discussion vivent dans gn-assistant.js. */
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* ---- Menu mobile ---- */
  var burger = $('#gh-burger'), menu = $('#gh-menu');
  if (burger && menu) {
    burger.addEventListener('click', function () {
      var open = menu.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ---- SOS : maintien 2 s -> confirmation -> position GPS -> formulaire d'alerte prérempli ---- */
  var sos = $('#gn-sos'), dlg = $('#gn-sos-dlg'), hint = $('#gn-sos-hint');
  if (sos && dlg) {
    var HOLD = 2000, timer = null, holding = false, fired = false, hintTimer = null;
    var title = $('#gn-sos-title'), st = $('#gn-sos-st'), go = $('#gn-sos-go'), cancelBtn = $('#gn-sos-cancel');
    title.setAttribute('tabindex', '-1');

    var start = function () {
      if (holding) return;
      holding = true; fired = false;
      sos.classList.add('is-holding');
      timer = setTimeout(function () {
        fired = true; holding = false; sos.classList.remove('is-holding'); if (hint) hint.hidden = true;
        st.textContent = '';
        if (typeof dlg.showModal === 'function') { dlg.showModal(); } else { dlg.setAttribute('open', ''); }
        title.focus();
      }, HOLD);
    };
    var stop = function () {
      if (!holding) return;
      holding = false; clearTimeout(timer); sos.classList.remove('is-holding');
      if (!fired && hint) { hint.hidden = false; clearTimeout(hintTimer); hintTimer = setTimeout(function () { hint.hidden = true; }, 3500); }
    };
    sos.addEventListener('pointerdown', function (e) { if (e.button > 0) return; start(); });
    ['pointerup', 'pointerleave', 'pointercancel'].forEach(function (ev) { sos.addEventListener(ev, stop); });
    sos.addEventListener('contextmenu', function (e) { e.preventDefault(); });
    sos.addEventListener('keydown', function (e) { if ((e.key === 'Enter' || e.key === ' ') && !e.repeat) { e.preventDefault(); start(); } });
    sos.addEventListener('keyup', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); stop(); } });
    sos.addEventListener('blur', stop);
    sos.addEventListener('click', function (e) { e.preventDefault(); });

    cancelBtn.addEventListener('click', function () { dlg.close(); sos.focus(); });

    go.addEventListener('click', function () {
      if (!('geolocation' in navigator)) { st.textContent = 'Votre navigateur ne permet pas de lire la position. Vous pouvez créer l’alerte sans position.'; return; }
      go.disabled = true;
      st.textContent = 'Recherche de votre position… Autorisez l’accès à la localisation si votre navigateur le demande.';
      navigator.geolocation.getCurrentPosition(function (pos) {
        try {
          sessionStorage.setItem('gn_sos', JSON.stringify({ lat: pos.coords.latitude, lon: pos.coords.longitude, acc: Math.round(pos.coords.accuracy || 0), ts: Date.now() }));
        } catch (err) {}
        var url = sos.getAttribute('data-alert-url');
        window.location.href = url + (url.indexOf('?') > -1 ? '&' : '?') + 'sos=1';
      }, function (err) {
        go.disabled = false;
        st.textContent = err && err.code === 1
          ? 'Accès à la position refusé. Vous pouvez autoriser la localisation dans votre navigateur, ou créer l’alerte sans position.'
          : 'Position indisponible pour le moment. Vous pouvez réessayer ou créer l’alerte sans position.';
      }, { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 });
    });
  }
})();
