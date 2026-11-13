@extends('layouts.adminmaster')

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h1 class="page-title"><span class="font-weight-normal text-muted ms-2">Assistant</span></h1>
	</div>
	<div class="page-rightheader ms-md-auto">
		<a class="btn btn-outline-primary" href="{{ url('/assistant') }}" target="_blank" rel="noopener">Voir la page publique</a>
	</div>
</div>
<!--End Page header-->

@php
	$ro = $canEdit ? '' : 'disabled';
	$v = function ($k) use ($s) { return old($k, $s[$k]); };
	$on = function ($k) use ($s) { return old('_token') ? (bool) old($k) : ($s[$k] === '1'); };
	$pct = function ($a, $b) { return $b > 0 ? round($a * 100 / $b) . ' %' : '–'; };
@endphp

@if($errors->any())
	<div class="alert alert-danger" role="alert">
		<strong>Corrigez les points suivants :</strong>
		<ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
	</div>
@endif

<div class="row">
	<!-- Statistiques anonymes -->
	<div class="col-xl-12 col-lg-12 col-md-12">
		<div class="card">
			<div class="card-header border-0">
				<h4 class="card-title">Statistiques anonymisées</h4>
			</div>
			<div class="card-body">
				<p class="text-muted">Seuls des compteurs sont conservés : entrée de la base trouvée, escalade ou non, réponse trouvée ou non. Le texte des visiteurs, l’adresse IP et les comptes ne sont jamais enregistrés.</p>
				<div class="row text-center mb-4">
					<div class="col-6 col-md-3 mb-3"><div class="fs-2 fw-bold">{{ $stats['all']['total'] }}</div><div class="text-muted">Échanges (total)</div></div>
					<div class="col-6 col-md-3 mb-3"><div class="fs-2 fw-bold">{{ $stats['recent']['total'] }}</div><div class="text-muted">Échanges (30 jours)</div></div>
					<div class="col-6 col-md-3 mb-3"><div class="fs-2 fw-bold">{{ $stats['all']['total'] - $stats['all']['matched'] - $stats['all']['escalated'] }}</div><div class="text-muted">Sans réponse trouvée</div></div>
					<div class="col-6 col-md-3 mb-3"><div class="fs-2 fw-bold">{{ $stats['all']['escalated'] }}</div><div class="text-muted">Escalades (message à risque)</div></div>
				</div>
				<p class="text-muted mb-0">Taux de réponses trouvées (30 jours) : <strong>{{ $pct($stats['recent']['matched'], $stats['recent']['total']) }}</strong>. Les « sans réponse trouvée » incluent les salutations, les demandes hors sujet et les questions de diagnostic : elles aident à repérer les sujets à ajouter à la base de connaissances.</p>
			</div>
		</div>
	</div>

	<!-- Réglages -->
	<div class="col-xl-12 col-lg-12 col-md-12">
		<form method="POST" action="{{ route('admin.assistant.settings') }}" class="card">
			@csrf
			<div class="card-header border-0">
				<h4 class="card-title">Réglages de l’assistant</h4>
			</div>
			<div class="card-body">
				<div class="form-check form-switch mb-4">
					<input class="form-check-input" type="checkbox" role="switch" id="enabled" name="enabled" value="1" {{ $on('enabled') ? 'checked' : '' }} {{ $ro }}>
					<label class="form-check-label fw-bold" for="enabled">Assistant activé</label>
					<div class="text-muted">Désactivé : le chat disparaît de l’accueil et de la page /assistant, qui proposent seulement de créer une alerte.</div>
				</div>

				<h5 class="mb-3">Textes affichés</h5>
				<div class="row">
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="greeting">Message d’accueil</label>
						<textarea class="form-control" id="greeting" name="greeting" rows="3" maxlength="600" {{ $ro }}>{{ $v('greeting') }}</textarea>
					</div>
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="disclaimer">Avertissement (« ne remplace pas un·e professionnel·le »)</label>
						<textarea class="form-control" id="disclaimer" name="disclaimer" rows="3" maxlength="400" {{ $ro }}>{{ $v('disclaimer') }}</textarea>
					</div>
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="fallback">Réponse quand rien ne correspond</label>
						<textarea class="form-control" id="fallback" name="fallback" rows="3" maxlength="900" {{ $ro }}>{{ $v('fallback') }}</textarea>
					</div>
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="offtopic">Réponse hors sujet</label>
						<textarea class="form-control" id="offtopic" name="offtopic" rows="3" maxlength="900" {{ $ro }}>{{ $v('offtopic') }}</textarea>
					</div>
					<div class="col-lg-12 mb-3">
						<label class="form-label" for="quick_questions">Questions rapides (une par ligne, 6 maximum affichées)</label>
						<textarea class="form-control" id="quick_questions" name="quick_questions" rows="4" maxlength="800" {{ $ro }}>{{ $v('quick_questions') }}</textarea>
					</div>
				</div>

				<h5 class="mt-4 mb-3">Escalade : messages à risque</h5>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" role="switch" id="escalation_enabled" name="escalation_enabled" value="1" {{ $on('escalation_enabled') ? 'checked' : '' }} {{ $ro }}>
					<label class="form-check-label" for="escalation_enabled">Détecter les messages à risque</label>
				</div>
				<div class="row">
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="escalation_keywords">Mots-clés et expressions déclencheurs (séparés par des virgules)</label>
						<textarea class="form-control" id="escalation_keywords" name="escalation_keywords" rows="6" {{ $ro }}>{{ $v('escalation_keywords') }}</textarea>
						<div class="text-muted">La casse, les accents et les petites fautes de frappe sont tolérés (ex. « frappe » détecte aussi « frappée »). Les mots de moins de 5 lettres doivent être exacts.</div>
					</div>
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="escalation_message">Message d’aide immédiate</label>
						<textarea class="form-control" id="escalation_message" name="escalation_message" rows="6" maxlength="1500" {{ $ro }}>{{ $v('escalation_message') }}</textarea>
						<div class="text-muted">Il s’affiche avec les boutons « Créer une alerte » et « Utiliser le bouton SOS ». Ne citez pas de numéro d’urgence propre à un pays.</div>
					</div>
				</div>

				<h5 class="mt-4 mb-3">Garde-fous</h5>
				@foreach($guardrails as $k => $label)
					<div class="form-check form-switch mb-2">
						<input class="form-check-input" type="checkbox" role="switch" id="{{ $k }}" name="{{ $k }}" value="1" {{ $on($k) ? 'checked' : '' }} {{ $ro }}>
						<label class="form-check-label" for="{{ $k }}">{{ $label }}</label>
					</div>
				@endforeach
				<div class="row mt-3">
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="no_diagnosis_text">Réponse à une demande de diagnostic</label>
						<textarea class="form-control" id="no_diagnosis_text" name="no_diagnosis_text" rows="4" maxlength="900" {{ $ro }}>{{ $v('no_diagnosis_text') }}</textarea>
					</div>
					<div class="col-lg-6 mb-3">
						<label class="form-label" for="pii_text">Rappel si le visiteur saisit des informations personnelles</label>
						<textarea class="form-control" id="pii_text" name="pii_text" rows="4" maxlength="600" {{ $ro }}>{{ $v('pii_text') }}</textarea>
					</div>
				</div>
			</div>
			@if($canEdit)
			<div class="card-footer">
				<div class="form-group float-end">
					<button type="submit" class="btn btn-secondary">{{ lang('Sauvegarder les modifications') }}</button>
				</div>
			</div>
			@endif
		</form>
	</div>

	<!-- Base de connaissances -->
	<div class="col-xl-12 col-lg-12 col-md-12">
		<div class="card">
			<div class="card-header border-0 d-flex justify-content-between align-items-center">
				<h4 class="card-title">Base de connaissances ({{ $kb->count() }})</h4>
				@if($canEdit)<button type="button" class="btn btn-primary" id="kb-new" data-bs-toggle="modal" data-bs-target="#kbModal">Ajouter une entrée</button>@endif
			</div>
			<div class="card-body">
				<p class="text-muted">Dans l’adresse du bouton, {alert} désigne la page de création d’alerte et {forum} le forum. Les mots-clés (séparés par des virgules) peuvent être sans accent ; plusieurs mots dans une expression doivent tous apparaître dans la question.</p>
				<div class="table-responsive">
					<table class="table table-bordered text-nowrap w-100">
						<thead>
							<tr><th>Ordre</th><th>Question</th><th>Mots-clés</th><th>Bouton</th><th>Utilisée</th><th>Actif</th><th>Actions</th></tr>
						</thead>
						<tbody>
						@foreach($kb as $e)
							<tr>
								<td>{{ $e->sort_order }}</td>
								<td class="text-wrap" style="min-width:16rem">{{ $e->question }}</td>
								<td class="text-wrap" style="min-width:14rem"><small>{{ \Illuminate\Support\Str::limit($e->keywords, 90) }}</small></td>
								<td class="text-wrap">@if($e->cta_label){{ $e->cta_label }} <small class="text-muted">{{ $e->cta_url }}</small>@else<span class="text-muted">–</span>@endif</td>
								<td>{{ $perEntry[$e->id] ?? 0 }}</td>
								<td>@if($e->active)<span class="badge bg-success">Actif</span>@else<span class="badge bg-secondary">Inactif</span>@endif</td>
								<td>
									@if($canEdit)
									<button type="button" class="btn btn-sm btn-primary kb-edit" data-bs-toggle="modal" data-bs-target="#kbModal"
										data-kb="{{ json_encode(['id' => $e->id, 'question' => $e->question, 'answer' => $e->answer, 'keywords' => $e->keywords, 'cta_label' => $e->cta_label, 'cta_url' => $e->cta_url, 'active' => (bool) $e->active, 'sort_order' => $e->sort_order], JSON_UNESCAPED_UNICODE) }}">Modifier</button>
									<form method="POST" action="{{ route('admin.assistant.kb.toggle', $e->id) }}" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-outline-secondary">{{ $e->active ? 'Désactiver' : 'Activer' }}</button></form>
									<form method="POST" action="{{ route('admin.assistant.kb.delete', $e->id) }}" class="d-inline" onsubmit="return confirm('Supprimer cette entrée ?');">@csrf<button type="submit" class="btn btn-sm btn-danger">Supprimer</button></form>
									@endif
								</td>
							</tr>
						@endforeach
						@if($kb->isEmpty())<tr><td colspan="7" class="text-center text-muted">Aucune entrée : l’assistant répondra uniquement par son message de repli.</td></tr>@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('modal')
