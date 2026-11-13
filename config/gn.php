<?php

return [
    // Navigation SPA (public/assets/ganho/js/gn-spa.js). Interrupteur unique : GN_SPA=false dans .env, puis php artisan config:clear.
    'spa' => env('GN_SPA', true),
];
