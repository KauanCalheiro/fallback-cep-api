<?php

namespace Kamoca\FallbackCepApi\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use Kamoca\FallbackCepApi\Contracts\CepProviderContract;
use RuntimeException;

class ViaCepProvider extends BaseCepProvider implements CepProviderContract
{
    public function resolve(string $cep): array
    {
        $response = Http::get($this->build($cep));

        if ($response->failed()) {
            throw new RuntimeException(
                __('fallback-cep.error.runtime.request_failed', [
                    'cep' => $cep,
                    'provider' => 'ViaCep',
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
            'cep' => preg_replace('/[^0-9]/', '', $data['cep']),
            'rua' => $data['logradouro'],
            'bairro' => $data['bairro'],
            'cidade' => $data['localidade'],
            'uf' => $data['uf'],
            'provider' => 'ViaCep',
        ];
    }
}
