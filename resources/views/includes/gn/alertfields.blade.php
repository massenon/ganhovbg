{{-- Champs communs du formulaire d'alerte (variantes OTP et sans OTP). Noms de champs et ids inchangés (le JS et le contrôleur en dépendent).
     Variables : $customfields, $categories, $gnCatLabel, $gnCatPlaceholder.
     Sections numérotées : 2 Votre situation, 3 Votre message, 4 Confirmation (la section 1 « coordonnées » est dans la vue). --}}
@php $gnMax = (int) setting('TICKET_CHARACTER'); @endphp

<section class="gnf-section" aria-labelledby="gnf-s2">
	<h3 class="gnf-section-title" id="gnf-s2"><span class="gnf-step" aria-hidden="true">2</span>Votre situation</h3>

	@if(setting('cc_email') == 'on')
	<div class="gnf-field">
		<label for="ccmail">Adresse e-mail en copie</label>
		<input type="email" class="gnf-ctl @error('ccmail') is-invalid @enderror" placeholder="copie@exemple.com" value="{{ old('ccmail') }}" name="ccmail" id="ccmail" autocomplete="off" aria-describedby="ccmail-help ccEmailError">
		<p class="gnf-help" id="ccmail-help">Une seule adresse en copie est autorisée.</p>
		<span id="ccEmailError" class="gnf-error alert-message"></span>
		@error('ccmail')
			<span class="gnf-error" role="alert">{{ lang($message) }}</span>
		@enderror
	</div>
	@endif

	<div class="gnf-field">
		<label for="subject">Sujet de l’alerte <span class="gnf-req" aria-hidden="true">*</span><span class="gn-visually-hidden">(obligatoire)</span></label>
		<input type="text" id="subject" maxlength="{{ $gnMax }}" class="gnf-ctl @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" autocomplete="off" placeholder="Ex. : harcèlement au travail" aria-required="true" aria-describedby="subject-help subjectmaxtext SubjectError">
		<div class="gnf-helprow">
			<p class="gnf-help" id="subject-help">Résumez la situation en quelques mots.</p>
			<span class="gnf-counter subjectmaxtext" id="subjectmaxtext" data-max="{{ $gnMax }}"><span id="subjectcount">0</span>/{{ $gnMax }}<span class="gn-visually-hidden"> {{lang('caractère(s)')}}</span></span>
		</div>
		<span id="SubjectError" class="gnf-error alert-message"></span>
	</div>

	<div class="gnf-field">
		<label for="category">{{ $gnCatLabel }} <span class="gnf-req" aria-hidden="true">*</span><span class="gn-visually-hidden">(obligatoire)</span></label>
		<div class="gnf-select">
			<select class="gnf-ctl @error('category') is-invalid @enderror" name="category" id="category" aria-required="true" aria-describedby="category-help CategoryError">
				<option value="">{{ $gnCatPlaceholder }}</option>
				@foreach ($categories as $category)
					<option value="{{ $category->id }}">{{ $category->name }}</option>
				@endforeach
			</select>
		</div>
		<p class="gnf-help" id="category-help">Choisissez ce qui décrit le mieux ce que vous vivez.</p>
		<span id="CategoryError" class="gnf-error alert-message"></span>
	</div>
	<div class="gnf-field" id="selectssSubCategory" style="display: none;">
		<label for="subscategory">Précision (sous-catégorie)</label>
		<div class="gnf-select"><select class="gnf-ctl" name="subscategory" id="subscategory"></select></div>
		<span id="subsCategoryError" class="gnf-error alert-message"></span>
	</div>
	<div id="selectSubCategory"></div>
	<div id="envatopurchase"></div>

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
						<input type="text" maxlength="255" class="gnf-ctl" name="custom_{{$customfield->id}}" id="custom_{{$customfield->id}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
					@elseif($customfield->fieldtypes == 'email')
						<input type="email" class="gnf-ctl" name="custom_{{$customfield->id}}" id="custom_{{$customfield->id}}" placeholder="vous@exemple.com" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
					@elseif($customfield->fieldtypes == 'textarea')
						<textarea name="custom_{{$customfield->id}}" maxlength="255" class="gnf-ctl" id="custom_{{$customfield->id}}" rows="4" {{$customfield->fieldrequired == '1' ? 'required' : ''}}></textarea>
					@elseif($customfield->fieldtypes == 'select')
						<div class="gnf-select">
							<select name="custom_{{$customfield->id}}" id="custom_{{$customfield->id}}" class="gnf-ctl" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
								<option value="">Choisir</option>
								@foreach(explode(',', $customfield->fieldoptions) as $seoption)
									<option value="{{$seoption}}">{{$seoption}}</option>
								@endforeach
							</select>
						</div>
					@endif
				</div>
			@endif
		@endforeach
	@endif
