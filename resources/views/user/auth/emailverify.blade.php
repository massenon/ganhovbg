@extends('layouts.gnauth')
@php
	$seo = ['title' => 'Vérification de votre e-mail : GANXÔ', 'description' => 'Recevez un nouveau lien pour vérifier l’adresse e-mail de votre compte GANXÔ Alerte VBG.', 'breadcrumbs' => [['Accueil', '/'], ['Connexion', '/customer/login'], ['Vérification de l’e-mail', '/customer/emailverification']]];
@endphp

@section('gn_eyebrow', 'Votre espace')
@section('gn_h1', 'Vérifier votre e-mail')
@section('gn_lead', 'Vérifiez votre adresse e-mail pour activer votre compte.')

@section('gn_form')
	<h2 class="gnf-card__title">Recevoir le lien</h2>
	<p class="gnf-card__sub">{{lang('Entrez l\'adresse e-mail qui est liée à votre compte.')}}</p>

	<form id="forgot" action="{{url('customer/emailverificationstore')}}" method="get" onsubmit="var v=this.email.value.trim();if(v){window.location.href=this.action+'/'+encodeURIComponent(v);}return false;">
		<div class="gnf-field">
			<label for="email">{{lang('Email')}}</label>
			<input class="gnf-ctl @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" type="email" autocomplete="email">
			@error('email')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>
		<button class="gn-btn gn-btn--primary gn-btn--block" type="submit">Recevoir le lien</button>
	</form>
@endsection
