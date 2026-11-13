/* Ganho — pages à formulaire (alerte, connexion cliente, contact). Sans dépendance.
   1) window.toastr minimal : les scripts existants appellent toastr.success/error/info/warning.
   2) Rechargement du captcha image : bouton .captchabtn (data-url = route captcha.reload). */
(function () {
  'use strict';

  function box() {
    var b = document.getElementById('gnf-toasts');
    if (!b) {
      b = document.createElement('div');
      b.id = 'gnf-toasts';
      b.className = 'gnf-toasts';
      b.setAttribute('role', 'status');
      b.setAttribute('aria-live', 'polite');
      document.body.appendChild(b);
    }
    return b;
  }
  function toast(kind) {
    return function (msg) {
      if (msg === undefined || msg === null || msg === '') return;
      var t = document.createElement('div');
      t.className = 'gnf-toast gnf-toast--' + kind;
      t.textContent = String(msg);
      box().appendChild(t);
      setTimeout(function () { if (t.parentNode) t.parentNode.removeChild(t); }, kind === 'error' ? 8000 : 5000);
    };
  }
  window.toastr = window.toastr || { success: toast('success'), error: toast('error'), info: toast('info'), warning: toast('warning') };

  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('.captchabtn') : null;
    if (!btn) return;
    e.preventDefault();
    var url = btn.getAttribute('data-url') || '/captchareload';
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        var holder = btn.closest('.captcha');
        var span = holder ? holder.querySelector('span') : document.querySelector('.captcha span');
        if (span && res && res.captcha) span.innerHTML = res.captcha;
      })
      .catch(function () {});
  });
})();
