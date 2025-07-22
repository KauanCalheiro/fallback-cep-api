<?php

namespace Moca\FallbackCepApi\Providers;

use InvalidArgumentException;
use Moca\FallbackCepApi\Contracts\CepProviderContract;

abstract class BaseCepProvider implements CepProviderContract
{
    private string $templateUrl;
    protected string $finalUrl;
    protected ?string $token;
    protected ?array $data;

    public function __construct(array $config = [])
    {
        $this->templateUrl = $config['url_template'] ?? '';
        $this->token = $config['token'] ?? null;
    }

    protected function build(string $cep): string
    {
        return str_replace(config('cep.placeholder'), $cep, $this->templateUrl);
    }

    public function validate(array $data): void
    {
        $requiredKeys = [
            'cep',
            'rua',
            'bairro',
            'cidade',
            'uf',
        ];

        foreach ($requiredKeys as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidArgumentException(__(
                    'fallback-cep-api.validation.missing_key',
                    ['key' => $key]
                ));
            }
        }
    }
}
