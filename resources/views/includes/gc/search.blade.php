{{-- Recherche d'articles : POST /search (HomeController@searchlist), résultats injectés par pages-content.js --}}
<div class="gc-search" id="gc-search" data-url="{{ url('/search') }}" role="search">
	<form action="{{ url('/knowledge') }}" method="get" onsubmit="return false">
		<label class="gh-sr" for="gc-search-q">Rechercher un article</label>
		<input class="gn-input" id="gc-search-q" type="search" name="q" autocomplete="off" placeholder="Rechercher un article, une question…" aria-controls="gc-search-list" aria-expanded="false">
		<button class="gn-btn gn-btn--light" type="submit">Rechercher</button>
	</form>
	<div class="gc-search__list" id="gc-search-list" role="region" aria-label="Résultats de recherche" aria-live="polite" hidden></div>
</div>
