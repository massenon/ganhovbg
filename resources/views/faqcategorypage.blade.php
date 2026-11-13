@extends('layouts.master')

@php
	// Mise en page Ganho (voir layouts/gnlean) : un thème de la FAQ
	$gnLean = true;
	$gnLogged = Auth::guard('customer')->check() && Auth::guard('customer')->user();
	$gnName = trim(strip_tags($faqcategory->faqcategoryname));
	$all = $faqcategory->faqdetails()->where('status', '1')->get();
	$gnItems = [];
	foreach ($all as $f) {
		$locked = $f->privatemode == 1 && !$gnLogged;
		$gnItems[] = ['q' => trim(strip_tags($f->question)), 'html' => $locked ? '' : gn_prose($f->answer)['html'], 'plain' => $locked ? '' : gn_excerpt($f->answer, 5000), 'locked' => $locked];
	}
	$gnFaqLd = [];
	foreach ($gnItems as $it) { if (!$it['locked'] && $it['plain'] !== '' && $it['q'] !== '') { $gnFaqLd[] = [$it['q'], $it['plain']]; } }
	$gnFirst = collect($gnItems)->first(function ($i) { return !$i['locked'] && $i['plain'] !== ''; });
	$gnOthers = \App\Models\FaqCategory::where('status', '1')->where('id', '!=', $faqcategory->id)->orderBy('id')->get();

	$seo = [
		'title' => \Illuminate\Support\Str::limit($gnName, 44, '…') . ' | FAQ GANXÔ',
		'description' => $gnFirst ? $gnName . ' : ' . $gnFirst['plain'] : 'Réponses de la FAQ GANXÔ Alerte VBG : ' . $gnName,
		'breadcrumbs' => [['Accueil', '/'], ['FAQ', '/faq'], [\Illuminate\Support\Str::limit($gnName, 50, '…'), '/faq/' . $faqcategory->id]],
		'faq' => $gnFaqLd,
	];
	$gcEyebrow = lang('FAQ’s', 'menu');
	$gcTitle = $gnName;
	$gcLead = count($gnItems) . ' ' . (count($gnItems) > 1 ? 'questions' : 'question') . ' dans ce thème.';
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
@endpush

@section('content')

	@include('includes.gc.hero')

	<section class="gn-section" aria-labelledby="gc-q-title">
		<div class="gn-container">
			@if (count($gnItems))
				<div class="gc-faq-block__h"><h2 id="gc-q-title">Questions et réponses</h2></div>
				@include('includes.gc.faq-items', ['items' => $gnItems])
			@else
				<div class="gc-empty">
					<h2 id="gc-q-title">Aucune question pour le moment</h2>
					<p>La FAQ sera bientôt mise à jour.</p>
				</div>
			@endif

			@if ($gnOthers->count())
				<div style="margin-top:var(--gn-space-8)">
					<h2 style="font-size:var(--gn-fs-h4)">Autres thèmes</h2>
					<ul class="gc-chips" style="margin-bottom:0">
						@foreach ($gnOthers as $o)
							<li><a href="{{ url('faq/' . $o->id) }}">{{ $o->faqcategoryname }}</a></li>
						@endforeach
					</ul>
				</div>
			@endif
			<p style="margin-top:var(--gn-space-6)"><a href="{{ url('/faq') }}">Retour à toute la FAQ</a></p>
		</div>
	</section>

	@include('includes.gc.faq-help')

@endsection
