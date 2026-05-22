<?php

namespace BlissJaspis\RajaOngkir\Providers;

use BlissJaspis\RajaOngkir\Contracts\RajaOngkirClient;
use BlissJaspis\RajaOngkir\RajaOngkir;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class RajaOngkirServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/rajaongkir-komerce.php',
            'rajaongkir-komerce'
        );

        $this->app->singleton(RajaOngkir::class, function (Application $app): RajaOngkir {
            return new RajaOngkir(
                apiKey: (string) $app['config']->get('rajaongkir-komerce.api_key'),
                baseUrl: (string) $app['config']->get('rajaongkir-komerce.base_url'),
                timeout: (int) $app['config']->get('rajaongkir-komerce.timeout', 30),
                retryTimes: (int) $app['config']->get('rajaongkir-komerce.retry_times', 0),
                retryMilliseconds: (int) $app['config']->get('rajaongkir-komerce.retry_sleep', 100),
                http: $app->make(HttpFactory::class),
            );
        });

        $this->app->alias(RajaOngkir::class, RajaOngkirClient::class);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/rajaongkir-komerce.php' => config_path('rajaongkir-komerce.php'),
        ], ['config', 'rajaongkir-komerce-config']);

        if (config('rajaongkir-komerce.fake')) {
            Http::fake([
                '*' => Http::response([
                    'meta' => [
                        'message' => 'Fake RajaOngkir response',
                        'code' => 200,
                        'status' => 'success',
                    ],
                    'data' => [],
                ], 200),
            ]);
        }

        if (blank(config('rajaongkir-komerce.api_key')) && ! config('rajaongkir-komerce.fake')) {
            logger()->warning(
                'RajaOngkir API key is not configured. Set RAJAONGKIR_API_KEY in your .env file.'
            );
        }
    }
}
