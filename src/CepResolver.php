<?php

namespace Moca\FallbackCepApi;

use Exception;
use Moca\FallbackCepApi\Providers\BrasilApiProvider;
use Moca\FallbackCepApi\Providers\ViaCepProvider;

class CepResolver
{
    /**
     * @var \Moca\FallbackCepApi\Providers\BaseCepProvider[]
     */
    protected array $providers = [];

    public function __construct(array $providersConfig = [])
    {
        $providers = collect($providersConfig)
            ->filter(fn ($config) => $config['enabled'] ?? false)
            ->sortBy(fn ($config) => $config['priority'] ?? 99);

        foreach ($providers as $providerName => $config) {
            $providerClass = match ($providerName) {
                'via_cep' => ViaCepProvider::class,
                'brasil_api' => BrasilApiProvider::class,
                default => null,
            };

            if ($providerClass && class_exists($providerClass)) {
                $provider = new $providerClass($config);
                $this->providers[] = $provider;
            }
        }
    }

    public function resolve(string $cep): ?array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        foreach ($this->providers as $provider) {
            try {
                return $provider->resolve($cep);
            } catch (Exception $_) {
            }
        }

        throw new Exception(__(
            'fallback-cep-api.error.runtime.all_failed',
            ['cep' => $cep]
        ));
    }
}
