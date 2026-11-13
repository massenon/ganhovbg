{{-- En-tête nuit des pages à formulaire. Variables : $gnEyebrow, $gnH1, $gnLead. Le fil d'Ariane lit $seo['breadcrumbs']. --}}
<section class="gnf-hero">
	<div class="gn-container">
		@include('includes.breadcrumbs')
		@if(!empty($gnEyebrow))<p class="gn-eyebrow">{{ $gnEyebrow }}</p>@endif
		<h1>{{ $gnH1 }}</h1>
		@if(!empty($gnLead))<p class="gnf-hero__lead">{{ $gnLead }}</p>@endif
	</div>
</section>
