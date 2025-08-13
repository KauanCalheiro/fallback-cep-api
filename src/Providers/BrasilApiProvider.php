<?php

namespace Kamoca\FallbackCepApi\Providers;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class BrasilApiProvider extends BaseCepProvider
{
    private const BASE_URL = 'https://brasilapi.com.br/api/cep/v2/%s';

    public function resolve(string $cep): array
    {
        $response = Http::get(sprintf(self::BASE_URL, $cep));

        if ($response->failed()) {
            throw new RuntimeException(
                __('fallback-cep.error.runtime.request_failed', [
                    'cep' => $cep,
                    'provider' => 'BrasilApi',
                    'error' => $response->body(),
                ])
            );
        }

        return $response->json();
    }

    public function transform(array $data): array
    {
        return [
            'cep' => $data['cep'] ?? null,
            'rua' => $data['street'] ?? null,
            'bairro' => $data['neighborhood'] ?? null,
            'cidade' => $data['city'] ?? null,
            'uf' => $data['state'] ?? null,
            'provider' => 'BrasilApi',
        ];
    }
}
