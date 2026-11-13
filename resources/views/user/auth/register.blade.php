@extends('layouts.gnauth')
@php
	$seo = ['title' => 'Créer un compte GANXÔ Alerte VBG', 'description' => 'Créez votre compte GANXÔ Alerte VBG pour suivre vos alertes et échanger avec l’équipe en toute simplicité.', 'breadcrumbs' => [['Accueil', '/'], ['Connexion', '/customer/login'], ['Créer un compte', '/customer/register']]];
@endphp

@section('gn_eyebrow', 'Votre espace')
@section('gn_h1', 'Créer un compte')
@section('gn_lead', 'Un compte vous permet de suivre vos alertes et de retrouver les réponses de l’équipe GANXÔ.')

@section('gn_form')
	<h2 class="gnf-card__title">Créer un compte</h2>

	@if(setting('REGISTER_DISABLE') == 'off')
		<div class="gnf-alert" role="alert">{{setting('login_disable_statement')}}</div>
	@endif

	@include('includes.gn.social')

	<form method="post" id="register_form">
		@honeypot

		<div class="gnf-row">
			<div class="gnf-field">
				<label for="firstname">{{lang('Nom')}} <span class="gnf-req" aria-hidden="true">*</span></label>
				<input class="gnf-ctl @error('firstname') is-invalid @enderror" id="firstname" type="text" name="firstname" value="{{old('firstname')}}" autocomplete="family-name">
				<span class="gnf-error" id="firstnameError"></span>
				@error('firstname')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
			</div>
			<div class="gnf-field">
				<label for="lastname">{{lang('Prénom')}} <span class="gnf-req" aria-hidden="true">*</span></label>
				<input class="gnf-ctl @error('lastname') is-invalid @enderror" id="lastname" type="text" name="lastname" value="{{old('lastname')}}" autocomplete="given-name">
				<span class="gnf-error" id="lastnameError"></span>
				@error('lastname')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
			</div>
		</div>
		<div class="gnf-field">
			<label for="email">{{lang('Email')}} <span class="gnf-req" aria-hidden="true">*</span></label>
			<input class="gnf-ctl @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{old('email')}}" autocomplete="username">
			<span class="gnf-error" id="emailError"></span>
			@error('email')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>
		<div class="gnf-row">
			<div class="gnf-field">
				<label for="password">{{lang('Mot de passe')}} <span class="gnf-req" aria-hidden="true">*</span></label>
				<input class="gnf-ctl @error('password') is-invalid @enderror" id="password" type="password" name="password" autocomplete="new-password">
				<span class="gnf-error" id="passwordError"></span>
				@error('password')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
			</div>
			<div class="gnf-field">
				<label for="password_confirmation">{{lang('Confirmez le mot de passe')}} <span class="gnf-req" aria-hidden="true">*</span></label>
				<input class="gnf-ctl @error('password_confirmation') is-invalid @enderror" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
				<span class="gnf-error" id="passwordconfirmError"></span>
				@error('password_confirmation')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
			</div>
		</div>

		@if($customfields->isNotEmpty())
			@foreach($customfields as $customfield)
				@if(in_array($customfield->fieldtypes, ['checkbox', 'radio']))
					<fieldset class="gnf-group">
						<legend>{{$customfield->fieldnames}} @if($customfield->fieldrequired == '1')<span class="gnf-req" aria-hidden="true">*</span>@endif</legend>
						@if($customfield->fieldtypes == 'checkbox')
							@foreach(explode(',', $customfield->fieldoptions) as $coption)
								<label class="gnf-check"><input type="checkbox" class="{{$customfield->fieldrequired == '1' ? 'required' : ''}}" name="custom_{{$customfield->id}}[]" value="{{$coption}}"><span>{{$coption}}</span></label>
							@endforeach
						@else
							@foreach(explode(',', $customfield->fieldoptions) as $roption)
								<label class="gnf-check"><input type="radio" name="custom_{{$customfield->id}}" value="{{$roption}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}><span>{{$roption}}</span></label>
							@endforeach
						@endif
					</fieldset>
				@else
					<div class="gnf-field">
						<label for="custom_{{$customfield->id}}">{{$customfield->fieldnames}} @if($customfield->fieldrequired == '1')<span class="gnf-req" aria-hidden="true">*</span>@endif</label>
						@if($customfield->fieldtypes == 'text')
							<input type="text" class="gnf-ctl" name="custom_{{$customfield->id}}" id="custom_{{$customfield->id}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
						@elseif($customfield->fieldtypes == 'email')
							<input type="email" class="gnf-ctl" name="custom_{{$customfield->id}}" id="custom_{{$customfield->id}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
						@elseif($customfield->fieldtypes == 'textarea')
							<textarea name="custom_{{$customfield->id}}" class="gnf-ctl" id="custom_{{$customfield->id}}" rows="5" {{$customfield->fieldrequired == '1' ? 'required' : ''}}></textarea>
						@elseif($customfield->fieldtypes == 'select')
							<select name="custom_{{$customfield->id}}" id="custom_{{$customfield->id}}" class="gnf-ctl" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
								<option value="">Choisir</option>
								@foreach(explode(',', $customfield->fieldoptions) as $seoption)
									<option value="{{$seoption}}">{{$seoption}}</option>
								@endforeach
							</select>
						@endif
					</div>
				@endif
			@endforeach
		@endif

		<div class="gnf-field @error('agree_terms') is-invalid @enderror">
			<label class="gnf-check" for="agree_terms">
				<input type="checkbox" id="agree_terms" value="agreed" name="agree_terms">
				<span>J’accepte les <a href="{{setting('terms_url')}}">conditions d’utilisation</a></span>
			</label>
			<span class="gnf-error" id="agreetermsError"></span>
			@error('agree_terms')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>

		@if(setting('CAPTCHATYPE')=='manual' && setting('RECAPTCH_ENABLE_REGISTER')=='yes')
		<div class="gnf-field">
			<label for="captcha">Recopiez le code de l’image</label>
			<div class="gnf-captcha">
				<input type="text" id="captcha" class="gnf-ctl @error('captcha') is-invalid @enderror" name="captcha" autocomplete="off">
				<div class="captcha"><span>{!! captcha_img('') !!}</span><button type="button" class="gnf-iconbtn captchabtn" data-url="{{route('captcha.reload')}}" title="Actualiser l’image" aria-label="Actualiser l’image du captcha">Actualiser</button></div>
			</div>
			@error('captcha')<span class="gnf-error" role="alert">{{ lang($message) }}</span>@enderror
		</div>
		@endif

		@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_REGISTER')=='yes')
		<div class="gnf-field">
			<div class="g-recaptcha" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
			<span class="gnf-error" id="captchaError"></span>
		</div>
		@endif

		<button type="submit" class="gn-btn gn-btn--primary gn-btn--block" id="register_button">Créer mon compte</button>

		<div class="gnf-links">
			<p>Vous avez déjà un compte ? <a href="{{url('customer/login')}}">{{lang('Se connecter')}}</a></p>
			<p><a href="{{url('/')}}">{{lang('Retour')}}</a></p>
		</div>
	</form>
