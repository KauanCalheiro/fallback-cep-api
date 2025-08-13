<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações dos provedores de CEP
    |--------------------------------------------------------------------------
    | Habilite ou desabilite provedores, defina prioridades e URLs base.
    */

    'providers' => [
        'via_cep' => [
            'enabled' => env('FALLBACK_CEP_API_VIA_CEP_ENABLED', true),
            'priority' => (int) env('FALLBACK_CEP_API_VIA_CEP_PRIORITY', 1),
            'class' => \Kamoca\FallbackCepApi\Providers\ViaCepProvider::class,
        ],

        'brasil_api' => [
            'enabled' => env('FALLBACK_CEP_API_BRASIL_API_ENABLED', true),
            'priority' => (int) env('FALLBACK_CEP_API_BRASIL_API_PRIORITY', 2),
            'class' => \Kamoca\FallbackCepApi\Providers\BrasilApiProvider::class,
        ],
    ],
];
