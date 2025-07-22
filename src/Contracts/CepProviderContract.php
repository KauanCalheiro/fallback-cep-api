<?php

namespace Moca\FallbackCepApi\Contracts;

interface CepProviderContract
{
    /**
     * Use the received CEP to search for the address data in some CEP provider.
     *
     * @param string $cep
     * @return array|null
     */
    public function resolve(string $cep): array;

    /**
     * Transform the data received from the provider into a standardized format.
     *
     * @param array $data
     * @return array|null
     */
    public function transform(array $data): array;

    /**
     * Validate the data received from the provider.
     *
     * @param array $data
     * @return self
     */
    public function validate(array $data): void;
}
