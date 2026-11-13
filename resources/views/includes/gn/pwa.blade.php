{{-- PWA : enregistrement du service worker (différé, sans bloquer le rendu). --}}
<script>if('serviceWorker' in navigator){window.addEventListener('load',function(){navigator.serviceWorker.register('{{ url('sw.js') }}',{scope:'/'}).catch(function(){})})}</script>
