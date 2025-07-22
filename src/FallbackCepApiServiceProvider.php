<?php

namespace Moca\FallbackCepApi;

use Illuminate\Support\ServiceProvider;
use function config_path;

class FallbackCepApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/cep.php', 'cep');

        $this->app->singleton(CepResolver::class, function ($app) {
            $config = $app['config']->get('cep.providers', []);
            return new CepResolver($config);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/cep.php' => config_path('cep.php'),
        ], 'cep-config');


        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'fallback-cep');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/fallback-cep'),
        ], 'fallback-cep-translations');
    }
}
