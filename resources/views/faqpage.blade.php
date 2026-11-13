@extends('layouts.master')

@php
	// Mise en page Ganho (voir layouts/gnlean) : FAQ par thèmes, accordéon natif, FAQPage JSON-LD = éléments réellement affichés
	$gnLean = true;
	$gnLogged = Auth::guard('customer')->check() && Auth::guard('customer')->user();
	$gnGroups = [];
	foreach (($faqcats ?? []) as $c) {
		$all = $c->faqdetails()->where('status', '1')->get();
		if ($all->isEmpty()) { continue; }
		$items = [];
		foreach ($all->take(5) as $f) {
			$locked = $f->privatemode == 1 && !$gnLogged;
			$items[] = ['q' => trim(strip_tags($f->question)), 'html' => $locked ? '' : gn_prose($f->answer)['html'], 'plain' => $locked ? '' : gn_excerpt($f->answer, 5000), 'locked' => $locked];
		}
		$gnGroups[] = ['id' => $c->id, 'name' => $c->faqcategoryname, 'items' => $items, 'total' => $all->count()];
	}
	$gnFaqLd = [];
	foreach ($gnGroups as $g) { foreach ($g['items'] as $it) { if (!$it['locked'] && $it['plain'] !== '' && $it['q'] !== '') { $gnFaqLd[] = [$it['q'], $it['plain']]; } } }

	$seo = [
		'title' => 'FAQ : questions fréquentes sur les VBG et GANXÔ',
		'description' => 'Réponses aux questions fréquentes : qu’est-ce qu’une violence basée sur le genre, comment fonctionne GANXÔ Alerte VBG et quelle aide recevoir.',
		'breadcrumbs' => [['Accueil', '/'], ['FAQ', '/faq']],
		'faq' => $gnFaqLd,
	];
	$gcEyebrow = 'FAQ';
	$gcTitle = 'Questions fréquentes';
	$gcLead = ($title->faqsub ?? '') ?: 'Des réponses claires aux questions les plus courantes sur les violences basées sur le genre et sur GANXÔ.';
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
@endpush

@section('content')

	@include('includes.gc.hero')

	<section class="gn-section gc-faq-sec" aria-label="Questions et réponses">
		<div class="gn-container">
			@if (count($gnGroups) === 0)
				<div class="gc-empty">
					<h2>Aucune question pour le moment</h2>
					<p>La FAQ sera bientôt mise à jour.</p>
					<p><a class="gn-btn gn-btn--primary" href="{{ url('/knowledge') }}">Lire les actualités</a></p>
				</div>
			@else
				@php
					$gnNorm = function ($s) { return mb_strtolower(trim(preg_replace('/[\s\p{P}]+/u', ' ', strip_tags((string) $s)))); };
				@endphp
				<div class="gc-faq-layout">
					@if (count($gnGroups) > 1)
						<nav class="gc-faq-nav" aria-label="Sur cette page">
							<p class="gc-faq-nav__t">Sur cette page</p>
							<ul>
								@foreach ($gnGroups as $g)
									<li><a href="#faq-{{ $g['id'] }}">{{ $g['name'] }}</a></li>
								@endforeach
							</ul>
						</nav>
					@endif
					<div class="gc-faq-main">
						@foreach ($gnGroups as $g)
							@php $gnSolo = count($g['items']) === 1 && $gnNorm($g['name']) === $gnNorm($g['items'][0]['q']); @endphp
							<div class="gc-faq-block {{ $gnSolo ? 'gc-faq-block--solo' : '' }}" id="faq-{{ $g['id'] }}">
								@unless ($gnSolo)
									<div class="gc-faq-block__h">
										<h2>{{ $g['name'] }}</h2>
										@if ($g['total'] > 5)<a href="{{ url('faq/' . $g['id']) }}">Tout voir ({{ $g['total'] }})</a>@endif
									</div>
								@endunless
								@include('includes.gc.faq-items', ['items' => $g['items'], 'hl' => $gnSolo ? 2 : 3])
							</div>
						@endforeach
					</div>
				</div>
			@endif
		</div>
	</section>

	@include('includes.gc.faq-help')

@endsection
