{{-- Entête Nuit des pages de contenu. Params : $gcEyebrow, $gcTitle, $gcLead (opt.), $gcSearch (bool, opt.). Lit $seo['breadcrumbs']. --}}
<section class="gc-hero" aria-labelledby="gc-title">
	<div class="gn-container">
		@include('includes.breadcrumbs')
		<div class="gc-hero__in">
			@if(!empty($gcEyebrow))<span class="gn-eyebrow">{{ $gcEyebrow }}</span>@endif
			<h1 id="gc-title">{{ $gcTitle }}</h1>
			@if(!empty($gcLead))<p class="gc-hero__lead">{{ $gcLead }}</p>@endif
			@if(!empty($gcSearch))@include('includes.gc.search')@endif
		</div>
	</div>
	<div class="gn-stripes" role="presentation"></div>
</section>
