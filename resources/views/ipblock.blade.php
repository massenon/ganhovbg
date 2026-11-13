<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="{{$seopage->description ? $seopage->description :''}}" name="description">
		<meta content="{{$seopage->author ? $seopage->author :''}}" name="author">
		<meta name="keywords" content="{{$seopage->keywords ? $seopage->keywords :''}}"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Title -->
		<title>{{$title->title}}</title>

		@if ($title->image4 == null)

		<!--Favicon -->
		<link rel="icon" href="{{asset('uploads/logo/favicons/favicon.ico')}}" type="image/x-icon"/>
		@else

		<!--Favicon -->
		<link rel="icon" href="{{asset('uploads/logo/favicons/'.$title->image4)}}" type="image/x-icon"/>
		@endif

		@if(str_replace('_', '-', app()->getLocale()) == 'عربى')

		<!-- Bootstrap css -->
		<link href="{{asset('assets/plugins/bootstrap/css/bootstrap.rtl.css')}}" rel="stylesheet" />
		@else

		<!-- Bootstrap css -->
		<link href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
		@endif

		<!-- Style css -->
		<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/css/updatestyles.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/css/skin-modes.css')}}" rel="stylesheet" />

		<!-- Animate css -->
		<link href="{{asset('assets/css/animated.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" />

		<style>
			:root {
				--primary:@php echo setting('theme_color') @endphp;
				--secondary:@php echo setting('theme_color_dark') @endphp;
			}

		</style>



	</head>

	<body class="@if(setting('DARK_MODE') == 1) dark-mode @endif @if(str_replace('_', '-', app()->getLocale()) == 'ar')
		rtl
	@endif">

		<div class="page login-bg1">
			<div class="page-single">
				<div class="container">
					<div class="row justify-content-center py-4">
						<div class="col-sm-12">
							<div class="card captcha-card px-3 py-5 mx-auto">
								<div class="pt-0 pb-1 text-center">

									<a class="header-brand" href="{{url('/')}}">
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
                                <div class="pb-0 px-5 pt-0 text-center">
                                    <h3 class="mb-2">{{lang('Enter Captcha')}}</h3>
                                </div>
                                <form class="card-body pt-3 pb-0" id="login" action="{{route('ipblock.update')}}" method="post">
                                    @csrf
                                    @honeypot

                                        <div class="form-group row justify-content-center">
                                            <div class="col-md-12 mb-3
											">
                                                <input type="text" id="captcha" class="form-control @error('captcha') is-invalid @enderror" placeholder="{{lang('Enter Captcha')}}" name="captcha">
                                                @error('captcha')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ lang($message) }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <div class="captcha d-flex border">
                                                    <span class="mx-auto">{!! captcha_img('') !!}</span>
                                                    <button type="button" class="btn btn-secondary btn-icon captchabtn"><i class="fe fe-refresh-cw"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="submit">
                                        <input class="btn btn-secondary btn-block" type="submit" value="{{lang('Submit')}}"
                                            onclick="this.disabled=true;this.form.submit();">
                                    </div>
                                </form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Jquery js-->
		<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>

		<!-- Bootstrap4 js-->
		<script src="{{asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>


		<script type="text/javascript">
			"use strict";

			(function($){

				// Captcha refresh
				$(".captchabtn").on('click', function(e){
					e.preventDefault();
					$.ajax({
						type:'GET',
						url:'{{route('captchas.reload')}}',
						success: function(res){
							$(".captcha span").html(res.captcha);
						}
					});
				});
			})(jQuery);
		</script>

	</body>
</html>

