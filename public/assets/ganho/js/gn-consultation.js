/* Fenêtre « Consultation en ligne » : ouverture, fermeture (bouton, Échap natif, clic sur le fond), retour du focus. */
(function () {
  var dlg = document.getElementById('gn-consult');
  if (!dlg || typeof dlg.showModal !== 'function') { return; }
  var last = null;
  function open(e) {
    if (e) { e.preventDefault(); }
    last = document.activeElement;
    dlg.showModal();
    document.documentElement.style.overflow = 'hidden';
  }
  function close() { if (dlg.open) { dlg.close(); } }
  dlg.addEventListener('close', function () {
    document.documentElement.style.overflow = '';
    if (last && last.focus) { last.focus(); }
  });
  dlg.addEventListener('click', function (e) { if (e.target === dlg) { close(); } });
  document.querySelectorAll('[data-gn-consult-open]').forEach(function (b) { b.addEventListener('click', open); });
  document.querySelectorAll('[data-gn-consult-close]').forEach(function (b) { b.addEventListener('click', close); });
  if (location.hash === '#consultation-contact') { open(); }
})();
