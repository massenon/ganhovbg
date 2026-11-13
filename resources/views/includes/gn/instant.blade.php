{{-- Navigation fluide : transitions de page + préchargement des pages avant le clic (Speculation Rules).
     $mode = 'moderate' (site public : au survol) ou 'conservative' (espaces connectés : au toucher/clic, prudent pour les liens d'action). --}}
@php $gnSpecMode = config('gn.spa', true) ? 'none' : ($mode ?? 'conservative'); @endphp
<link rel="stylesheet" href="{{ gn_asset('assets/ganho/css/gn-instant.css') }}">
@if($gnSpecMode !== 'none')
<script type="speculationrules">
{
  "prefetch": [{
    "where": { "and": [
      { "href_matches": "/*" },
      { "not": { "href_matches": ["/*logout*", "/*deconnexion*", "/likes/*", "/dislikes/*", "/language/*", "/*marquer*", "/*delete*", "/*destroy*", "/*supprimer*", "/*download*", "/*telecharger*", "/*export*", "/assistant/*", "/newsletter/*", "/api/*"] } },
      { "not": { "selector_matches": "[rel~=nofollow], [download], [target=_blank], [data-no-prefetch]" } }
    ] },
    "eagerness": "{{ $gnSpecMode }}"
  }]
}
</script>
@endif
