@extends('layouts.master')

@php
	// Mise en page allégée « Ganho » (voir layouts/gnlean) + SEO de la page d'accueil
	$gnLean = true;
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');

	// FAQ visible : base de données, sinon texte neutre et honnête
	$gnFaq = [];
	foreach (($faqs ?? collect()) as $f) {
		$a = preg_replace('/\sstyle="[^"]*"/i', '', strip_tags($f->answer, '<p><br><strong><b><em><i><ul><ol><li><a>'));
		$plain = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($f->answer), ENT_QUOTES, 'UTF-8')));
		if ($plain === '') { continue; }
		$gnFaq[] = ['q' => trim(strip_tags($f->question)), 'html' => $a, 'plain' => $plain];
	}
	if (count($gnFaq) === 0) {
		$gnFaq = [
			['q' => 'Qu’est-ce que GANXÔ Alerte VBG ?', 'html' => '<p>GANXÔ Alerte VBG est une plateforme d’assistance en ligne pour les victimes ou témoins de violences basées sur le genre et pour les personnes en détresse psychologique.</p>', 'plain' => 'GANXÔ Alerte VBG est une plateforme d’assistance en ligne pour les victimes ou témoins de violences basées sur le genre et pour les personnes en détresse psychologique.'],
			['q' => 'Comment créer une alerte ?', 'html' => '<p>Choisissez « Créer une alerte », décrivez la situation et envoyez le formulaire. L’équipe GANXÔ reçoit votre alerte et peut vous répondre via son suivi.</p>', 'plain' => 'Choisissez « Créer une alerte », décrivez la situation et envoyez le formulaire. L’équipe GANXÔ reçoit votre alerte et peut vous répondre via son suivi.'],
			['q' => 'Le bouton SOS appelle-t-il la police ?', 'html' => '<p>Non. Le bouton SOS prépare une alerte avec votre position GPS pour l’équipe GANXÔ. Il ne contacte ni la police ni les secours : en danger immédiat, appelez les secours de votre pays.</p>', 'plain' => 'Non. Le bouton SOS prépare une alerte avec votre position GPS pour l’équipe GANXÔ. Il ne contacte ni la police ni les secours : en danger immédiat, appelez les secours de votre pays.'],
		];
	}
	$gnFaq = array_slice($gnFaq, 0, 8);

	$seo = [
		'title' => 'GANXÔ Alerte VBG : signalez, parlez, soyez orienté·e',
		'description' => 'Victime ou témoin de violences basées sur le genre, ou en détresse psychologique ? Créez une alerte, parlez à l’assistant, trouvez de l’aide en ligne.',
		'breadcrumbs' => [['Accueil', '/']],
		'faq' => array_map(function ($x) { return [$x['q'], $x['plain']]; }, $gnFaq),
	];

	$gnAsstOn = \App\Models\AssistantSetting::flag('enabled');

	$gnPosts = collect(isset($article) && method_exists($article, 'items') ? $article->items() : ($article ?? []))->filter(function ($a) { return $a->status && strtolower($a->status) === 'published'; })->take(3)->values();
	$gnFaqTitle = ($title->faqtitle ?? '') ?: 'Questions fréquentes';
	$gnFaqSub = ($title->faqsub ?? '') ?: 'Des réponses claires aux questions les plus courantes.';
	$gnArtTitle = ($title->articletitle ?? '') ?: 'Restez informé·e';
	$gnArtSub = ($title->articlesub ?? '') ?: 'Actualités et ressources sur le genre, la sexualité, les violences basées sur le genre et la dépression.';
@endphp

@push('gn_head')
	<link rel="preload" as="image" type="image/webp" href="{{ asset('assets/ganho/img/photos/ph-12-640.webp') }}" imagesrcset="{{ asset('assets/ganho/img/photos/ph-12-640.webp') }} 640w, {{ asset('assets/ganho/img/photos/ph-12-1200.webp') }} 1200w" imagesizes="(max-width: 900px) 45vw, 300px" fetchpriority="high">
@endpush

@push('gn_scripts')
	<script src="{{ gn_asset('assets/ganho/js/gn-consultation.js') }}" defer></script>
@endpush

