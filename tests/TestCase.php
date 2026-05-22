<?php

namespace BlissJaspis\RajaOngkir\Tests;

use BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider;
use BlissJaspis\RajaOngkir\RajaOngkir;
use Illuminate\Http\Client\Factory as HttpFactory;
use Orchestra\Testbench\Concerns\WithWorkbench;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        // additional setup if needed
    }

    protected function getPackageProviders($app): array
    {
        return [
            RajaOngkirServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        tap($app['config'], function ($config) {
            $config->set('database.default', 'sqlite');
            $config->set('database.connections.sqlite', [
                'driver' => 'sqlite',
                'database' => ':memory:',
            ]);
            $config->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
            $config->set('rajaongkir-komerce.api_key', 'your-api-key');
            $config->set('rajaongkir-komerce.base_url', 'https://rajaongkir.komerce.id/api/v1');
            $config->set('rajaongkir-komerce.timeout', 30);
            $config->set('rajaongkir-komerce.retry_times', 0);
            $config->set('rajaongkir-komerce.retry_sleep', 100);
            $config->set('rajaongkir-komerce.fake', false);
        });
    }

    protected function makeRajaOngkir(): RajaOngkir
    {
        return new RajaOngkir(
            apiKey: (string) config('rajaongkir-komerce.api_key'),
            baseUrl: (string) config('rajaongkir-komerce.base_url'),
            timeout: (int) config('rajaongkir-komerce.timeout', 30),
            retryTimes: (int) config('rajaongkir-komerce.retry_times', 0),
            retryMilliseconds: (int) config('rajaongkir-komerce.retry_sleep', 100),
            http: $this->app->make(HttpFactory::class),
        );
    }
}
