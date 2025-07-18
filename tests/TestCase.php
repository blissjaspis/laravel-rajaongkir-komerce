<?php

namespace Tests;

use BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
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
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $app['config']->set('rajaongkir-komerce.api_key', 'your-api-key');
        $app['config']->set('rajaongkir-komerce.base_url', 'https://rajaongkir.komerce.id/api/v1');
        $app['config']->set('rajaongkir-komerce.headers', [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ]);
    }
}