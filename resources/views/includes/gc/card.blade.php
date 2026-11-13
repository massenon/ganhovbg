{{-- Carte d'article. Params : $a (Article), $lead (bool, opt.) --}}
@php
	$gcFile = $a->featureimage ? ($a->featureimage == 'frontend.jpg' ? 'uploads/featureimage/demo/frontend.jpg' : 'uploads/featureimage/' . $a->featureimage) : null;
	$gcImg = $gcFile ? gn_picture($gcFile, 'Illustration de l’article : ' . $a->title, $a->title, !empty($lead) ? '(max-width: 1000px) 92vw, 660px' : '(max-width: 640px) 92vw, (max-width: 1000px) 46vw, 380px', 'loading="lazy"') : '';
	$gcHref = url('/article/' . ($a->articleslug ?: $a->id));
	$gcPrivate = $a->privatemode == 1;
@endphp
<article class="gc-card @if(!empty($lead)) gc-card--lead @endif">
	<div class="gc-card__img">{!! $gcImg !!}@if($gcImg === '')<div class="gc-card__ph" aria-hidden="true"></div>@endif</div>
	<div class="gc-card__b">
		<div class="gc-card__meta">
			@if($a->category)<span class="gn-badge">{{ $a->category->name }}</span>@endif
			<time datetime="{{ $a->created_at->toDateString() }}">{{ $a->created_at->locale('fr')->translatedFormat('j F Y') }}</time>
			@if($gcPrivate)<span class="gn-badge">Connexion requise</span>@endif
		</div>
		<h3><a href="{{ $gcHref }}">{{ $a->title }}</a></h3>
		<p>{{ $gcPrivate ? 'Ce contenu est réservé aux personnes connectées.' : gn_excerpt($a->message, 150) }}</p>
		<a class="gc-card__more" href="{{ $gcHref }}" tabindex="-1" aria-hidden="true">Lire la suite</a>
	</div>
</article>