@endsection

@push('gn_scripts')
<script src="{{ gn_asset('assets/ganho/js/pages-forms.js') }}"></script>
@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_REGISTER')=='yes')<script src="https://www.google.com/recaptcha/api.js" async defer></script>@endif
<script type="text/javascript">
	"use strict";

	var facebook = "{{ route('social.login', 'facebook') }}";
	var google = "{{ route('social.login', 'google') }}";
	var twitter = "{{ route('social.login', 'twitter') }}";
	var envato = "{{ route('social.login', 'envato') }}";

	(function () {
		var form = document.getElementById('register_form');
		var btn = document.getElementById('register_button');
		var ids = ['firstnameError', 'lastnameError', 'emailError', 'passwordError', 'passwordconfirmError', 'agreetermsError', 'captchaError'];
		var LABEL = @json('Créer mon compte');

		function setText(id, v) {
			var el = document.getElementById(id);
			if (el) { el.textContent = (v === undefined || v === null) ? '' : (Array.isArray(v) ? v.join(' ') : String(v)); }
		}
		function reset() { btn.disabled = false; btn.textContent = LABEL; }

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			ids.forEach(function (id) { setText(id, ''); });
			btn.textContent = 'Création en cours…';
			btn.disabled = true;

			var required = document.querySelectorAll('.required');
			if (required.length !== 0 && document.querySelectorAll('.required:checked').length === 0) {
				reset();
				toastr.error(@json(lang('Check the all field(*) required', 'alerts')));
				return;
			}

			fetch("{{route('auth.register')}}", {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
					'X-Requested-With': 'XMLHttpRequest',
					'Accept': 'application/json'
				},
				body: new FormData(form)
			}).then(function (r) {
				return r.json().then(function (data) { return { ok: r.ok, data: data }; });
			}).then(function (res) {
				var data = res.data || {};
				if (!res.ok) {
					if (data.errors) {
						if (data.errors['g-recaptcha-response']) { setText('captchaError', data.errors['g-recaptcha-response'][0]); }
						setText('firstnameError', data.errors.firstname);
						setText('lastnameError', data.errors.lastname);
						setText('emailError', data.errors.email);
						setText('passwordError', data.errors.password);
						setText('passwordconfirmError', data.errors.password_confirmation);
						setText('agreetermsError', data.errors.agree_terms);
					} else if (data.message) {
						toastr.error(data.message);
					}
					reset();
					return;
				}
				if (data.error) { toastr.error(data.error); reset(); }
				if (data.success) {
					toastr.success(data.success);
					window.location.replace('{{url('customer/login')}}');
				}
			}).catch(function () { reset(); });
		});
	})();
</script>
@endpush
