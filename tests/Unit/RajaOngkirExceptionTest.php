<?php

namespace BlissJaspis\RajaOngkir\Tests\Unit;

use BlissJaspis\RajaOngkir\Exceptions\RajaOngkirException;
use BlissJaspis\RajaOngkir\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class RajaOngkirExceptionTest extends TestCase
{
    #[Test]
    public function it_throws_on_http_error_with_response_body(): void
    {
        Http::fake([
            '*' => Http::response([
                'meta' => [
                    'message' => 'Invalid API key',
                    'status' => 'error',
                ],
            ], 401),
        ]);

        try {
            $this->makeRajaOngkir()->getProvinces();
            $this->fail('Expected RajaOngkirException was not thrown.');
        } catch (RajaOngkirException $exception) {
            $this->assertSame(401, $exception->statusCode);
            $this->assertSame('Invalid API key', $exception->getMessage());
            $this->assertIsArray($exception->response);
        }
    }

    #[Test]
    public function it_throws_when_api_returns_unsuccessful_status(): void
    {
        Http::fake([
            '*' => Http::response([
                'meta' => [
                    'message' => 'Quota exceeded',
                    'status' => 'error',
                ],
                'data' => null,
            ], 200),
        ]);

        $this->expectException(RajaOngkirException::class);
        $this->expectExceptionMessage('Quota exceeded');

        $this->makeRajaOngkir()->getProvinces();
    }
}
