<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debug Configuration
    |--------------------------------------------------------------------------
    | Toggles the application's debug mode based on the environment variable
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | VAPID PUBLIC KEY
    |--------------------------------------------------------------------------
    */

    'vapid_public' => env('VAPID_PUBLIC_KEY', null),
    'vapid' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => 'mailto:example@domain.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Would you like the install button to appear on all pages?
      Set true/false
    |--------------------------------------------------------------------------
    */

    'install-button' => true,

    /*
    |--------------------------------------------------------------------------
    | PWA Manifest Configuration
    |--------------------------------------------------------------------------
    |  php artisan erag:pwa-update-manifest
    */

    'manifest' => [
        'name' => 'KSeven PWA',
        'short_name' => 'KSPWA',
        'description' => 'A Progressive Web Application setup for Laravel projects.',
        "version" => "0.0.0",
        'background_color' => '#000',
        'theme_color' => '#F12',
        "start_url" => "/?source=pwa",
        "scope" => "/",
        "display" => "standalone",
        "display_override" => ["standalone", "fullscreen"],
        "orientation" => "portrait-primary",
        "lang" => "pt-BR",
        "dir" => "ltr",
        "icons" => [
            [
                "src" => "/assets/images/96x96.png",
                "sizes" => "96x96",
                "type" => "image/png"
            ],
            [
                "src" => "/assets/images/192x192.png",
                "sizes" => "192x192",
                "type" => "image/png"
            ],
            [
                "src" => "/assets/images/512x512.png",
                "sizes" => "512x512",
                "type" => "image/png"
            ]
        ],
        "screenshots" => [
            [
                "src" => "/assets/images/screanshot1.jpg",
                "sizes" => "576x1280",
                "type" => "image/jpeg"
            ],
            [
                "src" => "/assets/images/screanshot2.jpg",
                "sizes" => "576x1280",
                "type" => "image/jpeg"
            ],
            [
                "src" => "/assets/images/screenshot-wide.jpg",
                "sizes" => "1365x605",
                "type" => "image/jpeg",
                "form_factor" => "wide"
            ]
        ],
        "categories" => ["general"],
        "shortcuts" => [
            [
                "name" => "KSeven",
                "short_name" => "KSeven",
                "description" => "Seu desenvolvedor...",
                "url" => "/",
                "icons" => [
                    [
                        "src" => "/assets/images/96x96.png",
                        "sizes" => "96x96",
                        "type" => "image/png"
                    ]
                ]
            ]
        ],
        "related_applications" => [
            [
                "platform" => "webapp",
                "url" => "https://kseven.com.br/"
            ],
            [
                "platform" => "play",
                "id" => "com.kseven.app"
            ]
        ],
        "prefer_related_applications" => false,
        "iarc_rating_id" => "e10+"
    ],

];
