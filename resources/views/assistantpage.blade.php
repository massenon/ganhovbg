@extends('layouts.master')

@php
	// Mise en page allégée « Ganho » (voir layouts/gnlean) : page dédiée à l'assistant
	$gnLean = true;
	$gnNoChatWindow = true; // la conversation est déjà affichée en pleine page (pas de fenêtre flottante en double)
	$gnAlertUrl = $assistant['alert_url'];

	$seo = [
		'title' => 'Assistant GANXÔ : aide sur les VBG et la dépression',
		'description' => 'Posez vos questions à l’assistant GANXÔ : violences basées sur le genre, dépression, soutien aux personnes LGBTQ, création d’une alerte. Conversation non enregistrée.',
		'breadcrumbs' => [['Accueil', '/'], ['Assistant', '/assistant']],
	];
	$gcEyebrow = 'Assistant GANXÔ';
	$gcTitle = 'Parlez à l’assistant, à votre rythme';
	$gcLead = 'Posez une question ou choisissez un sujet : l’assistant vous donne des repères et vous indique les prochaines étapes. Les réponses sont préparées à l’avance par l’équipe, aucune intelligence artificielle externe n’est utilisée.';
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-assistant.css') }}">
@endpush

@section('content')

	@include('includes.gc.hero')

	<section class="gn-section" aria-label="Discussion avec l’assistant">
		<div class="gn-container ga-grid">
			<div class="ga-main">
				@include('includes.gn.assistant', ['asst' => $assistant, 'gnAsstTag' => 'h2', 'gnAsstMode' => 'page', 'gnAsstUid' => 'gn-chat'])
			</div>

			<div class="ga-side">
				<aside class="ga-card ga-card--help" aria-labelledby="ga-help-title">
					<h2 id="ga-help-title">En danger&nbsp;?</h2>
					<p>Si vous êtes en danger immédiat, joignez d’abord les secours de votre pays ; le bouton SOS prépare une alerte avec votre position pour l’équipe GANXÔ.</p>
					<p><a class="gn-btn gn-btn--primary" href="{{ $gnAlertUrl }}">Créer une alerte</a></p>
				</aside>

				<section class="ga-card" aria-labelledby="ga-topics-title">
					<h2 id="ga-topics-title">Sujets abordés</h2>
					<ul class="ga-topics">
						<li>Créer une alerte pas à pas avec l’assistant, et fonctionnement du bouton SOS</li>
						<li>Types de violences, violences sexuelles, harcèlement en ligne</li>
						<li>Chantage à l’outing, discrimination, soutien LGBTQ</li>
						<li>Consentement</li>
						<li>Dépression, angoisse, aider un proche</li>
						<li>Forum d’entraide et contact</li>
					</ul>
					<p>Besoin d’une réponse plus détaillée&nbsp;? Consultez la <a href="{{ url('/faq') }}" title="Questions fréquentes sur GANXÔ">FAQ</a> ou rejoignez le <a href="{{ gn_forum_url() }}" title="Forum d’entraide GANXÔ">forum d’entraide</a>.</p>
				</section>

				<section class="ga-card" aria-labelledby="ga-priv-title">
					<h2 id="ga-priv-title">Votre confidentialité</h2>
					<p>La conversation reste dans cet onglet et n’est pas enregistrée sur nos serveurs. Seuls des compteurs anonymes (sujet abordé, sans votre texte, sans adresse IP ni compte) servent à améliorer les réponses.</p>
					<p class="ga-note">Utilisez « Effacer » pour supprimer la conversation, puis fermez l’onglet.</p>
					@if(!empty($assistant['disclaimer']))<p class="ga-note"><strong>{{ $assistant['disclaimer'] }}</strong></p>@endif
				</section>
			</div>
		</div>
	</section>

@endsection