@if($canEdit)
<div class="modal fade" id="kbModal" tabindex="-1" aria-labelledby="kbModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<form method="POST" action="{{ route('admin.assistant.kb.save') }}" class="modal-content" id="kb-form">
			@csrf
			<input type="hidden" name="id" id="kb-id" value="{{ old('id') }}">
			<div class="modal-header">
				<h5 class="modal-title" id="kbModalTitle">Entrée de la base de connaissances</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<label class="form-label" for="kb-question">Question</label>
					<input class="form-control" id="kb-question" name="question" maxlength="255" required value="{{ old('question') }}">
				</div>
				<div class="mb-3">
					<label class="form-label" for="kb-answer">Réponse</label>
					<textarea class="form-control" id="kb-answer" name="answer" rows="8" maxlength="3000" required>{{ old('answer') }}</textarea>
					<div class="text-muted">Pas de numéro de téléphone, de loi ni de statistique inventés. Ne demandez jamais d’informations identifiantes.</div>
				</div>
				<div class="mb-3">
					<label class="form-label" for="kb-keywords">Mots-clés (séparés par des virgules)</label>
					<textarea class="form-control" id="kb-keywords" name="keywords" rows="3" maxlength="1500">{{ old('keywords') }}</textarea>
				</div>
				<div class="row">
					<div class="col-md-5 mb-3">
						<label class="form-label" for="kb-cta-label">Texte du bouton (optionnel)</label>
						<input class="form-control" id="kb-cta-label" name="cta_label" maxlength="80" value="{{ old('cta_label') }}">
					</div>
					<div class="col-md-7 mb-3">
						<label class="form-label" for="kb-cta-url">Adresse du bouton</label>
						<input class="form-control" id="kb-cta-url" name="cta_url" maxlength="255" placeholder="{alert}, {forum}, /contact-us ou https://…" value="{{ old('cta_url') }}">
					</div>
					<div class="col-md-4 mb-3">
						<label class="form-label" for="kb-sort">Ordre d’affichage</label>
						<input class="form-control" id="kb-sort" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order') }}">
					</div>
					<div class="col-md-8 mb-3 d-flex align-items-end">
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" role="switch" id="kb-active" name="active" value="1" {{ old('_token') ? (old('active') ? 'checked' : '') : 'checked' }}>
							<label class="form-check-label" for="kb-active">Entrée active</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
				<button type="submit" class="btn btn-secondary">Enregistrer l’entrée</button>
			</div>
		</form>
	</div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
	var f = document.getElementById('kb-form');
	if (!f) return;
	function fill(d) {
		d = d || {};
		document.getElementById('kb-id').value = d.id || '';
		document.getElementById('kb-question').value = d.question || '';
		document.getElementById('kb-answer').value = d.answer || '';
		document.getElementById('kb-keywords').value = d.keywords || '';
		document.getElementById('kb-cta-label').value = d.cta_label || '';
		document.getElementById('kb-cta-url').value = d.cta_url || '';
		document.getElementById('kb-sort').value = (d.sort_order === undefined ? '' : d.sort_order);
		document.getElementById('kb-active').checked = d.active === undefined ? true : !!d.active;
	}
	document.querySelectorAll('.kb-edit').forEach(function (b) {
		b.addEventListener('click', function () { try { fill(JSON.parse(b.getAttribute('data-kb'))); } catch (e) {} });
	});
	var n = document.getElementById('kb-new');
	if (n) n.addEventListener('click', function () { fill(null); });
	@if(session('kb_form_open'))
	window.addEventListener('load', function () { if (window.bootstrap) new bootstrap.Modal(document.getElementById('kbModal')).show(); });
	@endif
});
</script>
@endsection
