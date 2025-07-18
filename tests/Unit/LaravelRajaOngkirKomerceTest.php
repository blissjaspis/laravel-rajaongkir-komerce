<?php

namespace BlissJaspis\RajaOngkir\Tests\Unit;

use BlissJaspis\RajaOngkir\RajaOngkir;
use BlissJaspis\RajaOngkir\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class LaravelRajaOngkirKomerceTest extends TestCase
{
    #[Test]
    public function laravel_rajaongkir_komerce_can_be_instantiated()
    {
        $rajaongkir = new RajaOngkir();
        $this->assertInstanceOf(RajaOngkir::class, $rajaongkir);
    }

    #[Test]
    public function config_can_publish()
    {
        $this->artisan('vendor:publish --provider="BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider"')
            ->assertExitCode(0);
    }
}