/* Widget d'installation de l'application (PWA). Android/Chrome/Edge : bouton natif via beforeinstallprompt.
   iOS Safari : consigne « Partager > Sur l'écran d'accueil ». Masqué si déjà installée ou refusé (14 jours). */
(function () {
  'use strict';
  var KEY = 'gn_install_dismissed', WAIT = 14 * 864e5, DELAY = 6000;
  var box = document.getElementById('gn-install');
  if (!box) return;
  function get() { try { return +localStorage.getItem(KEY) || 0; } catch (e) { return 0; } }
  function set() { try { localStorage.setItem(KEY, String(Date.now())); } catch (e) {} }
  var standalone = (window.matchMedia && matchMedia('(display-mode: standalone)').matches) || navigator.standalone === true;
  if (standalone || Date.now() - get() < WAIT) return;

  var ua = navigator.userAgent || '';
  var isIOS = /iphone|ipad|ipod/i.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
  var deferred = null;
  var go = box.querySelector('[data-gn-install-go]');
  var how = box.querySelector('[data-gn-install-how]');
  var timer = null;

  function cookieOpen() { var c = document.querySelector('.gn-cookie'); return !!c && getComputedStyle(c).display !== 'none'; }
  function show() { clearTimeout(timer); timer = setTimeout(function tick() { if (cookieOpen()) { timer = setTimeout(tick, 2000); return; } box.hidden = false; }, DELAY); }
  function hide(remember) { box.hidden = true; if (remember) set(); }

  window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault(); deferred = e; go.hidden = false; how.hidden = true; show();
  });
  window.addEventListener('appinstalled', function () { hide(true); deferred = null; });

  if (isIOS && !deferred) { go.hidden = true; how.hidden = false; show(); }

  go.addEventListener('click', function () {
    if (!deferred) return;
    deferred.prompt();
    deferred.userChoice.then(function (c) { deferred = null; hide(true); });
  });
  box.querySelector('[data-gn-install-close]').addEventListener('click', function () { hide(true); });
})();
