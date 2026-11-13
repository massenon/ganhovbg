@extends('layouts.adminmaster')

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">Newsletter</span></h1>
	</div>
	<div class="page-rightheader ms-md-auto">
		<a class="btn btn-outline-primary" href="{{ route('admin.newsletter.export', $filters) }}">Exporter en CSV</a>
	</div>
</div>
<!--End Page header-->

@if(session('success'))
	<div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif

<div class="row">
	<div class="col-md-4 mb-4"><div class="card h-100"><div class="card-body text-center"><div class="fs-2 fw-bold">{{ $stats['actifs'] }}</div><div class="text-muted">Abonné·es actifs</div></div></div></div>
	<div class="col-md-4 mb-4"><div class="card h-100"><div class="card-body text-center"><div class="fs-2 fw-bold">{{ $stats['nouveaux'] }}</div><div class="text-muted">Nouveaux (30 jours)</div></div></div></div>
	<div class="col-md-4 mb-4"><div class="card h-100"><div class="card-body text-center"><div class="fs-2 fw-bold">{{ $stats['desinscrits'] }}</div><div class="text-muted">Désinscrit·es</div></div></div></div>
</div>

@if($canEdit)
<div class="card" id="gn-nl-send">
	<div class="card-header border-0">
		<h4 class="card-title">Envoyer une newsletter</h4>
	</div>
	<div class="card-body">
		<p class="text-muted">Le message part aux <strong>{{ $stats['actifs'] }}</strong> abonné·es actif·ves, par lots de 25, avec un lien de désinscription personnel et un rappel du consentement. Plafond par envoi : {{ $maxPerCampaign }} destinataires (limite habituelle des hébergeurs) ; 3 envois par 24 heures au maximum.</p>
		<div id="gn-nl-alert" class="alert d-none" role="status" aria-live="polite"></div>
		<form id="gn-nl-form" novalidate data-no-spa>
			@csrf
			<div class="row g-3">
				<div class="col-12">
					<label class="form-label" for="gn-nl-subject">Objet</label>
					<input type="text" class="form-control" id="gn-nl-subject" name="subject" maxlength="190" placeholder="Ex. Les nouvelles de novembre sur GANXÔ" required>
				</div>
				<div class="col-12">
					<label class="form-label" for="gn-nl-body">Message</label>
					<textarea class="form-control" id="gn-nl-body" name="body" rows="8" maxlength="8000" placeholder="Écrivez votre message. Une ligne vide sépare deux paragraphes." required></textarea>
				</div>
				<div class="col-md-4">
					<label class="form-label" for="gn-nl-blabel">Texte du bouton (facultatif)</label>
					<input type="text" class="form-control" id="gn-nl-blabel" name="button_label" maxlength="60" placeholder="Lire les actualités">
				</div>
				<div class="col-md-8">
					<label class="form-label" for="gn-nl-burl">Lien du bouton</label>
					<input type="url" class="form-control" id="gn-nl-burl" name="button_url" maxlength="255" placeholder="https://ganho.app/knowledge">
				</div>
			</div>
			<div class="form-check mt-4">
				<input class="form-check-input" type="checkbox" id="gn-nl-confirm">
				<label class="form-check-label" for="gn-nl-confirm">J’ai relu le message (aperçu et test) et je confirme l’envoi aux {{ $stats['actifs'] }} abonné·es actif·ves.</label>
			</div>
			<div class="d-flex flex-wrap gap-2 mt-3">
				<button type="button" class="btn btn-outline-primary" id="gn-nl-preview">Aperçu</button>
				<button type="button" class="btn btn-outline-primary" id="gn-nl-test">Envoyer un test à mon adresse</button>
				<button type="button" class="btn btn-primary" id="gn-nl-go" disabled>Envoyer à {{ $stats['actifs'] }} abonné·e(s)</button>
			</div>
			<div class="mt-3 d-none" id="gn-nl-progress">
				<div class="progress" style="height:12px" role="progressbar" aria-label="Avancement de l’envoi" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar" id="gn-nl-bar" style="width:0%"></div></div>
				<p class="mt-2 mb-0" id="gn-nl-ptext" role="status" aria-live="polite"></p>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="gn-nl-modal" tabindex="-1" aria-labelledby="gn-nl-modal-t" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title" id="gn-nl-modal-t">Aperçu de l’e-mail</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button></div>
			<div class="modal-body p-0"><iframe id="gn-nl-frame" title="Aperçu de la newsletter" sandbox="" style="width:100%;height:560px;border:0;background:#F8F1FF"></iframe></div>
		</div>
	</div>
