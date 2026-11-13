{{-- Assistant GANXÔ : conversation. Params : $asst (AssistantEngine::pageConfig()), $gnAsstTag ('h2'|'h3'), $gnAsstMode ('window' = fenêtre flottante, 'page' = /assistant), $gnAsstUid (préfixe des id).
     Un seul exemplaire par page (pas d'id en double). Comportement : js/gn-assistant.js (POST /assistant/ask, alerte guidée, sessionStorage uniquement). --}}
@php
	$gnAsstTag = $gnAsstTag ?? 'h3';
	$gnAsstMode = $gnAsstMode ?? 'page';
	$u = $gnAsstUid ?? 'gn-chat';
@endphp
@if(!empty($asst['enabled']))
<div class="gh-chat gn-asst" id="{{ $u }}" data-gn-assistant data-mode="{{ $gnAsstMode }}" data-ask-url="{{ $asst['ask_url'] }}" data-config-url="{{ $asst['config_url'] }}" data-alert-url="{{ $asst['alert_url'] }}" data-alert-chat="{{ !empty($asst['alert_chat']) ? '1' : '0' }}" data-max="{{ $asst['max'] }}">
	<div class="gh-chat__bar">
		<span class="gh-chat__dot" aria-hidden="true"></span>
		<{{ $gnAsstTag }} id="{{ $u }}-title">Assistant GANXÔ</{{ $gnAsstTag }}>
		<button type="button" class="gh-chat__clear" data-gn-act="clear" title="Efface la conversation et le brouillon d’alerte de cet onglet">Effacer</button>
		@if($gnAsstMode === 'window')
			<button type="button" class="gh-chat__close" data-gn-act="close" aria-label="Fermer l’assistant" title="Fermer l’assistant (Échap)">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
			</button>
		@endif
	</div>
	@if(!empty($asst['alert_chat']))
	<div class="gh-chat__alertbar" data-gn-alertbar>
		<button type="button" class="gn-btn gn-btn--primary gh-chat__alertbtn" data-gn-act="start-alert">Créer une alerte avec l’assistant</button>
	</div>
	@endif
	<div class="gh-chat__log" data-gn-log role="log" aria-live="polite" aria-relevant="additions" aria-label="Conversation avec l’assistant" tabindex="0">
		<p class="gh-msg gh-msg--bot" data-gn-greeting>{{ $asst['greeting'] }}</p>
	</div>
	<div class="gh-chat__flowbar" data-gn-flowbar hidden>
		<button type="button" class="gh-flowbtn" data-gn-act="back">Revenir en arrière</button>
		<button type="button" class="gh-flowbtn" data-gn-act="cancel">Annuler l’alerte</button>
	</div>
	<div class="gh-chat__chips" data-gn-chips role="group" aria-label="Réponses et questions suggérées">
		@foreach($asst['quick'] as $q)
			<button type="button" class="gh-sug" data-gn-ask="{{ $q }}">{{ $q }}</button>
		@endforeach
	</div>
	<form class="gh-chat__form" data-gn-form autocomplete="off">
		<label class="gh-sr" for="{{ $u }}-input" data-gn-label>Votre message à l’assistant</label>
		<p class="gh-chat__help" id="{{ $u }}-help" data-gn-help hidden></p>
		<div class="gh-chat__compose">
			<textarea class="gn-input gh-chat__input" id="{{ $u }}-input" data-gn-input rows="3" maxlength="{{ $asst['max'] }}" placeholder="Écrivez votre question…" enterkeyhint="send" aria-describedby="{{ $u }}-hint {{ $u }}-help"></textarea>
			<button class="gh-chat__send" type="submit" data-gn-send aria-label="Envoyer" title="Envoyer" disabled>
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 3L10 14"/><path d="M21 3l-7 18-4-7-7-4z"/></svg>
			</button>
		</div>
		<p class="gh-chat__hint" id="{{ $u }}-hint">Entrée pour envoyer, Maj+Entrée pour passer à la ligne.</p>
	</form>
	<noscript><p class="gh-chat__foot">Le chat demande JavaScript. Vous pouvez <a href="{{ $asst['alert_url'] }}">créer une alerte</a> ou consulter la <a href="{{ url('/faq') }}">FAQ</a>.</p></noscript>
	<p class="gh-chat__foot">Ne saisissez pas d’informations personnelles dans les questions : la conversation n’est pas enregistrée sur nos serveurs et n’est pas lue par l’équipe. Pour être entendu·e, créez une alerte.@if(!empty($asst['disclaimer'])) <strong>{{ $asst['disclaimer'] }}</strong>@endif</p>
</div>
@else
<div class="gh-chat gh-chat--off" id="{{ $u }}-off">
	<div class="gh-chat__bar"><span class="gh-chat__dot gh-chat__dot--off" aria-hidden="true"></span><{{ $gnAsstTag }} id="{{ $u }}-title">Assistant GANXÔ</{{ $gnAsstTag }}></div>
	<div class="gh-chat__off">
		<p>L’assistant est momentanément indisponible. Vous pouvez écrire à l’équipe GANXÔ en créant une alerte.</p>
		<a class="gn-btn gn-btn--primary" href="{{ $asst['alert_url'] }}">Créer une alerte</a>
	</div>
</div>
@endif
