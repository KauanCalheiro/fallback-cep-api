<?php

namespace Kamoca\FallbackCepApi\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use Kamoca\FallbackCepApi\Contracts\CepProviderContract;
use RuntimeException;
use Str;

class ViaCepProvider extends BaseCepProvider
{
    private const BASE_URL = 'https://viacep.com.br/ws/%s/json/';

    public function resolve(string $cep): array
    {
        throw new RuntimeException(
            __('fallback-cep.error.runtime.provider_not_implemented', [
                'provider' => 'ViaCep',
                'message' => 'The ViaCepProvider is not implemented yet.',
            ])
        );
        $response = Http::get(sprintf(self::BASE_URL, $cep));

        if ($response->failed()) {
            throw new RuntimeException(
                __('fallback-cep.error.runtime.request_failed', [
                    'cep' => $cep,
                    'provider' => 'ViaCep',
                    'error' => $response->body(),
                ])
            );
        }

        return $response->json();
    }

    public function transform(array $data): array
    {
        return [
            'cep' => preg_replace('/[^0-9]/', '', $data['cep']),
            'rua' => $data['logradouro'],
            'bairro' => $data['bairro'],
            'cidade' => $data['localidade'],
            'uf' => $data['uf'],
            'provider' => 'ViaCep',
        ];
    }
}
