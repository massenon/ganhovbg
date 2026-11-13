@extends('layouts.usermaster')

@section('content')
@php
	$gnU = Auth::guard('customer')->user();
	$gnPays = collect($countries)->map(function ($c) {
		$label = (class_exists('Locale') ? \Locale::getDisplayRegion('-' . $c->code, 'fr') : '') ?: $c->name;
		return ['value' => $c->name, 'label' => $label];
	})->sortBy(function ($c) { return \Illuminate\Support\Str::ascii(mb_strtolower($c['label'])); })->values();
	$gnTerms = setting('terms_url');
@endphp

<div class="gcx-pagehead">
	<nav class="gcx-crumbs" aria-label="Fil d’Ariane"><ol><li><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li><li aria-current="page">Modifier le profil</li></ol></nav>
	<h1>Modifier le profil</h1>
	<p class="gcx-lead">Vos informations restent confidentielles. Vous pouvez compléter votre téléphone, votre pays, votre fuseau horaire et votre photo.</p>
</div>

<section class="gcx-card" aria-labelledby="gcx-prof-t">
	<div class="gcx-card__head"><h2 id="gcx-prof-t">Détails du profil</h2></div>
	<form method="POST" action="{{ route('client.profilesetup') }}" enctype="multipart/form-data" class="gcx-card__body" novalidate>
		@csrf
		@honeypot
		<div class="gcx-grid2">
			<div class="gcx-field">
				<label for="pf-firstname">Nom</label>
				<input type="text" id="pf-firstname" name="firstname" value="{{ old('firstname', $gnU->firstname) }}" readonly aria-describedby="pf-ro-help">
				@error('firstname')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
			</div>
			<div class="gcx-field">
				<label for="pf-lastname">Prénom</label>
				<input type="text" id="pf-lastname" name="lastname" value="{{ old('lastname', $gnU->lastname) }}" readonly aria-describedby="pf-ro-help">
				@error('lastname')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
			</div>
			<div class="gcx-field">
				<label for="pf-username">Pseudo</label>
				<input type="text" id="pf-username" name="username" value="{{ $gnU->username }}" readonly aria-describedby="pf-ro-help">
			</div>
			<div class="gcx-field">
				<label for="pf-email">Adresse e-mail</label>
				<input type="email" id="pf-email" value="{{ $gnU->email }}" readonly aria-describedby="pf-ro-help">
			</div>
			<div class="gcx-field">
				<label for="pf-phone">Numéro de téléphone <span class="gcx-opt">(facultatif)</span></label>
				<input type="text" id="pf-phone" name="phone" inputmode="tel" autocomplete="tel" value="{{ old('phone', $gnU->phone) }}" placeholder="Ex. : 22963608513">
				@error('phone')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
			</div>
			<div class="gcx-field">
				<label for="pf-country">Pays</label>
				<select id="pf-country" name="country" autocomplete="country-name">
					<option value="">Choisir un pays</option>
					@foreach($gnPays as $c)
						<option value="{{ $c['value'] }}" {{ $c['value'] == $gnU->country ? 'selected' : '' }}>{{ $c['label'] }}</option>
					@endforeach
				</select>
			</div>
			<div class="gcx-field">
				<label for="pf-tz">Fuseau horaire</label>
				<select id="pf-tz" name="timezone">
					<option value="">Choisir un fuseau horaire</option>
					@foreach($timezones as $tz)
						<option value="{{ $tz->timezone }}" {{ $tz->timezone == $gnU->timezone ? 'selected' : '' }}>{{ $tz->timezone }} ({{ $tz->utc }})</option>
					@endforeach
				</select>
			</div>

			@if (setting('PROFILE_USER_ENABLE') == 'yes')
				<div class="gcx-field">
					<label for="pf-image">Photo de profil <span class="gcx-opt">(facultatif)</span></label>
					<div class="gcx-file">
						<input type="file" id="pf-image" name="image" accept="image/png, image/jpeg" aria-describedby="pf-image-help" class="gcx-file__in">
						<label for="pf-image" class="gcx-btn gcx-btn--ghost">Choisir une photo</label>
						<span class="gcx-file__name" id="pf-image-name" aria-live="polite">Aucun fichier choisi</span>
					</div>
					<p class="gcx-help" id="pf-image-help">Formats JPG ou PNG, 5 Mo au plus.</p>
					@error('image')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
					@if ($gnU->image)
						<div class="gcx-photo">
							<img src="{{ asset('uploads/profile/' . $gnU->image) }}" alt="Votre photo de profil actuelle" width="64" height="64">
							<button type="button" class="gcx-btn gcx-btn--danger" data-gcx-rmimg="{{ $gnU->id }}">Retirer la photo</button>
						</div>
					@endif
				</div>
			@endif
		</div>
		<p class="gcx-help" id="pf-ro-help">Le nom, le prénom, le pseudo et l’adresse e-mail ne peuvent pas être modifiés ici. Écrivez à l’équipe si une correction est nécessaire.</p>

		@foreach(($customfield ?? []) as $cf)
			@if($cf->fieldtypes != 'textarea')
				<div class="gcx-field">
					<label>{{ $cf->fieldnames }}</label>
					<input type="text" value="{{ $cf->privacymode == '1' && $cf->values ? decrypt($cf->values) : $cf->values }}" readonly>
				</div>
			@endif
		@endforeach

		<div class="gcx-form__foot"><button type="submit" class="gcx-btn gcx-btn--primary">Enregistrer les modifications</button></div>
	</form>
