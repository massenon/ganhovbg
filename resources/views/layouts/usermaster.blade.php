<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<script>document.documentElement.classList.add('js')</script>
		@include('includes.gn.spa', ['zone' => 'client'])

		@include('includes.styles')
		<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/gn-assistant.css') }}">
		<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/gn-customer.css') }}">

		@if(setting('GOOGLE_ANALYTICS_ENABLE') == 'yes')
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('GOOGLE_ANALYTICS')}}"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '{{setting('GOOGLE_ANALYTICS')}}');
		</script>
		@endif

	</head>

	<body class="gcx-body {{getIsRtl()}}
	@if(setting('SPRUKOADMIN_C') == 'off')
		@if(setting('DARK_MODE') == 1) dark-mode @endif
	@else
		@if(Auth::guard('customer')->check())
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null)
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting->darkmode == 1) dark-mode @endif
			@endif
		@else
			@if(setting('DARK_MODE') == 1) dark-mode @endif
		@endif
	@endif
		">
		<a class="gcx-skip" href="#gcx-main">Aller au contenu</a>

		<div class="gcx-shell">
			@include('includes.user.gnsidebar')

			<div class="gcx-col">
				@include('includes.user.gntopbar')

				<main class="gcx-main" id="gcx-main" tabindex="-1">
					<div class="page">
						<div class="page-main">
							@yield('content')
						</div>
					</div>
				</main>

				<footer class="gcx-foot">
					<span>© {{ date('Y') }} GANXÔ Alerte VBG</span>
					@php $gnPages = collect($page ?? \App\Models\Pages::all())->filter(function ($pg) { return $pg->status == '1' && ($pg->viewonpages == 'both' || $pg->viewonpages == 'footer'); }); @endphp
					<nav aria-label="Informations légales">
						@foreach ($gnPages as $pg)<a href="{{ url('page/' . $pg->pageslug) }}">{{ $pg->pagename }}</a>@endforeach
					</nav>
				</footer>
			</div>
		</div>

		@include('includes.scripts')
		@include('includes.gn.pwa')
		@include('includes.gn.install')
		@include('includes.gn.chatwindow')
		<script src="{{ gn_asset('assets/ganho/js/gn-assistant.js') }}" defer></script>
		<script src="{{ gn_asset('assets/ganho/js/gn-customer.js') }}" defer></script>

	<!--gn:page-scripts-->
	@if (Session::has('error'))
	<script>
		toastr.error("{!! Session::get('error') !!}");
	</script>
	@elseif(Session::has('success'))
	<script>
		toastr.success("{!! Session::get('success') !!}");
	</script>
	@elseif(Session::has('info'))
	<script>
		toastr.info("{!! Session::get('info') !!}");
	</script>
	@elseif(Session::has('warning'))
	<script>
		toastr.warning("{!! Session::get('warning') !!}");
	</script>
	@endif
	<!--/gn:page-scripts-->

	<div id="gn-spa-modal">@yield('modal')</div>

</body>

</html>
