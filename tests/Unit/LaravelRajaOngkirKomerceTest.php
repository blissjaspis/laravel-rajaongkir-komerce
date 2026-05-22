<?php

namespace BlissJaspis\RajaOngkir\Tests\Unit;

use BlissJaspis\RajaOngkir\Contracts\RajaOngkirClient;
use BlissJaspis\RajaOngkir\Facades\RajaOngkir as RajaOngkirFacade;
use BlissJaspis\RajaOngkir\RajaOngkir;
use BlissJaspis\RajaOngkir\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LaravelRajaOngkirKomerceTest extends TestCase
{
    #[Test]
    public function resolves_from_container(): void
    {
        $rajaongkir = $this->app->make(RajaOngkir::class);

        $this->assertInstanceOf(RajaOngkir::class, $rajaongkir);
        $this->assertInstanceOf(RajaOngkirClient::class, $this->app->make(RajaOngkirClient::class));
    }

    #[Test]
    public function resolves_as_singleton(): void
    {
        $first = $this->app->make(RajaOngkir::class);
        $second = $this->app->make(RajaOngkir::class);

        $this->assertSame($first, $second);
    }

    #[Test]
    public function facade_resolves_same_singleton_instance(): void
    {
        $fromContainer = $this->app->make(RajaOngkir::class);
        $fromFacade = RajaOngkirFacade::getFacadeRoot();

        $this->assertSame($fromContainer, $fromFacade);
    }

    #[Test]
    public function merges_default_config(): void
    {
        $this->assertSame('your-api-key', config('rajaongkir-komerce.api_key'));
        $this->assertSame('https://rajaongkir.komerce.id/api/v1', config('rajaongkir-komerce.base_url'));
        $this->assertSame(30, config('rajaongkir-komerce.timeout'));
        $this->assertSame(0, config('rajaongkir-komerce.retry_times'));
        $this->assertFalse(config('rajaongkir-komerce.fake'));
    }

    #[Test]
    public function config_can_publish_with_config_tag(): void
    {
        $this->artisan('vendor:publish --provider="BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider" --tag="config"')
            ->assertExitCode(0);
    }

    #[Test]
    public function config_can_publish_with_package_tag(): void
    {
        $this->artisan('vendor:publish --tag=rajaongkir-komerce-config')
            ->assertExitCode(0);
    }
}
