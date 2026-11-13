		@include('includes.seo')


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

		<!-- Animate css -->
		<link href="{{gn_asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!-- P-scroll bar css-->
		<link href="{{gn_asset('assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{gn_asset('assets/css/icons.css')}}" rel="stylesheet" />

		<!-- Select2 css -->
		<link href="{{gn_asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="{{gn_asset('assets/plugins/toastr/toastr.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{gn_asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

		<!--gn:page-head-->@yield('styles')<!--/gn:page-head-->

		<!-- Color Setting -->
		<style>
			:root {
				--primary: @php echo setting('theme_color') @endphp;
				--secondary: @php echo setting('theme_color_dark') @endphp;
			}
		</style>

		@include('includes.gn.appshell')

		<!-- Custom css -->
		<style>

			<?php echo customcssjs('CUSTOMCSS'); ?>

		</style>

		@if(setting('GOOGLEFONT_DISABLE') == 'off')

		<!-- Google Fonts -->
		<style>

			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

		</style>

		@endif

		<!-- Jquery js-->
		<script src="{{gn_asset('assets/plugins/jquery/jquery.min.js')}}"></script>
