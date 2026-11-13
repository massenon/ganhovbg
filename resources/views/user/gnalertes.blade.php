@extends('layouts.usermaster')

@section('content')
@php extract(include resource_path('views/includes/user/gnmaps.php')); @endphp
@php $gnFr = fn ($d) => $d ? \Carbon\Carbon::parse($d)->locale('fr')->translatedFormat('j F Y') : '—'; @endphp

<div class="gcx-pagehead">
	<nav class="gcx-crumbs" aria-label="Fil d’Ariane"><ol><li><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li><li aria-current="page">{{ $conf['titre'] }}</li></ol></nav>
	<h1>{{ $conf['titre'] }}</h1>
	<p class="gcx-lead">{{ $conf['lead'] }}</p>
</div>

<div class="gcx-tabs" role="tablist" aria-label="Filtrer par état">
	<a role="tab" href="{{ route('gn.alertes', 'en-attente') }}" aria-selected="{{ $etat === 'en-attente' ? 'true' : 'false' }}">En attente</a>
	<a role="tab" href="{{ route('gn.alertes', 'suivies') }}" aria-selected="{{ $etat === 'suivies' ? 'true' : 'false' }}">Suivies</a>
	<a role="tab" href="{{ route('gn.alertes', 'resolues') }}" aria-selected="{{ $etat === 'resolues' ? 'true' : 'false' }}">Résolues</a>
</div>

<section class="gcx-card" aria-labelledby="gcx-list-t">
	<div class="gcx-card__head">
		<h2 id="gcx-list-t">{{ $tickets->count() }} {{ $tickets->count() > 1 ? 'dossiers' : 'dossier' }}</h2>
		<label class="gcx-search"><span class="gcx-sr">Rechercher dans mes alertes</span><input type="search" placeholder="Rechercher (objet, numéro)…" data-gcx-filter="#gcx-list"></label>
	</div>

	@if($tickets->isEmpty())
		<div class="gcx-empty">
			<p>{{ $conf['vide'] }}</p>
			@if($etat !== 'resolues')<a class="gcx-btn gcx-btn--cta" href="{{ route('client.ticket') }}">+ Créer une alerte / un cas</a>@endif
		</div>
	@else
		<ul class="gcx-list" id="gcx-list">
			@foreach($tickets as $t)
				@php $step = $gnEtape($t); @endphp
				<li class="gcx-item" data-search="{{ \Illuminate\Support\Str::lower($t->subject . ' ' . $t->ticket_id) }}" data-id="{{ $t->id }}">
					<div class="gcx-item__main">
						<a class="gcx-item__t" href="{{ route('loadmore.load_data', $t->ticket_id) }}">{{ $t->subject }}</a>
						<ul class="gcx-meta">
							<li>#{{ $t->ticket_id }}</li>
							<li>Créée le {{ $gnFr($t->created_at) }}</li>
							@if($t->category)<li>{{ $t->category->name }}</li>@endif
							@if($t->priority)<li>Priorité : {{ $gnPrio[$t->priority] ?? $t->priority }}</li>@endif
							<li>Dernière activité : {{ $gnFr($t->last_reply ?: $t->updated_at) }}</li>
						</ul>
						<ol class="gcx-steps" aria-label="Avancement : {{ $gnSteps[$step - 1] }}">
							@foreach($gnSteps as $i => $label)
								<li @if($i + 1 < $step) class="is-done" @elseif($i + 1 === $step) class="is-now" aria-current="step" @endif><span>{{ $label }}</span></li>
							@endforeach
						</ol>
					</div>
					<div class="gcx-item__side">
						<span class="gcx-pill gcx-pill--{{ $gnStatutCls[$t->status] ?? 'info' }}">{{ $gnStatut[$t->status] ?? $t->status }}</span>
						<div class="gcx-item__act">
							<a class="gcx-btn gcx-btn--ghost" href="{{ route('loadmore.load_data', $t->ticket_id) }}">Ouvrir le dossier</a>
							<button type="button" class="gcx-btn gcx-btn--danger" data-gcx-del="{{ route('client.ticket.delete', $t->id) }}" data-gcx-del-name="{{ $t->subject }}">Supprimer</button>
						</div>
					</div>
				</li>
			@endforeach
		</ul>
		<p class="gcx-empty gcx-empty--filter" data-gcx-nomatch hidden>Aucune alerte ne correspond à votre recherche.</p>
	@endif
</section>

<dialog class="gcx-dialog" id="gcx-del-dialog" aria-labelledby="gcx-del-t">
	<form method="dialog">
		<h2 id="gcx-del-t">Supprimer cette alerte ?</h2>
		<p>« <span data-gcx-del-label></span> » sera définitivement supprimée, avec ses messages et pièces jointes. Cette action est irréversible.</p>
		<div class="gcx-dialog__act">
			<button class="gcx-btn gcx-btn--ghost" value="cancel">Annuler</button>
			<button class="gcx-btn gcx-btn--danger" value="ok" data-gcx-del-ok>Supprimer</button>
		</div>
	</form>
</dialog>
@endsection