@section('content')

	<!-- Héros Nuit -->
	<section class="gh-hero" aria-labelledby="hero-title">
		<div class="gn-container gh-hero__grid">
			<div>
				<span class="gn-eyebrow">GANXÔ Alerte VBG</span>
				<h1 id="hero-title">Victime de violences basées sur le genre <span class="gn-gradient-text">ou de dépression&nbsp;?</span></h1>
				<p class="gh-hero__lead">Vous n’êtes pas seul·e. Confiez-vous à l’équipe GANXÔ, parlez à l’assistant et trouvez de l’assistance en ligne : associations, psychologues, avocat·es.</p>
				<div class="gh-actions">
					<a class="gn-btn gn-btn--light gn-btn--lg" href="{{ $gnAlertUrl }}">Créer une alerte</a>
					<a class="gn-btn gn-btn--ghost gn-btn--lg" href="{{ url('/assistant') }}" title="Parler à l’assistant GANXÔ" data-gn-open-chat>Parler à l’assistant</a>
				</div>
				<p class="gh-note">Besoin d’aide ? Bénéficiez d’une assistance psychologique et juridique immédiate via notre <a href="{{ url('/assistant') }}" title="Parler à l’Assistant Ganho" data-gn-open-chat>Assistant Ganho</a>.</p>
			</div>
			<div class="gh-arches">
				<div class="gn-arch">@include('includes.gn.photo', ['n' => '12', 'lcp' => true, 'sizes' => '(max-width: 900px) 45vw, 300px'])</div>
				<div class="gn-arch gn-arch--outline">@include('includes.gn.photo', ['n' => '18', 'sizes' => '(max-width: 900px) 45vw, 300px'])</div>
			</div>
		</div>
		<div class="gn-stripes" role="presentation"></div>
	</section>

	<!-- Comment ça marche -->
	<section class="gn-section" id="comment-ca-marche" aria-labelledby="how-title">
		<div class="gn-container">
			<div class="gh-head">
				<span class="gn-eyebrow">En trois temps</span>
				<h2 id="how-title">Comment ça marche</h2>
				<p>De la première alerte à l’orientation, vous gardez la main à chaque étape.</p>
			</div>
			<div class="gh-steps">
				<article class="gh-step gh-step--1">
					<div>
						<span class="gh-step__n" aria-hidden="true">1</span>
						<h3>Signaler</h3>
						<p>Créez une alerte : décrivez ce qui se passe avec vos mots et choisissez la catégorie qui correspond le mieux à votre situation.</p>
						@if(($alertcats ?? collect())->count())
							<ul class="gh-chips" aria-label="Catégories d’alerte proposées">
								@foreach($alertcats as $c)<li>{{ $c->name }}</li>@endforeach
							</ul>
						@endif
					</div>
					<a class="gn-btn gn-btn--light" href="{{ $gnAlertUrl }}">Créer une alerte</a>
				</article>
				<article class="gh-step gh-step--2">
					<span class="gh-step__n" aria-hidden="true">2</span>
					<h3>Être écouté·e</h3>
					<p>L’équipe GANXÔ reçoit votre alerte. Vous pouvez suivre votre alerte et échanger avec l’équipe depuis la plateforme.</p>
				</article>
				<article class="gh-step gh-step--3">
					<span class="gh-step__n" aria-hidden="true">3</span>
					<h3>Être orienté·e</h3>
					<p>Trouvez des contacts d’associations, de psychologues ou d’avocat·es qui offrent du soutien.</p>
				</article>
			</div>
		</div>
	</section>

	<!-- Faits -->
	<section class="gn-section gn-section--blue" aria-labelledby="facts-title">
		<div class="gn-container gh-facts">
			<div>
				<div class="gh-head" style="margin-bottom:var(--gn-space-5)">
					<span class="gn-eyebrow" style="color:var(--gn-yellow)">Les faits à connaître</span>
					<h2 id="facts-title">Ce que disent les chiffres</h2>
				</div>
				<div class="gh-fact">
					<span class="gh-fact__v">1&nbsp;sur&nbsp;3</span>
					<p class="gh-fact__t">Dans le monde, environ une femme sur trois a subi des violences physiques ou sexuelles au cours de sa vie.</p>
					<p class="gh-fact__s">Source : <a href="https://www.who.int/fr/news-room/fact-sheets/detail/violence-against-women" rel="noopener" target="_blank" title="Organisation mondiale de la Santé : violences à l’encontre des femmes (nouvel onglet)">Organisation mondiale de la Santé (OMS), fiche d’information « Violence à l’encontre des femmes »</a>.</p>
				</div>
				<div class="gh-fact">
					<span class="gh-fact__v">280&nbsp;M</span>
					<p class="gh-fact__t">Environ 280 millions de personnes dans le monde vivent avec une dépression, une maladie qui se soigne.</p>
					<p class="gh-fact__s">Source : <a href="https://www.who.int/fr/news-room/fact-sheets/detail/depression" rel="noopener" target="_blank" title="Organisation mondiale de la Santé : dépression (nouvel onglet)">Organisation mondiale de la Santé (OMS), fiche d’information « Dépression »</a>.</p>
				</div>
				<div class="gh-fact">
					<span class="gh-fact__v">3&nbsp;gestes</span>
					<p class="gh-fact__t">Pour agir ici : créer une alerte, parler à l’assistant, ou maintenir le bouton SOS pour joindre votre position à une alerte.</p>
				</div>
				@if(($feature ?? collect())->count())
					<ul class="gh-mission" aria-label="Ce que permet la plateforme">
						@foreach($feature as $box)<li>{{ $box->title }}<span>{{ preg_replace('/\s+/', ' ', $box->subtitle) }}</span></li>@endforeach
					</ul>
				@endif
			</div>
			<div class="gn-arch">@include('includes.gn.photo', ['n' => '19', 'sizes' => '(max-width: 900px) 320px, 330px'])</div>
		</div>
	</section>

	<!-- Assistant : présentation seulement ; la conversation vit dans la fenêtre flottante (aucun doublon du widget) -->
	@if($gnAsstOn)
	<section class="gn-section gn-section--soft" id="assistant" aria-labelledby="assistant-title">
		<div class="gn-container gh-asstteaser">
			<div class="gh-asstteaser__txt">
				<span class="gn-eyebrow">Assistant GANXÔ</span>
				<h2 id="assistant-title">Parlez à l’assistant, à votre rythme</h2>
				<p>Posez une question ou choisissez un sujet : l’assistant vous donne des repères et vous indique les prochaines étapes. Il peut aussi vous aider à <strong>créer une alerte pas à pas</strong> : vous écrivez, il pose les questions, vous relisez avant l’envoi.</p>
				<ul class="gh-asstteaser__pts">
					<li>Une question à la fois, avec des boutons de réponse.</li>
					<li>Vous pouvez revenir en arrière ou annuler à chaque étape.</li>
					<li>La conversation reste dans votre onglet : rien n’est enregistré tant que vous n’envoyez pas d’alerte.</li>
				</ul>
				<div class="gh-actions">
					<a class="gn-btn gn-btn--primary gn-btn--lg" href="{{ url('/assistant') }}" title="Ouvrir l’assistant GANXÔ" data-gn-open-chat>Ouvrir l’assistant</a>
					<a class="gn-btn gn-btn--ghost gn-btn--lg" href="{{ url('/assistant?alerte=1') }}" title="Créer une alerte avec l’assistant" data-gn-open-chat data-gn-start-alert>Créer une alerte avec l’assistant</a>
				</div>
				<p class="gh-disclaimer">Les réponses sont préparées à l’avance par l’équipe, aucune intelligence artificielle externe n’est utilisée. <a href="{{ url('/assistant') }}" title="Page dédiée à l’assistant GANXÔ">Ouvrir l’assistant en pleine page</a>.</p>
			</div>
			<div class="gh-asstteaser__demo" aria-hidden="true">
				<div class="gh-asstteaser__bar"><span class="gh-chat__dot"></span>Assistant GANXÔ</div>
				<div class="gh-asstteaser__log">
					<p class="gh-msg gh-msg--bot">Quelle adresse e-mail pouvons-nous utiliser pour le suivi ?</p>
					<p class="gh-msg gh-msg--me">…</p>
					<p class="gh-msg gh-msg--bot">De quel type de situation s’agit-il ?</p>
					<div class="gh-asstteaser__btns"><span class="gh-sug">Violence verbale</span><span class="gh-sug">Violence sexuelle</span></div>
				</div>
			</div>
		</div>
	</section>
	@endif

	<!-- Services -->
	<section class="gh-services" aria-labelledby="svc-title" style="margin-block:var(--gn-space-5)">
		<div class="gn-container">
			<div class="gh-head">
				<span class="gn-eyebrow" style="color:var(--gn-yellow)">Services</span>
				<h2 id="svc-title">Ce que GANXÔ vous propose</h2>
				<p>Cinq façons d’être accompagné·e, selon votre besoin du moment.</p>
			</div>
			<div class="gh-svc">
				<article class="gh-svc__i gh-svc__i--alert">
					<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 10h32v22H20l-9 8v-8H8z"/><path d="M24 17v7M24 28h.01"/></svg>
					<h3>Créer une alerte</h3>
					<p>Racontez votre situation à l’équipe GANXÔ, sans compte à créer. Vous recevez un suivi et pouvez échanger depuis la plateforme.</p>
					<a href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
				</article>
				<article class="gh-svc__i gh-svc__i--chat">
					<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="12" width="32" height="22" rx="8"/><path d="M18 34l-4 6M17 23h.01M24 23h.01M31 23h.01"/></svg>
					<h3>Chat assistant</h3>
					<p>Des repères immédiats et des pistes d’orientation, à toute heure.</p>
					<a href="{{ url('/assistant') }}" title="Parler à l’assistant GANXÔ" data-gn-open-chat>Parler à l’assistant</a>
				</article>
				<article class="gh-svc__i gh-svc__i--sos">
					<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M24 42s13-11 13-21a13 13 0 0 0-26 0c0 10 13 21 13 21z"/><circle cx="24" cy="21" r="4.5"/></svg>
					<h3>SOS avec position GPS</h3>
					<p>Maintenez le bouton SOS : après votre confirmation, votre position est jointe à une alerte pour l’équipe GANXÔ. Aucun service d’urgence n’est contacté.</p>
				</article>
				<article class="gh-svc__i gh-svc__i--dir">
					<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 8h22a6 6 0 0 1 6 6v26H16a6 6 0 0 1-6-6z"/><path d="M16 16h16M16 23h16M16 30h9"/></svg>
					<h3>Annuaire d’aide</h3>
					<p>Des contacts d’associations, de psychologues et d’avocat·es qui offrent du soutien.</p>
					@if (setting('FAQ_ENABLE') == 'yes')<a href="{{ url('/faq') }}">Consulter la FAQ</a>@endif
				</article>
				<article class="gh-svc__i gh-svc__i--psy">
					<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M24 40C10 31 8 22 8 18a8 8 0 0 1 16-2 8 8 0 0 1 16 2c0 4-2 13-16 22z"/></svg>
					<h3>Orientation psychologique</h3>
					<p>Des ressources pour comprendre la dépression et être orienté·e vers un·e professionnel·le.</p>
					@if (setting('KNOWLEDGE_ENABLE') == 'yes')<a href="{{ url('/knowledge') }}">Lire les ressources</a>@endif
				</article>
			</div>
		</div>
	</section>

	<!-- Consultation en ligne -->
	@include('includes.gn.consultation')

	<!-- FAQ -->
	@if(($title->faqcheck ?? 'on') == 'on')
	<section class="gn-section" id="faq" aria-labelledby="faq-title">
		<div class="gn-container gh-faq">
			<div>
				<div class="gh-head" style="margin-bottom:var(--gn-space-5)">
					<span class="gn-eyebrow">FAQ</span>
					<h2 id="faq-title">{{ $gnFaqTitle }}</h2>
					<p>{{ $gnFaqSub }}</p>
				</div>
				<div class="gh-acc">
					@foreach($gnFaq as $i => $f)
						<details {{ $i === 0 ? 'open' : '' }}>
							<summary><h3 style="font:inherit;margin:0;letter-spacing:inherit">{{ $f['q'] }}</h3></summary>
							<div class="gh-acc__a">{!! $f['html'] !!}</div>
						</details>
					@endforeach
				</div>
				@if(($faqcategorys ?? collect())->count() && setting('FAQ_ENABLE') == 'yes')
					<ul class="gh-themes" aria-label="Parcourir la FAQ par thème">
						@foreach($faqcategorys->take(6) as $fc)
							<li><a href="{{ route('faq.faqcategory', $fc->id) }}">{{ \Illuminate\Support\Str::limit($fc->faqcategoryname, 60) }}</a></li>
						@endforeach
						<li><a href="{{ url('/faq') }}">Toute la FAQ</a></li>
					</ul>
				@endif
			</div>
			<div class="gn-arch">@include('includes.gn.photo', ['n' => '15', 'sizes' => '(max-width: 900px) 300px, 330px'])</div>
		</div>
	</section>
	@endif

	<!-- Appel à l'action -->
	<section class="gn-section gh-cta" aria-labelledby="cta-title">
		<div class="gn-container gh-cta__in">
			<div>
				<h2 id="cta-title">Vous n’êtes pas seul·e</h2>
				<p>@if(($call->callcheck ?? '') == 'on' && ($call->subtitle ?? '') !== ''){{ $call->subtitle }}@else Rejoignez la communauté du forum GANXÔ pour discuter, poser des questions, partager et trouver du soutien.@endif</p>
				<p class="gh-cta__belong">Lesbiennes, gays, bi, trans, queer, intersexes, asexuel·les, non-binaires, pansexuel·les, en questionnement : vous avez votre place ici.</p>
				<div class="gh-actions" style="margin-top:var(--gn-space-5)">
					<a class="gn-btn gn-btn--lg" href="{{ gn_forum_url() }}" title="Forum d’entraide GANXÔ">@if(($call->callcheck ?? '') == 'on' && ($call->buttonname ?? '') !== ''){{ $call->buttonname }}@else Partager son expérience sur le forum @endif</a>
					<a class="gn-btn gn-btn--ghost gn-btn--lg" href="{{ $gnAlertUrl }}">Créer une alerte</a>
				</div>
			</div>
			<div class="gn-arch">@include('includes.gn.photo', ['n' => '14', 'sizes' => '(max-width: 800px) 240px, 300px'])</div>
		</div>
	</section>

	<!-- Actualités -->
	@if(($title->articlecheck ?? 'on') == 'on')
	<section class="gn-section" id="actualites" aria-labelledby="news-title">
		<div class="gn-container">
			<div class="gh-head">
				<span class="gn-eyebrow">Actualités et ressources</span>
				<h2 id="news-title">{{ $gnArtTitle }}</h2>
				<p>{{ $gnArtSub }}</p>
			</div>
			@if($gnPosts->count())
				<div class="gh-news">
					@foreach($gnPosts as $k => $p)
						@php
							$img = $p->featureimage ? gn_image('uploads/featureimage/' . $p->featureimage) : null;
							$href = url('/article/' . ($p->articleslug ?: $p->id));
							$excerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($p->message), ENT_QUOTES, 'UTF-8'))), 110);
						@endphp
						<a class="gh-post" href="{{ $href }}">
							<div class="gh-post__img">
								@if($img)
									<picture>
										@if(count($img['webp']))
											<source type="image/webp" srcset="@foreach($img['webp'] as $w => $u){{ $u }} {{ $w }}w{{ !$loop->last ? ', ' : '' }}@endforeach" sizes="(max-width: 800px) 92vw, 380px">
										@endif
										<img src="{{ $img['src'] }}" alt="Illustration de l’article : {{ $p->title }}" title="{{ $p->title }}" width="{{ $img['w'] }}" height="{{ $img['h'] }}" loading="lazy" decoding="async">
									</picture>
								@endif
							</div>
							<div class="gh-post__b">
								<h3>{{ $p->title }}</h3>
								<p>{{ $excerpt }}</p>
								<span class="gh-post__more" aria-hidden="true">Lire la suite</span>
							</div>
						</a>
					@endforeach
				</div>
				@if (setting('KNOWLEDGE_ENABLE') == 'yes')<p class="gh-more"><a class="gn-btn gn-btn--primary" href="{{ url('/knowledge') }}">Toutes les actualités</a></p>@endif
			@else
				<p>Les premiers articles arrivent bientôt. En attendant, consultez la <a href="{{ url('/faq') }}">FAQ</a>.</p>
			@endif
		</div>
	</section>
	@endif

	<!-- Contact -->
	<section class="gn-section gn-section--warm" id="contact" aria-labelledby="contact-title">
		<div class="gn-container gh-contact">
			<div>
				<span class="gn-eyebrow">Contact</span>
				<h2 id="contact-title">Une question, une suggestion ?</h2>
				<p>Pour une question sur la plateforme, un partenariat ou un retour d’expérience, écrivez-nous. Pour être aidé·e dans une situation de violence ou de détresse, <a href="{{ $gnAlertUrl }}">créez une alerte</a> : c’est le chemin le plus direct vers l’équipe.</p>
				<div class="gh-links">
					@if (setting('CONTACT_ENABLE') == 'yes')<a class="gn-btn gn-btn--primary" href="{{ url('/contact-us') }}">Contactez-nous</a>@endif
					<a class="gn-btn gn-btn--ghost" style="color:var(--gn-text)" href="{{ gn_forum_url() }}" title="Forum d’entraide GANXÔ">Rejoindre le forum</a>
				</div>
			</div>
			<div class="gn-arch">@include('includes.gn.photo', ['n' => '13', 'sizes' => '(max-width: 800px) 240px, 300px'])</div>
		</div>
	</section>

@endsection
