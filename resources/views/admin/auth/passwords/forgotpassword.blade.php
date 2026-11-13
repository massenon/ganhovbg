@extends('layouts.custommaster')
                            	@section('content')

								<!--Forgot Password -->
                            	<div class="pb-0 px-5 pt-0 text-center">
									<h1 class="mb-2">{{lang('Mot de passe oublié?')}}</h1>
									<p class="text-muted fs-13 mb-1">{{lang('Entrez l\'adresse e-mail qui est liée à votre compte.')}}</p>
								</div>
								<form class="card-body pt-3 pb-0" id="forgot" action="{{route('password.email')}}" method="post">
                                	@csrf

									@honeypot

									<div class="form-group">
										<label class="form-label">{{lang('Email')}}</label>
										<input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{lang('Email')}}" type="email">
                                        @error('email')

                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

									</div>
									<div class="submit">
                                        <input class="btn btn-secondary btn-block" type="submit" value="{{lang('Submit')}}" onclick="this.disabled=true;this.form.submit();">
									</div>
									<div class="text-center mt-4">
										<p class="text-dark mb-0"><a class="text-primary ms-1" href="{{url('/')}}">{{lang('Retour')}}</a></p>
									</div>
								</form>
								<!--End Forgot Password -->

                            	@endsection
