<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
	<head>

		@include('includes.seo')

		@if(getIsRtl() == 'rtl')

		<!-- Bootstrap css -->
		<link href="{{gn_asset('assets/plugins/bootstrap/css/bootstrap.rtl.css')}}" rel="stylesheet" />
		@else

		<!-- Bootstrap css -->
		<link href="{{gn_asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
		@endif

		<!-- Style css -->
		<link href="{{gn_asset('assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/skin-modes.css')}}" rel="stylesheet" />
		<link href="{{gn_asset('assets/css/updatestyles.css')}}" rel="stylesheet" />

		<!-- Animate css -->
		<link href="{{gn_asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{gn_asset('assets/css/icons.css')}}" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="{{gn_asset('assets/plugins/toastr/toastr.css')}}" rel="stylesheet" />

		<style>
			:root {
		--primary:@php echo setting('theme_color') @endphp;
		--secondary:@php echo setting('theme_color_dark') @endphp;
			}

		</style>

		<style>

			<?php echo customcssjs('CUSTOMCSS'); ?>

		</style>

		@if(setting('GOOGLEFONT_DISABLE') == 'off')

		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

		</style>

		@endif

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

	<body class="@if(setting('DARK_MODE') == 1) dark-mode @endif {{getIsRtl()}}">

        @if(setting('ANNOUNCEMENT_USER') == 'non_login_users' || setting('ANNOUNCEMENT_USER') == 'all_users')
            <div class="uhelp-announcement-alertgroup">
            @foreach ($announcement as $anct)
            @if ($anct->status == 1)
            <div class="alert" role="alert" style="background: linear-gradient(to right, {{$anct->primary_color}}, {{$anct->secondary_color}});">
            <div class="container">
            <button type="submit" class="btn-close ms-5 float-end text-white notifyclose" data-id="{{$anct->id}}">×</button>
            <div class="d-flex align-items-top">
            <div class="uhelp-announcement me-2">
            <svg class="svg-info" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
            <div class="text-default d-flex align-items-top">
            <div class="notice-heading d-flex align-items-top flex-fill">
            <div>
            <span class="fs-15 font-weight-bold text-white flex-fill">{{$anct->title}}</span>
            <span class="text-white opacity-50 mx-2"><i class="ti ti-minus"></i></span>
            <span class="mb-0 text-white uhelp-alert-content alert-notice">{!!$anct->notice!!}
            @if($anct->buttonon == 1)
            <a class="btn btn-sm ms-2 text-white text-decoration-underline" href="{{$anct->buttonurl}}" target="_blank">{{$anct->buttonname}}</a>
            @endif
            </span>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            @endif
            @endforeach

            @foreach ($announcements as $ancts)
            @php
            $announceDay = explode(',', $ancts->announcementday);
            $now = today()->format('D');

            @endphp
            @foreach ($announceDay as $announceDays)
            @if ($ancts->status == 1 && $announceDays == $now)
            <div class="alert alert-days" role="alert" style="background: linear-gradient(to right, {{$ancts->primary_color}}, {{$ancts->secondary_color}});">
            <div class="container">
            <button type="submit" class="btn-close ms-5 float-end text-white notifyclose" data-id="{{$ancts->id}}">×</button>
            <div class="d-flex align-items-top">
            <div class="uhelp-announcement me-2">
            <svg class="svg-info" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
            </div>
            <div class="text-default d-flex align-items-top">
            <div class="notice-heading d-flex align-items-top flex-fill">
            <div>
            <span class="fs-15 font-weight-bold text-white flex-fill">{{$ancts->title}}</span>
            <span class="text-white opacity-50 mx-2"><i class="ti ti-minus"></i></span>
            <span class="mb-0 text-white uhelp-alert-content alert-notice">{!!$ancts->notice!!}
            @if($ancts->buttonon == 1)
            <a class="btn btn-sm ms-2 text-white text-decoration-underline" href="{{$ancts->buttonurl}}" target="_blank">{{$ancts->buttonname}}</a>
            @endif
            </span>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            @endif
            @endforeach
            @endforeach
            </div>
        @endif

		<div class="page login-bg1">
            <div class="page-single">
                <div class="container">
                    <div class="row justify-content-center py-4">
                        <div class="col-sm-12">
                            <div class="card authentication-card-register py-5 mx-auto">
                                <div class="pb-1 pt-0 text-center">

                                    <a class="header-brand ms-0" href="{{url('/')}}">
                                        @if ($title->image !== null)

                                        <img src="{{asset('uploads/logo/logo/'.$title->image)}}" class="header-brand-img custom-logo-dark"
                                            alt="{{$title->image}}">
                                        @else
                                        <img src="{{asset('uploads/logo/logo/logo-white.png')}}" class="header-brand-img custom-logo-dark"
                                            alt="logo">
                                        @endif
                                        @if ($title->image1 !== null)

                                            <img src="{{asset('uploads/logo/darklogo/'.$title->image1)}}" class="header-brand-img custom-logo"
                                            alt="{{$title->image1}}">
                                        @else

                                        <img src="{{asset('uploads/logo/darklogo/logo.png')}}" class="header-brand-img custom-logo"
                                            alt="logo">

                                        @endif

                                    </a>
                                </div>

                                @yield('content')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<!-- Jquery js-->
		<script src="{{gn_asset('assets/plugins/jquery/jquery.min.js')}}"></script>

		<!-- Bootstrap4 js-->
		<script src="{{gn_asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{gn_asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

		<script>

			@php echo customcssjs('CUSTOMJS') @endphp
		</script>

        <script type="text/javascript">
            "use strict";
            (function($){
            let notifyClose = document.querySelectorAll('.notifyclose');
            notifyClose.forEach(ele => {
            if(ele){
            let id = ele.getAttribute('data-id');
            if(getCookie(id)){
            ele.closest('.alert').classList.add('d-none');
            }else{
            ele.addEventListener('click', setCookie);
            }
            }
            })


            function setCookie($event) {
            console.log('set');
            const d = new Date();
            let id = $event.currentTarget.getAttribute('data-id');
            d.setTime(d.getTime() + (30 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = id + "=" + 'announcement_close' + ";" + expires + ";path=/";
            $event.currentTarget.closest('.alert').classList.add('d-none');
            }

            function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
            c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
            }
            }
            return '';
            }
            })(jQuery);
        </script>

		<!--INTERNAL Toastr js -->
		<script src="{{gn_asset('assets/plugins/toastr/toastr.min.js')}}"></script>

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
			@yield('scripts')

			@yield('modal')
	</body>
</html>
