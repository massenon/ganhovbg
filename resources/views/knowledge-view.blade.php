@extends('layouts.master')

@php
	// Mise en page Ganho (voir layouts/gnlean) : détail d'un article, SEO + JSON-LD Article tirés des données réelles
	$gnLean = true;
	$post = $articles;
	$gnPrivate = $post->privatemode == 1;
	$gnLogged = Auth::guard('customer')->check() && Auth::guard('customer')->user();
	$gnCanRead = !$gnPrivate || $gnLogged;
	$gnSlugPath = 'article/' . ($post->articleslug ?: $post->id);
	$gnCanonical = gn_url($gnSlugPath);
	$gnCat = $post->category;
	$gnProse = $gnCanRead ? gn_prose(ucfirst((string) $post->message)) : ['html' => '', 'toc' => []];
	$gnToc = collect($gnProse['toc'])->where(2, 2)->count() >= 2 ? $gnProse['toc'] : [];
	$gnChapo = trim((string) ($post->chapo ?? ''));
	$gnAuthor = trim((string) ($post->author_name ?? '')) ?: 'Équipe GANXÔ';
	$gnWords = str_word_count(strip_tags((string) $post->message) . ' ' . $gnChapo, 0, 'àâäçéèêëîïôöùûüÿœÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸŒ');
	$gnMinutes = max(1, (int) ceil($gnWords / 200));
	$gnUpdated = ($post->updated_at && $post->created_at && $post->updated_at->diffInHours($post->created_at) >= 24) ? $post->updated_at : null;
	$gnTags = collect(preg_split('/\s*,\s*/u', (string) $post->tags, -1, PREG_SPLIT_NO_EMPTY))->map(function ($t) { return trim($t); })->filter()->unique()->values();
	$gnSource = trim((string) ($post->source ?? ''));
	$gnCaption = trim((string) ($post->image_caption ?? ''));
	$gnCredit = trim((string) ($post->image_credit ?? ''));
	$gnFile = $post->featureimage ? ($post->featureimage == 'frontend.jpg' ? 'uploads/featureimage/demo/frontend.jpg' : 'uploads/featureimage/' . $post->featureimage) : null;
	$gnImg = $gnFile ? gn_image($gnFile) : null;
	$gnDesc = $gnCanRead ? gn_excerpt($post->message, 155) : 'Cet article est réservé aux personnes connectées à GANXÔ Alerte VBG.';
	if ($gnDesc === '') { $gnDesc = 'Article publié par GANXÔ Alerte VBG : ' . $post->title; }
	// SEO éditorial (colonnes meta_title, meta_description, faq) : prioritaire sur les valeurs déduites
	if ($gnCanRead && trim((string) ($post->meta_description ?? '')) !== '') { $gnDesc = trim($post->meta_description); }
	$gnMetaTitle = trim((string) ($post->meta_title ?? '')) ?: strip_tags($post->title);
	$gnFaq = [];
	if ($gnCanRead && !empty($post->faq)) {
		$gnFaqRaw = json_decode($post->faq, true);
		foreach ((array) $gnFaqRaw as $qa) { if (is_array($qa) && count($qa) >= 2 && trim($qa[0]) !== '' && trim($qa[1]) !== '') { $gnFaq[] = [trim($qa[0]), trim($qa[1])]; } }
	}

	$gnCrumbs = [['Accueil', '/'], ['Actualités', '/knowledge']];
	if ($gnCat) { $gnCrumbs[] = [\Illuminate\Support\Str::limit($gnCat->name, 40, '…'), '/category/' . ($gnCat->categoryslug ?: $gnCat->id)]; }
	$gnCrumbs[] = [\Illuminate\Support\Str::limit($post->title, 60, '…'), '/' . $gnSlugPath];

	$gnArticleLd = array_filter([
		'@type' => 'NewsArticle',
		'@id' => $gnCanonical . '#article',
		'mainEntityOfPage' => ['@id' => $gnCanonical . '#webpage'],
		'headline' => \Illuminate\Support\Str::limit(strip_tags($post->title), 110, ''),
		'description' => $gnChapo ?: $gnDesc,
		'inLanguage' => 'fr-FR',
		'image' => $gnImg ? [$gnImg['src']] : null,
		'datePublished' => optional($post->created_at)->toIso8601String(),
		'dateModified' => optional($post->updated_at ?: $post->created_at)->toIso8601String(),
		'author' => ['@type' => 'Organization', 'name' => $gnAuthor, 'url' => gn_url('/')],
		'wordCount' => $gnWords,
		'alternativeHeadline' => $gnChapo ?: null,
		'publisher' => ['@id' => gn_url('#organization')],
		'articleSection' => $gnCat ? $gnCat->name : null,
		'keywords' => $post->tags ?: null,
		'isAccessibleForFree' => $gnPrivate ? 'False' : 'True',
	]);
	$seo = [
		'title' => \Illuminate\Support\Str::limit($gnMetaTitle, 60, '…'),
		'description' => $gnDesc,
		'canonical' => $gnCanonical,
		'robots' => $gnPrivate ? 'noindex,follow' : null,
		'type' => 'article',
		'image' => $gnImg ? $gnImg['src'] : null,
		'image_w' => $gnImg ? $gnImg['w'] : null,
		'image_h' => $gnImg ? $gnImg['h'] : null,
		'image_alt' => 'Illustration de l’article : ' . strip_tags($post->title),
		'published' => optional($post->created_at)->toIso8601String(),
		'modified' => optional($post->updated_at ?: $post->created_at)->toIso8601String(),
		'breadcrumbs' => $gnCrumbs,
		'schema' => [$gnArticleLd],
		'faq' => $gnFaq ?: null,
	];
	$seo = array_filter($seo, function ($v) { return $v !== null; });

	$gnRelated = collect(method_exists($recentarticles ?? null, 'items') ? $recentarticles->items() : ($recentarticles ?? []))->filter(function ($a) use ($post) { return strtolower((string) $a->status) === 'published' && $a->id !== $post->id; })->take(3)->values();
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
	$gnMedia = $gnCanRead ? $post->getMedia('article') : collect();
	$gnLikes = $like->count();
	$gnDislikes = $dislike->count();
	$gnMine = isset($viewrating) && $viewrating ? (string) $viewrating->rating : null;
