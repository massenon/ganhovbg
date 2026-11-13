@extends('layouts.master')
@php
	// Présentation Ganho (layouts/gnlean). Champs, noms, validation et captcha inchangés.
	$gnLean = true;
	$seo = ['title' => 'Contactez-nous : équipe GANXÔ Alerte VBG', 'description' => 'Une question ou une suggestion sur la plateforme GANXÔ Alerte VBG ? Écrivez à l’équipe via le formulaire de contact.', 'breadcrumbs' => [['Accueil', '/'], ['Contactez-nous', '/contact-us']]];
	$gnEyebrow = 'Contact';
	$gnH1 = 'Contactez-nous';
	$gnLead = 'Une question, une suggestion ou un problème sur la plateforme ? Écrivez à l’équipe GANXÔ.';
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
@endphp

@push('gn_head')
<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-forms.css') }}">
@endpush

@section('content')
@include('includes.gn.formhero')

<div class="gnf-main">
	<div class="gn-container gnf-grid gnf-grid--contact">
		<div class="gnf-card">
			<h2 class="gnf-card__title">Écrivez-nous</h2>
			<p class="gnf-card__sub">Nous lisons chaque message. Pour signaler une violence, utilisez plutôt le formulaire d’alerte.</p>

			<form method="POST" action="{{url('/contact-us')}}">
				@csrf
				@honeypot

				<div class="gnf-row">
					<div class="gnf-field">
						<label for="name">{{lang('Nom')}}</label>
						<input class="gnf-ctl @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name">
						@error('name')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
					</div>
					<div class="gnf-field">
						<label for="email">{{lang('Email')}}</label>
						<input class="gnf-ctl @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email">
						@error('email')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
					</div>
				</div>
				<div class="gnf-row">
					<div class="gnf-field">
						<label for="phone_number">{{lang('Numéro de téléphone')}}</label>
						<input class="gnf-ctl @error('phone_number') is-invalid @enderror" id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" autocomplete="tel">
						@error('phone_number')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
					</div>
					<div class="gnf-field">
						<label for="subject">Sujet</label>
						<input class="gnf-ctl @error('subject') is-invalid @enderror" id="subject" type="text" name="subject" value="{{ old('subject') }}">
						@error('subject')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
					</div>
				</div>
				<div class="gnf-field">
					<label for="message">{{lang('Message')}}</label>
					<textarea class="gnf-ctl @error('message') is-invalid @enderror" id="message" rows="6" name="message">{{ old('message') }}</textarea>
					@error('message')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
				</div>

				@if(setting('CAPTCHATYPE')=='manual' && setting('RECAPTCH_ENABLE_CONTACT')=='yes')
				<div class="gnf-field">
					<label for="captcha">Recopiez le code de l’image</label>
					<div class="gnf-captcha">
						<input type="text" id="captcha" class="gnf-ctl @error('captcha') is-invalid @enderror" name="captcha" autocomplete="off">
						<div class="captcha"><span>{!! captcha_img('') !!}</span><button type="button" class="gnf-iconbtn captchabtn" data-url="{{route('captcha.reload')}}" title="Actualiser l’image" aria-label="Actualiser l’image du captcha">Actualiser</button></div>
					</div>
					@error('captcha')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
				</div>
				@endif

				@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_CONTACT')=='yes')
				<div class="gnf-field">
					<div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
					@if ($errors->has('g-recaptcha-response'))<span class="gnf-error" role="alert">{{ $errors->first('g-recaptcha-response') }}</span>@endif
				</div>
				@endif

				<div class="gnf-actions">
					<button type="submit" class="gn-btn gn-btn--primary gn-btn--lg" onclick="this.disabled=true;this.form.submit();">Envoyer le message</button>
				</div>
			</form>
		</div>

		<aside class="gnf-aside" aria-label="Autres moyens d’obtenir de l’aide">
			<div class="gnf-note gnf-note--urgent">
				<h2>Besoin d’aide maintenant ?</h2>
				<p>Ce formulaire n’est pas surveillé en continu. Pour signaler une violence, <a href="{{ $gnAlertUrl }}">créez une alerte VBG</a>. En danger immédiat, appelez les secours de votre pays.</p>
			</div>
			<div class="gnf-note">
				<h2>Avant d’écrire</h2>
				<ul class="gnf-contactlist">
					@if(setting('FAQ_ENABLE') == 'yes')<li>Une réponse existe peut-être déjà : consultez la <a href="{{ url('/faq') }}">foire aux questions</a>.</li>@endif
					@if(setting('KNOWLEDGE_ENABLE') == 'yes')<li>Retrouvez nos conseils dans les <a href="{{ url('/knowledge') }}">actualités</a>.</li>@endif
					<li>Revenir à la <a href="{{ url('/') }}">page d’accueil</a>.</li>
				</ul>
			</div>
		</aside>
	</div>
</div>
@endsection

@push('gn_scripts')
<script src="{{ gn_asset('assets/ganho/js/pages-forms.js') }}" defer></script>
@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_CONTACT')=='yes')<script src="https://www.google.com/recaptcha/api.js" async defer></script>@endif
@endpush