</section>

<section class="gcx-card" aria-labelledby="gcx-pw-t">
	<div class="gcx-card__head"><h2 id="gcx-pw-t">Changer le mot de passe</h2></div>
	<form method="POST" action="{{ route('change.password') }}" class="gcx-card__body" novalidate>
		@csrf
		@honeypot
		<div class="gcx-grid2">
			@if ($gnU->password != null)
				<div class="gcx-field">
					<label for="pw-current">Mot de passe actuel <span class="gcx-req" aria-hidden="true">*</span></label>
					<input type="password" id="pw-current" name="current_password" autocomplete="current-password" required>
					@error('current_password')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
				</div>
			@endif
			<div class="gcx-field">
				<label for="pw-new">Nouveau mot de passe <span class="gcx-req" aria-hidden="true">*</span></label>
				<div class="gcx-pw">
					<input type="password" id="pw-new" name="password" autocomplete="new-password" minlength="8" required aria-describedby="pw-help">
					<button type="button" class="gcx-pw__btn" data-gcx-pwtoggle="#pw-new" aria-pressed="false">Afficher</button>
				</div>
				<p class="gcx-help" id="pw-help">8 caractères au minimum. <button type="button" class="gcx-linkbtn" data-gcx-pwgen="#pw-new,#pw-confirm">Générer un mot de passe</button></p>
				@error('password')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
			</div>
			<div class="gcx-field">
				<label for="pw-confirm">Confirmer le mot de passe <span class="gcx-req" aria-hidden="true">*</span></label>
				<input type="password" id="pw-confirm" name="password_confirmation" autocomplete="new-password" required>
				@error('password_confirmation')<p class="gcx-error" role="alert">{{ $message }}</p>@enderror
			</div>
		</div>
		<p class="gcx-help">Après le changement, vous serez déconnecté·e et devrez vous reconnecter avec le nouveau mot de passe.</p>
		<div class="gcx-form__foot"><button type="submit" class="gcx-btn gcx-btn--primary">Changer le mot de passe</button></div>
	</form>
</section>

@if(setting('cust_profile_delete_enable') == 'on')
<section class="gcx-card gcx-card--danger" aria-labelledby="gcx-del-acc-t">
	<div class="gcx-card__head"><h2 id="gcx-del-acc-t">Supprimer mon compte</h2></div>
	<div class="gcx-card__body">
		<p>Une fois votre compte supprimé, vous ne pourrez plus vous connecter avec les mêmes identifiants. Vous devrez créer un nouveau compte. Cette action est irréversible.</p>
		<label class="gcx-check"><input type="checkbox" id="acc-agree"><span>J’ai compris @if($gnTerms) et j’accepte les <a href="{{ $gnTerms }}">conditions d’utilisation</a>@endif.</span></label>
		<div class="gcx-form__foot"><button type="button" class="gcx-btn gcx-btn--danger" id="acc-del" data-id="{{ $gnU->id }}" disabled>Supprimer mon compte</button></div>
	</div>
