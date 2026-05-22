<?php

namespace BlissJaspis\RajaOngkir\Tests\Unit;

use BlissJaspis\RajaOngkir\Data\RajaOngkirResponse;
use BlissJaspis\RajaOngkir\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RajaOngkirResponseTest extends TestCase
{
    #[Test]
    public function it_parses_meta_wrapped_responses(): void
    {
        $response = RajaOngkirResponse::fromArray([
            'meta' => [
                'message' => 'Success Get Provinces',
                'code' => 200,
                'status' => 'success',
            ],
            'data' => [['id' => 1]],
        ]);

        $this->assertTrue($response->successful());
        $this->assertSame('success', $response->status());
        $this->assertCount(1, $response->data);
    }

    #[Test]
    public function it_parses_flat_responses(): void
    {
        $response = RajaOngkirResponse::fromArray([
            'status' => 'success',
            'data' => [['id' => 1]],
        ]);

        $this->assertTrue($response->successful());
        $this->assertSame('success', $response->status());
    }

    #[Test]
    public function it_can_be_converted_back_to_array(): void
    {
        $response = RajaOngkirResponse::fromArray([
            'meta' => ['status' => 'success'],
            'data' => ['foo' => 'bar'],
        ]);

        $this->assertSame([
            'meta' => ['status' => 'success'],
            'data' => ['foo' => 'bar'],
        ], $response->toArray());
    }
}
