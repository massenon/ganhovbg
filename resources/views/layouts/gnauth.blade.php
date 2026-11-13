@extends('layouts.master')
@php
	// Coque des pages d'authentification cliente (Ganho) : nuit + bandes du spectre, carte blanche à 32 px.
	// La page enfant fournit $seo (avant) et les sections gn_eyebrow, gn_h1, gn_lead, gn_form, et @push('gn_scripts') si besoin.
	$gnLean = true;
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
@endphp

@push('gn_head')
<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-forms.css') }}">
@endpush

@section('content')
<div class="gnf-auth">
	<div class="gn-container gnf-auth__grid">
		@include('includes.breadcrumbs')
		<div class="gnf-auth__intro">
			<p class="gn-eyebrow">@yield('gn_eyebrow')</p>
			<h1>@yield('gn_h1')</h1>
			<p class="gnf-auth__lead">@yield('gn_lead')</p>
			<div class="gnf-auth__urg">
				@if(setting('GUEST_TICKET') == 'yes')<p>Pas envie de créer un compte ? <a href="{{ $gnAlertUrl }}">Créez une alerte VBG</a>. En danger immédiat, appelez les secours de votre pays.</p>
				@else<p>En danger immédiat, appelez les secours de votre pays.</p>@endif
			</div>
		</div>
		<div class="gnf-card">
			@yield('gn_form')
		</div>
	</div>
	<div class="gn-stripes" aria-hidden="true"></div>
</div>
@endsection
