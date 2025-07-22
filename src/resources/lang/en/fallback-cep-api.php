<?php

return [
    'error' => [
        'validation' => [
            'missing_key' => 'Missing required key: :key',
        ],
        'runtime' => [
            'request_failed' => 'Could not resolve CEP ":cep" using provider ":provider". Error: :error',
            'all_failed' => 'All providers failed to resolve CEP ":cep".',
        ],
    ],
];