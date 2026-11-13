{{-- Page d'erreur autonome (500, 503) : aucune requête base de données, aucune session, aucun JS. Même design Ganho. --}}
<!DOCTYPE html>
<html lang="fr" class="no-js">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ $gcHeading }} | GANXÔ Alerte VBG</title>
		<meta name="description" content="{{ $gcText }}">
		<meta name="robots" content="noindex,nofollow">
		<meta name="theme-color" content="#0B0B14">
		<link rel="icon" href="{{ asset('assets/ganho/img/favicon.ico') }}" sizes="48x48">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/ganho/img/icone-appli-192.png') }}">
		<link rel="preload" href="{{ asset('assets/ganho/fonts/Montserrat-latin-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
		<link rel="stylesheet" href="{{ asset('assets/ganho/css/ganho.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/ganho/css/pages-content.css') }}">
	</head>
	<body>
		<a class="gn-skip" href="#main">Aller au contenu</a>
		<header class="gh-nav">
			<div class="gn-container gh-nav__in">
				<a class="gh-brand" href="{{ url('/') }}" title="GANXÔ Alerte VBG : retour à l’accueil" aria-label="GANXÔ Alerte VBG, accueil">
					<picture>
						<source type="image/webp" srcset="{{ asset('assets/ganho/img/logo-lettres-blanches-416w.webp') }}">
						<img src="{{ asset('assets/ganho/img/logo-lettres-blanches-416w.png') }}" alt="Logo GANXÔ Alerte VBG" title="GANXÔ Alerte VBG" width="147" height="42" decoding="async">
					</picture>
				</a>
			</div>
		</header>
		<main id="main">
			@include('includes.gc.error')
		</main>
	</body>
</html>
