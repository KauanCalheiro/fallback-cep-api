<?php

namespace Kamoca\FallbackCepApi\Providers;

use InvalidArgumentException;

abstract class BaseCepProvider
{
    /**
     * Required keys expected in the resolved address data.
     *
     * @var string[]
     */
    private const REQUIRED_ADDRESS_KEYS = [
        'cep',
        'rua',
        'bairro',
        'cidade',
        'uf',
    ];

    /**
     * Perform the raw CEP resolution request to the provider.
     *
     * @param string $cep CEP to be resolved.
     * @return array Raw data returned by the provider.
     */
    abstract protected function resolve(string $cep): array;

    /**
     * Transform the raw provider data into standardized address format.
     *
     * @param array $data Raw data from the provider.
     * @return array Transformed and normalized address data.
     */
    abstract protected function transform(array $data): array;

    /**
     * Validate that the resolved data contains all required keys.
     *
     * @param array $data Transformed address data.
     * @throws InvalidArgumentException if any required key is missing.
     */
    final protected function validate(array $data): void
    {
        foreach (self::REQUIRED_ADDRESS_KEYS as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidArgumentException(__(
                    'fallback-cep.error.validation.missing_key',
                    ['key' => $key]
                ));
            }
        }
    }

    /**
     * Execute the full resolution pipeline: resolve, transform, and validate.
     *
     * @param string $cep CEP to resolve.
     * @return array Validated and transformed address data.
     */
    final public function run($cep): array
    {
        $data = $this->resolve($cep);
        $data = $this->transform($data);
        $this->validate($data);
        return $data;
    }
}
