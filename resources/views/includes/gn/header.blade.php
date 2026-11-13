@php
	$gnAlertUrl = setting('GUEST_TICKET') == 'yes' ? route('guest.ticket') : url('customer/login');
@endphp
<a class="gn-skip" href="#main">Aller au contenu</a>
<header class="gh-nav">
	<div class="gn-container gh-nav__in">
		<a class="gh-brand" href="{{ url('/') }}" title="GANXÔ Alerte VBG : retour à l’accueil" aria-label="GANXÔ Alerte VBG, accueil">
			<picture>
				<source type="image/webp" srcset="{{ asset('assets/ganho/img/logo-lettres-blanches-416w.webp') }}">
				<img src="{{ asset('assets/ganho/img/logo-lettres-blanches-416w.png') }}" alt="Logo GANXÔ Alerte VBG" title="GANXÔ Alerte VBG" width="147" height="42" decoding="async">
			</picture>
		</a>
		<button type="button" class="gh-burger" id="gh-burger" aria-expanded="false" aria-controls="gh-menu">Menu</button>
		<nav class="gh-menu" id="gh-menu" aria-label="Navigation principale">
			@if (\App\Models\AssistantSetting::flag('enabled'))<a href="{{ url('/assistant') }}" title="Parler à l’assistant GANXÔ" data-gn-open-chat>Assistant</a>@endif
			@if (setting('KNOWLEDGE_ENABLE') == 'yes')<a href="{{ url('/knowledge') }}">Actualités</a>@endif
			@if (setting('FAQ_ENABLE') == 'yes')<a href="{{ url('/faq') }}">FAQ</a>@endif
			@if (setting('CONTACT_ENABLE') == 'yes')<a href="{{ url('/contact-us') }}">Contactez-nous</a>@endif
			@if (Auth::guard('customer')->check())
				<a href="{{ url('customer/') }}">Mon espace</a>
			@else
				<a href="{{ url('customer/login') }}">Se connecter</a>
			@endif
			<a class="gn-btn gn-btn--primary" href="{{ $gnAlertUrl }}">Créer une alerte VBG</a>
		</nav>
	</div>
</header>
