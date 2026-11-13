/* Ganho — préremplit le formulaire d'alerte existant avec la position lue par le bouton SOS (sessionStorage 'gn_sos').
   Ne modifie ni les champs, ni les routes, ni l'envoi : il ajoute seulement un texte modifiable dans le message. */
(function () {
  'use strict';
  var raw = null;
  try { raw = sessionStorage.getItem('gn_sos'); } catch (e) {}
  if (!raw || !/[?&]sos=1/.test(location.search)) return;
  var d; try { d = JSON.parse(raw); } catch (e) { return; }
  if (!d || typeof d.lat !== 'number' || typeof d.lon !== 'number' || Date.now() - d.ts > 30 * 60 * 1000) return;

  var lat = d.lat.toFixed(6), lon = d.lon.toFixed(6);
  var map = 'https://www.openstreetmap.org/?mlat=' + lat + '&mlon=' + lon + '#map=17/' + lat + '/' + lon;
  var text = 'Alerte SOS avec position GPS : ' + lat + ', ' + lon + (d.acc ? ' (précision environ ' + d.acc + ' m)' : '') + ' — ' + map + ' — relevée le ' + new Date(d.ts).toLocaleString('fr-FR') + '.';

  function notice() {
    var form = document.getElementById('guest_form') || document.querySelector('form');
    if (!form || document.getElementById('gn-sos-notice')) return;
    var n = document.createElement('div');
    n.id = 'gn-sos-notice'; n.className = 'alert alert-warning font-weight-bold'; n.setAttribute('role', 'status');
    n.textContent = 'Votre position GPS est jointe au message ci-dessous (' + lat + ', ' + lon + '). Vous pouvez la modifier ou la supprimer avant d’envoyer. Cette alerte est transmise à l’équipe GANXÔ : aucun service d’urgence n’est contacté.';
    form.insertBefore(n, form.firstChild);
  }

  var tries = 0;
  (function fill() {
    var ta = document.querySelector('textarea[name="message"]');
    var ready = ta && (!window.jQuery || !jQuery.fn.summernote || ta.nextElementSibling && /note-editor/.test(ta.nextElementSibling.className) || tries > 40);
    if (!ready) { tries++; return setTimeout(fill, 150); }
    var html = '<p>' + text.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</p><p><br></p>';
    if (window.jQuery && jQuery.fn.summernote && ta.nextElementSibling && /note-editor/.test(ta.nextElementSibling.className)) {
      jQuery(ta).summernote('code', html);
    } else {
      ta.value = text + '\n\n';
    }
    var subj = document.getElementById('subject') || document.querySelector('input[name="subject"]');
    if (subj && !subj.value) subj.value = 'Alerte SOS avec position';
    notice();
    try { sessionStorage.removeItem('gn_sos'); } catch (e) {}
  })();
})();
