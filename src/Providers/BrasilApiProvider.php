<?php

namespace Moca\FallbackCepApi\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use Moca\FallbackCepApi\Contracts\CepProviderContract;
use RuntimeException;

class BrasilApiProvider extends BaseCepProvider implements CepProviderContract
{
    public function resolve(string $cpf): array
    {
        $response = Http::get($this->build($cpf));

        if ($response->failed()) {
            throw new RuntimeException(
                __('fallback-cep-api.error.runtime.request_failed', [
                    'cep' => $cpf,
                    'provider' => 'BrasilApi',
                    'error' => $response->body(),
                ])
            );
        }

        $data = $this->transform($response->json());

        $this->validate($data);

        return $data;
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
