<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],

    // Déjalo vacío si usarás patrones:
    'allowed_origins' => [],

    // ✅ Patrones que cubren producción + previews de Vercel
    'allowed_origins_patterns' => [
        '^https:\/\/pawction-frontend\.vercel\.app$',                                // prod
        '^https:\/\/pawction-frontend-git-[a-z0-9-]+-tonimbixs-projects\.vercel\.app$', // previews "git-..."
        '^https:\/\/pawction-frontend-[a-z0-9-]+-tonimbixs-projects\.vercel\.app$',     // previews con hash (p.ej. khferg1f5-...)
    ],

    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