@endphp

@push('gn_head')
	<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/pages-content.css') }}">
@endpush
@push('gn_scripts')
	<script src="{{ gn_asset('assets/ganho/js/pages-content.js') }}" defer></script>
@endpush

@section('content')

	<section class="gc-hero" aria-labelledby="gc-title">
		<div class="gn-container">
			@include('includes.breadcrumbs')
			<div class="gc-hero__in">
				@if($gnCat)<a class="gc-tag" href="{{ url('/category/' . ($gnCat->categoryslug ?: $gnCat->id)) }}">{{ $gnCat->name }}</a>@else<span class="gn-eyebrow">{{ lang('Actualités') }}</span>@endif
				<h1 id="gc-title">{{ $post->title }}</h1>
				@if($gnChapo)<p class="gc-chapo">{{ $gnChapo }}</p>@endif
				<ul class="gc-hero__meta">
					<li>Par <strong>{{ $gnAuthor }}</strong></li>
					<li>Publié le <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->locale('fr')->translatedFormat('j F Y') }}</time></li>
					@if($gnUpdated)<li>Mis à jour le <time datetime="{{ $gnUpdated->toIso8601String() }}">{{ $gnUpdated->locale('fr')->translatedFormat('j F Y') }}</time></li>@endif
					<li>{{ $gnMinutes }} min de lecture</li>
					@if(setting('article_count') == 'on')<li>{{ $post->views }} {{ $post->views > 1 ? 'vues' : 'vue' }}</li>@endif
				</ul>
				@include('includes.gc.search')
			</div>
		</div>
		<div class="gn-stripes" role="presentation"></div>
	</section>

	<section class="gn-section" aria-label="Contenu de l’article">
		<div class="gn-container">
			<div class="gc-read">
				<div class="gc-read__main">
					@if($gnCanRead)
						@if($gnImg)
							<figure class="gc-figure">{!! gn_picture($gnFile, $gnCaption ?: 'Illustration de l’article : ' . $post->title, $post->title, '(max-width: 1000px) 92vw, 760px', 'fetchpriority="high"') !!}
								@if($gnCaption || $gnCredit)<figcaption>{{ $gnCaption }}@if($gnCredit) <span class="gc-credit">{{ $gnCredit }}</span>@endif</figcaption>@endif
							</figure>
						@endif
						<div class="gc-prose">{!! $gnProse['html'] !!}</div>

						@if(count($gnFaq))
							<section class="gc-faq-art" aria-labelledby="gc-faq-title">
								<h2 id="gc-faq-title">Questions fréquentes</h2>
								@include('includes.gc.faq-items', ['items' => collect($gnFaq)->map(function ($qa) { return ['q' => $qa[0], 'html' => '<p>' . e($qa[1]) . '</p>', 'locked' => false]; })->all(), 'hl' => 3])
							</section>
						@endif

						<div class="gc-after">
							@if($gnSource)<p class="gc-source"><strong>Source :</strong> {{ $gnSource }}</p>@endif
							@if($gnTags->count())
								<ul class="gc-tags" aria-label="Mots-clés">
									@foreach($gnTags as $t)<li><a href="{{ url('/knowledge?search=' . urlencode($t)) }}">#{{ $t }}</a></li>@endforeach
								</ul>
							@endif
							<div class="gc-share" data-url="{{ $gnCanonical }}">
								<span class="gc-share__t">Partager :</span>
								<a href="https://wa.me/?text={{ urlencode($post->title . ' ' . $gnCanonical) }}" target="_blank" rel="noopener">WhatsApp</a>
								<a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($gnCanonical) }}" target="_blank" rel="noopener">Facebook</a>
								<a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&amp;url={{ urlencode($gnCanonical) }}" target="_blank" rel="noopener">X</a>
								<a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($gnCanonical) }}" target="_blank" rel="noopener">LinkedIn</a>
								<button type="button" data-copy-link>Copier le lien</button>
							</div>
							@if($gnMedia->count())
								<div>
									<h2 style="font-size:var(--gn-fs-h4)">Pièces jointes</h2>
									<ul class="gc-files">
										@foreach ($gnMedia as $m)
											@php $gnExt = strtoupper(pathinfo($m->file_name, PATHINFO_EXTENSION)); @endphp
											<li>
												<span>{{ pathinfo($m->file_name, PATHINFO_FILENAME) }}.{{ strtolower($gnExt) }}</span>
												<a href="{{ route('imageurl', [$m->id, $m->file_name]) }}" target="_blank" rel="noopener" title="Ouvrir {{ $m->file_name }} dans un nouvel onglet">Ouvrir</a>
												<a href="{{ route('imagedownload', [$m->id, $m->file_name]) }}" title="Télécharger {{ $m->file_name }}">Télécharger</a>
											</li>
										@endforeach
									</ul>
								</div>
							@endif

							@if($gnLogged)
								<div class="gc-rate" id="gc-rate" data-article="{{ $post->id }}" data-url="{{ route('likedislike') }}">
									<p>Cet article vous a-t-il été utile ?</p>
									<button type="button" data-value="like" aria-pressed="{{ $gnMine === '1' ? 'true' : 'false' }}" @if($gnMine === '1') disabled @endif>Utile <span class="gc-rate__count">{{ $gnLikes }}</span></button>
									<button type="button" data-value="dislike" aria-pressed="{{ $gnMine === '-1' ? 'true' : 'false' }}" @if($gnMine === '-1') disabled @endif>Pas utile <span class="gc-rate__count">{{ $gnDislikes }}</span></button>
									<span class="gn-hint" role="status"></span>
								</div>
							@elseif($gnLikes + $gnDislikes > 0)
								<p class="gc-rate__count" style="margin:0">Avis des lecteurs : {{ $gnLikes }} utile, {{ $gnDislikes }} pas utile.</p>
							@endif
						</div>
					@else
						<div class="gc-notice" role="note">
							<p><strong>Article réservé.</strong> Vous devez être connecté·e pour accéder à ce contenu.</p>
							<p style="margin-top:var(--gn-space-3)"><a class="gn-btn gn-btn--primary" href="{{ url('customer/login') }}">Se connecter</a></p>
						</div>
					@endif
				</div>

				<aside class="gc-read__aside" aria-label="Autour de l’article">
					@if(count($gnToc))
						<details class="gc-toc gc-toc--collapse" open>
							<summary>Dans cet article</summary>
							<ol>
								@foreach($gnToc as $t)
									<li @if($t[2] === 3) style="padding-left:var(--gn-space-4)" @endif><a href="#{{ $t[0] }}">{{ $t[1] }}</a></li>
								@endforeach
							</ol>
						</details>
					@endif
					<div class="gc-box gc-box--news" id="newsletter-aside">
						<h2>Newsletter</h2>
						<p>Recevez nos actualités et ressources. Désinscription en un clic.</p>
						<form method="POST" action="{{ route('newsletter.subscribe') }}" novalidate>
							@csrf
							<input type="hidden" name="anchor" value="newsletter-aside">
							<div class="gh-sr" aria-hidden="true"><label>Ne pas remplir <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
							<label class="gh-sr" for="newsletter_email_aside">Votre adresse e-mail</label>
							<input id="newsletter_email_aside" name="newsletter_email" type="email" autocomplete="email" placeholder="vous@exemple.org" value="{{ old('newsletter_email') }}" required>
							<label class="gc-box__consent"><input type="checkbox" name="newsletter_consent" value="1" required> J’accepte de recevoir la newsletter.</label>
							<button type="submit" class="gn-btn gn-btn--primary">S’abonner</button>
							@if (session('newsletter_status'))<p class="gc-box__msg" role="status">{{ session('newsletter_status') }}</p>@endif
							@if (session('newsletter_error'))<p class="gc-box__msg gc-box__msg--err" role="alert">{{ session('newsletter_error') }}</p>@endif
						</form>
					</div>
					<div class="gc-box gc-box--help">
						<h2>Besoin d’aide ?</h2>
						<p>Créez une alerte VBG : l’équipe GANXÔ vous écoute et vous oriente.</p>
						<a class="gn-btn gn-btn--light" href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
					</div>
					@if($gnRelated->count())
						<div class="gc-box gc-box--list">
							<h2>À lire aussi</h2>
							<ul>
								@foreach($gnRelated->take(3) as $a)
									<li><a href="{{ url('/article/' . ($a->articleslug ?: $a->id)) }}">{{ $a->title }}</a></li>
								@endforeach
							</ul>
						</div>
					@endif
				</aside>
			</div>
		</div>
	</section>

	@if($gnRelated->count())
		<section class="gn-section gn-section--soft gn-section--round-top" aria-labelledby="gc-more-title">
			<div class="gn-container">
				<div class="gh-head" style="margin-bottom:var(--gn-space-5)">
					<span class="gn-eyebrow">Continuer la lecture</span>
					<h2 id="gc-more-title">Articles recommandés</h2>
				</div>
				<ul class="gc-grid">
					@foreach($gnRelated as $a)
						<li>@include('includes.gc.card', ['a' => $a])</li>
					@endforeach
				</ul>
			</div>
		</section>
	@endif

	<section class="gn-section" aria-labelledby="gc-help-title">
		<div class="gn-container">
			<div class="gc-help">
				<div>
					<h2 id="gc-help-title">Vous vous reconnaissez dans cet article&nbsp;?</h2>
					<p>Vous n’êtes pas seul·e. Créez une alerte pour être écouté·e et orienté·e par l’équipe GANXÔ.</p>
				</div>
				<div class="gc-help__a">
					<a class="gn-btn gn-btn--primary" href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
					<a class="gn-btn gn-btn--ghost" href="{{ url('/knowledge') }}">Toutes les actualités</a>
				</div>
			</div>
		</div>
	</section>

@endsection
