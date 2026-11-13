{{--
	Tete SEO reutilisable (title, description, canonical, robots, Open Graph, Twitter, favicons, theme-color, JSON-LD).
	Surcharge par page : definir en haut de la vue enfant (hors section) un tableau $seo, par exemple :
	@php $seo = ['title' => '...', 'description' => '...', 'breadcrumbs' => [['Accueil','/'],['FAQ','/faq']], 'faq' => [[question, reponse], ...]]; @endphp
	Cles : title, description, canonical, robots, type (website|article), image (url absolue), image_alt, image_w, image_h,
	       breadcrumbs, faq, published, modified, schema (tableau JSON-LD supplementaire).
--}}
@php
	$seo = isset($seo) && is_array($seo) ? $seo : [];
	$siteName = 'GANXÔ Alerte VBG';
	$path = trim(request()->path(), '/');
	$pageTitle = $seo['title'] ?? 'GANXÔ Alerte VBG : plateforme d’assistance';
	if (mb_strlen($pageTitle) > 60) { $pageTitle = \Illuminate\Support\Str::limit($pageTitle, 60, '…'); }
	$desc = $seo['description'] ?? trim(strip_tags((string) ($seopage->description ?? '')));
	if ($desc === '') { $desc = 'Plateforme d’assistance confidentielle pour les victimes ou témoins de violences basées sur le genre et les personnes en détresse psychologique.'; }
	$desc = \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', $desc), 155, '…');
	$canonical = $seo['canonical'] ?? gn_url($path === '' ? '/' : $path);
	$noindexPrefixes = ['customer', 'admin', 'search', 'comment', 'replies', 'rating', 'login', 'register', 'install'];
	$firstSeg = explode('/', $path)[0] ?? '';
	$isGuestTicket = $firstSeg === 'guest' && $path !== 'guest/openticket';
	$robots = $seo['robots'] ?? ((in_array($firstSeg, $noindexPrefixes, true) || $isGuestTicket) ? 'noindex,follow' : 'index,follow,max-image-preview:large,max-snippet:-1');
	$ogImage = $seo['image'] ?? gn_url('assets/ganho/img/og-default-1200x630.png');
	$ogW = $seo['image_w'] ?? (isset($seo['image']) ? null : 1200);
	$ogH = $seo['image_h'] ?? (isset($seo['image']) ? null : 630);
	$ogAlt = $seo['image_alt'] ?? 'Logo GANXÔ Alerte VBG sur fond nuit avec les bandes du spectre arc-en-ciel';
	$ogType = $seo['type'] ?? 'website';
	$crumbs = $seo['breadcrumbs'] ?? [['Accueil', '/']];
	$logoUrl = gn_url('assets/ganho/img/logo-couleur-832w.png');

	$graph = [
		[
			'@type' => 'Organization',
			'@id' => gn_url('#organization'),
			'name' => 'GANXÔ',
			'alternateName' => 'Ganho Alerte VBG',
			'url' => gn_url('/'),
			'logo' => ['@type' => 'ImageObject', 'url' => $logoUrl, 'width' => 832, 'height' => 264],
			'description' => 'Plateforme d’assistance pour les victimes ou témoins de violences basées sur le genre et les personnes en détresse psychologique.',
		],
		[
			'@type' => 'WebSite',
			'@id' => gn_url('#website'),
			'url' => gn_url('/'),
			'name' => $siteName,
			'inLanguage' => 'fr-FR',
			'publisher' => ['@id' => gn_url('#organization')],
		],
		array_filter([
			'@type' => 'WebPage',
			'@id' => $canonical . '#webpage',
			'url' => $canonical,
			'name' => $pageTitle,
			'description' => $desc,
			'inLanguage' => 'fr-FR',
			'isPartOf' => ['@id' => gn_url('#website')],
			'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $ogImage],
			'breadcrumb' => ['@id' => $canonical . '#breadcrumb'],
			'datePublished' => $seo['published'] ?? null,
			'dateModified' => $seo['modified'] ?? null,
		]),
	];
	$items = [];
	foreach ($crumbs as $i => $c) {
		$items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => preg_match('#^https?://#', $c[1]) ? $c[1] : gn_url($c[1])];
	}
	$graph[] = ['@type' => 'BreadcrumbList', '@id' => $canonical . '#breadcrumb', 'itemListElement' => $items];
	if (!empty($seo['faq'])) {
		$ents = [];
		foreach ($seo['faq'] as $qa) {
			$ents[] = ['@type' => 'Question', 'name' => $qa[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]]];
		}
		$graph[] = ['@type' => 'FAQPage', '@id' => $canonical . '#faq', 'mainEntity' => $ents];
	}
	if (!empty($seo['schema'])) { foreach ($seo['schema'] as $extra) { $graph[] = $extra; } }
@endphp
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ $pageTitle }}</title>
		<meta name="description" content="{{ $desc }}">
		<meta name="robots" content="{{ $robots }}">
		<meta name="author" content="{{ $seopage->author ?? 'GANXÔ' }}">
		@if(!empty($seopage->keywords))<meta name="keywords" content="{{ $seopage->keywords }}">@endif

		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="canonical" href="{{ $canonical }}">
		<link rel="alternate" hreflang="fr" href="{{ $canonical }}">
		<link rel="alternate" hreflang="x-default" href="{{ $canonical }}">

		<meta name="theme-color" content="#0B0B14">
		<link rel="icon" href="{{ gn_asset('assets/ganho/img/favicon.ico') }}" sizes="48x48">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ gn_asset('assets/ganho/img/icone-appli-32.png') }}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ gn_asset('assets/ganho/img/icone-appli-192.png') }}">
		<link rel="apple-touch-icon" sizes="180x180" href="{{ gn_asset('assets/ganho/img/icone-appli-180.png') }}">
		<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

		<meta property="og:type" content="{{ $ogType }}">
		<meta property="og:site_name" content="{{ $siteName }}">
		<meta property="og:locale" content="fr_FR">
		<meta property="og:title" content="{{ $pageTitle }}">
		<meta property="og:description" content="{{ $desc }}">
		<meta property="og:url" content="{{ $canonical }}">
		<meta property="og:image" content="{{ $ogImage }}">
		<meta property="og:image:secure_url" content="{{ $ogImage }}">
		@if($ogW)<meta property="og:image:width" content="{{ $ogW }}">@endif
		@if($ogH)<meta property="og:image:height" content="{{ $ogH }}">@endif

		<meta property="og:image:alt" content="{{ $ogAlt }}">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="{{ $pageTitle }}">
		<meta name="twitter:description" content="{{ $desc }}">
		<meta name="twitter:image" content="{{ $ogImage }}">
		<meta name="twitter:image:alt" content="{{ $ogAlt }}">

		<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
