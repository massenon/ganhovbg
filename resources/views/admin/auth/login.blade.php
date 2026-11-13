                            @extends('layouts.custommaster')
                            @section('content')

                                <div class="px-4 pb-4 pt-0 text-center">
									<h3 class="mb-2">{{lang('Se connecter')}}</h3>
									<p class="text-muted fs-13 mb-1">{{lang('Connectez-vous à votre compte')}}</p>
								</div>
								<form class="card-body pt-3 pb-0" id="login" action="{{route('login')}}" method="post">

								@csrf

								@honeypot

									<div class="form-group">
										<label class="form-label">{{lang('Email')}} <span class="text-red">*</span></label>
										<input class="form-control  @error('email') is-invalid @enderror" placeholder="{{lang('Email')}}" type="email" id="email" value="{{old('email')}}" name="email">
										@error('email')
											<span class="invalid-feedback" role="alert">
												<strong>{{ lang($message) }}</strong>
											</span>
										@enderror
									</div>
									<div class="form-group">
										<label class="form-label">{{lang('Mot de passe')}} <span class="text-red">*</span></label>
										<input class="form-control  @error('password') is-invalid @enderror" placeholder="{{lang('Mot de passe')}}" type="password" id="password" name="password">
										@error('password')
											<span class="invalid-feedback" role="alert">
												<strong>{{ lang($message) }}</strong>
											</span>
										@enderror
									</div>
									<div class="form-group">
										<label class="custom-control form-checkbox">
											<input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
											<span class="custom-control-label">{{lang('Restez connecté')}}</span>
										</label>
									</div>

									@if(setting('CAPTCHATYPE')=='manual')
										@if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN')=='yes')

										<div class="form-group row">
											<div class="col-md-12 mb-3">
												<input type="text" id="captcha" class="form-control @error('captcha') is-invalid @enderror" placeholder="{{lang('Enter Captcha')}}" name="captcha">
												@error('captcha')

													<span class="invalid-feedback" role="alert">
														<strong>{{ lang($message) }}</strong>
													</span>
												@enderror

											</div>
											<div class="col-md-12">
												<div class="captcha d-flex border">
													<span class="mx-auto mt-2">{!! captcha_img('') !!}</span>
													<button type="button" class="btn btn-secondary btn-icon captchabtn"><i class="fe fe-refresh-cw"></i></button>
												</div>
											</div>
										</div>
										@endif
									@endif

                                    <!--- if Enable the Google ReCaptcha --->
                                    <div class="form-group">
                                        @if(setting('CAPTCHATYPE')=='google')
                                            @if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN')=='yes')

                                            <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
                                            @if ($errors->has('g-recaptcha-response'))

                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ lang($errors->first('g-recaptcha-response')) }}</strong>
                                                </span>
                                            @endif
                                            @endif
                                        @endif

                                    </div>
                                    <!--- End Google ReCaptcha --->

									<div class="submit">
										<button class="btn btn-secondary btn-block"  type="submit" onclick="this.disabled=true;this.innerHTML=`<i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Se connecter')}}</button>
									</div>
									<div class="text-center mt-3">
										<p class="mb-0"><a href="{{route('password.request')}}">{{lang('Mot de passe oublié?')}}</a></p>
									</div>
								</form>
                            @endsection
		@section('scripts')

		<!-- Captcha js-->
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

		<!-- copy username and password -->
		<script type="text/javascript">

			"use strict";

			(function($){

				$(".sprukoclick").on("click",function (e) {
					e.preventDefault();

					$("#email").val($(this).data("email"));

					$("#password").val($(this).data("pswd"));

				});

				// Variables
				var facebook = "{{ route('social.login', 'facebook') }}";
				var google = "{{ route('social.login', 'google') }}";
				var twitter = "{{ route('social.login', 'twitter') }}";
				var envato = "{{ route('social.login', 'envato') }}";



				$(".captchabtn").on('click', function(e){
					e.preventDefault();
					$.ajax({
						type:'GET',
						url:'{{route('captcha.reload')}}',
						success: function(res){
							$(".captcha span").html(res.captcha);
						}
					});
				});
			})(jQuery);

		</script>

		@endsection

