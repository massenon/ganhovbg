@extends('layouts.usermaster')

@section('content')
@php extract(include resource_path('views/includes/user/gnmaps.php')); @endphp
@php
	$gnU = Auth::guard('customer')->user();
	$gnPrenom = trim((string) ($gnU->firstname ?: $gnU->username));
	$gnC = [
		'attente' => $tickets->whereIn('status', ['New', 'On-Hold'])->count(),
		'suivies' => $tickets->whereIn('status', ['Inprogress', 'Re-Open'])->count(),
		'resolues' => $tickets->where('status', 'Closed')->count(),
	];
	$gnLast = $tickets->first();
	$gnStep = $gnLast ? $gnEtape($gnLast) : 0;
	$gnFr = fn ($d) => $d ? \Carbon\Carbon::parse($d)->locale('fr')->translatedFormat('j F Y') : '—';
	$gnMailNotifs = $gnU->unreadNotifications()->where('data->status', 'mail')->get();
@endphp

@if(setting('ANNOUNCEMENT_USER') == 'only_login_user' || setting('ANNOUNCEMENT_USER') == 'all_users')
	@php
		$gnAnn = collect($announcement)->filter(fn ($a) => $a->status == 1);
		$gnAnnDays = collect($announcements)->filter(fn ($a) => $a->status == 1 && in_array(today()->format('D'), explode(',', (string) $a->announcementday), true));
	@endphp
	@foreach($gnAnn->merge($gnAnnDays) as $a)
		<div class="gcx-announce" role="status" data-gcx-announce="{{ $a->id }}" style="--ann1:{{ $a->primary_color }};--ann2:{{ $a->secondary_color }}">
			<p><strong>{{ $a->title }}</strong> <span>{!! $a->notice !!}</span> @if($a->buttonon == 1)<a href="{{ $a->buttonurl }}" target="_blank" rel="noopener">{{ $a->buttonname }}</a>@endif</p>
			<button type="button" data-gcx-close aria-label="Fermer l’annonce">&times;</button>
		</div>
	@endforeach
@endif

@foreach($gnMailNotifs as $n)
	<div class="gcx-announce gcx-announce--mail" role="status">
		<p><strong>{{ $n->data['mailsubject'] ?? '' }}</strong> <span>{{ \Illuminate\Support\Str::limit($n->data['mailtext'] ?? '', 300) }}</span> <a href="{{ route('customer.notiication.view', $n->id) }}">En savoir plus</a></p>
	</div>
@endforeach

<div class="gcx-welcome">
	<h1>Bonjour {{ $gnPrenom }},</h1>
	<p>Vous êtes dans un espace sécurisé et confidentiel.</p>
</div>

<section class="gcx-hero" aria-labelledby="gcx-hero-t">
	<div class="gcx-hero__tx">
		<h2 id="gcx-hero-t">Victime de violences basées sur le genre ou de détresse psychologique&nbsp;?</h2>
		<p>Nos équipes d’écoute et d’assistance sont disponibles pour vous accompagner. Vous pouvez écrire à votre rythme, ou en parler d’abord à l’assistant.</p>
		<div class="gcx-hero__act">
			<a class="gcx-btn gcx-btn--cta gcx-btn--lg" href="{{ route('client.ticket') }}">+ Créer une alerte / un cas maintenant</a>
			<a class="gcx-btn gcx-btn--light gcx-btn--lg" href="https://wa.me/22963608513" target="_blank" rel="noopener">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
				Parler à un psychologue (WhatsApp)
			</a>
		</div>
		<p class="gcx-hero__note">En danger immédiat, joignez d’abord les secours de votre pays. L’alerte GANXÔ ne contacte pas les services d’urgence.</p>
	</div>
</section>

