{{-- Scripts du formulaire d'alerte (jQuery + Summernote : nécessaires à l'éditeur et au flux AJAX existant, chargés uniquement sur cette page).
     Variable : $gnOtp (true = flux avec vérification OTP, formulaire #emailotp_form + #guest_form ; false = formulaire unique). --}}
@push('gn_scripts')
<script src="{{ gn_asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ gn_asset('assets/ganho/js/pages-forms.js') }}"></script>
<script src="{{ gn_asset('assets/plugins/summernote/summernote.js') }}"></script>
@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
<script src="{{ gn_asset('assets/plugins/dropzone/dropzone.js') }}"></script>
@endif
<script type="text/javascript">
	"use strict";

	var licensekey;
	var SITEURL = '{{url('')}}';

	(function ($) {

		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

		var LABEL_SEND = $('#createticketbtn').text().trim();
		var LABEL_SENDING = 'Envoi en cours…';
		var $btn = $('#createticketbtn');

		// ---------- Erreurs : message sous le champ, aria-invalid, récapitulatif en tête de formulaire ----------
		var ERR_MAP = [
			['#EmailError', '#email', 'email'], ['#verifyotpError', '#verifyotp', 'verifyotp'], ['#ccEmailError', '#ccmail', 'ccmail'],
			['#SubjectError', '#subject', 'subject'], ['#CategoryError', '#category', 'category'], ['#MessageError', '.note-editable', 'message'],
			['#agreetermsError', '#agree_terms', 'agree_terms']
		];
		function frMsg(m, key) {
			if (!m || /[à-ÿ’]/.test(m)) { return m; }
			if (/required/i.test(m)) { return key === 'agree_terms' ? 'Veuillez confirmer l’exactitude des faits.' : (key === 'category' ? 'Choisissez le type de situation.' : 'Ce champ est obligatoire.'); }
			if (/valid email/i.test(m)) { return 'Saisissez une adresse e-mail valide, par exemple vous@exemple.com.'; }
			if (/greater than|too long/i.test(m)) { return 'Ce texte est trop long.'; }
			if (/disposable|indisposable/i.test(m)) { return 'Cette adresse e-mail n’est pas acceptée.'; }
			if (/selected .* is invalid/i.test(m)) { return key === 'agree_terms' ? 'Veuillez confirmer l’exactitude des faits.' : 'Valeur non valide.'; }
			return m;
		}
		function firstMsg(v, key) { return frMsg(Array.isArray(v) ? (v[0] || '') : (v || ''), key); }
		function ctlOf(sel) { return $(sel).first(); }
		function labelText(ctl) {
			var l = ctl.closest('.gnf-field').find('label').first().clone();
			l.find('.gnf-req,.gn-visually-hidden').remove();
			return $.trim(l.text());
		}
		function setFieldError(errSel, ctlSel, msg) {
			var $e = $(errSel), $c = ctlOf(ctlSel), $f = $e.closest('.gnf-field');
			$e.text(msg || '');
			$f.toggleClass('has-error', !!msg);
			if ($c.length) {
				$c.toggleClass('is-invalid', !!msg);
				if (msg) { $c.attr('aria-invalid', 'true'); } else { $c.removeAttr('aria-invalid'); }
				if ($c.hasClass('note-editable')) { $c.closest('.note-editor').toggleClass('is-invalid', !!msg); }
			}
		}
		function clearErrors() {
			$.each(ERR_MAP, function (i, p) { setFieldError(p[0], p[1], ''); });
			$('#gnf-summary').attr('hidden', true).empty();
		}
		function showErrors(errors) {
			var items = [];
			$.each(ERR_MAP, function (i, p) {
				var msg = firstMsg(errors[p[2]], p[2]);
				setFieldError(p[0], p[1], msg);
				var $c = ctlOf(p[1]);
				if (msg && $c.length) {
					if (!$c.attr('id')) { $c.attr('id', 'gnf-ed-' + p[2]); }
					items.push('<li><a href="#' + $c.attr('id') + '" data-gnf-focus="' + $c.attr('id') + '">' + $('<div>').text(labelText($c) + ' : ' + msg).html() + '</a></li>');
				}
			});
			if (items.length) {
				$('#gnf-summary').html('<p class="gnf-summary__title">Veuillez corriger ' + (items.length > 1 ? 'les ' + items.length + ' champs suivants' : 'le champ suivant') + ' avant d’envoyer.</p><ul>' + items.join('') + '</ul>').removeAttr('hidden');
				var el = document.getElementById('gnf-summary');
				if (el.scrollIntoView) { el.scrollIntoView({ block: 'center', behavior: 'smooth' }); }
				el.focus({ preventScroll: true });
			}
		}
		$('body').on('click', '#gnf-summary a[data-gnf-focus]', function (e) {
			e.preventDefault();
			var t = document.getElementById($(this).data('gnf-focus'));
			if (t) { t.focus(); if (t.scrollIntoView) { t.scrollIntoView({ block: 'center' }); } }
		});
		// Corriger un champ efface son erreur
		$('body').on('input change', '.gnf-field .gnf-ctl, #agree_terms', function () {
			var $f = $(this).closest('.gnf-field');
			$f.find('.gnf-error').text('');
			$f.removeClass('has-error');
			$(this).removeClass('is-invalid').removeAttr('aria-invalid');
		});
		$('body').on('input', '.note-editable', function () {
			var $f = $(this).closest('.gnf-field');
			$f.find('.gnf-error').text(''); $f.removeClass('has-error');
			$(this).removeAttr('aria-invalid').closest('.note-editor').removeClass('is-invalid');
		});

		function setLoading(on) {
			$btn.prop('disabled', on).toggleClass('is-loading', on).attr('aria-busy', on ? 'true' : 'false').text(on ? LABEL_SENDING : LABEL_SEND);
		}

		function fieldWithLabel(id, labelHtml, control) {
			var wrap = document.createElement('div');
			wrap.setAttribute('class', 'gnf-field');
			var label = document.createElement('label');
			label.setAttribute('for', id);
			label.innerHTML = labelHtml;
			wrap.appendChild(label);
			if (control.tagName === 'SELECT') {
				var sw = document.createElement('div');
				sw.setAttribute('class', 'gnf-select');
				sw.appendChild(control);
				wrap.appendChild(sw);
			} else {
				wrap.appendChild(control);
			}
			return wrap;
		}

		// Quand la catégorie change : liste des sous-catégories
		$('#category').on('change', function (e) {
			var cat_id = e.target.value;
			$('#selectssSubCategory').hide();
			$.ajax({
				url: "{{ route('guest.subcategorylist') }}",
				type: "POST",
				data: { cat_id: cat_id },
				cache: false,
				async: true,
				success: function (data) {
					if (data.subcategoriess) {
						$('#subscategory').html(data.subcategoriess);
						$('#selectssSubCategory').show();
					} else {
						$('#selectssSubCategory').hide();
						$('#subscategory').html('');
					}
					if (data.subcategories.length >= 1) {
						$('#selectSubCategory .gnf-field').remove();
						var sel = document.createElement('select');
						sel.setAttribute('class', 'gnf-ctl');
						sel.setAttribute('id', 'subcategory');
						sel.setAttribute('name', 'project');
						var opt = document.createElement('option');
						opt.setAttribute('value', '');
						opt.textContent = @json('Projet');
						sel.appendChild(opt);
						document.querySelector('#selectSubCategory').appendChild(fieldWithLabel('subcategory', @json('Projet'), sel));
						$.each(data.subcategories, function (index, subcategory) {
							var o = document.createElement('option');
							o.value = subcategory.name;
							o.textContent = subcategory.name;
							sel.appendChild(o);
						});
					} else {
						$('#selectSubCategory .gnf-field').remove();
					}
					@if(setting('ENVATO_ON') == 'on')
					if (data.envatosuccess.length >= 1) {
						$('#envatopurchase .gnf-field').remove();
						var inp = document.createElement('input');
						inp.setAttribute('class', 'gnf-ctl');
						inp.setAttribute('type', 'search');
						inp.setAttribute('id', 'envato_id');
						inp.setAttribute('name', 'envato_id');
						inp.setAttribute('placeholder', 'Saisissez votre code d’achat');
						var hid = document.createElement('input');
						hid.setAttribute('type', 'hidden');
						hid.setAttribute('id', 'envato_support');
						hid.setAttribute('name', 'envato_support');
						var f = fieldWithLabel('envato_id', "Code d’achat Envato <span class='gnf-req'>*</span>", inp);
						f.appendChild(hid);
						document.querySelector('#envatopurchase').appendChild(f);
						$('.purchasecode').attr('disabled', true);
					} else {
						$('#envatopurchase .gnf-field').remove();
						$('.purchasecode').removeAttr('disabled');
					}
					@endif
				},
				error: function () {}
			});
		});

		@php $module = Module::all(); @endphp
		@if(in_array('Uhelpupdate', $module))
		// Validation du code d'achat
		$("body").on('keyup', '#envato_id', function () {
			let value = $(this).val();
			if (value != '') {
				if (value.length == '36') {
					var _token = $('input[name="_token"]').val();
					$.ajax({
						url: "{{ route('guest.envatoverify') }}",
						method: "POST",
						data: { data: value, _token: _token },
						dataType: "json",
						success: function (data) {
							if (data.valid == 'true') {
								$('#envato_id').addClass('is-valid').attr('readonly', true);
								$('.purchasecode').removeAttr('disabled');
								$('#envato_support').val('Supported');
								licensekey = data.key;
								toastr.success(data.message);
							}
							if (data.valid == 'expried') {
								@if(setting('ENVATO_EXPIRED_BLOCK') == 'on')
								$('.purchasecode').attr('disabled', true);
								$('#envato_id').addClass('is-invalid');
								$('#envato_support').val('Expired');
								toastr.error(data.message);
								@endif
								@if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
								$('#envato_id').addClass('is-valid').attr('readonly', true);
								$('.purchasecode').removeAttr('disabled');
								$('#envato_support').val('Expired');
								licensekey = data.key;
								toastr.warning(data.message);
								@endif
							}
							if (data.valid == 'false') {
								$('.purchasecode').attr('disabled', true);
								$('#envato_id').addClass('is-invalid');
								toastr.error(data.message);
							}
						},
						error: function () {}
					});
				}
			} else {
				toastr.error('Le code d’achat est obligatoire.');
				$('.purchasecode').attr('disabled', true);
				$('#envato_id').addClass('is-invalid');
			}
		});
		@endif

		// Éditeur : barre sobre (sans menus déroulants Bootstrap, absents de cette page)
		$('.summernote').summernote({
			placeholder: '',
			tabsize: 1,
			height: 200,
			tooltip: false,
			disableDragAndDrop: true,
			toolbar: [['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'ol']]],
			callbacks: { onImageUpload: function () {} }
		});
		// Accessibilité de l'éditeur : nom, description et lien avec le libellé
		$('.note-editable').attr({ role: 'textbox', 'aria-multiline': 'true', 'aria-labelledby': 'gnf-message-label', 'aria-describedby': 'gnf-message-help MessageError', 'aria-required': 'true' });
		$('#gnf-message-label').on('click', function () { $('.note-editable').trigger('focus'); });
		$('.note-editor button').attr('type', 'button');

		@if($gnOtp)
		// ---------- Étape 1 : e-mail, envoi du code (OTP) ----------
		$('body').on('submit', '#emailotp_form', function (e) {
			e.preventDefault();
			let email = $('#email').val();
			$('#EmailError').html('');
			$('#sprukoverifybtn').hide();
			$('#email').attr('readonly', true).css('pointer-events', 'none').removeClass('is-valid is-invalid');
			$('#spinnerenable').show();

			$.ajax({
				url: "{{route('guest.emailsvalidate')}}",
				method: "post",
				data: { email: email },
				success: (data) => {
					if (data.message == 'domainblock') {
						$('#EmailError').html(data.error);
						$('#spinnerenable').hide();
						$('#email').removeClass('is-valid').addClass('is-invalid').css('pointer-events', 'auto').removeAttr('readonly');
						$('#sprukoverifybtn').show();
					}
					if (data.message == 'createverifyotp') {
						$('#email').removeAttr('readonly').attr('disabled', true).removeClass('is-invalid').addClass('is-valid');
						$('#spinnerenable').hide();
						$('#emailalerroff').fadeIn();
						$('#Alertemailerror').html(@json(lang('An OTP (One Time Password) has been sent to your email ID. Please enter the OTP below to submit your guest ticket.')));
						$('#verifyottp').show();
						$('#sprukoverifybtn').html('Renvoyer le code');
						var timeleft = 15;
						var downloadTimer = setInterval(function () {
							$('#sprukoverifybtn').hide();
							timeleft -= 1;
							if (timeleft <= 0) {
								clearInterval(downloadTimer);
								$('#email').removeClass('is-valid');
								$('#sprukoverifybtn').show();
							}
						}, 1000);
					}
				},
				error: (data) => {
					var r = data.responseJSON || {};
					if (r.message === 'accessdenied') {
						$('.gnf-card').first().html('<h2>{{lang('Access Denied', 'errorpages')}}</h2><p>{{lang('Il semble que vous n\'êtes pas autorisé à accéder à cette page', 'errorpages')}}</p>');
						return;
					}
					$('#verifyottp').hide();
					$('#EmailError').html(r.errors && r.errors.email ? r.errors.email[0] : '');
					$('#spinnerenable').hide();
					$('#email').removeClass('is-valid').addClass('is-invalid').css('pointer-events', 'auto').removeAttr('readonly');
					$('#sprukoverifybtn').show();
				}
			});
		});

		// Le reste du formulaire reste inactif tant que le code n'est pas validé
		$('.verifyotp').css({ 'pointer-events': 'none', 'opacity': '0.5', 'cursor': 'not-allowed', 'user-select': 'none' });
		@endif

		// ---------- Envoi de l'alerte ----------
		$('body').on('submit', '#guest_form', function (e) {
			e.preventDefault();
			clearErrors();
			setLoading(true);
			var formData = new FormData(this);
			formData.set('envato_id', licensekey);

			let checked = document.querySelectorAll('.required:checked').length;
			var isValid = checked > 0;
			if (document.querySelectorAll('.required').length == '0') {
				ajax(formData);
			} else if (isValid) {
				ajax(formData);
			} else {
				setLoading(false);
				toastr.error('Vérifiez les champs obligatoires (*).');
			}
		});

		function resetBtn() { setLoading(false); }

		function ajax(formData) {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: SITEURL + "{{ $gnOtp ? '/guest/openticket' : '/guest/openticketnootp' }}",
				data: formData,
				cache: false,
				contentType: false,
				processData: false,

				success: (data) => {
					if (data.guest == 'pass') {
						clearErrors();
						toastr.success(data.success);
						try {
							localStorage.removeItem("guestsubject");
							localStorage.removeItem("guestmessage");
							localStorage.removeItem("guestemail");
						} catch (err) {}
						window.location.replace('{{url('guest/ticketdetails/')}}' + '/' + data.data.id);
					}
					if (data.guest == 'invaildotp') {
						showErrors({ verifyotp: data.success });
						resetBtn();
					}
					if (data.email == 'already') {
						resetBtn();
						toastr.error(data.error);
					}
					if (data.message == "domainblock") {
						resetBtn();
						showErrors({ email: data.error });
					}
					if (data.message == 'envatoerror') {
						toastr.error(data.error);
						window.location.reload();
					}
				},
				error: function (data) {
					var r = data.responseJSON || {};
					if (r.message === 'accessdenied') {
						$('.gnf-card').first().html('<h2>{{lang('Access Denied', 'errorpages')}}</h2><p>{{lang('Il semble que vous n\'êtes pas autorisé à accéder à cette page', 'errorpages')}}</p>');
						return;
					}
					if (r.message !== "Server Error" && r.errors) {
						showErrors(r.errors);
						resetBtn();
					} else if (r.message !== "Server Error") {
						toastr.error(r.message || 'Échec de l’envoi de l’alerte, veuillez réessayer.');
						resetBtn();
					} else {
						toastr.error('Échec de la création de l’alerte, veuillez réessayer.');
						setTimeout(function () { window.location.reload(); }, 1500);
					}
				}
			});
		}

		@if($gnOtp)
		// ---------- Vérification du code OTP (6 chiffres) ----------
		$('#verifyotp').on('keyup', function (e) {
			let otpvalue = e.target.value;
			let otplength = otpvalue.length;
			let result = otpvalue.match(/[0-9]/g);
			$('#verifyotpError').html('');
			if (result && otplength == '6') {
				$('#verifyotp').attr('disabled', true).css('pointer-events', 'none');
				$('#emailalerroff').fadeOut();
				$.ajax({
					url: '{{route('guest.verifyotp')}}',
					method: 'post',
					data: { otpvalue: otpvalue },
					success: (data) => {
						if (data.success) {
							$('#email').removeAttr('disabled').attr('readonly', true);
							$('#verifyotp').removeAttr('disabled').attr('readonly', true).removeClass('is-invalid').addClass('is-valid');
							$('.verifyotp').css({ 'pointer-events': 'visible', 'opacity': '1', 'cursor': 'auto', 'user-select': 'auto' });
							if (data[0] && data[0].email) { $('#sprukoemailverfy').val(data[0].email); }
						}
						if (data.error) {
							$('#verifyotp').addClass('is-invalid').removeAttr('disabled').css('pointer-events', 'auto');
							toastr.error(data.error);
							$('.verifyotp').css({ 'opacity': '0.5', 'cursor': 'not-allowed', 'user-select': 'none' });
						}
					},
					error: () => {
						$('#verifyotpError').html('');
						$('#verifyotp').removeAttr('disabled').css('pointer-events', 'auto');
					}
				});
			}
		});
		@endif

		// Brouillon local (sujet et message), comme avant
		$('.note-editable').on('keyup', function (e) {
			try { localStorage.setItem('guestmessage', e.target.innerHTML); } catch (err) {}
		});

		// Compteur de caractères du sujet (« 12/55 »)
		function updateCounter() {
			var el = document.getElementById('subject');
			if (!el) { return; }
			var max = parseInt($('#subjectmaxtext').data('max'), 10) || 0;
			var n = el.value.length;
			$('#subjectcount').text(n);
			$('#subjectmaxtext').toggleClass('is-near', max > 0 && n >= max * 0.85 && n < max).toggleClass('is-full', max > 0 && n >= max);
		}
		$('#subject').on('input keyup focus', function (e) {
			updateCounter();
			if (e.type !== 'focus') { try { localStorage.setItem('guestsubject', e.target.value); } catch (err) {} }
		});
		updateCounter();
		// Le préremplissage (SOS, brouillon) change la valeur sans événement : on resynchronise
		var cTries = 0, cTimer = setInterval(function () { updateCounter(); if (++cTries > 30) { clearInterval(cTimer); } }, 300);

		$(window).on('load', function () {
			var s = null, m = null;
			try { s = localStorage.getItem('guestsubject'); m = localStorage.getItem('guestmessage'); } catch (err) {}
			if ((s || m) && !/[?&]sos=1/.test(location.search)) {
				if (s) { document.querySelector('#subject').value = s; }
				if (m) { $('.summernote').summernote('code', m); }
			}
		});

	})(jQuery);

	@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
	// Téléversement de fichiers
	var uploadedDocumentMap = {};
	Dropzone.options.documentDropzone = {
		url: '{{route('guest.imageupload')}}',
		dictDefaultMessage: '<span class="gnf-dz__icon" aria-hidden="true"></span><span class="gnf-dz__title">Glissez vos fichiers ici</span><span class="gnf-dz__or">ou <span class="gnf-dz__browse">parcourez votre appareil</span></span>',
		dictRemoveFile: 'Retirer',
		dictCancelUpload: 'Annuler',
		dictInvalidFileType: 'Ce type de fichier n’est pas accepté.',
		dictFileTooBig: 'Fichier trop volumineux (@{{filesize}} Mo). Maximum : @{{maxFilesize}} Mo.',
		dictMaxFilesExceeded: 'Nombre maximal de fichiers atteint.',
		dictResponseError: 'Échec du téléversement (erreur @{{statusCode}}).',
		previewTemplate: '<div class="dz-preview dz-file-preview"><div class="dz-details"><span class="dz-filename" data-dz-name></span><span class="dz-size" data-dz-size></span></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-error-message" role="alert"><span data-dz-errormessage></span></div></div>',
		maxFilesize: '{{setting('FILE_UPLOAD_MAX')}}',
		addRemoveLinks: true,
		acceptedFiles: '{{setting('FILE_UPLOAD_TYPES')}}',
		maxFiles: parseInt('{{setting('MAX_FILE_UPLOAD')}}', 10) || null,
		headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
		success: function (file, response) {
			$('form').append('<input type="hidden" name="ticket[]" value="' + response.name + '">');
			uploadedDocumentMap[file.name] = response.name;
		},
		removedfile: function (file) {
			file.previewElement.remove();
			var name = '';
			if (typeof file.file_name !== 'undefined') { name = file.file_name; } else { name = uploadedDocumentMap[file.name]; }
			$('form').find('input[name="ticket[]"][value="' + name + '"]').remove();
		},
		init: function () {
			var dz = this;
			// Zone utilisable au clavier : Entrée ou Espace ouvre le sélecteur de fichiers
			this.element.addEventListener('keydown', function (ev) {
				if ((ev.key === 'Enter' || ev.key === ' ') && ev.target === dz.element) {
					ev.preventDefault();
					if (dz.hiddenFileInput) { dz.hiddenFileInput.click(); }
				}
			});
			this.on('addedfile', function (file) {
				var rm = file.previewElement && file.previewElement.querySelector('.dz-remove');
				if (!rm) { return; }
				rm.setAttribute('role', 'button');
				rm.setAttribute('tabindex', '0');
				rm.setAttribute('aria-label', 'Retirer le fichier ' + file.name);
				rm.addEventListener('keydown', function (ev) { if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); rm.click(); } });
			});
			@if(isset($project) && $project->document)
			var files = {!! json_encode($project->document) !!};
			for (var i in files) {
				var file = files[i];
				this.options.addedfile.call(this, file);
				file.previewElement.classList.add('dz-complete');
				$('form').append('<input type="hidden" name="ticket[]" value="' + file.file_name + '">');
			}
			@endif
		}
	};
	@endif
</script>
@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_GUEST')=='yes')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script src="{{ gn_asset('assets/ganho/js/gn-sos-prefill.js') }}" defer></script>
@endpush