</div>
@endif

@if($campaigns->count())
<div class="card">
	<div class="card-header border-0"><h4 class="card-title">Historique des envois</h4></div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-vcenter text-nowrap mb-0">
				<thead><tr><th>Objet</th><th>Date</th><th>Destinataires</th><th>Envoyés</th><th>Échecs</th><th>État</th>@if($canEdit)<th class="text-end">Actions</th>@endif</tr></thead>
				<tbody>
				@foreach($campaigns as $c)
					<tr>
						<td>{{ \Illuminate\Support\Str::limit($c->subject, 48) }}</td>
						<td>{{ \Carbon\Carbon::parse($c->created_at)->locale('fr')->translatedFormat('j F Y H:i') }}</td>
						<td>{{ $c->total }}</td><td>{{ $c->sent_count }}</td><td>{{ $c->failed_count }}</td>
						<td>@if($c->status === 'sent')<span class="badge bg-success">Terminé</span>@elseif($c->status === 'sending')<span class="badge bg-warning text-dark">En cours</span>@else<span class="badge bg-secondary">Abandonné</span>@endif
							@if($c->last_error && $c->failed_count)<small class="text-muted d-block">Dernière erreur : {{ \Illuminate\Support\Str::limit($c->last_error, 60) }}</small>@endif</td>
						@if($canEdit)
						<td class="text-end">
							@if($c->status === 'sending')
								<button type="button" class="btn btn-sm btn-primary gn-nl-resume" data-id="{{ $c->id }}" data-total="{{ $c->total }}">Reprendre l’envoi</button>
								<form method="POST" action="{{ route('admin.newsletter.abort', $c->id) }}" class="d-inline" data-no-spa>@csrf<button type="submit" class="btn btn-sm btn-outline-danger">Abandonner</button></form>
							@endif
						</td>
						@endif
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endif

<div class="card">
	<div class="card-header border-0">
		<h4 class="card-title">Liste des abonné·es</h4>
	</div>
	<div class="card-body">
		<form method="GET" action="{{ route('admin.newsletter') }}" class="row g-2 mb-4" role="search">
			<div class="col-md-6">
				<label class="form-label" for="nl-q">Rechercher une adresse e-mail</label>
				<input type="search" id="nl-q" name="q" class="form-control" value="{{ $filters['q'] }}" placeholder="exemple@domaine.org">
			</div>
			<div class="col-md-3">
				<label class="form-label" for="nl-etat">État</label>
				<select id="nl-etat" name="etat" class="form-select">
					<option value="">Tous</option>
					<option value="actifs" @selected($filters['etat'] === 'actifs')>Actifs</option>
					<option value="desinscrits" @selected($filters['etat'] === 'desinscrits')>Désinscrit·es</option>
				</select>
			</div>
			<div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100">Filtrer</button></div>
		</form>

		<div class="table-responsive">
			<table class="table table-vcenter text-nowrap mb-0">
				<thead>
					<tr><th>Adresse e-mail</th><th>Inscrit·e le</th><th>État</th>@if($canEdit)<th class="text-end">Actions</th>@endif</tr>
				</thead>
				<tbody>
					@forelse($subscribers as $s)
						<tr>
							<td>{{ $s->email }}</td>
							<td>{{ \Carbon\Carbon::parse($s->created_at)->locale('fr')->translatedFormat('j F Y') }}</td>
							<td>@if($s->unsubscribed_at)<span class="badge bg-secondary">Désinscrit·e</span>@else<span class="badge bg-success">Actif</span>@endif</td>
							@if($canEdit)
							<td class="text-end">
								<form method="POST" action="{{ route('admin.newsletter.toggle', $s->id) }}" class="d-inline">@csrf
									<button type="submit" class="btn btn-sm btn-outline-secondary">{{ $s->unsubscribed_at ? 'Réactiver' : 'Désinscrire' }}</button>
								</form>
								<form method="POST" action="{{ route('admin.newsletter.delete', $s->id) }}" class="d-inline" onsubmit="return confirm('Supprimer définitivement cette adresse de la liste ?');">@csrf
									<button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
								</form>
							</td>
							@endif
						</tr>
					@empty
						<tr><td colspan="4" class="text-center text-muted py-5">Aucun·e abonné·e pour le moment.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="mt-4">{{ $subscribers->links('pagination::bootstrap-4') }}</div>
		<p class="text-muted mt-4 mb-0">Seuls l’adresse e-mail et la date de consentement sont conservées, pas d’adresse IP. Les personnes se désinscrivent elles-mêmes par le lien présent dans chaque envoi.</p>
	</div>
