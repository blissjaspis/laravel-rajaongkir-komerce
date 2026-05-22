<?php

namespace BlissJaspis\RajaOngkir\Tests\Unit;

use BlissJaspis\RajaOngkir\Exceptions\RajaOngkirException;
use BlissJaspis\RajaOngkir\RajaOngkir;
use BlissJaspis\RajaOngkir\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;

#[TestDox('RajaOngkir')]
class RajaOngkirTest extends TestCase
{
    private RajaOngkir $rajaOngkir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rajaOngkir = $this->makeRajaOngkir();
    }

    #[Test]
    public function get_provinces_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/destination/province';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'name' => 'DKI Jakarta'],
                    ['id' => 2, 'name' => 'Jawa Barat'],
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getProvinces();

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET' &&
                   $request->hasHeader('key', config('rajaongkir-komerce.api_key')) &&
                   $request->hasHeader('Accept', 'application/json') &&
                   ! $request->hasHeader('Content-Type');
        });

        $this->assertTrue($result->successful());
        $this->assertCount(2, $result->data);
    }

    #[Test]
    public function get_city_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/destination/city/1';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'name' => 'Jakarta Pusat', 'province_id' => 1],
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getCity(1);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET';
        });

        $this->assertTrue($result->successful());
        $this->assertCount(1, $result->data);
    }

    #[Test]
    public function get_district_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/destination/district/1';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'name' => 'Gambir', 'city_id' => 1],
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getDistrict(1);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET';
        });

        $this->assertTrue($result->successful());
        $this->assertCount(1, $result->data);
    }

    #[Test]
    public function get_sub_district_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/destination/sub-district/1';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'name' => 'Gambir Utara', 'district_id' => 1],
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getSubDistrict(1);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET';
        });

        $this->assertTrue($result->successful());
        $this->assertCount(1, $result->data);
    }

    #[Test]
    public function get_waybill_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/track/waybill';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    'waybill' => '123456789',
                    'status' => 'delivered',
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getWaybill('123456789', 'jne');

        Http::assertSent(function ($request) use ($url) {
            $body = $request->data();

            return $request->url() === $url &&
                   $request->method() === 'POST' &&
                   $body['awb'] === '123456789' &&
                   $body['courier'] === 'jne';
        });

        $this->assertTrue($result->successful());
    }

    #[Test]
    public function get_cost_domestic_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/calculate/domestic-cost';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    'cost' => 15000,
                    'service' => 'REG',
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getCostDomestic('jakarta', 'bandung', 1000, 'jne', 'lowest');

        Http::assertSent(function ($request) use ($url) {
            $body = $request->data();

            return $request->url() === $url &&
                   $request->method() === 'POST' &&
                   $body['origin'] === 'jakarta' &&
                   $body['destination'] === 'bandung' &&
                   $body['weight'] === 1000 &&
                   $body['courier'] === 'jne' &&
                   $body['price'] === 'lowest';
        });

        $this->assertTrue($result->successful());
    }

    #[Test]
    public function get_cost_domestic_uses_default_filter(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/calculate/domestic-cost';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [],
            ], 200),
        ]);

        $this->rajaOngkir->getCostDomestic('jakarta', 'bandung', 1000, 'jne');

        Http::assertSent(function ($request) use ($url) {
            $body = $request->data();

            return $request->url() === $url &&
                   $body['price'] === 'lowest';
        });
    }

    #[Test]
    public function get_cost_international_makes_correct_request(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/calculate/international-cost';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    'cost' => 150000,
                    'service' => 'International',
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->getCostInternational('jakarta', 'singapore', 1000, 'dhl', 'fastest');

        Http::assertSent(function ($request) use ($url) {
            $body = $request->data();

            return $request->url() === $url &&
                   $request->method() === 'POST' &&
                   $body['origin'] === 'jakarta' &&
                   $body['destination'] === 'singapore' &&
                   $body['weight'] === 1000 &&
                   $body['courier'] === 'dhl' &&
                   $body['price'] === 'fastest';
        });

        $this->assertTrue($result->successful());
    }

    #[Test]
    public function get_cost_international_uses_default_filter(): void
    {
        $url = config('rajaongkir-komerce.base_url').'/calculate/international-cost';

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [],
            ], 200),
        ]);

        $this->rajaOngkir->getCostInternational('jakarta', 'singapore', 1000, 'dhl');

        Http::assertSent(function ($request) {
            $body = $request->data();

            return $body['price'] === 'lowest';
        });
    }

    #[Test]
    public function search_domestic_makes_correct_request(): void
    {
        $search = 'jakarta';
        $limit = 5;
        $offset = 10;
        $url = config('rajaongkir-komerce.base_url')."/destination/domestic-destination?search={$search}&limit={$limit}&offset={$offset}";

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'name' => 'Jakarta'],
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->searchDomestic($search, $limit, $offset);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET' &&
                   $request->url() === $url;
        });

        $this->assertTrue($result->successful());
    }

    #[Test]
    public function search_domestic_uses_default_parameters(): void
    {
        $search = 'jakarta';
        $limit = 10;
        $offset = 0;
        $url = config('rajaongkir-komerce.base_url')."/destination/domestic-destination?search={$search}&limit={$limit}&offset={$offset}";

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [],
            ], 200),
        ]);

        $this->rajaOngkir->searchDomestic($search, $limit, $offset);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET';
        });
    }

    #[Test]
    public function search_international_makes_correct_request(): void
    {
        $search = 'singapore';
        $limit = 3;
        $offset = 5;
        $url = config('rajaongkir-komerce.base_url')."/destination/international-destination?search={$search}&limit={$limit}&offset={$offset}";

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [
                    ['id' => 1, 'name' => 'Singapore'],
                ],
            ], 200),
        ]);

        $result = $this->rajaOngkir->searchInternational($search, $limit, $offset);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET';
        });

        $this->assertTrue($result->successful());
    }

    #[Test]
    public function search_international_uses_default_parameters(): void
    {
        $search = 'singapore';
        $limit = 10;
        $offset = 0;
        $url = config('rajaongkir-komerce.base_url')."/destination/international-destination?search={$search}&limit={$limit}&offset={$offset}";

        Http::fake([
            $url => Http::response([
                'status' => 'success',
                'data' => [],
            ], 200),
        ]);

        $this->rajaOngkir->searchInternational($search);

        Http::assertSent(function ($request) use ($url) {
            return $request->url() === $url &&
                   $request->method() === 'GET';
        });
    }

    #[Test]
    public function http_requests_include_correct_headers(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'success'], 200),
        ]);

        $this->rajaOngkir->getProvinces();

        Http::assertSent(function ($request) {
            return $request->hasHeader('key', config('rajaongkir-komerce.api_key')) &&
                   $request->hasHeader('Accept', 'application/json') &&
                   ! $request->hasHeader('Content-Type');
        });
    }

    #[Test]
    public function http_exception_is_thrown_on_error_response(): void
    {
        Http::fake([
            '*' => Http::response(['error' => 'Unauthorized'], 401),
        ]);

        $this->expectException(RajaOngkirException::class);

        $this->rajaOngkir->getProvinces();
    }
}
