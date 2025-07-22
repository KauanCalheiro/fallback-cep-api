<?php

$placeholder = env('FALLBACK_CEP_API_PLACEHOLDER', '{{cep}}');

return [

    /*
    |--------------------------------------------------------------------------
    | Placeholder usado nas URLs dos provedores
    |--------------------------------------------------------------------------
    |
    | Esse valor será substituído pelo CEP fornecido ao construir a URL final.
    | O valor padrão é '{{cep}}'.
    |
    */

    'placeholder' => $placeholder,

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
            'url_template' => env('FALLBACK_CEP_API_VIA_CEP_BASE_URL', "https://viacep.com.br/ws/{$placeholder}/json/"),
            'token' => null,
        ],

        'brasil_api' => [
            'enabled' => env('FALLBACK_CEP_API_BRASIL_API_ENABLED', true),
            'priority' => (int) env('FALLBACK_CEP_API_BRASIL_API_PRIORITY', 2),
            'url_template' => env('FALLBACK_CEP_API_BRASIL_API_BASE_URL', "https://brasilapi.com.br/api/cep/v1/{$placeholder}"),
            'token' => null,
        ],
    ],

];
