<?php

namespace Kamoca\FallbackCepApi\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use Kamoca\FallbackCepApi\Contracts\CepProviderContract;
use RuntimeException;

class BrasilApiProvider extends BaseCepProvider implements CepProviderContract
{
    public function resolve(string $cep): array
    {
        $response = Http::get($this->build($cep));

        if ($response->failed()) {
            throw new RuntimeException(
                __('fallback-cep.error.runtime.request_failed', [
                    'cep' => $cep,
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
