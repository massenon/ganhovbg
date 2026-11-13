{{-- Espace client Ganho : barre du haut (menu mobile, accès rapides, CTA alerte, cloche, avatar). --}}
@php
	$gnU = Auth::guard('customer')->user();
	$gnAvatar = ($gnU && $gnU->image) ? asset('uploads/profile/' . $gnU->image) : asset('uploads/profile/user-profile.png');
	$gnUnread = $gnU ? $gnU->unreadNotifications()->count() : 0;
@endphp
<header class="gcx-top">
	<button type="button" class="gcx-top__menu" data-gcx-menu aria-controls="gcx-side" aria-expanded="false" aria-label="Ouvrir le menu">
		<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
	</button>
	<nav class="gcx-top__links" aria-label="Aide et informations">
		<a href="{{ url('/') }}">Accueil</a>
		@if (setting('KNOWLEDGE_ENABLE') == 'yes')<a href="{{ url('/knowledge') }}">Actualités</a>@endif
		@if (setting('FAQ_ENABLE') == 'yes')<a href="{{ url('/faq') }}">Questions fréquentes</a>@endif
		@if (setting('CONTACT_ENABLE') == 'yes')<a href="{{ url('/contact-us') }}">Contact</a>@endif
	</nav>
	<div class="gcx-top__right">
		<a class="gcx-top__cta" href="{{ route('client.ticket') }}">+ <span>Créer une alerte</span></a>
		@if($gnU)
			<a class="gcx-top__bell" href="{{ route('client.notification') }}" aria-label="Notifications{{ $gnUnread ? ' : ' . $gnUnread . ' non lue(s)' : '' }}">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"/><path d="M10 20a2 2 0 0 0 4 0"/></svg>
				@if($gnUnread)<b>{{ $gnUnread }}</b>@endif
			</a>
			<a class="gcx-top__user" href="{{ route('client.profile') }}" title="Modifier le profil">
				<img src="{{ $gnAvatar }}" alt="" width="40" height="40">
				<span><b>{{ $gnU->username }}</b><small>{{ $gnU->email }}</small></span>
			</a>
		@endif
	</div>
</header>
