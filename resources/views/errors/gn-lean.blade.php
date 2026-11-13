{{-- Page d'erreur complète (en-tête, pied de page, SOS) : 403, 404, 419, 429. Params : $gcCode, $gcHeading, $gcText, $gcPhoto, $gcActions, $gcLinks (bool) --}}
@extends('layouts.master')

@php
	$gnLean = true;
	$seo = [
		'title' => $gcHeading . ' | GANXÔ',
		'description' => $gcText,
		'robots' => 'noindex,follow',
		'canonical' => gn_url('/'),
		'breadcrumbs' => [['Accueil', '/']],
	];
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
@endpush

@section('content')

	@include('includes.gc.error')

	@if(!empty($gcLinks))
		<section class="gn-section" aria-labelledby="gc-links-title">
			<div class="gn-container">
				<div class="gh-head" style="margin-bottom:var(--gn-space-5)">
					<h2 id="gc-links-title">Où aller maintenant&nbsp;?</h2>
				</div>
				<ul class="gc-links">
					<li><a href="{{ url('/') }}">Accueil<small>Créer une alerte, parler à l’assistant, comprendre comment ça marche.</small></a></li>
					@if (setting('KNOWLEDGE_ENABLE') == 'yes')<li><a href="{{ url('/knowledge') }}">Actualités et ressources<small>Articles sur les violences basées sur le genre et la santé mentale.</small></a></li>@endif
					@if (setting('FAQ_ENABLE') == 'yes')<li><a href="{{ url('/faq') }}">Questions fréquentes<small>Des réponses claires aux questions les plus courantes.</small></a></li>@endif
					@if (setting('CONTACT_ENABLE') == 'yes')<li><a href="{{ url('/contact-us') }}">Nous contacter<small>Écrire à l’équipe GANXÔ.</small></a></li>@endif
				</ul>
			</div>
		</section>
	@endif

@endsection
