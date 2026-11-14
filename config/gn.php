<?php

return [
    // Navigation SPA (public/assets/ganho/js/gn-spa.js). Interrupteur unique : GN_SPA=false dans .env, puis php artisan config:clear.
    'spa' => env('GN_SPA', true),

    // Adresse publique du forum (jamais deduite d'APP_URL en production). Surchargeable par FORUM_URL dans .env.
    'forum_url' => env('FORUM_URL', 'https://forum.ganho.app'),
];
