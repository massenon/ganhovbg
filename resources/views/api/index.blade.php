<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<!-- Title -->
		<title>{{lang('Purchase Code Verification')}}</title>

		<!--Favicon -->
		<link rel="icon" href="{{asset('assets/images/brand/favicon.ico')}}" type="image/x-icon"/>

		<!-- Bootstrap css -->
		<link href="{{gn_asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />

		<!-- Style css -->
		<link href="{{gn_asset('assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/updatestyles.css')}}" rel="stylesheet" />

		<!-- Animate css -->
		<link href="{{gn_asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{gn_asset('assets/css/icons.css')}}" rel="stylesheet" />

		<!-- Select2 css -->
		<link href="{{gn_asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

		<!-- P-scroll bar css-->
		<link href="{{gn_asset('assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />

		@if(setting('GOOGLEFONT_DISABLE') == 'off')
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
		</style>
		@endif

	</head>

	<body>

		<div class="page error-bg">
			<div class="page-content m-0">
				<div class="container text-center">
					<div class="alert alert-danger">
						{{lang('License code is already installed on maximum allowed product instance.')}}
					</div>
				</div>
			</div>
		</div>

		<!-- Jquery js-->
		<script src="{{gn_asset('assets/plugins/jquery/jquery.min.js')}}"></script>

		<!-- Bootstrap4 js-->
		<script src="{{gn_asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

		<!-- Select2 js -->
		<script src="{{gn_asset('assets/plugins/select2/select2.full.min.js')}}"></script>

		<!-- P-scroll js-->
		<script src="{{gn_asset('assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>

		<!-- Custom js-->
		<script src="{{gn_asset('assets/js/custom.js')}}"></script>

	</body>
</html>