<section aria-labelledby="gcx-kpi-t">
	<h2 class="gcx-h2" id="gcx-kpi-t">Suivi de vos dossiers</h2>
	<ul class="gcx-kpis">
		<li><a class="gcx-kpi gcx-kpi--warn" href="{{ route('gn.alertes', 'en-attente') }}">
			<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
			<span class="gcx-kpi__n">{{ str_pad($gnC['attente'], 2, '0', STR_PAD_LEFT) }}</span><span class="gcx-kpi__l">En attente</span></a></li>
		<li><a class="gcx-kpi gcx-kpi--info" href="{{ route('gn.alertes', 'suivies') }}">
			<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/></svg>
			<span class="gcx-kpi__n">{{ str_pad($gnC['suivies'], 2, '0', STR_PAD_LEFT) }}</span><span class="gcx-kpi__l">Suivies</span></a></li>
		<li><a class="gcx-kpi gcx-kpi--ok" href="{{ route('gn.alertes', 'resolues') }}">
			<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/></svg>
			<span class="gcx-kpi__n">{{ str_pad($gnC['resolues'], 2, '0', STR_PAD_LEFT) }}</span><span class="gcx-kpi__l">Résolues</span></a></li>
	</ul>
</section>

<section class="gcx-card" aria-labelledby="gcx-last-t">
	<div class="gcx-card__head">
		<h2 id="gcx-last-t">Activité récente @if($gnLast)<small>Dossier #{{ $gnLast->ticket_id }}</small>@endif</h2>
	</div>
	@if($gnLast)
		<div class="gcx-last">
			<p class="gcx-last__st">
				<span class="gcx-pill gcx-pill--{{ $gnStatutCls[$gnLast->status] ?? 'info' }}">{{ $gnStatut[$gnLast->status] ?? $gnLast->status }}</span>
				<strong>{{ $gnLast->subject }}</strong>
			</p>
			<p class="gcx-last__sub">
				@if($gnLast->toassignuser)Pris en charge par {{ $gnLast->toassignuser->name }}.@elseif($gnLast->status === 'Closed')Ce dossier est clôturé.@else Votre alerte a bien été reçue : l’équipe l’examine et vous répond rapidement.@endif
				Dernière activité le {{ $gnFr($gnLast->last_reply ?: $gnLast->updated_at) }}.
			</p>
			<ol class="gcx-steps gcx-steps--big" aria-label="Avancement : {{ $gnSteps[$gnStep - 1] }}">
				@foreach($gnSteps as $i => $label)
					<li @if($i + 1 < $gnStep) class="is-done" @elseif($i + 1 === $gnStep) class="is-now" aria-current="step" @endif><span>{{ $label }}</span></li>
				@endforeach
			</ol>
			<div class="gcx-last__act">
				<a class="gcx-btn gcx-btn--primary" href="{{ route('loadmore.load_data', $gnLast->ticket_id) }}">Ouvrir le dossier</a>
				<a class="gcx-btn gcx-btn--ghost" href="{{ route('loadmore.load_data', $gnLast->ticket_id) }}#gcx-reponse">Contacter mon référent</a>
			</div>
		</div>
	@else
		<div class="gcx-empty">
			<p>Vous n’avez pas encore créé d’alerte. Quand vous serez prêt·e, nous sommes là pour vous écouter.</p>
			<a class="gcx-btn gcx-btn--cta" href="{{ route('client.ticket') }}">+ Créer une alerte / un cas</a>
		</div>
	@endif
</section>

<section aria-labelledby="gcx-res-t">
	<h2 class="gcx-h2" id="gcx-res-t">Ressources d’aide et d’apaisement</h2>
	<ul class="gcx-res">
		<li><a href="{{ url('/article/calmer-une-crise-dangoisse-en-4-etapes') }}"><b>Calmer une crise d’angoisse</b><span>4 étapes simples et un exercice de respiration.</span></a></li>
		<li><a href="{{ url('/article/consultation-en-ligne-psychologue') }}"><b>Consulter un psychologue en ligne</b><span>Parler sans vous déplacer, en toute confidentialité.</span></a></li>
		<li><a href="{{ url('/faq') }}"><b>Vos droits et vos démarches</b><span>Les réponses à vos questions les plus fréquentes.</span></a></li>
		<li><a href="{{ url('/knowledge') }}"><b>Actualités et ressources</b><span>Santé mentale, droits, prévention des violences.</span></a></li>
	</ul>
</section>
@endsection
