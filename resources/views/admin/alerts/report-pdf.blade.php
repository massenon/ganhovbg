<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport statistique des alertes VBG</title>
<style>
	@page { margin: 34px 36px 52px 36px; }
	body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #17132B; line-height: 1.45; }
	h1 { font-size: 20px; margin: 0 0 4px 0; color: #0B0B14; }
	h2 { font-size: 12.5px; margin: 20px 0 8px 0; color: #0B0B14; padding-bottom: 4px; border-bottom: 2px solid #E6DFF0; }
	.meta { color: #55506B; font-size: 9px; margin: 0; }
	table { border-collapse: collapse; width: 100%; }
	.band td { height: 6px; padding: 0; }
	.head { background: #0B0B14; color: #fff; padding: 16px 20px; }
	.kpi td { width: 25%; padding: 0 5px 0 0; vertical-align: top; }
	.kpi .c { background: #F8F1FF; border-radius: 10px; padding: 10px 12px; }
	.kpi .n { font-size: 20px; font-weight: bold; color: #2B33C9; }
	.kpi .l { font-size: 8.5px; color: #55506B; }
	.tb th { text-align: left; font-size: 8.5px; color: #55506B; text-transform: uppercase; padding: 5px 6px; border-bottom: 1px solid #D8CDEB; }
	.tb td { padding: 5px 6px; border-bottom: 1px solid #EDE7F5; vertical-align: middle; }
	.bar { background: #2B33C9; height: 9px; border-radius: 5px; }
	.barbg { background: #EFE9F7; height: 9px; border-radius: 5px; }
	.num { text-align: right; width: 44px; font-weight: bold; }
	.box { border: 1.5px solid #C8200E; border-radius: 10px; padding: 9px 12px; background: #FDF1EF; color: #8A1708; margin-top: 18px; }
	.note { font-size: 8px; color: #55506B; margin-top: 10px; }
	.wk td { vertical-align: bottom; text-align: center; padding: 0 2px; font-size: 7.5px; color: #55506B; }
	.col { background: #0A66E8; border-radius: 3px 3px 0 0; width: 100%; }
	.nobreak { page-break-inside: avoid; }
</style>
</head>
<body>
@php
	$spectrum = ['#5B2BB8', '#2B33C9', '#0A66E8', '#0F8B6D', '#3F9A1A', '#FFBE05', '#F66100', '#E8381A'];
	$pal = ['#2B33C9', '#0A66E8', '#0F8B6D', '#F66100', '#5B2BB8', '#E8381A', '#3F9A1A', '#FFBE05'];
	$fmtH = function ($h) { return $h === null ? '—' : ($h >= 48 ? number_format($h / 24, 1, ',', ' ') . ' j' : number_format($h, 1, ',', ' ') . ' h'); };
	$bars = function ($data, $total) use ($pal) {
		$out = '<table class="tb"><tbody>';
		$i = 0;
		foreach ($data as $label => $n) {
			$pct = $total ? round($n * 100 / $total) : 0;
			$out .= '<tr><td style="width:34%">' . e($label) . '</td><td><div class="barbg"><div class="bar" style="width:' . max(2, $pct) . '%;background:' . $pal[$i % count($pal)] . '"></div></div></td><td class="num">' . $n . '</td><td style="width:36px;color:#55506B">' . $pct . ' %</td></tr>';
			$i++;
		}
		return $out . '</tbody></table>';
	};
@endphp

<table class="band"><tr>@foreach($spectrum as $c)<td style="background:{{ $c }}"></td>@endforeach</tr></table>
<table class="head"><tr>
	<td style="width:60%;color:#fff;vertical-align:middle">
		@if($logo)<img src="{{ $logo }}" alt="GANXÔ" style="height:30px"><br>@endif
		<span style="font-size:17px;font-weight:bold;color:#fff">Rapport statistique des alertes VBG</span>
	</td>
	<td style="text-align:right;color:#B9B4D0;font-size:8.5px;vertical-align:middle">
		{{ $period }}<br>{{ $scope }}<br>Généré le {{ $generated }} par {{ $by }}
	</td>
</tr></table>

@if(count($filters))
	<p class="meta" style="margin-top:8px">Filtres appliqués : @foreach($filters as $k => $v){{ $k }} = « {{ $v }} »@if(!$loop->last) · @endif @endforeach</p>
@endif

<h2>Indicateurs clés</h2>
<table class="kpi"><tr>
	<td><div class="c"><div class="n">{{ $stats['total'] }}</div><div class="l">Alertes reçues</div></div></td>
	<td><div class="c"><div class="n">{{ $stats['pending'] }}</div><div class="l">En attente</div></div></td>
	<td><div class="c"><div class="n">{{ $stats['followed'] }}</div><div class="l">Suivies (prises en charge)</div></div></td>
	<td><div class="c"><div class="n">{{ $stats['closed'] }}</div><div class="l">Résolues</div></div></td>
</tr></table>
<table class="kpi" style="margin-top:6px"><tr>
	<td><div class="c"><div class="n">{{ $stats['rate'] }} %</div><div class="l">Taux de résolution</div></div></td>
	<td><div class="c"><div class="n">{{ $fmtH($stats['avg_reply']) }}</div><div class="l">Délai moyen de première réponse</div></div></td>
	<td><div class="c"><div class="n">{{ $fmtH($stats['avg_resolution']) }}</div><div class="l">Délai moyen de résolution</div></div></td>
	<td></td>
</tr></table>

<div class="nobreak">
	<h2>Répartition par statut</h2>
	@if($stats['total']){!! $bars($stats['statuts'], $stats['total']) !!}@else<p class="meta">Aucune alerte sur la période.</p>@endif
</div>
<div class="nobreak">
	<h2>Répartition par catégorie</h2>
	@if($stats['total']){!! $bars($stats['categories'], $stats['total']) !!}@else<p class="meta">Aucune alerte sur la période.</p>@endif
</div>
<div class="nobreak">
	<h2>Répartition par priorité</h2>
	@if($stats['total']){!! $bars($stats['priorites'], $stats['total']) !!}@else<p class="meta">Aucune alerte sur la période.</p>@endif
</div>

<div class="nobreak">
	<h2>Évolution par semaine</h2>
	@if(count($stats['weeks']))
		@php $max = max($stats['weeks']); @endphp
		<table class="wk"><tr>
			@foreach($stats['weeks'] as $day => $n)
				<td style="height:90px"><div style="font-size:8px;color:#17132B;font-weight:bold">{{ $n }}</div><div class="col" style="height:{{ max(3, round($n * 70 / $max)) }}px"></div></td>
			@endforeach
		</tr><tr>
			@foreach($stats['weeks'] as $day => $n)
				<td>{{ \Carbon\Carbon::parse($day)->format('d/m') }}</td>
			@endforeach
		</tr></table>
		<p class="note">Chaque barre = une semaine (date du lundi) ; valeur = alertes reçues.</p>
	@else
		<p class="meta">Aucune alerte sur la période.</p>
	@endif
</div>

<div class="nobreak">
	<h2>Équipe : cas par agent (10 premiers)</h2>
	@if(count($stats['agents']))
		<table class="tb"><thead><tr><th>Agent</th><th class="num">Cas</th><th class="num">Résolus</th></tr></thead><tbody>
			@foreach($stats['agents'] as $name => $a)
				<tr><td>{{ $name }}</td><td class="num">{{ $a['total'] }}</td><td class="num">{{ $a['closed'] }}</td></tr>
			@endforeach
		</tbody></table>
	@else
		<p class="meta">Aucune alerte affectée sur la période.</p>
	@endif
</div>

<div class="box nobreak">
	<strong>Données sensibles : ne pas diffuser.</strong><br>
	Ce rapport ne contient que des statistiques agrégées, sans contenu de message ni identité de victime. Il est destiné à l’équipe GANXÔ Alerte VBG et doit être conservé de façon sécurisée, puis supprimé lorsqu’il n’est plus nécessaire.
</div>
<p class="note">Définitions : « en attente » = alertes reçues ou mises en attente, sans prise en charge ; « suivies » = prises en charge et non clôturées ; « résolues » = clôturées. Les délais sont des moyennes sur les alertes concernées (première réponse d’un·e agent ; clôture).</p>
</body>
</html>
