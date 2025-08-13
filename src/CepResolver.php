<?php

namespace Kamoca\FallbackCepApi;

use Exception;
use Kamoca\FallbackCepApi\Exceptions\CepResolutionException;
use Log;

class CepResolver
{
    /**
     * Array of instantiated CEP providers.
     *
     * @var \Kamoca\FallbackCepApi\Providers\BaseCepProvider[]
     */
    protected array $providers = [];

    /**
     * CepResolver constructor.
     *
     * Instantiates enabled and valid providers, sorted by priority.
     *
     * @param array $providersConfig Array of provider configurations.
     */
    public function __construct(array $providersConfig = [])
    {
        $this->providers = collect($providersConfig)
            ->filter(fn ($config) => ($config['enabled'] ?? false) && (isset($config['class']) && class_exists($config['class'])))
            ->sortBy(fn ($config) => $config['priority'] ?? 99)
            ->map(fn ($config) => new $config['class']())
            ->values()
            ->all();
    }

    /**
     * Resolve the given CEP by querying providers in priority order.
     *
     * Tries each provider until one successfully returns data.
     * If all providers fail, throws a custom exception with error details.
     *
     * @param string $cep The CEP to resolve.
     * @return array|null The address data returned by the provider.
     *
     * @throws CepResolutionException When all providers fail to resolve the CEP.
     */
    public function resolve(string $cep): ?array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        $errors = [];

        foreach ($this->providers as $provider) {
            try {
                return $provider->run($cep);
            } catch (Exception $e) {
                $errors[] = [
                    'provider' => get_class($provider),
                    'message' => $e->getMessage(),
                ];
            }
        }

        throw new CepResolutionException(
            __('fallback-cep.error.runtime.all_failed', ['cep' => $cep]),
            $errors
        );
    }
}
