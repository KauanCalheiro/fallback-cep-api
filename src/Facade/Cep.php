<?php

namespace Kamoca\FallbackCepApi\Facade;

use Illuminate\Support\Facades\Facade;
use Kamoca\FallbackCepApi\CepResolver;

class Cep extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CepResolver::class;
    }
}