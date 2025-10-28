<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],

    // Deja 'allowed_origins' vacío y usa patrones:
    'allowed_origins' => [],

    // ✅ Producción + previews de tu scope en Vercel
    'allowed_origins_patterns' => [
        '^https:\/\/pawction-frontend\.vercel\.app$',                                   // prod
        '^https:\/\/pawction-frontend-git-[a-z0-9-]+-tonimbixs-projects\.vercel\.app$', // previews tipo "git-..."
        '^https:\/\/pawction-frontend-[a-z0-9-]+-tonimbixs-projects\.vercel\.app$',     // previews con hash (dmafbt2qs, etc.)
    ],

    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
