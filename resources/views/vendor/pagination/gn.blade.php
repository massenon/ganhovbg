@if ($paginator->hasPages())
<ul class="gc-pages">
	@if ($paginator->onFirstPage())
		<li><span class="gc-pages__nav is-off" aria-disabled="true"><span aria-hidden="true">←</span> Précédent</span></li>
	@else
		<li><a class="gc-pages__nav" href="{{ $paginator->previousPageUrl() }}" rel="prev"><span aria-hidden="true">←</span> Précédent</a></li>
	@endif

	@foreach ($elements as $element)
		@if (is_string($element))
			<li class="gc-pages__gap" aria-hidden="true">…</li>
		@endif
		@if (is_array($element))
			@foreach ($element as $page => $url)
				@if ($page == $paginator->currentPage())
					<li><span class="gc-pages__n is-current" aria-current="page"><span class="gh-sr">Page </span>{{ $page }}</span></li>
				@else
					<li><a class="gc-pages__n" href="{{ $url }}" aria-label="Aller à la page {{ $page }}">{{ $page }}</a></li>
				@endif
			@endforeach
		@endif
	@endforeach

	@if ($paginator->hasMorePages())
		<li><a class="gc-pages__nav" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant <span aria-hidden="true">→</span></a></li>
	@else
		<li><span class="gc-pages__nav is-off" aria-disabled="true">Suivant <span aria-hidden="true">→</span></span></li>
	@endif
</ul>
@endif
