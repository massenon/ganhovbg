
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

		<!--Favicon Ganho -->
		<link rel="icon" href="{{ gn_asset('assets/ganho/img/favicon.ico') }}" sizes="48x48">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ gn_asset('assets/ganho/img/icone-appli-32.png') }}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ gn_asset('assets/ganho/img/icone-appli-192.png') }}">
		<link rel="apple-touch-icon" sizes="180x180" href="{{ gn_asset('assets/ganho/img/icone-appli-180.png') }}">

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

    <div class="page login-bg1">
        <div class="page-single">
            <div class="container">
                <div class="row justify-content-center py-4">
                    <div class="col-sm-12">
                        <div class="card authentication-card mx-auto">
                            <div class="pt-0 pb-2 pt-4 text-center">

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
                            <div class="px-5 my-2 pt-0 text-center">
                                <h3 class="mb-2">Authentification de la victime</h3>
                                 <p class="text-muted fs-13 mb-1">Connectez-vous à votre compte</p>
                            </div>

                            <div class="card-body pt-2">
                                @php
                                $email = $ticket->cust->email;
                                list($emailname, $domain) = explode('@', $email);
                                $emailname = substr_replace($emailname, str_repeat("*", 8), 2, -2);
                                $EmailAddresshide = $emailname.'@'.$domain;

                                @endphp
                                <table class="table mb-0 border-0 table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="w-100">
                                                <span class="w-50">{{lang('Arlette ID')}}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span class="font-weight-semibold">{{$ticket->ticket_id}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-100">
                                                <span class="w-50">{{lang('Email')}}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span class="font-weight-semibold">{{$EmailAddresshide}}</span>

                                            </td>
                                        </tr>
                                        <tr id="sprukootpverify">
                                            <td class="w-100">
                                                <span class="w-50">{{lang('Vérifier OTP')}}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <input class="form-control " placeholder="{{lang('OTP')}}" type="password" maxlength="6" name="verifyguestotp" id="guestsprukoverifyotp" >
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>


                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary w-100 sprukobtnverifyemail" data-id="{{$ticket->id}}" >{{lang('Obtenir OTP')}}</button>
                            </div>

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


			<script type="text/javascript">
				'use strict';

				$('#sprukootpverify').hide();
				const sprukobtnverifyemail = document.getElementsByClassName('sprukobtnverifyemail');
				sprukobtnverifyemail[0].addEventListener("click", verifyclick);

				function verifyclick(e){
					let id = e.target.getAttribute('data-id');
					$(e.target).html('<i class="fa fa-spinner fa-spin"></i>');
					$.ajax({
						type: "post",
						url: "{{ route('guest.senddataverify') }}",
						data: {
							_token : "{{ csrf_token() }}",
							id : id

						},
						success: function (data) {

                            $('#sprukootpverify').show();
                                var timeleft = 15;

                                var downloadTimer = setInterval(() => {

                                $(e.target).html(`verify your otp in ${timeleft} sec`);
                                sprukobtnverifyemail[0].removeEventListener("click", verifyclick);


                                timeleft -= 1;
                                if(timeleft <= 0){
                                    clearInterval(downloadTimer);
                                    $(e.target).html('{{lang('Resend OTP')}}');
                                    sprukobtnverifyemail[0].addEventListener("click", verifyclick);

                                }
                            }, 1000);

						},

						error: function (data) {
							console.log('Error:', data);
						}
					});
				}


				$('#guestsprukoverifyotp').on('keyup', function(e){

					let otpvalue = e.target.value;
					let otplength = otpvalue.length;
					let result = otpvalue.match(/[0-9]/g);
					var stop = $(this);
					$('#verifyotpError').html('');
					if(result){
						if(otplength == '6')
						{
							$.ajax({
								url: '{{route('guest.verifyguestotp')}}',
								method: 'post',
								data:{
									_token : "{{ csrf_token() }}",
									otpvalue : otpvalue,
								},
								success:(data) => {

									if(data.success)
									{
										toastr.success(data.success)
										location.replace('{{route('guest.ticketdetailshow', $ticket->ticket_id)}}');
									}
									if(data.error)
									{
										toastr.error(data.error)
									}

								},

								error:(data) => {
									console.log(data);
								}
							});

						}
					}
				})
			</script>

			@yield('modal')
	</body>
</html>






