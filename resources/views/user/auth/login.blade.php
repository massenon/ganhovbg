@extends('layouts.gnauth')
@php
	$seo = ['title' => 'Connexion à votre espace GANXÔ', 'description' => 'Connectez-vous à votre compte GANXÔ Alerte VBG pour suivre vos alertes et échanger avec l’équipe.', 'breadcrumbs' => [['Accueil', '/'], ['Connexion', '/customer/login']]];
@endphp

@section('gn_eyebrow', 'Votre espace')
@section('gn_h1', lang('Se connecter'))
@section('gn_lead', 'Suivez vos alertes et échangez avec l’équipe GANXÔ.')

@section('gn_form')
	<h2 class="gnf-card__title">{{lang('Connectez-vous à votre compte')}}</h2>

	@if(setting('login_disable') == 'on')
		<div class="gnf-alert" role="alert">{{setting('login_disable_statement')}}</div>
	@endif

	@include('includes.gn.social')

	<form id="login_form" name="login_form" method="post">
		@csrf
		@honeypot

		<div class="gnf-field">
			<label for="username">{{lang('Email')}} <span class="gnf-req" aria-hidden="true">*</span></label>
			<input class="gnf-ctl" type="email" name="email" required id="username" autocomplete="username">
			<div id="err" class="gnf-error" role="alert"></div>
		</div>
		<div class="gnf-field">
			<label for="password">{{lang('Mot de passe')}} <span class="gnf-req" aria-hidden="true">*</span></label>
			<input class="gnf-ctl" type="password" id="password" name="password" required autocomplete="current-password">
			<div id="passworderr" class="gnf-error" role="alert"></div>
		</div>
		<div class="gnf-field">
			<label class="gnf-check" for="remember">
				<input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
				<span>{{lang('Restez connecté')}}</span>
			</label>
		</div>

		@if(setting('CAPTCHATYPE')=='manual' && setting('RECAPTCH_ENABLE_LOGIN')=='yes')
		<div class="gnf-field">
			<label for="captcha">Recopiez le code de l’image</label>
			<div class="gnf-captcha">
				<input type="text" id="captcha" class="gnf-ctl @error('captcha') is-invalid @enderror" name="captcha" autocomplete="off">
				<div class="captcha"><span>{!! captcha_img('') !!}</span><button type="button" class="gnf-iconbtn captchabtn" data-url="{{route('captcha.reload')}}" title="Actualiser l’image" aria-label="Actualiser l’image du captcha">Actualiser</button></div>
			</div>
			<div id="captchaerr" class="gnf-error" role="alert"></div>
		</div>
		@endif

		@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_LOGIN')=='yes')
		<div class="gnf-field">
			<div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-callback="recaptchaCallback" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
			<span class="gnf-error" id="captchaError" role="alert"></span>
		</div>
		@endif

		<button class="gn-btn gn-btn--primary gn-btn--block" type="submit" onclick="login();this.form.submit();this.disabled=true;">{{lang('Se connecter')}}</button>

		<div class="gnf-links">
			<p><a href="{{url('customer/forgotpassword')}}">{{lang('Mot de passe oublié?')}}</a></p>
			@if(setting('REGISTER_DISABLE') == 'on')
			<p>Pas encore de compte ? <a href="{{route('auth.register')}}">{{lang('Register', 'menu')}}</a></p>
			@endif
			<p><a href="{{url('/')}}">Retour à l’accueil</a></p>
		</div>
	</form>
@endsection

@push('gn_scripts')
<script src="{{ gn_asset('assets/ganho/js/pages-forms.js') }}"></script>
@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_LOGIN')=='yes')<script src="https://www.google.com/recaptcha/api.js" async defer></script>@endif
<script type="text/javascript">
	"use strict";

	var facebook = "{{ route('social.login', 'facebook') }}";
	var google = "{{ route('social.login', 'google') }}";
	var twitter = "{{ route('social.login', 'twitter') }}";
	var envato = "{{ route('social.login', 'envato') }}";

	function gnShow(id, text) {
		var el = document.getElementById(id);
		if (el) { el.textContent = (text === undefined || text === null) ? '' : String(text); }
	}

	// Connexion AJAX (même requête et mêmes codes de retour qu'avant)
	function login() {
		var form = document.getElementById('login_form');
		if (document.getElementById('username').value === '') { gnShow('err', @json(lang('Please enter the username'))); }
		if (document.getElementById('password').value === '') { gnShow('passworderr', @json(lang('Please enter the password.'))); }

		var body = new URLSearchParams(new FormData(form)).toString();
		fetch('{{route('client.do_ajaxlogin')}}', {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json',
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			body: body
		}).then(function (r) { return r.json(); }).then(function (response) {
			if (response && response.errors) {
				gnShow('err', response.errors.email);
				gnShow('captchaerr', response.errors.captcha);
				if (response.errors['g-recaptcha-response'] && response.errors['g-recaptcha-response'][0]) {
					gnShow('captchaError', response.errors['g-recaptcha-response'][0]);
				}
			}
			if (response == 1) {
				window.location.replace('{{route('client.dashboard')}}');
			} else if (response == 3) {
				toastr.error(@json(lang('The username or password you entered is incorrect. Please Check', 'alerts')));
				gnShow('err', @json(lang('The username or password you entered is incorrect. Please Check', 'alerts')));
			} else if (response && response.error == 4) {
				toastr.info(@json(lang('Your Email is not verified yet. An email verification link was sent successfully. Please check and verify your email.', 'alerts')));
				window.location.href = 'emailverificationstore/' + response.email;
			} else if (response == 5) {
				toastr.error(@json(lang('Your account is currently inactive. Please contact the admin.', 'alerts')));
			} else if (response == 30) {
				toastr.warning(@json(lang('Temporary Login disable', 'alerts')));
			}
		}).catch(function () {});
	}
</script>
@endpush
