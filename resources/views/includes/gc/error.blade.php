{{-- Corps des pages d'erreur Ganho. Params : $gcCode, $gcHeading, $gcText, $gcPhoto (n° de photo), $gcActions [[libellé, url, classe]] --}}
<section class="gc-err" aria-labelledby="gc-title">
	<div class="gn-container gc-err__grid">
		<div>
			<span class="gn-eyebrow">Erreur {{ $gcCode }}</span>
			<p class="gc-err__code gn-gradient-text" aria-hidden="true">{{ $gcCode }}</p>
			<h1 id="gc-title">{{ $gcHeading }}</h1>
			<p>{{ $gcText }}</p>
			<div class="gc-err__a">
				@foreach ($gcActions as $act)
					<a class="gn-btn {{ $act[2] ?? 'gn-btn--light' }} gn-btn--lg" href="{{ $act[1] }}">{{ $act[0] }}</a>
				@endforeach
			</div>
		</div>
		<div class="gc-err__arch">
			<div class="gn-arch">@include('includes.gn.photo', ['n' => $gcPhoto, 'lcp' => true, 'sizes' => '(max-width: 800px) 220px, 340px'])</div>
		</div>
	</div>
	<div class="gn-stripes" role="presentation"></div>
</section>
