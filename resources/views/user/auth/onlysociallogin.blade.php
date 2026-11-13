@extends('layouts.gnauth')
@php
	$seo = ['title' => 'Connexion à votre espace GANXÔ', 'description' => 'Connectez-vous ou créez votre compte GANXÔ Alerte VBG avec un compte existant.', 'breadcrumbs' => [['Accueil', '/'], ['Connexion', '/customer/login']], 'canonical' => gn_url('customer/login')];
@endphp

@section('gn_eyebrow', 'Votre espace')
@section('gn_h1', 'Se connecter ou créer un compte')
@section('gn_lead', 'Utilisez l’un des comptes proposés pour accéder à votre espace.')

@section('gn_form')
	<h2 class="gnf-card__title">{{'Se connecter ou créer un compte'}}</h2>

	@if(setting('login_disable') == 'on')
		<div class="gnf-alert" role="alert">{{setting('login_disable_statement')}}</div>
	@else
		@include('includes.gn.social', ['gnNoOr' => true])
	@endif
@endsection

@push('gn_scripts')
<script type="text/javascript">
	"use strict";
	var facebook = "{{ route('social.login', 'facebook') }}";
	var google = "{{ route('social.login', 'google') }}";
	var twitter = "{{ route('social.login', 'twitter') }}";
	var envato = "{{ route('social.login', 'envato') }}";
</script>
@endpush
