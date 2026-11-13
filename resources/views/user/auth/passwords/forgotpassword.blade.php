@extends('layouts.gnauth')
@php
	$seo = ['title' => 'Mot de passe oublié : GANXÔ Alerte VBG', 'description' => 'Saisissez l’adresse e-mail liée à votre compte GANXÔ pour recevoir un lien de réinitialisation du mot de passe.', 'breadcrumbs' => [['Accueil', '/'], ['Connexion', '/customer/login'], ['Mot de passe oublié', '/customer/forgotpassword']]];
@endphp

@section('gn_eyebrow', 'Votre espace')
@section('gn_h1', lang('Mot de passe oublié?'))
@section('gn_lead', 'Nous vous envoyons un lien pour choisir un nouveau mot de passe.')

@section('gn_form')
	<h2 class="gnf-card__title">{{lang('Réinitialiser le mot de passe')}}</h2>
	<p class="gnf-card__sub">{{lang('Entrez l\'adresse e-mail qui est liée à votre compte.')}}</p>

	<form id="forgot" action="{{url('customer/forgotpassword')}}" method="post">
		@csrf
		@honeypot
		<div class="gnf-field">
			<label for="email">{{lang('Email')}}</label>
			<input class="gnf-ctl @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" type="email" autocomplete="email">
			@error('email')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>
		<button class="gn-btn gn-btn--primary gn-btn--block" type="submit" onclick="this.disabled=true;this.form.submit();">Envoyer le lien</button>
		<div class="gnf-links">
			<p><a href="{{url('customer/login')}}">{{lang('Se connecter')}}</a></p>
			<p><a href="{{url('/')}}">{{lang('Retour')}}</a></p>
		</div>
	</form>
@endsection
