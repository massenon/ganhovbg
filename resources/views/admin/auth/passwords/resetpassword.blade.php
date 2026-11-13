@extends('layouts.custommaster')
                            @section('content')

								<div class="pb-0 px-5 pt-0 text-center">
									<h3 class="mb-2">{{lang('Réinitialiser le mot de passe')}}</h3>
									<p class="text-muted fs-13 mb-1">{{lang('Enter the email address registered on your account')}}</p>
									@if (session('status'))
									<div class="alert alert-success" role="alert">
										{{ session('status') }}
									</div>
									@endif
								</div>

								<form class="card-body pt-3 pb-0" method="POST" action="{{route('password.update')}}" >
                                @csrf
								@honeypot
                                <input type="hidden" name="token" value="{{ $token }}">
									<div class="form-group">
										<label class="form-label"  for="email" >{{lang('Email')}}</label>
										<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $users->email ?? old('email') }}" autocomplete="email" placeholder="{{lang('example@mail.com')}}" autofocus readonly>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
									<div class="form-group">
										<label class="form-label" for="password" >{{lang('Nouveau mot de passe')}}</label>
										<input class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{lang('Mot de passe')}}" type="password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
									<div class="form-group">
										<label class="form-label" for="password-confirm" >{{lang('Confirmez le mot de passe')}}</label>
										<input class="form-control" placeholder="{{lang('Mot de passe')}}" id="password-confirm"  name="password_confirmation" type="password">
									</div>
									<div class="submit">
                                    <button type="submit" class="btn btn-secondary btn-block" onclick="this.disabled=true;this.form.submit();">
                                        {{lang('Réinitialiser le mot de passe')}}
                                    </button>
									</div>
									<div class="text-center mt-4">
										<p class="text-dark mb-0">{{lang('Vous vous souvenez de votre mot de passe ?')}}<a class="text-primary ms-1" href="{{url('admin/login')}}">{{lang('Se connecter')}}</a></p>
									</div>
								</form>

                            @endsection