</section>

<section class="gnf-section" aria-labelledby="gnf-s3">
	<h3 class="gnf-section-title" id="gnf-s3"><span class="gnf-step" aria-hidden="true">3</span>Votre message</h3>

	<div class="gnf-field ticket-summernote">
		<label for="gnf-message" id="gnf-message-label">Description de la situation <span class="gnf-req" aria-hidden="true">*</span><span class="gn-visually-hidden">(obligatoire)</span></label>
		<textarea class="gnf-ctl summernote @error('message') is-invalid @enderror" id="gnf-message" name="message"></textarea>
		<p class="gnf-help" id="gnf-message-help">Racontez ce qui s’est passé, quand et où, à votre rythme. Ces précisions aident l’équipe à vous répondre.</p>
		<span id="MessageError" class="gnf-error alert-message"></span>
	</div>

	@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
	<div class="gnf-field">
		<span class="gnf-label" id="gnf-files-label">Pièces jointes <span class="gnf-opt">(facultatif)</span></span>
		<div class="needsclick dropzone" id="document-dropzone" role="button" tabindex="0" aria-labelledby="gnf-files-label" aria-describedby="gnf-files-help"></div>
		<p class="gnf-help" id="gnf-files-help">Formats acceptés : {{ str_replace(',', ', ', setting('FILE_UPLOAD_TYPES')) }}. Taille maximale : {{setting('FILE_UPLOAD_MAX')}} {{lang('MB', 'filesetting')}} par fichier.</p>
	</div>
	@endif
</section>

<section class="gnf-section" aria-labelledby="gnf-s4">
	<h3 class="gnf-section-title" id="gnf-s4"><span class="gnf-step" aria-hidden="true">4</span>Confirmation et envoi</h3>

	@if(setting('CAPTCHATYPE')=='manual' && setting('RECAPTCH_ENABLE_GUEST')=='yes')
	<div class="gnf-field">
		<label for="captcha">Code de l’image <span class="gnf-req" aria-hidden="true">*</span><span class="gn-visually-hidden">(obligatoire)</span></label>
		<div class="gnf-captcha">
			<input type="text" id="captcha" class="gnf-ctl @error('captcha') is-invalid @enderror" placeholder="Recopiez le code" name="captcha" autocomplete="off" aria-describedby="captcha-help">
			<div class="captcha"><span>{!! captcha_img('') !!}</span><button type="button" class="gnf-iconbtn captchabtn" data-url="{{route('captcha.reload')}}" title="Actualiser l’image" aria-label="Actualiser l’image du captcha">Actualiser</button></div>
		</div>
		<p class="gnf-help" id="captcha-help">Recopiez les caractères de l’image pour prouver que vous n’êtes pas un robot.</p>
		@error('captcha')
			<span class="gnf-error" role="alert">{{ lang($message) }}</span>
		@enderror
	</div>
	@endif
	@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_GUEST')=='yes')
	<div class="gnf-field">
		<div class="g-recaptcha" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
		@if ($errors->has('g-recaptcha-response'))
			<span class="gnf-error" role="alert">{{ $errors->first('g-recaptcha-response') }}</span>
		@endif
	</div>
	@endif

	<div class="gnf-field gnf-consent @error('agree_terms') is-invalid @enderror">
		<label class="gnf-check"><input type="checkbox" value="agreed" name="agree_terms" id="agree_terms" checked aria-describedby="agreetermsError"><span>Je confirme que les faits décrits sont exacts.</span></label>
		<span class="gnf-error" id="agreetermsError"></span>
	</div>
</section>
