@extends('layouts.usermaster')

@section('content')
@php
	$gnMax = (int) setting('TICKET_CHARACTER') ?: 255;
	$gnUp = setting('USER_FILE_UPLOAD_ENABLE') == 'yes';
	$gnTypes = trim((string) setting('FILE_UPLOAD_TYPES'));
	$gnUpMax = (int) setting('FILE_UPLOAD_MAX') ?: 5;
	$gnUpCount = (int) setting('MAX_FILE_UPLOAD') ?: 5;
@endphp

<div class="gcx-pagehead">
	<nav class="gcx-crumbs" aria-label="Fil d’Ariane"><ol><li><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li><li aria-current="page">Créer une alerte / un cas</li></ol></nav>
	<h1>Créer une alerte / un cas</h1>
	<p class="gcx-lead">Racontez ce qui se passe à votre rythme, en trois étapes. Vous pouvez enregistrer un brouillon et revenir plus tard : rien n’est envoyé avant la dernière étape.</p>
</div>

<div class="gcx-wiz" id="gcx-wiz" data-max="{{ $gnMax }}">
	<ol class="gcx-wiz__steps" aria-label="Étapes">
		<li data-step="1" aria-current="step"><span>1</span> Votre situation</li>
		<li data-step="2"><span>2</span> Votre message</li>
		<li data-step="3"><span>3</span> Confirmation</li>
	</ol>

	<p class="gcx-wiz__draft" id="gcx-draft-note" hidden role="status">Un brouillon a été retrouvé et remis dans le formulaire. <button type="button" id="gcx-draft-clear">Effacer le brouillon</button></p>

	<form id="gcx-form" class="gcx-card gcx-wiz__card" method="POST" action="{{ route('client.ticketcreate') }}" novalidate enctype="multipart/form-data">
		@csrf
		<p class="gcx-wiz__err" id="gcx-err" role="alert" hidden></p>

		<section class="gcx-pane" data-pane="1" aria-labelledby="gcx-h1">
			<h2 id="gcx-h1" tabindex="-1">Votre situation</h2>
			<div class="gcx-field">
				<label for="category">Que vivez-vous ? <span class="gcx-req" aria-hidden="true">*</span><span class="gcx-sr">(obligatoire)</span></label>
				<select id="category" name="category" required aria-describedby="category-help category-err">
					<option value="">Choisir une catégorie</option>
					@foreach ($categories as $category)
						<option value="{{ $category->id }}">{{ $category->name }}</option>
					@endforeach
				</select>
				<p class="gcx-help" id="category-help">Choisissez ce qui décrit le mieux la situation.</p>
				<p class="gcx-error" id="category-err"></p>
			</div>
			<div class="gcx-field" id="gcx-sub" hidden>
				<label for="subscategory">Précision (sous-catégorie)</label>
				<select id="subscategory" name="subscategory"></select>
			</div>

			@if(setting('cc_email') == 'on')
				<div class="gcx-field">
					<label for="ccmail">Adresse e-mail en copie</label>
					<input type="email" id="ccmail" name="ccmail" placeholder="copie@exemple.com" autocomplete="off">
					<p class="gcx-help">Une seule adresse en copie est autorisée.</p>
				</div>
			@endif

			@foreach($customfields as $customfield)
				@if(in_array($customfield->fieldtypes, ['checkbox', 'radio']))
					<fieldset class="gcx-group">
						<legend>{{ $customfield->fieldnames }} @if($customfield->fieldrequired == '1')<span class="gcx-req" aria-hidden="true">*</span>@endif</legend>
						@foreach(explode(',', $customfield->fieldoptions) as $opt)
							<label class="gcx-check"><input type="{{ $customfield->fieldtypes }}" name="custom_{{ $customfield->id }}{{ $customfield->fieldtypes == 'checkbox' ? '[]' : '' }}" value="{{ $opt }}" @if($customfield->fieldrequired == '1' && $customfield->fieldtypes == 'radio') required @endif><span>{{ $opt }}</span></label>
						@endforeach
					</fieldset>
				@else
					<div class="gcx-field">
						<label for="custom_{{ $customfield->id }}">{{ $customfield->fieldnames }} @if($customfield->fieldrequired == '1')<span class="gcx-req" aria-hidden="true">*</span>@endif</label>
						@if($customfield->fieldtypes == 'textarea')
							<textarea id="custom_{{ $customfield->id }}" name="custom_{{ $customfield->id }}" maxlength="255" rows="3" @if($customfield->fieldrequired == '1') required @endif></textarea>
						@elseif($customfield->fieldtypes == 'select')
							<select id="custom_{{ $customfield->id }}" name="custom_{{ $customfield->id }}" @if($customfield->fieldrequired == '1') required @endif>
								<option value="">Choisir</option>
								@foreach(explode(',', $customfield->fieldoptions) as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
							</select>
						@else
							<input type="{{ $customfield->fieldtypes == 'email' ? 'email' : 'text' }}" id="custom_{{ $customfield->id }}" name="custom_{{ $customfield->id }}" maxlength="255" @if($customfield->fieldrequired == '1') required @endif>
						@endif
					</div>
				@endif
			@endforeach
		</section>

		<section class="gcx-pane" data-pane="2" aria-labelledby="gcx-h2" hidden>
			<h2 id="gcx-h2" tabindex="-1">Votre message</h2>
			<div class="gcx-field">
				<label for="subject">Sujet de l’alerte <span class="gcx-req" aria-hidden="true">*</span><span class="gcx-sr">(obligatoire)</span></label>
				<input type="text" id="subject" name="subject" maxlength="{{ $gnMax }}" autocomplete="off" placeholder="Ex. : harcèlement au travail" required aria-describedby="subject-help subject-err">
				<div class="gcx-helprow"><p class="gcx-help" id="subject-help">Résumez la situation en quelques mots.</p><span class="gcx-counter"><span id="subject-count">0</span>/{{ $gnMax }}</span></div>
				<p class="gcx-error" id="subject-err"></p>
			</div>
			<div class="gcx-field">
				<label for="message">Description de la situation <span class="gcx-req" aria-hidden="true">*</span><span class="gcx-sr">(obligatoire)</span></label>
				<textarea id="message" rows="8" required aria-describedby="message-help message-err" placeholder="Racontez ce qui s’est passé, quand et où, à votre rythme."></textarea>
				<input type="hidden" name="message" id="message-html">
				<p class="gcx-help" id="message-help">Ces précisions aident l’équipe à vous répondre. Vous pouvez vous arrêter à tout moment.</p>
				<p class="gcx-error" id="message-err"></p>
			</div>
			@if($gnUp)
				<div class="gcx-field">
					<label for="gcx-file">Pièces jointes <span class="gcx-opt">(facultatif)</span></label>
					<div class="gcx-file">
						<input type="file" id="gcx-file" class="gcx-file__in" multiple @if(preg_match('/^(\.[a-z0-9]+,?)+$/i', $gnTypes)) accept="{{ $gnTypes }}" @endif aria-describedby="file-help">
						<label for="gcx-file" class="gcx-btn gcx-btn--ghost">Ajouter des fichiers</label>
					</div>
					<p class="gcx-help" id="file-help">Formats : {{ str_replace(',', ', ', $gnTypes) }}. {{ $gnUpMax }} Mo maximum par fichier, {{ $gnUpCount }} fichier(s) au plus.</p>
					<ul class="gcx-files" id="gcx-files" aria-live="polite"></ul>
				</div>
			@endif
		</section>

		<section class="gcx-pane" data-pane="3" aria-labelledby="gcx-h3" hidden>
			<h2 id="gcx-h3" tabindex="-1">Confirmation</h2>
			<p class="gcx-help">Relisez, puis envoyez. L’équipe GANXÔ reçoit votre alerte et vous répond dans cet espace.</p>
			<dl class="gcx-recap">
				<div><dt>Situation</dt><dd id="rc-cat">—</dd></div>
				<div><dt>Sujet</dt><dd id="rc-subject">—</dd></div>
				<div><dt>Message</dt><dd id="rc-message">—</dd></div>
				<div id="rc-files-row" hidden><dt>Pièces jointes</dt><dd id="rc-files">—</dd></div>
			</dl>
			<label class="gcx-check gcx-consent"><input type="checkbox" name="agree_terms" id="agree_terms" value="agreed" aria-describedby="agree-err"><span>Je confirme que les faits décrits sont exacts.</span></label>
			<p class="gcx-error" id="agree-err"></p>
			<p class="gcx-note">En danger immédiat, joignez d’abord les secours de votre pays : une alerte GANXÔ ne contacte pas les services d’urgence.</p>
		</section>

		<div class="gcx-wiz__nav">
			<button type="button" class="gcx-btn gcx-btn--ghost" id="gcx-prev" hidden>Retour</button>
			<button type="button" class="gcx-btn gcx-btn--ghost" id="gcx-save">Enregistrer le brouillon</button>
			<span class="gcx-wiz__sp"></span>
			<button type="button" class="gcx-btn gcx-btn--primary" id="gcx-next">Continuer</button>
			<button type="submit" class="gcx-btn gcx-btn--cta" id="gcx-send" hidden>Envoyer l’alerte</button>
		</div>
		<p class="gcx-wiz__saved" id="gcx-saved" role="status" hidden>Brouillon enregistré sur cet appareil.</p>
	</form>
</div>
@endsection

@section('scripts')
<script>
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); }, $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var wiz = $('#gcx-wiz'); if (!wiz) { return; }
  var form = $('#gcx-form'), step = 1, MAX = parseInt(wiz.dataset.max, 10) || 255;
  var KEY = 'gcx_draft_{{ Auth::guard('customer')->id() }}';
  var panes = $$('.gcx-pane'), steps = $$('.gcx-wiz__steps li');
  var prev = $('#gcx-prev'), next = $('#gcx-next'), send = $('#gcx-send'), err = $('#gcx-err');
  var uploaded = [];

  function say(el, msg) { el.textContent = msg || ''; }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]; }); }

  function show(n) {
    step = n;
    panes.forEach(function (p) { p.hidden = parseInt(p.dataset.pane, 10) !== n; });
    steps.forEach(function (li) { var k = parseInt(li.dataset.step, 10); li.classList.toggle('is-done', k < n); if (k === n) { li.setAttribute('aria-current', 'step'); } else { li.removeAttribute('aria-current'); } });
    prev.hidden = n === 1; next.hidden = n === 3; send.hidden = n !== 3;
    err.hidden = true;
    if (n === 3) { recap(); }
    var h = $('#gcx-h' + n); if (h) { h.focus({ preventScroll: false }); }
  }
  function valid(n) {
    err.hidden = true;
    if (n === 1) {
      if (!$('#category').value) { say($('#category-err'), 'Choisissez une catégorie pour continuer.'); $('#category').focus(); return false; }
      say($('#category-err'), '');
      var bad = $$('[data-pane="1"] [required]').filter(function (el) { return (el.type === 'radio') ? !$$('[name="' + el.name + '"]:checked').length : !el.value; })[0];
      if (bad) { bad.focus(); err.textContent = 'Merci de renseigner les champs obligatoires.'; err.hidden = false; return false; }
    }
    if (n === 2) {
      var ok = true;
      if (!$('#subject').value.trim()) { say($('#subject-err'), 'Indiquez un sujet.'); $('#subject').focus(); ok = false; } else { say($('#subject-err'), ''); }
      if (!$('#message').value.trim()) { say($('#message-err'), 'Décrivez la situation.'); if (ok) { $('#message').focus(); } ok = false; } else { say($('#message-err'), ''); }
      return ok;
    }
    return true;
  }
  function recap() {
    var c = $('#category'); $('#rc-cat').textContent = c.options[c.selectedIndex] ? c.options[c.selectedIndex].text : '—';
    $('#rc-subject').textContent = $('#subject').value;
    $('#rc-message').textContent = $('#message').value;
    $('#rc-files-row').hidden = !uploaded.length; $('#rc-files').textContent = uploaded.map(function (f) { return f.original; }).join(', ');
  }

  next.addEventListener('click', function () { if (valid(step)) { show(step + 1); } });
  prev.addEventListener('click', function () { show(step - 1); });

  /* compteur du sujet */
  $('#subject').addEventListener('input', function () { $('#subject-count').textContent = this.value.length; });

  /* sous-catégories */
  $('#category').addEventListener('change', function () {
    var box = $('#gcx-sub'), sel = $('#subscategory'); box.hidden = true; sel.innerHTML = '';
    if (!this.value) { return; }
    var fd = new FormData(); fd.append('cat_id', this.value); fd.append('_token', form._token.value);
    fetch('{{ route('guest.subcategorylist') }}', { method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (d) { if (d && d.subcategoriess) { sel.innerHTML = d.subcategoriess; box.hidden = false; } })
      .catch(function () {});
  });

  /* pièces jointes */
  var fileIn = $('#gcx-file');
  if (fileIn) {
    var list = $('#gcx-files');
    function renderFiles() {
      list.innerHTML = uploaded.map(function (f, i) { return '<li>' + esc(f.original) + ' <button type="button" data-rm="' + i + '" aria-label="Retirer ' + esc(f.original) + '">Retirer</button></li>'; }).join('');
    }
    list.addEventListener('click', function (e) { var b = e.target.closest('[data-rm]'); if (b) { uploaded.splice(parseInt(b.dataset.rm, 10), 1); renderFiles(); } });
    fileIn.addEventListener('change', function () {
      var max = {{ $gnUpCount }}, mb = {{ $gnUpMax }};
      Array.prototype.forEach.call(fileIn.files, function (file) {
        if (uploaded.length >= max) { toastr.error('Vous pouvez joindre {{ $gnUpCount }} fichier(s) au plus.'); return; }
        if (file.size > mb * 1048576) { toastr.error('« ' + file.name + ' » dépasse {{ $gnUpMax }} Mo.'); return; }
        var fd = new FormData(); fd.append('file', file); fd.append('_token', form._token.value);
        fetch('{{ route('imageupload') }}', { method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
          .then(function (r) { if (!r.ok) { throw new Error(); } return r.json(); })
          .then(function (d) { uploaded.push({ name: d.name, original: d.original_name }); renderFiles(); })
          .catch(function () { toastr.error('Le fichier « ' + file.name + ' » n’a pas pu être envoyé.'); });
      });
      fileIn.value = '';
    });
  }

  /* brouillon local */
  var draftNote = $('#gcx-draft-note'), saved = $('#gcx-saved');
  function fields() { return ['category', 'subject', 'message', 'ccmail'].map(function (id) { return $('#' + id); }).filter(Boolean); }
  function save(silent) {
    var data = {}; fields().forEach(function (el) { data[el.id] = el.value; });
    try { localStorage.setItem(KEY, JSON.stringify(data)); } catch (e) { return; }
    if (!silent) { saved.hidden = false; setTimeout(function () { saved.hidden = true; }, 2500); }
  }
  function restore() {
    var raw; try { raw = localStorage.getItem(KEY); } catch (e) { return; }
    if (!raw) { return; }
    try { var d = JSON.parse(raw); } catch (e) { return; }
    var any = false;
    fields().forEach(function (el) { if (d[el.id]) { el.value = d[el.id]; any = true; } });
    if (any) {
      draftNote.hidden = false; $('#subject-count').textContent = $('#subject').value.length;
      if ($('#category').value) { $('#category').dispatchEvent(new Event('change')); }
    }
  }
  $('#gcx-save').addEventListener('click', function () { save(false); });
  $('#gcx-draft-clear').addEventListener('click', function () { try { localStorage.removeItem(KEY); } catch (e) {} fields().forEach(function (el) { el.value = ''; }); $('#subject-count').textContent = '0'; draftNote.hidden = true; show(1); });
  form.addEventListener('input', function () { save(true); });
  restore();

  /* envoi */
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!valid(1) || !valid(2)) { show(!$('#category').value ? 1 : 2); return; }
    if (!$('#agree_terms').checked) { say($('#agree-err'), 'Cochez la case pour confirmer.'); $('#agree_terms').focus(); return; }
    say($('#agree-err'), '');
    $('#message-html').value = '<p>' + esc($('#message').value).replace(/\r?\n\r?\n/g, '</p><p>').replace(/\r?\n/g, '<br>') + '</p>';
    var fd = new FormData(form);
    fd.set('envato_id', 'undefined');
    uploaded.forEach(function (f) { fd.append('ticket[]', f.name); });
    send.disabled = true; send.textContent = 'Envoi en cours…';
    fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (res) {
        if (res.ok) {
          try { localStorage.removeItem(KEY); } catch (e) {}
          if (window.toastr) { toastr.success('Votre alerte a bien été envoyée.'); }
          window.location.replace('{{ route('client.dashboard') }}');
          return;
        }
        send.disabled = false; send.textContent = 'Envoyer l’alerte';
        var er = (res.j && res.j.errors) || {}, msgs = [];
        if (er.category) { say($('#category-err'), 'Choisissez une catégorie.'); msgs.push('catégorie'); }
        if (er.subject) { say($('#subject-err'), 'Indiquez un sujet (255 caractères au plus).'); msgs.push('sujet'); }
        if (er.message) { say($('#message-err'), 'Décrivez la situation.'); msgs.push('message'); }
        if (er.agree_terms) { say($('#agree-err'), 'Cochez la case pour confirmer.'); }
        err.textContent = (res.j && res.j.error) ? res.j.error : 'Certains champs sont à corriger : ' + (msgs.join(', ') || 'vérifiez le formulaire') + '.'; err.hidden = false;
        if (er.category) { show(1); } else if (er.subject || er.message) { show(2); }
      })
      .catch(function () { send.disabled = false; send.textContent = 'Envoyer l’alerte'; err.textContent = 'Erreur réseau : votre brouillon est conservé, réessayez dans un instant.'; err.hidden = false; });
  });
  show(1);
})();
</script>
@endsection
