@extends('layouts.usermaster')

@section('content')
@php $gnDark = optional(Auth::guard('customer')->user()->custsetting)->darkmode == 1; @endphp

<div class="gcx-pagehead">
	<nav class="gcx-crumbs" aria-label="Fil d’Ariane"><ol><li><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li><li aria-current="page">Préférences</li></ol></nav>
	<h1>Préférences</h1>
	<p class="gcx-lead">Réglez l’apparence de votre espace. Ces choix ne sont visibles que par vous.</p>
</div>

<section class="gcx-card" aria-labelledby="gcx-pref-t">
	<div class="gcx-card__head"><h2 id="gcx-pref-t">Apparence</h2></div>
	<div class="gcx-card__body">
		<label class="gcx-switch">
			<input type="checkbox" id="gcx-dark" role="switch" @if($gnDark) checked @endif>
			<span class="gcx-switch__ui" aria-hidden="true"></span>
			<span class="gcx-switch__tx"><b>Mode nuit</b><small>Fond sombre, plus doux pour les yeux le soir et moins visible à l’écran.</small></span>
		</label>
		<p class="gcx-help" id="gcx-pref-msg" role="status"></p>
	</div>
</section>

<section class="gcx-card" aria-labelledby="gcx-conf-t">
	<div class="gcx-card__head"><h2 id="gcx-conf-t">Discrétion et sécurité</h2></div>
	<div class="gcx-card__body">
		<p>Pensez à vous déconnecter après chaque visite si vous utilisez un appareil partagé. Vous pouvez modifier votre mot de passe ou supprimer votre compte depuis <a href="{{ route('client.profile') }}">votre profil</a>.</p>
	</div>
</section>
@endsection

@section('scripts')
<script>
(function () {
  var box = document.getElementById('gcx-dark'); if (!box) { return; }
  box.addEventListener('change', function () {
    var on = box.checked, msg = document.getElementById('gcx-pref-msg');
    document.body.classList.toggle('dark-mode', on);
    var fd = new FormData(); fd.append('dark', on ? 1 : 0); fd.append('_token', '{{ csrf_token() }}');
    fetch('{{ url('espace-client/preferences/reglages') }}', { method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
      .then(function (r) { if (!r.ok) { throw new Error(); } msg.textContent = on ? 'Mode nuit activé.' : 'Mode nuit désactivé.'; })
      .catch(function () { msg.textContent = 'Le réglage n’a pas pu être enregistré.'; box.checked = !on; document.body.classList.toggle('dark-mode', !on); });
  });
})();
</script>
@endsection
