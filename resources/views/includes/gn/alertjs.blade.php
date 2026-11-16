{{-- Scripts du formulaire d'alerte : JavaScript natif, sans jQuery ni éditeur riche (le champ « description » est un simple <textarea>).
     Variable : $gnOtp (true = flux avec vérification OTP, formulaire #emailotp_form + #guest_form ; false = formulaire unique). --}}
@push('gn_scripts')
<script src="{{ gn_asset('assets/ganho/js/pages-forms.js') }}"></script>
@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
<script src="{{ gn_asset('assets/plugins/dropzone/dropzone.js') }}"></script>
@endif
<script type="text/javascript">
(function () {
	"use strict";

	var SITEURL = '{{url('')}}';
	var licensekey;
	var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
	var $ = function (s, r) { return (r || document).querySelector(s); };
	var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
	var btn = $('#createticketbtn');
	var LABEL_SEND = btn ? btn.textContent.trim() : '';
	var LABEL_SENDING = 'Envoi en cours…';
	var msgEl = $('#gnf-message');

	function post(url, body) {
		var headers = { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' };
		if (!(body instanceof FormData)) {
			headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
			body = new URLSearchParams(body).toString();
		}
		return fetch(url, { method: 'POST', headers: headers, body: body, credentials: 'same-origin', cache: 'no-store' })
			.then(function (r) { return r.json().catch(function () { return {}; }).then(function (j) { return { ok: r.ok, status: r.status, json: j }; }); });
	}
	function esc(t) { var d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
	function toast(kind, m) { if (window.toastr && window.toastr[kind]) { window.toastr[kind](m); } }
	function show(el, on) { if (el) { el.style.display = on ? '' : 'none'; } }
	function setText(sel, t) { var e = $(sel); if (e) { e.textContent = t || ''; } }

	// ---------- Erreurs : message sous le champ, aria-invalid, récapitulatif en tête de formulaire ----------
	var ERR_MAP = [
		['#EmailError', '#email', 'email'], ['#verifyotpError', '#verifyotp', 'verifyotp'], ['#ccEmailError', '#ccmail', 'ccmail'],
		['#SubjectError', '#subject', 'subject'], ['#CategoryError', '#category', 'category'], ['#MessageError', '#gnf-message', 'message'],
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
	function labelText(ctl) {
		var f = ctl.closest('.gnf-field'), l = f && f.querySelector('label');
		if (!l) { return ''; }
		l = l.cloneNode(true);
		$$('.gnf-req,.gn-visually-hidden', l).forEach(function (n) { n.remove(); });
		return l.textContent.trim();
	}
	function setFieldError(errSel, ctlSel, msg) {
		var e = $(errSel), c = $(ctlSel), f = e && e.closest('.gnf-field');
		if (e) { e.textContent = msg || ''; }
		if (f) { f.classList.toggle('has-error', !!msg); }
		if (c) {
			c.classList.toggle('is-invalid', !!msg);
			if (msg) { c.setAttribute('aria-invalid', 'true'); } else { c.removeAttribute('aria-invalid'); }
		}
	}
	function clearErrors() {
		ERR_MAP.forEach(function (p) { setFieldError(p[0], p[1], ''); });
		var s = $('#gnf-summary'); if (s) { s.hidden = true; s.innerHTML = ''; }
	}
	function showErrors(errors) {
		var items = [];
		ERR_MAP.forEach(function (p) {
			var msg = firstMsg(errors[p[2]], p[2]);
			setFieldError(p[0], p[1], msg);
			var c = $(p[1]);
			if (msg && c) {
				if (!c.id) { c.id = 'gnf-ed-' + p[2]; }
				items.push('<li><a href="#' + c.id + '" data-gnf-focus="' + c.id + '">' + esc(labelText(c) + ' : ' + msg) + '</a></li>');
			}
		});
		var el = $('#gnf-summary');
		if (items.length && el) {
			el.innerHTML = '<p class="gnf-summary__title">Veuillez corriger ' + (items.length > 1 ? 'les ' + items.length + ' champs suivants' : 'le champ suivant') + ' avant d’envoyer.</p><ul>' + items.join('') + '</ul>';
			el.hidden = false;
			if (el.scrollIntoView) { el.scrollIntoView({ block: 'center', behavior: 'smooth' }); }
			el.focus({ preventScroll: true });
		}
	}
	document.body.addEventListener('click', function (e) {
		var a = e.target.closest && e.target.closest('#gnf-summary a[data-gnf-focus]');
		if (!a) { return; }
		e.preventDefault();
		var t = document.getElementById(a.getAttribute('data-gnf-focus'));
		if (t) { t.focus(); if (t.scrollIntoView) { t.scrollIntoView({ block: 'center' }); } }
	});
	// Corriger un champ efface son erreur
	document.body.addEventListener('input', function (e) {
		var t = e.target;
		if (!t.closest || !(t.matches('.gnf-field .gnf-ctl') || t.id === 'agree_terms')) { return; }
		var f = t.closest('.gnf-field');
		if (f) { f.classList.remove('has-error'); var er = f.querySelector('.gnf-error'); if (er) { er.textContent = ''; } }
		t.classList.remove('is-invalid'); t.removeAttribute('aria-invalid');
	});
	document.body.addEventListener('change', function (e) {
		if (e.target.id === 'agree_terms') { e.target.classList.remove('is-invalid'); e.target.removeAttribute('aria-invalid'); }
	});

	function setLoading(on) {
		if (!btn) { return; }
		btn.disabled = on; btn.classList.toggle('is-loading', on);
		btn.setAttribute('aria-busy', on ? 'true' : 'false');
		btn.textContent = on ? LABEL_SENDING : LABEL_SEND;
	}

	function fieldWithLabel(id, labelHtml, control) {
		var wrap = document.createElement('div');
		wrap.className = 'gnf-field';
		var label = document.createElement('label');
		label.setAttribute('for', id);
		label.innerHTML = labelHtml;
		wrap.appendChild(label);
		if (control.tagName === 'SELECT') {
			var sw = document.createElement('div');
			sw.className = 'gnf-select';
			sw.appendChild(control);
			wrap.appendChild(sw);
		} else {
			wrap.appendChild(control);
		}
		return wrap;
	}

	// Quand la catégorie change : liste des sous-catégories
	var cat = $('#category');
	if (cat) {
		cat.addEventListener('change', function (e) {
			show($('#selectssSubCategory'), false);
			post("{{ route('guest.subcategorylist') }}", { cat_id: e.target.value }).then(function (res) {
				var data = res.json || {};
				if (data.subcategoriess) {
					$('#subscategory').innerHTML = data.subcategoriess;
					show($('#selectssSubCategory'), true);
				} else {
					show($('#selectssSubCategory'), false);
					$('#subscategory').innerHTML = '';
				}
				var holder = $('#selectSubCategory');
				$$('.gnf-field', holder).forEach(function (n) { n.remove(); });
				if (data.subcategories && data.subcategories.length >= 1) {
					var sel = document.createElement('select');
					sel.className = 'gnf-ctl'; sel.id = 'subcategory'; sel.name = 'project';
					var opt = document.createElement('option');
					opt.value = ''; opt.textContent = @json('Projet');
					sel.appendChild(opt);
					holder.appendChild(fieldWithLabel('subcategory', @json('Projet'), sel));
					data.subcategories.forEach(function (sc) {
						var o = document.createElement('option');
						o.value = sc.name; o.textContent = sc.name;
						sel.appendChild(o);
					});
				}
				@if(setting('ENVATO_ON') == 'on')
				var ev = $('#envatopurchase');
				$$('.gnf-field', ev).forEach(function (n) { n.remove(); });
				if (data.envatosuccess && data.envatosuccess.length >= 1) {
					var inp = document.createElement('input');
					inp.className = 'gnf-ctl'; inp.type = 'search'; inp.id = 'envato_id'; inp.name = 'envato_id';
					inp.placeholder = 'Saisissez votre code d’achat';
					var hid = document.createElement('input');
					hid.type = 'hidden'; hid.id = 'envato_support'; hid.name = 'envato_support';
					var f = fieldWithLabel('envato_id', "Code d’achat Envato <span class='gnf-req'>*</span>", inp);
					f.appendChild(hid);
					ev.appendChild(f);
					$$('.purchasecode').forEach(function (b) { b.disabled = true; });
				} else {
					$$('.purchasecode').forEach(function (b) { b.disabled = false; });
				}
				@endif
			}).catch(function () {});
		});
	}

	@php $module = Module::all(); @endphp
	@if(in_array('Uhelpupdate', $module))
	// Validation du code d'achat
	document.body.addEventListener('keyup', function (e) {
		if (!e.target || e.target.id !== 'envato_id') { return; }
		var inp = e.target, value = inp.value;
		function lock(on) { $$('.purchasecode').forEach(function (b) { b.disabled = on; }); }
		if (value !== '') {
			if (value.length == 36) {
				var fd = new FormData(); fd.set('data', value); fd.set('_token', csrf);
				post("{{ route('guest.envatoverify') }}", fd).then(function (res) {
					var data = res.json || {};
					if (data.valid == 'true') {
						inp.classList.add('is-valid'); inp.readOnly = true; lock(false);
						$('#envato_support').value = 'Supported'; licensekey = data.key; toast('success', data.message);
					}
					if (data.valid == 'expried') {
						@if(setting('ENVATO_EXPIRED_BLOCK') == 'on')
						lock(true); inp.classList.add('is-invalid'); $('#envato_support').value = 'Expired'; toast('error', data.message);
						@endif
						@if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
						inp.classList.add('is-valid'); inp.readOnly = true; lock(false); $('#envato_support').value = 'Expired'; licensekey = data.key; toast('warning', data.message);
						@endif
					}
					if (data.valid == 'false') { lock(true); inp.classList.add('is-invalid'); toast('error', data.message); }
				}).catch(function () {});
			}
		} else {
			toast('error', 'Le code d’achat est obligatoire.');
			lock(true); inp.classList.add('is-invalid');
		}
	});
	@endif

	@if($gnOtp)
	// ---------- Étape 1 : e-mail, envoi du code (OTP) ----------
	var otpForm = $('#emailotp_form');
	var mailEl = $('#email'), verifyBtn = $('#sprukoverifybtn');
	function lockRest(on) {
		var r = $('.verifyotp'); if (!r) { return; }
		r.style.opacity = on ? '0.5' : '1';
		r.style.cursor = on ? 'not-allowed' : 'auto';
		r.style.userSelect = on ? 'none' : 'auto';
		if ('inert' in r) { r.inert = on; } else { r.style.pointerEvents = on ? 'none' : 'visible'; }
	}
	if (otpForm) {
		otpForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var email = mailEl.value;
			setText('#EmailError', '');
			show(verifyBtn, false);
			mailEl.readOnly = true; mailEl.style.pointerEvents = 'none'; mailEl.classList.remove('is-valid', 'is-invalid');
			show($('#spinnerenable'), true);
			function reopen() {
				show($('#spinnerenable'), false);
				mailEl.classList.remove('is-valid'); mailEl.classList.add('is-invalid'); mailEl.style.pointerEvents = 'auto'; mailEl.readOnly = false;
				show(verifyBtn, true);
			}
			post("{{route('guest.emailsvalidate')}}", { email: email }).then(function (res) {
				var data = res.json || {};
				if (!res.ok) {
					if (data.message === 'accessdenied') {
						$('.gnf-card').innerHTML = '<h2>{{lang('Access Denied', 'errorpages')}}</h2><p>{{lang('Il semble que vous n\'êtes pas autorisé à accéder à cette page', 'errorpages')}}</p>';
						return;
					}
					show($('#verifyottp'), false);
					setText('#EmailError', data.errors && data.errors.email ? data.errors.email[0] : '');
					reopen();
					return;
				}
				if (data.message == 'domainblock') {
					$('#EmailError').innerHTML = data.error;
					reopen();
				}
				if (data.message == 'createverifyotp') {
					mailEl.readOnly = false; mailEl.disabled = true; mailEl.classList.remove('is-invalid'); mailEl.classList.add('is-valid');
					show($('#spinnerenable'), false);
					show($('#emailalerroff'), true);
					setText('#Alertemailerror', @json(lang('An OTP (One Time Password) has been sent to your email ID. Please enter the OTP below to submit your guest ticket.')));
					show($('#verifyottp'), true);
					verifyBtn.textContent = 'Renvoyer le code';
					var timeleft = 15;
					var timer = setInterval(function () {
						show(verifyBtn, false);
						timeleft -= 1;
						if (timeleft <= 0) { clearInterval(timer); mailEl.classList.remove('is-valid'); show(verifyBtn, true); }
					}, 1000);
				}
			}).catch(function () { reopen(); });
		});
		// Le reste du formulaire reste inactif tant que le code n'est pas validé
		lockRest(true);
	}
	@endif

	// ---------- Envoi de l'alerte ----------
	var form = $('#guest_form');
	function ajax(formData) {
		post(SITEURL + "{{ $gnOtp ? '/guest/openticket' : '/guest/openticketnootp' }}", formData).then(function (res) {
			var data = res.json || {};
			if (res.ok) {
				if (data.guest == 'pass') {
					clearErrors();
					toast('success', data.success);
					try { localStorage.removeItem("guestsubject"); localStorage.removeItem("guestmessage"); localStorage.removeItem("guestemail"); } catch (err) {}
					window.location.replace('{{url('guest/ticketdetails/')}}' + '/' + data.data.id);
					return;
				}
				if (data.guest == 'invaildotp') { showErrors({ verifyotp: data.success }); setLoading(false); }
				if (data.email == 'already') { setLoading(false); toast('error', data.error); }
				if (data.message == "domainblock") { setLoading(false); showErrors({ email: data.error }); }
				if (data.message == 'envatoerror') { toast('error', data.error); window.location.reload(); }
				if (!data.guest && !data.email && !data.message) { setLoading(false); toast('error', 'L’envoi n’a pas abouti. Patientez quelques secondes puis réessayez.'); }
				return;
			}
			if (data.message === 'accessdenied') {
				$('.gnf-card').innerHTML = '<h2>{{lang('Access Denied', 'errorpages')}}</h2><p>{{lang('Il semble que vous n\'êtes pas autorisé à accéder à cette page', 'errorpages')}}</p>';
				return;
			}
			if (data.message !== "Server Error" && data.errors) {
				showErrors(data.errors); setLoading(false);
			} else if (data.message !== "Server Error") {
				toast('error', data.message || 'Échec de l’envoi de l’alerte, veuillez réessayer.'); setLoading(false);
			} else {
				toast('error', 'Échec de la création de l’alerte, veuillez réessayer.');
				setTimeout(function () { window.location.reload(); }, 1500);
			}
		}).catch(function () {
			toast('error', 'Échec de l’envoi de l’alerte, veuillez réessayer.');
			setLoading(false);
		});
	}
	if (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			clearErrors();
			setLoading(true);
			var formData = new FormData(form);
			if (licensekey) { formData.set('envato_id', licensekey); }
			var req = $$('.required');
			if (req.length == 0 || $$('.required:checked').length > 0) {
				ajax(formData);
			} else {
				setLoading(false);
				toast('error', 'Vérifiez les champs obligatoires (*).');
			}
		});
	}

	@if($gnOtp)
	// ---------- Vérification du code OTP (6 chiffres) ----------
	var otpEl = $('#verifyotp');
	if (otpEl) {
		otpEl.addEventListener('keyup', function (e) {
			var v = e.target.value;
			setText('#verifyotpError', '');
			if (/[0-9]/.test(v) && v.length == 6) {
				otpEl.disabled = true; otpEl.style.pointerEvents = 'none';
				show($('#emailalerroff'), false);
				post('{{route('guest.verifyotp')}}', { otpvalue: v }).then(function (res) {
					var data = res.json || {};
					if (!res.ok) { setText('#verifyotpError', ''); otpEl.disabled = false; otpEl.style.pointerEvents = 'auto'; return; }
					if (data.success) {
						mailEl.disabled = false; mailEl.readOnly = true;
						otpEl.disabled = false; otpEl.readOnly = true; otpEl.classList.remove('is-invalid'); otpEl.classList.add('is-valid');
						lockRest(false);
						var hv = $('#sprukoemailverfy'); if (hv && data[0] && data[0].email) { hv.value = data[0].email; }
					}
					if (data.error) {
						otpEl.classList.add('is-invalid'); otpEl.disabled = false; otpEl.style.pointerEvents = 'auto';
						toast('error', data.error);
						lockRest(true);
					}
				}).catch(function () { otpEl.disabled = false; otpEl.style.pointerEvents = 'auto'; });
			}
		});
	}
	@endif

	// ---------- Compteurs de caractères (sujet et description) + brouillon local ----------
	function counter(inputId, countId, boxId, key) {
		var el = document.getElementById(inputId), n = document.getElementById(countId), box = document.getElementById(boxId);
		if (!el || !n || !box) { return function () {}; }
		var max = parseInt(box.getAttribute('data-max'), 10) || 0;
		function upd() {
			var len = el.value.length;
			n.textContent = len;
			box.classList.toggle('is-near', max > 0 && len >= max * 0.85 && len < max);
			box.classList.toggle('is-full', max > 0 && len >= max);
		}
		el.addEventListener('input', function () { upd(); try { localStorage.setItem(key, el.value); } catch (err) {} });
		el.addEventListener('focus', upd);
		upd();
		return upd;
	}
	var updSubject = counter('subject', 'subjectcount', 'subjectmaxtext', 'guestsubject');
	var updMessage = counter('gnf-message', 'gnf-message-n', 'gnf-message-count', 'guestmessage');
	// Le préremplissage (SOS, brouillon) change la valeur sans événement : on resynchronise
	var cTries = 0, cTimer = setInterval(function () { updSubject(); updMessage(); if (++cTries > 30) { clearInterval(cTimer); } }, 300);

	window.addEventListener('load', function () {
		var s = null, m = null;
		try { s = localStorage.getItem('guestsubject'); m = localStorage.getItem('guestmessage'); } catch (err) {}
		if ((s || m) && !/[?&]sos=1/.test(location.search)) {
			var subj = $('#subject');
			if (s && subj && !subj.value) { subj.value = s; }
			if (m && msgEl && !msgEl.value) {
				// anciens brouillons : du HTML produit par l'ancien éditeur, ramené à du texte simple
				if (/<\/?[a-z][^>]*>/i.test(m)) {
					var d = new DOMParser().parseFromString(m.replace(/<\/p>|<br\s*\/?>|<\/li>/gi, '\n'), 'text/html');
					m = (d.body.textContent || '').replace(/\n{3,}/g, '\n\n').trim();
				}
				msgEl.value = m.slice(0, 4000);
			}
			updSubject(); updMessage();
		}
	});

	@if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
	// Téléversement de fichiers
	var uploadedDocumentMap = {};
	function hiddenFiles(name) { return $$('input[name="ticket[]"]').filter(function (i) { return i.value === name; }); }
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
			var h = document.createElement('input');
			h.type = 'hidden'; h.name = 'ticket[]'; h.value = response.name;
			(form || document.body).appendChild(h);
			uploadedDocumentMap[file.name] = response.name;
		},
		removedfile: function (file) {
			file.previewElement.remove();
			var name = typeof file.file_name !== 'undefined' ? file.file_name : uploadedDocumentMap[file.name];
			hiddenFiles(name).forEach(function (i) { i.remove(); });
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
		}
	};
	@endif
})();
</script>
@if(setting('CAPTCHATYPE')=='google' && setting('RECAPTCH_ENABLE_GUEST')=='yes')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script src="{{ gn_asset('assets/ganho/js/gn-sos-prefill.js') }}" defer></script>
@endpush
