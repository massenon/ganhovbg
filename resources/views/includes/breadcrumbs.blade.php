{{-- Fil d'Ariane visible : lit $seo['breadcrumbs'] (meme source que le JSON-LD BreadcrumbList). --}}
@if(!empty($seo['breadcrumbs']) && count($seo['breadcrumbs']) > 1)
<nav class="gn-crumbs" aria-label="Fil d'Ariane">
	<ol>
		@foreach($seo['breadcrumbs'] as $c)
			@if($loop->last)
				<li aria-current="page">{{ $c[0] }}</li>
			@else
				<li><a href="{{ url($c[1]) }}">{{ $c[0] }}</a></li>
			@endif
		@endforeach
	</ol>
</nav>
@endif
