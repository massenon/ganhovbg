{{-- Tableau de bord administrateur / prestataire : accueil d'action + cartes KPI cliquables (utilise les variables du contrôleur) --}}
@php
	$gnSuper = isset($totaltickets);
	$gnFirst = trim(explode(' ', (string) Auth::user()->name)[0]);
	$gnHour = (int) now(Auth::user()->timezone)->format('G');
	$gnHello = $gnHour < 18 ? 'Bonjour' : 'Bonsoir';
	$gnNew = (int) ($recentticketcount ?? 0);
	$gnPulse = (int) ($replyrecent ?? $totalactiverecent ?? 0) > 0;
	if ($gnSuper) {
		$gnKpis = [
			['n' => ($recentticketcount ?? 0), 'l' => 'Nouvelles alertes', 'h' => 'À attribuer', 'c' => 'warn', 'u' => route('admin.recenttickets')],
			['n' => ($totalactivetickets ?? 0), 'l' => 'Alertes suivies', 'h' => 'En cours de prise en charge', 'c' => 'info', 'u' => route('admin.activeticket'), 'pulse' => $gnPulse],
			['n' => ($totalclosedtickets ?? 0), 'l' => 'Alertes résolues', 'h' => 'Dossiers clôturés', 'c' => 'ok', 'u' => route('admin.closedticket')],
			['n' => ($totaltickets ?? 0), 'l' => 'Toutes les alertes', 'h' => 'Historique complet', 'c' => 'ink', 'u' => route('admin.alltickets')],
			['n' => $selfassigncount ?? 0, 'l' => 'Mes cas', 'h' => 'Enregistrés à mon nom', 'c' => 'violet', 'u' => route('admin.selfassignticketview')],
			['n' => $suspendedticketcount ?? 0, 'l' => 'Alertes suspendues', 'h' => 'En pause', 'c' => 'muted', 'u' => route('admin.suspendedtickets')],
		];
	} else {
		$gnKpis = [
			['n' => $recentticketcount ?? 0, 'l' => 'Nouvelles alertes', 'h' => 'À prendre en charge', 'c' => 'warn', 'u' => route('admin.recenttickets')],
			['n' => $totalactivetickets ?? 0, 'l' => 'Alertes suivies', 'h' => 'En cours dans mes groupes', 'c' => 'info', 'u' => route('admin.activeticket'), 'pulse' => $gnPulse],
			['n' => $selfassigncount ?? 0, 'l' => 'Mes cas', 'h' => 'Enregistrés à mon nom', 'c' => 'violet', 'u' => route('admin.selfassignticketview')],
			['n' => $myassignedticketcount ?? 0, 'l' => 'Alertes qui me sont confiées', 'h' => 'Attribuées par l’équipe', 'c' => 'ink', 'u' => url('/admin/myassignedtickets')],
			['n' => $myclosedticketcount ?? 0, 'l' => 'Mes cas résolus', 'h' => 'Clôturés par moi', 'c' => 'ok', 'u' => route('admin.myclosedtickets')],
			['n' => $suspendticketcount ?? 0, 'l' => 'Mes cas suspendus', 'h' => 'En pause', 'c' => 'muted', 'u' => route('admin.mysuspendtickets')],
		];
	}
@endphp
<section class="gn-dash-hero" aria-labelledby="gn-dash-title">
	<div>
		<h2 id="gn-dash-title">{{ $gnHello }} {{ $gnFirst }}</h2>
		<p>
			@if($gnNew > 0)
				{{ $gnNew }} {{ $gnNew > 1 ? 'nouvelles alertes attendent' : 'nouvelle alerte attend' }} d’être prise{{ $gnNew > 1 ? 's' : '' }} en charge.
			@else
				Aucune nouvelle alerte à prendre en charge pour le moment. Merci pour votre vigilance.
			@endif
		</p>
	</div>
	<div class="gn-dash-hero__act">
		<a class="btn btn-primary" href="{{ route('admin.recenttickets') }}">Voir les alertes à traiter</a>
		<a class="btn btn-outline-light" href="{{ url('/admin/createticket') }}">Créer une alerte / un cas</a>
	</div>
</section>
<ul class="gn-kpis" aria-label="Indicateurs de suivi">
	@foreach($gnKpis as $k)
		<li>
			<a class="gn-kpi gn-kpi--{{ $k['c'] }}" href="{{ $k['u'] }}">
				<span class="gn-kpi__n">{{ $k['n'] }}@if(!empty($k['pulse']))<span class="gn-kpi__dot" title="Nouvelle réponse d’une victime" role="img" aria-label="Nouvelle réponse d’une victime"></span>@endif</span>
				<span class="gn-kpi__l">{{ $k['l'] }}</span>
				<span class="gn-kpi__h">{{ $k['h'] }}</span>
			</a>
		</li>
	@endforeach
</ul>
