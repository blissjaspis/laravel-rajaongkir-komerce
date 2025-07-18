<?php

namespace BlissJaspis\RajaOngkir\Tests\Unit;

use BlissJaspis\RajaOngkir\RajaOngkir;
use BlissJaspis\RajaOngkir\Tests\TestCase;

class LaravelRajaOngkirKomerceTest extends TestCase
{
    public function test_laravel_rajaongkir_komerce_can_be_instantiated()
    {
        $rajaongkir = new RajaOngkir();
        $this->assertInstanceOf(RajaOngkir::class, $rajaongkir);
    }
}