</div>


@if($canEdit)
<script>
(function () {
	'use strict';
	var URL = {
		preview: @json(route('admin.newsletter.preview')),
		test: @json(route('admin.newsletter.test')),
		start: @json(route('admin.newsletter.campaign')),
		batch: @json(route('admin.newsletter.batch', 0))
	};
	var token = @json(csrf_token());
	var form = document.getElementById('gn-nl-form');
	if (!form) { return; }
	var alertBox = document.getElementById('gn-nl-alert');
	var go = document.getElementById('gn-nl-go'), confirmBox = document.getElementById('gn-nl-confirm');
	var busy = false;

	function say(msg, kind) {
		alertBox.className = 'alert alert-' + (kind || 'info');
		alertBox.textContent = msg;
	}
	function data() { return new FormData(form); }
	function post(url, body) {
		return fetch(url, {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}, body: body || new FormData()})
			.then(function (r) { return r.json().catch(function () { return {ok: false, message: 'Réponse inattendue du serveur (code ' + r.status + ').'}; }).then(function (j) { j._status = r.status; return j; }); });
	}
	confirmBox.addEventListener('change', function () { go.disabled = !confirmBox.checked || busy; });

	document.getElementById('gn-nl-preview').addEventListener('click', function () {
		post(URL.preview, data()).then(function (j) {
			if (!j.ok) { say(j.message || 'Aperçu impossible.', 'danger'); return; }
			document.getElementById('gn-nl-frame').srcdoc = j.html;
			var m = document.getElementById('gn-nl-modal');
			if (window.bootstrap) { window.bootstrap.Modal.getOrCreateInstance(m).show(); } else if (window.jQuery) { window.jQuery(m).modal('show'); }
		});
	});
	document.getElementById('gn-nl-test').addEventListener('click', function () {
		say('Envoi du test…', 'info');
		post(URL.test, data()).then(function (j) { say(j.message || (j.ok ? 'Test envoyé.' : 'Échec du test.'), j.ok ? 'success' : 'danger'); });
	});

	function progress(sent, failed, total) {
		var done = sent + failed, pct = total ? Math.round(done * 100 / total) : 100;
		document.getElementById('gn-nl-progress').classList.remove('d-none');
		var bar = document.getElementById('gn-nl-bar'); bar.style.width = pct + '%';
		bar.parentNode.setAttribute('aria-valuenow', pct);
		document.getElementById('gn-nl-ptext').textContent = 'Envoi en cours : ' + sent + ' envoyé(s)' + (failed ? ', ' + failed + ' échec(s)' : '') + ' sur ' + total + '. Ne fermez pas cette page (vous pourrez reprendre si besoin).';
	}
	function loop(id, total) {
		busy = true; go.disabled = true;
		(function next() {
			post(URL.batch.replace(/\/0\/lot$/, '/' + id + '/lot')).then(function (j) {
				if (!j.ok) {
					busy = false;
					say((j.message || 'L’envoi a été interrompu.') + (j.busy ? '' : ' Vous pouvez le reprendre depuis l’historique.'), j.busy ? 'warning' : 'danger');
					return;
				}
				progress(j.sent, j.failed, j.total || total);
				if (j.done) {
					busy = false;
					say('Envoi terminé : ' + j.sent + ' message(s) envoyé(s)' + (j.failed ? ', ' + j.failed + ' échec(s)' : '') + '. La page va se recharger…', j.failed ? 'warning' : 'success');
					setTimeout(function () { location.reload(); }, 1800);
				} else { next(); }
			}).catch(function () { busy = false; say('Erreur réseau : l’envoi est en pause, reprenez-le depuis l’historique.', 'danger'); });
		})();
	}
	go.addEventListener('click', function () {
		if (!confirmBox.checked) { return; }
		say('Préparation de l’envoi…', 'info'); go.disabled = true;
		post(URL.start, data()).then(function (j) {
			if (!j.ok) { say(j.message || 'Impossible de lancer l’envoi.', 'danger'); go.disabled = !confirmBox.checked; return; }
			say('Envoi lancé pour ' + j.total + ' abonné·e(s).', 'info');
			loop(j.id, j.total);
		});
	});
	document.querySelectorAll('.gn-nl-resume').forEach(function (b) {
		b.addEventListener('click', function () { say('Reprise de l’envoi…', 'info'); loop(b.dataset.id, +b.dataset.total); });
	});
})();
</script>
@endif

@endsection
