{{-- En-tête commun admin + espace client : thème Ganho, préchargement du CSS critique, PWA. --}}
<meta name="theme-color" content="#0B0B14">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ gn_asset('assets/ganho/img/icone-appli-180.png') }}">
<link rel="preload" href="{{ asset('assets/ganho/fonts/Montserrat-latin-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ gn_asset('assets/ganho/css/gn-app.css') }}" as="style">
<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/gn-app.css') }}">
@include('includes.gn.instant', ['mode' => 'conservative'])
