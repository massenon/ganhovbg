        <!--Register Modal-->
        <div class="modal fade" id="registermodal">
            <div class="modal-dialog register-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{lang('Register', 'menu')}}</h5>
                        <button class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="single-page customerpage">
                            <div class="wrapper wrapper2 box-shadow-0 border-0">

                                @if($socialAuthSettings->envato_status == 'enable' || $socialAuthSettings->google_status == 'enable'||$socialAuthSettings->facebook_status == 'enable' || $socialAuthSettings->twitter_status == 'enable')

                                    <div class="login-icons card-body pt-3 pb-0 text-center justify-content-center">
                                        @if($socialAuthSettings->envato_status == 'enable')
                                        <a class="btn header-buttons text-start social-icon-2 btn-lg btn-lime text-white mb-4 btn-block p-0" href="javascript:;" data-bs-toggle="tooltip" title="{{lang('Login with Envato')}}" onclick="window.location.href = envato;" data-bs-original-title="{{lang('Login with Envato')}}">
                                            <div class="d-inline w-15 justify-content-center">
                                                <svg class="px-4 py-2 my-auto border-end border-white-1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="_x38_5-envato"><g><g><g>
                                                    <path fill="#fff" d="M401.225,19.381c-17.059-8.406-103.613,1.196-166.01,61.218      c-98.304,98.418-95.947,228.089-95.947,228.089s-3.248,13.335-17.086-6.011c-30.305-38.727-14.438-127.817-12.651-140.23      c2.508-17.494-8.615-17.999-13.243-12.229c-109.514,152.46-10.616,277.288,54.136,316.912c75.817,46.386,225.358,46.354,284.922-85.231C509.547,218.042,422.609,29.875,401.225,19.381L401.225,19.381z M401.225,19.381"></path></g></g></g></g><g id="Layer_1"></g>
                                                </svg>
                                            </div>

                                            <span class="px-4 py-2 my-auto text-white">{{lang('Login with Envato')}}</span>
                                        </a>
                                        @endif
                                        @if($socialAuthSettings->google_status == 'enable')
                                        <a class="btn header-buttons text-start social-icon-2 btn-lg btn-google text-white mb-4 btn-block p-0" href="javascript:;" data-bs-toggle="tooltip"
                                            title="{{lang('Login with Google')}}" onclick="window.location.href = google;" data-bs-original-title="{{lang('Login with Google')}}">
                                            <div class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                                                <i class="fa fa-google"></i>
                                            </div>
                                            <span class="px-4 py-2 my-auto text-white">{{lang('Login with Google')}}</span>
                                        </a>
                                        @endif
                                        @if($socialAuthSettings->facebook_status == 'enable')
                                        <a class="btn header-buttons text-start social-icon-2 btn-lg btn-facebook text-white mb-4 btn-block p-0" href="javascript:;" data-bs-toggle="tooltip"
                                            title="{{lang('Login with Facebook')}}" onclick="window.location.href = facebook;" data-bs-original-title="{{lang('Login with Facebook')}}">
                                            <div class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                                                <i class="fa fa-facebook"></i>
                                            </div>
                                            <span class="px-4 py-2 my-auto text-white">{{lang('Login with Facebook')}}</span>
                                        </a>
                                        @endif
                                        @if($socialAuthSettings->twitter_status == 'enable')
                                        <a class="btn header-buttons text-start social-icon-2 btn-lg btn-twitter text-white btn-block p-0" href="javascript:;" data-bs-toggle="tooltip"
                                            title="{{lang('Login with Twitter')}}" onclick="window.location.href = twitter;" data-bs-original-title="{{lang('Login with Twitter')}}">
                                            <div class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                                                <i class="fa fa-twitter"></i>
                                            </div>
                                            <span class="px-4 py-2 my-auto text-white">{{lang('Login with Twitter')}}</span>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="text-center mt-5 login-form">
                                        <div class="divider">
                                            {{lang('Or')}}
                                        </div>
                                    </div>

                                @endif

                                <div class="card-body border-top-0 pt-4">
                                    @if(setting('REGISTER_DISABLE') == 'off')
									<div class="alert alert-light-warning br-13 border-0 text-center" role="alert">

										<span class="">{{setting('login_disable_statement')}}</span>

									</div>

									@endif
                                    <form  id="registerform">
                                        @csrf

                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-grouphas-feedback ">
                                                    <label class="form-label">{{lang('Nom')}} <span class="text-red">*</span></label>
                                                    <input class="form-control " placeholder="{{lang('Firstname')}}" type="text"
                                                        name="firstname" required="" >
                                                        <span class="text-danger">
                                                            <strong id="firstnameerror"></strong>
                                                        </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="form-label">{{lang('Prénom')}} <span class="text-red">*</span></label>
                                                    <input class="form-control " placeholder="{{lang('Lastname')}}" type="text"
                                                        name="lastname"  >
                                                        <span class="text-danger">
                                                            <strong id="lastnameerror"></strong>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label class="form-label" for="emailInput">{{lang('Email')}} <span class="text-red">*</span></label>
                                            <input class="form-control" id="emailInput" placeholder="{{lang('Email')}}" type="email"
                                                name="email">
                                            <span class="text-danger">
                                                <strong id="emailerror"></strong>
                                            </span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label class="form-label">{{lang('Mot de passe')}} <span class="text-red">*</span></label>
                                            <input class="form-control" placeholder="{{lang('Mot de passe')}}" type="password" name="password">
                                            <span class="text-danger">
                                                <strong id="passworderror"></strong>
                                            </span>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label class="form-label">{{lang('Confirmez le mot de passe')}} <span class="text-red">*</span></label>
                                            <input class="form-control" placeholder="{{lang('Mot de passe')}}" type="password"
                                                name="password_confirmation">
                                            <span class="invalid-feedback" role="alert" id="password_confirmationError">
                                                <strong></strong>
                                            </span>
                                        </div>
                                        @php
                                        use App\Models\Customfield;
                                            $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();
                                        @endphp
                                        @if($customfields->isNotEmpty())
											@foreach($customfields as $customfield)
											<div class="form-group ">
												<div class="row">
													<div class="col-md-12">
														<label class="form-label">{{$customfield->fieldnames}}
															@if($customfield->fieldrequired == '1')

															<span class="text-red">*</span>
															@endif
														</label>

														@if($customfield->fieldtypes == 'text')

															<input type="{{$customfield->fieldtypes}}" class="form-control" name="custom_{{$customfield->id}}" id="" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
														@endif
														@if($customfield->fieldtypes == 'email')

															<input type="{{$customfield->fieldtypes}}" class="form-control" name="custom_{{$customfield->id}}" id="" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
														@endif
														@if($customfield->fieldtypes == 'textarea')

															<textarea name="custom_{{$customfield->id}}" class="form-control" id="" cols="30" rows="5" {{$customfield->fieldrequired == '1' ? 'required' : ''}}></textarea>
														@endif
														@if($customfield->fieldtypes == 'checkbox')

															@php
																$coptions = explode(',', $customfield->fieldoptions)
															@endphp
															@foreach($coptions as $key => $coption)
															<label class="custom-control form-checkbox d-inline-block me-3">
																<input type="{{$customfield->fieldtypes}}" class="custom-control-input {{$customfield->fieldrequired == '1' ? 'required' : ''}}"  name="custom_{{$customfield->id}}[]" value="{{$coption}}" id="" >

																<span class="custom-control-label">{{$coption}}</span>
															</label>

															@endforeach


														@endif
														@if($customfield->fieldtypes == 'select')
															<select name="custom_{{$customfield->id}}" id="" class="form-control select2-show-search" data-placeholder="{{lang('Select')}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
																@php
																	$seoptions = explode(',', $customfield->fieldoptions)
																@endphp
																<option></option>
																@foreach($seoptions as $seoption)

																<option value="{{$seoption}}">{{$seoption}}</option>
																@endforeach
															</select>
														@endif
														@if($customfield->fieldtypes == 'radio')
														@php
															$roptions = explode(',', $customfield->fieldoptions)
														@endphp
														@foreach($roptions as $roption)
														<label class="custom-control form-radio d-inline-block me-3">
															<input type="{{$customfield->fieldtypes}}" class="custom-control-input" name="custom_{{$customfield->id}}" value="{{$roption}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
															<span class="custom-control-label">{{$roption}}</span>
														</label>


														@endforeach

														@endif

													</div>
												</div>
											</div>
											@endforeach
										@endif

                                        <div class="form-group has-feedback">
                                            <label class="custom-control form-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                value="agreed" name="agree_terms" >
                                                <span class="custom-control-label">{{lang('I agree with')}} <a href="{{setting('terms_url')}}"
                                                        class="text-primary">{{lang('Terms of services')}}</a>
                                                </span>
                                            </label>
                                            <span class="text-danger" role="alert" >
                                                <strong id="exampleError"></strong>
                                            </span>
                                        </div>


                                        @if(setting('CAPTCHATYPE')=='manual')
                                            @if(setting('RECAPTCH_ENABLE_REGISTER')=='yes')
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <input type="text" id="captcha" class="form-control @error('captcha') is-invalid @enderror" placeholder="{{lang('Enter Captcha')}}" name="captcha">
                                                    @error('captcha')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ lang($message) }}</strong>
                                                        </span>
                                                    @enderror
                                                    <span class="text-danger" role="alert" >
                                                        <strong id="captchaError"></strong>
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="captcha">
                                                        <span>{!! captcha_img('') !!}</span>
                                                        <button type="button" class="btn btn-outline-info btn-sm captchabtn"><i class="fe fe-refresh-cw"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endif

                                        <!--- if Enable the Google ReCaptcha --->
                                        @if(setting('CAPTCHATYPE')=='google')
                                        @if(setting('RECAPTCH_ENABLE_REGISTER')=='yes')
                                        <div class="form-group">
                                            <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
                                            <span class="text-danger" role="alert" >
                                                <strong id="googleError"></strong>
                                            </span>

                                            <!-- @if ($errors->has('g-recaptcha-response'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif -->
                                        </div>
                                        @endif
                                        @endif
                                        <div>
                                            <input type="button" class="btn btn-secondary btn-block" id="submitForm"
                                                value="{{lang('Create Account')}}" />

                                        </div>
                                        <div class="text-center mt-4">
                                            <p class="text-dark mb-0">{{lang('Already have an account?')}}<a class="text-primary ms-1"
                                                    href="#" data-bs-toggle="modal" id="login"
                                                    data-bs-target="#loginmodal">{{lang('Se connecter')}}</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Register Modal  -->

        <script type="text/javascript">
            "use strict";

            var facebook = "{{ route('social.login', 'facebook') }}";
            var google = "{{ route('social.login', 'google') }}";
            var twitter = "{{ route('social.login', 'twitter') }}";
            var envato = "{{ route('social.login', 'envato') }}";

            //set button id on click to hide first modal
            $("#login").on( "click", function() {

                $('#registermodal').modal('hide');
                $('#registerform').trigger("reset");

            });

            //trigger next modal
            $("#login").on( "click", function() {
                    $('#loginmodal').modal('show');

            });

            (function($){

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

                // End Register Js
                $('#registermodal').on('click', '#submitForm', function(){
                    var registerForm = $("#registerform");
                    var formData = registerForm.serialize();
                    $( '#firstnameerror' ).html( "" );
                    $( '#lastnameerror' ).html( "" );
                    $( '#emailerror' ).html( "" );
                    $( '#passworderror' ).html( "" );
                    $( '#exampleError' ).html( "" );
                    $( '#captchaError' ).html( "" );
                    $( '#googleError' ).html( "" );
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    let checked  = document.querySelectorAll('.required:checked').length;
					var isValid = checked > 0;
					if(document.querySelectorAll('.required').length == '0'){
						ajax(formData);
					}else{

						if(isValid){
							ajax(formData);
						}else{
							// $('#register_button').prop('disabled', false);
							// $('#register_button').html(`{{lang('Créer une Alerte/Cas')}}`);
							toastr.error('{{lang('Check the all field(*) required', 'alerts')}}')
						}
					}

                });


                function ajax(formData)
                {
                    $.ajax({
                        url:'{{url('customer/register1')}}',
                        type:'POST',
                        data:formData,
                        success:function(data) {
                            if(data.errors) {
                                if(data.errors.firstname){
                                    $( '#firstnameerror' ).html( data.errors.firstname[0] );
                                }
                                if(data.errors.lastname){
                                    $( '#lastnameerror' ).html( data.errors.lastname[0] );
                                }
                                if(data.errors.email){
                                    $( '#emailerror' ).html( data.errors.email[0] );
                                }
                                if(data.errors.password){
                                    $( '#passworderror' ).html( data.errors.password[0] );
                                }
                                if(data.errors.agree_terms){
                                    $( '#exampleError' ).html( data.errors.agree_terms[0] );
                                }
                                if(data.errors.captcha){
                                    $( '#captchaError' ).html( data.errors.captcha[0] );
                                }
                                if(data.errors["g-recaptcha-response"][0]){
                                    $('#googleError').html(data.errors["g-recaptcha-response"][0]);
                                }

                            }
                            if(data.success) {

                                toastr.success("{{lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')}}");
                                $('#registermodal').modal('hide');
                                $('#registerform').trigger("reset");
                            }
                        },
                    });
                }



			})(jQuery);
        </script>

        <!-- Captrcha Js-->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
