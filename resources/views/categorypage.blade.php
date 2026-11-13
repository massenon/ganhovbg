@extends('layouts.master')

@php
	// Mise en page Ganho (voir layouts/gnlean) : articles d'une catégorie.
	abort_if(empty($category), 404);
	$gnLean = true;
	$gnCatName = $category->name;
	$gnPosts = collect(method_exists($categorys ?? null, 'items') ? $categorys->items() : ($categorys ?? []))->flatMap(function ($c) { return $c->articles; })
		->filter(function ($a) { return strtolower((string) $a->status) === 'published'; })
		->sortByDesc('created_at')->unique('id')->values();
	$gnCatUrl = '/category/' . ($category->categoryslug ?: $category->id);
	$gnPopular = collect(method_exists($populararticles ?? null, 'items') ? $populararticles->items() : ($populararticles ?? []))->filter(function ($a) { return strtolower((string) $a->status) === 'published'; })->values();
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');

	$seo = [
		'title' => \Illuminate\Support\Str::limit($gnCatName, 44, '…') . ' : articles | GANXÔ',
		'description' => 'Tous les articles de la catégorie « ' . $gnCatName . ' » sur GANXÔ Alerte VBG : ' . $gnPosts->count() . ' ' . ($gnPosts->count() > 1 ? 'articles publiés' : 'article publié') . ' pour comprendre et trouver de l’aide.',
		'breadcrumbs' => [['Accueil', '/'], ['Actualités', '/knowledge'], [\Illuminate\Support\Str::limit($gnCatName, 50, '…'), $gnCatUrl]],
	];
	$gcEyebrow = 'Catégorie';
	$gcTitle = $gnCatName;
	$gcLead = $gnPosts->count() . ' ' . ($gnPosts->count() > 1 ? 'articles' : 'article') . ' dans cette catégorie.';
	$gcSearch = true;
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
			<div class="gh-head" style="margin-bottom:var(--gn-space-5)">
				<h2 id="gc-list-title">Articles de la catégorie</h2>
			</div>
			@if ($gnPosts->isEmpty())
				<div class="gc-empty">
					<h2>Aucun article pour le moment</h2>
					<p>Cette catégorie sera bientôt complétée.</p>
					<p><a class="gn-btn gn-btn--primary" href="{{ url('/knowledge') }}">Voir toutes les actualités</a></p>
				</div>
			@else
				<ul class="gc-grid">
					@foreach ($gnPosts as $a)
						<li>@include('includes.gc.card', ['a' => $a])</li>
					@endforeach
				</ul>
				<p style="margin-top:var(--gn-space-6)"><a href="{{ url('/knowledge') }}">Voir toutes les actualités</a></p>
			@endif
		</div>
	</section>

	@if ($gnPopular->count() > 0)
		<section class="gn-section gn-section--soft gn-section--round-top" aria-labelledby="gc-pop-title">
			<div class="gn-container gc-split">
				<div class="gh-head" style="margin-bottom:0">
					<span class="gn-eyebrow">À lire aussi</span>
					<h2 id="gc-pop-title">Les plus lus</h2>
					<p>Les articles les plus consultés du site.</p>
				</div>
				<ol class="gc-rank">
					@foreach ($gnPopular as $p)
						<li><a href="{{ url('/article/' . ($p->articleslug ?: $p->id)) }}">{{ \Illuminate\Support\Str::limit($p->title, 90) }}</a></li>
					@endforeach
				</ol>
			</div>
		</section>
	@endif

@endsection
