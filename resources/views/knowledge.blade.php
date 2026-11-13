@extends('layouts.master')

@php
	// Mise en page Ganho (voir layouts/gnlean) : liste des actualités
	$gnLean = true;
	$gnPosts = collect(method_exists($article, 'items') ? $article->items() : $article)->filter(function ($a) { return strtolower((string) $a->status) === 'published' && ($a->articleslug || $a->id); })->values();
	$gnPopular = collect(method_exists($populararticle ?? null, 'items') ? $populararticle->items() : ($populararticle ?? []))->filter(function ($a) { return strtolower((string) $a->status) === 'published'; })->values();
	$gnCats = collect(method_exists($categorys ?? null, 'items') ? $categorys->items() : ($categorys ?? []))->map(function ($c) {
		$c->gn_count = $c->articles->filter(function ($a) { return strtolower((string) $a->status) === 'published'; })->count();
		return $c;
	})->filter(function ($c) { return $c->gn_count > 0; })->values();

	$seo = [
		'title' => 'Actualités et ressources sur les VBG et la santé mentale',
		'description' => 'Articles et ressources de GANXÔ sur le genre, la sexualité, les violences basées sur le genre et la dépression : comprendre, se protéger, trouver de l’aide.',
		'breadcrumbs' => [['Accueil', '/'], ['Actualités', '/knowledge']],
	];
	$gcEyebrow = 'Ressources GANXÔ';
	$gcTitle = lang('Actualités');
	$gcLead = ($title->articlesub ?? '') ?: 'Actualités et ressources sur le genre, la sexualité, les violences basées sur le genre et la dépression.';
	$gcSearch = true;
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
@endpush
@push('gn_scripts')
	<script src="{{ gn_asset('assets/ganho/js/pages-content.js') }}" defer></script>
@endpush

@section('content')

	@include('includes.gc.hero')

	<section class="gn-section" aria-labelledby="gc-list-title">
		<div class="gn-container">
			@if ($gnPosts->isEmpty())
				<div class="gc-empty">
					<h2 id="gc-list-title">Cette section sera bientôt mise à jour.</h2>
					<p>Revenez bientôt : de nouveaux articles seront publiés. En attendant, la FAQ répond aux questions les plus courantes.</p>
					<p><a class="gn-btn gn-btn--primary" href="{{ url('/faq') }}">Lire la FAQ</a></p>
				</div>
			@else
				@if ($gnCats->count() > 0)
					<nav aria-label="Thèmes des articles">
						<ul class="gc-chips">
							@foreach ($gnCats as $c)
								<li><a href="{{ url('/category/' . ($c->categoryslug ?: $c->id)) }}">{{ $c->name }} <small>{{ $c->gn_count }}</small></a></li>
							@endforeach
						</ul>
					</nav>
				@endif

				<div class="gh-head" style="margin-bottom:var(--gn-space-5)">
					<h2 id="gc-list-title">Articles récents</h2>
				</div>
				<ul class="gc-grid">
					@foreach ($gnPosts as $a)
						<li @if($loop->first && $gnPosts->count() >= 4) class="gc-lead" @endif>@include('includes.gc.card', ['a' => $a, 'lead' => $loop->first && $gnPosts->count() >= 4])</li>
					@endforeach
				</ul>
				@if (method_exists($article, 'hasPages') && $article->hasPages())
					<nav class="gc-pager" aria-label="Pagination des articles">{{ $article->links('vendor.pagination.gn') }}</nav>
				@endif
			@endif
		</div>
	</section>

	@if ($gnPopular->count() > 3)
		<section class="gn-section gn-section--soft gn-section--round-top" aria-labelledby="gc-pop-title">
			<div class="gn-container gc-split">
				<div class="gh-head" style="margin-bottom:0">
					<span class="gn-eyebrow">Les plus consultés</span>
					<h2 id="gc-pop-title">Les plus lus</h2>
					<p>Les articles que les lecteurs et lectrices ouvrent le plus souvent.</p>
				</div>
				<ol class="gc-rank">
					@foreach ($gnPopular as $p)
						<li><a href="{{ url('/article/' . ($p->articleslug ?: $p->id)) }}">{{ \Illuminate\Support\Str::limit($p->title, 90) }}</a></li>
					@endforeach
				</ol>
			</div>
		</section>
	@endif

	<section class="gn-section" aria-labelledby="gc-help-title">
		<div class="gn-container">
			<div class="gc-help">
				<div>
					<h2 id="gc-help-title">Besoin d’en parler maintenant&nbsp;?</h2>
					<p>Créez une alerte : l’équipe GANXÔ la reçoit et peut vous répondre. Aucun compte n’est nécessaire.</p>
				</div>
				<div class="gc-help__a">
					<a class="gn-btn gn-btn--primary" href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
					@if (setting('FAQ_ENABLE') == 'yes')<a class="gn-btn gn-btn--ghost" href="{{ url('/faq') }}">Questions fréquentes</a>@endif
				</div>
			</div>
		</div>
	</section>

@endsection