</section>
@endif

<dialog class="gcx-dialog" id="gcx-confirm" aria-labelledby="gcx-confirm-t">
	<form method="dialog">
		<h2 id="gcx-confirm-t"></h2>
		<p id="gcx-confirm-p"></p>
		<div class="gcx-dialog__act">
			<button class="gcx-btn gcx-btn--ghost" value="cancel">Annuler</button>
			<button class="gcx-btn gcx-btn--danger" value="ok">Confirmer</button>
		</div>
	</form>
</dialog>
@endsection

@section('scripts')
<script>
(function () {
  'use strict';
  var d = document, tok = '{{ csrf_token() }}';
  var dlg = d.getElementById('gcx-confirm');
  function ask(title, text) {
    return new Promise(function (resolve) {
      d.getElementById('gcx-confirm-t').textContent = title; d.getElementById('gcx-confirm-p').textContent = text;
      dlg.addEventListener('close', function once() { dlg.removeEventListener('close', once); resolve(dlg.returnValue === 'ok'); });
      dlg.showModal();
    });
  }
  function call(method, url) {
    return fetch(url, { method: method, credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': tok, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (r) { if (!r.ok) { throw new Error('http'); } return r.json(); });
  }

  /* afficher / masquer, générer */
  d.querySelectorAll('[data-gcx-pwtoggle]').forEach(function (b) {
    b.addEventListener('click', function () {
      var i = d.querySelector(b.getAttribute('data-gcx-pwtoggle')), show = i.type === 'password';
      i.type = show ? 'text' : 'password'; b.textContent = show ? 'Masquer' : 'Afficher'; b.setAttribute('aria-pressed', show ? 'true' : 'false');
    });
  });
  d.querySelectorAll('[data-gcx-pwgen]').forEach(function (b) {
    b.addEventListener('click', function () {
      var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789@#$%', out = '', buf = new Uint32Array(14);
      (window.crypto || window.msCrypto).getRandomValues(buf);
      for (var k = 0; k < buf.length; k++) { out += chars.charAt(buf[k] % chars.length); }
      b.getAttribute('data-gcx-pwgen').split(',').forEach(function (s) { var i = d.querySelector(s); if (i) { i.value = out; } });
      var t = d.querySelector('[data-gcx-pwtoggle]'); var first = d.querySelector('#pw-new'); if (first && first.type === 'password' && t) { t.click(); }
      if (window.toastr) { toastr.info('Mot de passe généré : notez-le avant d’enregistrer.'); }
    });
  });

  /* nom du fichier choisi (affiché en français) */
  var fin = d.getElementById('pf-image');
  if (fin) { fin.addEventListener('change', function () { d.getElementById('pf-image-name').textContent = fin.files.length ? fin.files[0].name : 'Aucun fichier choisi'; }); }

  /* retirer la photo */
  d.querySelectorAll('[data-gcx-rmimg]').forEach(function (b) {
    b.addEventListener('click', function () {
      ask('Retirer votre photo de profil ?', 'Votre photo sera supprimée de votre profil.').then(function (ok) {
        if (!ok) { return; }
        call('DELETE', '{{ url('espace-client/profil/photo') }}/' + b.getAttribute('data-gcx-rmimg')).then(function () { location.reload(); })
          .catch(function () { if (window.toastr) { toastr.error('La photo n’a pas pu être retirée.'); } });
      });
    });
  });

  /* suppression du compte */
  var agree = d.getElementById('acc-agree'), del = d.getElementById('acc-del');
  if (agree && del) {
    agree.addEventListener('change', function () { del.disabled = !agree.checked; });
    del.addEventListener('click', function () {
      ask('Supprimer définitivement votre compte ?', 'Cette action est irréversible : votre compte et vos données seront supprimés.').then(function (ok) {
        if (!ok) { return; }
        call('POST', '{{ url('espace-client/profil/supprimer') }}/' + del.getAttribute('data-id')).then(function () { location.href = '{{ url('/') }}'; })
          .catch(function () { if (window.toastr) { toastr.error('Le compte n’a pas pu être supprimé.'); } });
      });
    });
  }
})();
</script>
@endsection
