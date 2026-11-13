@extends('layouts.master')

@php
	// Mise en page Ganho (voir layouts/gnlean) : pages statiques et légales (404 gérée par le contrôleur).
	$gnLean = true;
	$gnProse = gn_prose($pages->pagedescription);
	$gnToc = count($gnProse['toc']) >= 3 ? $gnProse['toc'] : [];
	$gnName = trim(strip_tags($pages->pagename));
	$gnPath = '/page/' . $pages->pageslug;
	$gnDesc = gn_excerpt($pages->pagedescription, 155);
	if ($gnDesc === '') { $gnDesc = $gnName . ' : informations de GANXÔ Alerte VBG.'; }
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');

	$seo = [
		'title' => \Illuminate\Support\Str::limit($gnName, 48, '…') . ' | GANXÔ',
		'description' => $gnDesc,
		'breadcrumbs' => [['Accueil', '/'], [\Illuminate\Support\Str::limit($gnName, 50, '…'), $gnPath]],
		'modified' => optional($pages->updated_at)->toIso8601String(),
		'published' => optional($pages->created_at)->toIso8601String(),
	];
	$seo = array_filter($seo, function ($v) { return $v !== null; });
	$gcEyebrow = 'Informations';
	$gcTitle = $gnName;
	$gcLead = $pages->updated_at ? 'Dernière mise à jour le ' . $pages->updated_at->locale('fr')->translatedFormat('j F Y') . '.' : null;
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
@endpush

@section('content')

	@include('includes.gc.hero')

	<section class="gn-section" aria-label="Contenu de la page">
		<div class="gn-container">
			<div class="gc-read @if(!count($gnToc)) gc-read--solo @endif">
				<div class="gc-read__main">
					<div class="gc-prose">{!! $gnProse['html'] !!}</div>
				</div>
				@if(count($gnToc))
					<aside class="gc-read__aside" aria-label="Sommaire">
						<details class="gc-toc gc-toc--collapse" open>
							<summary>Sommaire</summary>
							<ol>
								@foreach($gnToc as $t)
									<li @if($t[2] === 3) style="padding-left:var(--gn-space-4)" @endif><a href="#{{ $t[0] }}">{{ $t[1] }}</a></li>
								@endforeach
							</ol>
						</details>
					</aside>
				@endif
			</div>
		</div>
	</section>

	<section class="gn-section gn-section--soft gn-section--round-top" aria-labelledby="gc-help-title">
		<div class="gn-container">
			<div class="gc-help" style="background:var(--gn-surface)">
				<div>
					<h2 id="gc-help-title">Une question sur vos données ou sur le service&nbsp;?</h2>
					<p>Consultez la FAQ ou écrivez à l’équipe GANXÔ. Pour parler d’une situation de violence, créez une alerte.</p>
				</div>
				<div class="gc-help__a">
					@if (setting('CONTACT_ENABLE') == 'yes')<a class="gn-btn gn-btn--primary" href="{{ url('/contact-us') }}">Nous contacter</a>@endif
					@if (setting('FAQ_ENABLE') == 'yes')<a class="gn-btn gn-btn--ghost" href="{{ url('/faq') }}">Questions fréquentes</a>@endif
					<a class="gn-btn gn-btn--ghost" href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
				</div>
			</div>
		</div>
	</section>

@endsection
