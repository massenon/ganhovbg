@if(!empty($gnLean))
@include('layouts.gnlean')
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
	<head>
    	@include('includes.styles')


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

	<body class="{{getIsRtl()}}
	@if(setting('SPRUKOADMIN_C') == 'off')
		@if(setting('DARK_MODE') == 1) dark-mode @endif
	@else
		@if(Auth::guard('customer')->check())
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting->darkmode == 1) dark-mode @endif
		@else
			@if(setting('DARK_MODE') == 1) dark-mode @endif
		@endif
	@endif">

        @if(setting('ANNOUNCEMENT_USER') == 'non_login_users' && Auth::guard('customer')->check() == false || setting('ANNOUNCEMENT_USER') == 'all_users')
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
        @else
            @if(setting('ANNOUNCEMENT_USER') == 'only_login_user' && Auth::guard('customer')->check() == true)
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
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            @endif
        @endif

				@include('includes.user.mobileheader')

				@include('includes.menu')

				<div class="page page-1">
					<div class="page-main">

							@yield('content')

					</div>
				</div>

				@include('includes.footer')

    	@include('includes.scripts')

		@guest
		@if (customcssjs('CUSTOMCHATENABLE') == 'enable')
		@if (customcssjs('CUSTOMCHATUSER') == 'public')
		@php echo customcssjs('CUSTOMCHAT') @endphp
		@endif
		@endif
		@else
		@if (customcssjs('CUSTOMCHATENABLE') == 'enable')
		@if (customcssjs('CUSTOMCHATUSER') == 'user')
		@if (Auth::guard('customer')->check() && Auth::guard('customer')->user())
		@php echo customcssjs('CUSTOMCHAT') @endphp
		@endif
		@endif
		@if (customcssjs('CUSTOMCHATUSER') == 'public')
		@php echo customcssjs('CUSTOMCHAT') @endphp
		@endif
		@endif
	@endguest
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

			@if(setting('REGISTER_POPUP') == 'yes')
			@if(!Auth::guard('customer')->check())

			@include('user.auth.modalspopup.register')

			@include('user.auth.modalspopup.login')

			@include('user.auth.modalspopup.forgotpassword')

			@endif
			@endif

			@if(setting('GUEST_TICKET') == 'yes')

				@include('guestticket.guestmodal')

			@endif

			@yield('modal')

	</body>
</html>
@endif
