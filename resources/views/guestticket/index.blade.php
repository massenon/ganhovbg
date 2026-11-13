@extends('layouts.master')
@php
	// Présentation Ganho (layouts/gnlean). Variante avec vérification de l'e-mail par code (OTP).
	$gnLean = true;
	$seo = ['title' => 'Créer une alerte VBG : signaler une violence', 'description' => 'Créez une alerte pour signaler une violence basée sur le genre ou une détresse psychologique. L’équipe GANXÔ reçoit votre alerte et peut vous répondre.', 'breadcrumbs' => [['Accueil', '/'], ['Créer une alerte', '/guest/openticket']]];
	$gnEyebrow = 'Alerte VBG';
	$gnH1 = 'Créer une alerte';
	$gnLead = 'Décrivez la situation avec vos mots. L’équipe GANXÔ reçoit votre alerte et peut vous répondre via son suivi.';
	$gnCatLabel = 'Type de situation';
	$gnCatPlaceholder = 'Choisissez le type de situation';
@endphp

@push('gn_head')
<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-forms.css') }}">
<link rel="stylesheet" href="{{ gn_asset('assets/plugins/summernote/summernote.css') }}">
@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')<link rel="stylesheet" href="{{ gn_asset('assets/plugins/dropzone/dropzone.css') }}">@endif
@endpush

@section('content')
@include('includes.gn.formhero')

<div class="gnf-main">
	<div class="gn-container gnf-grid">
		<div class="gnf-card">
			<h2 class="gnf-card__title">Victime de violences basées sur le genre ou de détresse psychologique ?</h2>
			<p class="gnf-card__sub">Les champs marqués d’un astérisque (<span class="gnf-req" aria-hidden="true">*</span>) sont obligatoires.</p>

			<form method="post" id="emailotp_form" enctype="multipart/form-data" novalidate>
				<section class="gnf-section" aria-labelledby="gnf-s1">
					<h3 class="gnf-section-title" id="gnf-s1"><span class="gnf-step" aria-hidden="true">1</span>Vos coordonnées</h3>
					<div class="gnf-field">
						<label for="email">Adresse e-mail <span class="gnf-req" aria-hidden="true">*</span><span class="gn-visually-hidden">(obligatoire)</span></label>
						<div class="gnf-inline">
							<input type="email" class="gnf-ctl" placeholder="vous@exemple.com" name="email" value="" id="email" autocomplete="email" inputmode="email" required aria-describedby="email-help EmailError">
							<span class="gnf-spin" style="display: none;" id="spinnerenable" role="status"><span class="gn-visually-hidden">Envoi du code…</span></span>
						</div>
						<p class="gnf-help" id="email-help">Nous y envoyons un code à 6 chiffres pour confirmer que l’adresse est la vôtre.</p>
						<span id="EmailError" class="gnf-error alert-message"></span>
						<div class="gnf-info" id="emailalerroff" style="display: none;" role="status">
							<p><span id="Alertemailerror"></span></p>
						</div>
					</div>
					<button type="submit" class="gn-btn gn-btn--primary" id="sprukoverifybtn">Recevoir le code</button>
				</section>
			</form>

			<form method="post" id="guest_form" enctype="multipart/form-data" novalidate>
				@csrf
				@honeypot

				<div class="gnf-otp" id="verifyottp" style="display: none;">
					<div class="gnf-field">
						<label for="verifyotp">Code de vérification</label>
						<input type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" class="gnf-ctl gnf-ctl--code" placeholder="000000" name="verifyotp" value="" id="verifyotp" aria-describedby="verifyotp-help verifyotpError">
						<p class="gnf-help" id="verifyotp-help">Saisissez les 6 chiffres reçus par e-mail.</p>
						<span id="verifyotpError" class="gnf-error alert-message"></span>
					</div>
				</div>

				<div class="gnf-summary" id="gnf-summary" role="alert" tabindex="-1" hidden></div>

				<div class="verifyotp">
					@include('includes.gn.alertfields')
					<div class="gnf-actions">
						<p class="gnf-actions__note">L’alerte est transmise à l’équipe GANXÔ, qui peut vous répondre par e-mail.</p>
						<button type="submit" class="gn-btn gn-btn--primary gn-btn--lg purchasecode" id="createticketbtn">Envoyer l’alerte</button>
					</div>
				</div>
			</form>
		</div>

		<aside class="gnf-aside" aria-label="À savoir avant d’envoyer">
			<div class="gnf-note gnf-note--urgent">
				<h2>En danger immédiat ?</h2>
				<p>Cette alerte est transmise à l’équipe GANXÔ. Elle ne contacte ni la police ni les secours : en danger immédiat, appelez les secours de votre pays.</p>
			</div>
			<div class="gnf-note">
				<h2>Avant d’envoyer</h2>
				<ul>
					<li>Un code à 6 chiffres envoyé à votre e-mail confirme que l’adresse est la vôtre.</li>
					<li>Le bouton SOS (en bas à droite) prépare cette alerte avec votre position GPS ; vous pouvez la modifier avant l’envoi.</li>
					<li>Vous suivez ensuite l’alerte et les réponses de l’équipe sur une page dédiée.</li>
				</ul>
			</div>
		</aside>
	</div>
</div>
@endsection

@include('includes.gn.alertjs', ['gnOtp' => true])
