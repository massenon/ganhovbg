{{-- Navigation SPA : balise de zone + routeur. Interrupteur unique : GN_SPA=false dans .env (puis php artisan config:clear). --}}
<meta name="gn-zone" content="{{ $zone }}">
@if(config('gn.spa', true))
<script src="{{ gn_asset('assets/ganho/js/gn-spa.js') }}"></script>
@endif
