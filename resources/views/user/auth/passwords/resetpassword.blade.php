@extends('layouts.gnauth')
@php
	$seo = ['title' => 'Nouveau mot de passe : GANXÔ Alerte VBG', 'description' => 'Choisissez un nouveau mot de passe pour votre compte GANXÔ Alerte VBG.', 'breadcrumbs' => [['Accueil', '/'], ['Connexion', '/customer/login'], ['Nouveau mot de passe', '/customer/login']], 'canonical' => gn_url('customer/login')];
@endphp

@section('gn_eyebrow', 'Votre espace')
@section('gn_h1', lang('Réinitialiser le mot de passe'))
@section('gn_lead', 'Choisissez un nouveau mot de passe pour retrouver l’accès à votre compte.')

@section('gn_form')
	<h2 class="gnf-card__title">{{lang('Nouveau mot de passe')}}</h2>

	<form method="POST" action="{{url('customer/reset-password')}}">
		@csrf
		@honeypot
		<input type="hidden" name="token" value="{{ $token }}">
		<div class="gnf-field">
			<label for="email">{{lang('Email')}}</label>
			<input id="email" type="email" class="gnf-ctl @error('email') is-invalid @enderror" name="email" value="{{ $user->email ?? old('email') }}" autocomplete="email" placeholder="{{lang('example@mail.com')}}" autofocus readonly>
			@error('email')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>
		<div class="gnf-field">
			<label for="password">{{lang('Nouveau mot de passe')}}</label>
			<input class="gnf-ctl @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password">
			@error('password')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>
		<div class="gnf-field">
			<label for="password-confirm">{{lang('Confirmez le mot de passe')}}</label>
			<input class="gnf-ctl" id="password-confirm" name="password_confirmation" type="password" autocomplete="new-password">
		</div>
		<button type="submit" class="gn-btn gn-btn--primary gn-btn--block" onclick="this.disabled=true;this.form.submit();">{{lang('Réinitialiser le mot de passe')}}</button>
		<div class="gnf-links">
			<p>{{lang('Vous vous souvenez de votre mot de passe ?')}} <a href="{{url('/login')}}">{{lang('Se connecter')}}</a></p>
		</div>
	</form>
@endsection
