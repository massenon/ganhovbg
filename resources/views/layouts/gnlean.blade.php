<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
	<head>
		@include('includes.seo')
		@include('includes.gn.spa', ['zone' => 'public'])
		<script>document.documentElement.className='js'</script>
		<link rel="preload" href="{{ asset('assets/ganho/fonts/Montserrat-latin-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
		<!--gn:page-head-->@stack('gn_head')<!--/gn:page-head-->
		@include('includes.gn.instant', ['mode' => 'moderate'])
		<link rel="preload" href="{{ gn_asset('assets/ganho/css/ganho.css') }}" as="style">
		<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/ganho.css') }}">
		<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/gn-assistant.css') }}">
		@if(trim(customcssjs('CUSTOMCSS')) !== '')<style>{!! customcssjs('CUSTOMCSS') !!}</style>@endif

		@if(setting('GOOGLE_ANALYTICS_ENABLE') == 'yes')
		<script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('GOOGLE_ANALYTICS') }}"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '{{ setting('GOOGLE_ANALYTICS') }}');
		</script>
		@endif
	</head>
	<body{!! empty($gnNoChatWindow) ? ' class="has-asst"' : '' !!}>
		<div id="gn-flash">
		@if(isset($announcement) && (setting('ANNOUNCEMENT_USER') == 'all_users' || (setting('ANNOUNCEMENT_USER') == 'non_login_users' && !Auth::guard('customer')->check())))
			@foreach ($announcement as $anct)
				@if ($anct->status == 1)
					<div class="gh-flash" role="status"><strong>{{ $anct->title }}</strong> {!! strip_tags($anct->notice, '<a><strong><em>') !!}</div>
				@endif
			@endforeach
		@endif
		@foreach (['error' => 'gh-flash gh-flash--error', 'success' => 'gh-flash', 'info' => 'gh-flash', 'warning' => 'gh-flash'] as $k => $cls)
			@if (Session::has($k))<div class="{{ $cls }}" role="status">{{ strip_tags(Session::get($k)) }}</div>@endif
		@endforeach
		</div>

		@include('includes.gn.header')

		<main id="main">
			@yield('content')
		</main>

		@include('includes.gn.footer')
		@include('includes.gn.sos')
		@if(empty($gnNoChatWindow))@include('includes.gn.chatwindow')@endif

		@include('includes.gn.pwa')
		@include('includes.gn.install')
		<script src="{{ gn_asset('assets/ganho/js/ganho-home.js') }}" defer></script>
		<script src="{{ gn_asset('assets/ganho/js/gn-assistant.js') }}" defer></script>
			<!--gn:page-scripts-->@stack('gn_scripts')<!--/gn:page-scripts-->

		@guest
			@if (customcssjs('CUSTOMCHATENABLE') == 'enable' && customcssjs('CUSTOMCHATUSER') == 'public')
				@php echo customcssjs('CUSTOMCHAT') @endphp
			@endif
		@endguest
	</body>
</html>
