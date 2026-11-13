{{-- Espace client Ganho : barre latérale (architecture INSTRUCTION.txt : Dashboard, Créer une alerte, Mes alertes avec badges, Profil, Déconnexion). --}}
@php
	$gnU = Auth::guard('customer')->user();
	$gnCnt = ['attente' => 0, 'suivies' => 0, 'resolues' => 0];
	$gnNotif = 0;
	if ($gnU) {
		$gnBase = \App\Models\Ticket\Ticket::where('cust_id', $gnU->id);
		$gnCnt['attente'] = (clone $gnBase)->whereIn('status', ['New', 'On-Hold'])->count();
		$gnCnt['suivies'] = (clone $gnBase)->whereIn('status', ['Inprogress', 'Re-Open'])->count();
		$gnCnt['resolues'] = (clone $gnBase)->where('status', 'Closed')->count();
		$gnNotif = $gnU->unreadNotifications()->count();
	}
	$gnRoute = optional(request()->route())->getName();
	$gnIs = function (array $names) use ($gnRoute) { return in_array($gnRoute, $names, true) ? 'aria-current="page"' : ''; };
	$gnIco = function ($d) { return '<svg class="gcx-ico" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $d . '</svg>'; };
@endphp
<aside class="gcx-side" id="gcx-side" aria-label="Navigation de l’espace personnel">
	<div class="gcx-side__top">
		<a class="gcx-logo" href="{{ url('/') }}" title="GANXÔ Alerte VBG : accueil">
			<img src="{{ gn_asset('assets/ganho/img/logo-lettres-blanches-416w.png') }}" alt="GANXÔ Alerte VBG" width="150" height="43">
		</a>
		<button type="button" class="gcx-side__close" data-gcx-menu aria-label="Fermer le menu">&times;</button>
	</div>

	<a class="gcx-cta" href="{{ route('client.ticket') }}">
		{!! $gnIco('<path d="M12 5v14M5 12h14"/>') !!}<span>Créer une alerte / un cas</span>
	</a>

	<nav class="gcx-nav" aria-label="Menu principal">
		<ul>
			<li><a href="{{ route('client.dashboard') }}" {!! $gnIs(['client.dashboard']) !!}>{!! $gnIco('<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>') !!}<span>Tableau de bord</span></a></li>
		</ul>
		<p class="gcx-nav__t" id="gcx-t-alertes">Mes alertes</p>
		<ul aria-labelledby="gcx-t-alertes">
			<li><a href="{{ route('gn.alertes', 'en-attente') }}" {!! $gnIs(['gn.alertes']) && request()->route('etat') === 'en-attente' ? 'aria-current="page"' : '' !!}>{!! $gnIco('<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>') !!}<span>En attente</span><b class="gcx-badge gcx-badge--warn">{{ $gnCnt['attente'] }}</b></a></li>
			<li><a href="{{ route('gn.alertes', 'suivies') }}" {!! $gnIs(['gn.alertes']) && request()->route('etat') === 'suivies' ? 'aria-current="page"' : '' !!}>{!! $gnIco('<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/>') !!}<span>Suivies</span><b class="gcx-badge gcx-badge--info">{{ $gnCnt['suivies'] }}</b></a></li>
			<li><a href="{{ route('gn.alertes', 'resolues') }}" {!! $gnIs(['gn.alertes']) && request()->route('etat') === 'resolues' ? 'aria-current="page"' : '' !!}>{!! $gnIco('<circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/>') !!}<span>Résolues</span><b class="gcx-badge gcx-badge--ok">{{ $gnCnt['resolues'] }}</b></a></li>
		</ul>
		<p class="gcx-nav__t" id="gcx-t-compte">Mon espace</p>
		<ul aria-labelledby="gcx-t-compte">
			<li><a href="{{ route('client.notification') }}" {!! $gnIs(['client.notification']) !!}>{!! $gnIco('<path d="M6 8a6 6 0 1 1 12 0c0 7 3 8 3 8H3s3-1 3-8"/><path d="M10 20a2 2 0 0 0 4 0"/>') !!}<span>Notifications</span>@if($gnNotif > 0)<b class="gcx-badge gcx-badge--hot">{{ $gnNotif }}</b>@endif</a></li>
			<li><a href="{{ route('client.profile') }}" {!! $gnIs(['client.profile']) !!}>{!! $gnIco('<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.5-6 8-6s8 2 8 6"/>') !!}<span>Modifier le profil</span></a></li>
			<li><a href="{{ route('client.general') }}" {!! $gnIs(['client.general']) !!}>{!! $gnIco('<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/>') !!}<span>Préférences</span></a></li>
		</ul>
	</nav>

	<div class="gcx-side__bottom">
		<button type="button" class="gcx-support" data-gn-open-chat>
			<span class="gcx-support__av" aria-hidden="true">G</span>
			<span class="gcx-support__tx"><b>{{ setting('businesshourstitle') ?: 'Assistance' }}</b><small>{{ setting('businesshourssubtitle') ?: 'Je suis l’assistant GANXÔ. Cliquez ici pour discuter.' }}</small></span>
		</button>
		<form action="{{ route('client.logout') }}" method="POST" class="gcx-logout">
			@csrf
			<button type="submit">{!! $gnIco('<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/>') !!}<span>Se déconnecter</span></button>
		</form>
	</div>
</aside>
<div class="gcx-scrim" data-gcx-menu hidden></div>
