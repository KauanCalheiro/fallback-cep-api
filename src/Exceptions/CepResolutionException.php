<?php

namespace Kamoca\FallbackCepApi\Exceptions;

use Exception;

class CepResolutionException extends Exception
{
    protected array $errorBag;

    public function __construct(string $message, array $errorBag = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorBag = $errorBag;
    }

    public function getErrorBag(): array
    {
        return $this->errorBag;
    }
}
