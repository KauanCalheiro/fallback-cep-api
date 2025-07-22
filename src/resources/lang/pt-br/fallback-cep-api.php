<?php

return [
    'error' => [
        'validation' => [
            'missing_key' => 'Chave obrigatória ausente: :key',
        ],
        'runtime' => [
            'request_failed' => 'Não foi possível resolver o CEP ":cep" usando o provedor ":provider". Erro: :error',
            'all_failed' => 'Todos os provedores falharam ao resolver o CEP ":cep".',
        ],
    ],
];