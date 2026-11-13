		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="{{$seopage->description ? $seopage->description :''}}" name="description">
		<meta content="{{$seopage->author ? $seopage->author :''}}" name="author">
		<meta name="keywords" content="{{$seopage->keywords ? $seopage->keywords :''}}"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Title -->
		<title>{{$title->title}}</title>


		<!--Favicon Ganho -->
		<link rel="icon" href="{{ gn_asset('assets/ganho/img/favicon.ico') }}" sizes="48x48">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ gn_asset('assets/ganho/img/icone-appli-32.png') }}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ gn_asset('assets/ganho/img/icone-appli-192.png') }}">
		<link rel="apple-touch-icon" sizes="180x180" href="{{ gn_asset('assets/ganho/img/icone-appli-180.png') }}">


		@if(str_replace('_', '-', app()->getLocale()) == 'عربى')

		<!-- Bootstrap css -->
		<link href="{{gn_asset('assets/plugins/bootstrap/css/bootstrap.rtl.css')}}" rel="stylesheet" />
		@else

		<!-- Bootstrap css -->
		<link href="{{gn_asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
		@endif

		<!-- Style css -->
		<link href="{{gn_asset('assets/css/style.purged.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/updatestyles.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/skin-modes.css')}}" rel="stylesheet" />

		<!-- Animate css -->
		<link href="{{gn_asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!--Sidemenu css -->
		<link href="{{gn_asset('assets/css/sidemenu.css')}}" rel="stylesheet" />

		<!-- P-scroll bar css-->
		<link href="{{gn_asset('assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{gn_asset('assets/css/icons.css')}}" rel="stylesheet" />

		<!-- Select2 css -->
		<link href="{{asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="{{gn_asset('assets/plugins/toastr/toastr.css')}}" rel="stylesheet" />

		<!--INTERNAL Ratings css -->
		<link href="{{gn_asset('assets/plugins/ratings/jquerystarrating.css')}}" rel="stylesheet" />

		<!--gn:page-head-->@yield('styles')<!--/gn:page-head-->

		<!-- Color Changes -->
		<style>
			:root {
		--primary:@php echo setting('theme_color') @endphp;
		--secondary:@php echo setting('theme_color_dark') @endphp;
			}

		</style>
		@if(setting('GOOGLEFONT_DISABLE') == 'off')

		<!-- Google Fonts -->
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
		</style>
		@endif

		@include('includes.gn.appshell')

		<!-- Custom Css -->
		<style>

			<?php echo customcssjs('CUSTOMCSS'); ?>

		</style>

		<!-- Jquery js-->
		<script src="{{gn_asset('assets/plugins/jquery/jquery.min.js')}}"></script>
