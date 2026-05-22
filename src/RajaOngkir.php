<?php

namespace BlissJaspis\RajaOngkir;

use Illuminate\Support\Facades\Http;

class RajaOngkir
{
    protected string $apiKey;

    protected string $baseUrl;

    /** @var array<string, string> */
    protected array $headers = [
        'Accept' => 'application/json',
    ];

    public function __construct()
    {
        $this->apiKey = (string) config('rajaongkir-komerce.api_key');
        $this->baseUrl = (string) config('rajaongkir-komerce.base_url');
    }

    /** @return array<string, mixed> */
    public function getProvinces(): array
    {
        return $this->sendRequest('get', '/destination/province');
    }

    /** @return array<string, mixed> */
    public function getCity(int|string $provinceId): array
    {
        return $this->sendRequest('get', '/destination/city/'.$provinceId);
    }

    /** @return array<string, mixed> */
    public function getDistrict(int|string $cityId): array
    {
        return $this->sendRequest('get', '/destination/district/'.$cityId);
    }

    /** @return array<string, mixed> */
    public function getSubDistrict(int|string $districtId): array
    {
        return $this->sendRequest('get', '/destination/sub-district/'.$districtId);
    }

    /** @return array<string, mixed> */
    public function getWaybill(string $waybill, string $courier): array
    {
        return $this->sendRequest('post', '/track/waybill', [
            'awb' => $waybill,
            'courier' => $courier,
        ]);
    }

    /** @return array<string, mixed> */
    public function getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): array
    {
        return $this->sendRequest('post', '/calculate/domestic-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => $filter,
        ]);
    }

    /** @return array<string, mixed> */
    public function getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): array
    {
        return $this->sendRequest('post', '/calculate/international-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => $filter,
        ]);
    }

    /** @return array<string, mixed> */
    public function searchDomestic(string $search, int $limit = 10, int $offset = 0): array
    {
        return $this->sendRequest('get', '/destination/domestic-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /** @return array<string, mixed> */
    public function searchInternational(string $search, int $limit = 10, int $offset = 0): array
    {
        return $this->sendRequest('get', '/destination/international-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sendRequest(string $method, string $endpoint, array $data = []): array
    {
        $headers = $this->headers;

        // Only set Content-Type for POST requests since GET requests don't have a body
        if (strtoupper($method) === 'POST') {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $request = Http::baseUrl($this->baseUrl)->withHeaders([
            'key' => $this->apiKey,
            ...$headers,
        ]);

        $response = match (strtoupper($method)) {
            'POST' => $request->asForm()->post($endpoint, $data),
            default => $request->get($endpoint, $data),
        };

        $result = $response->throw()->json();

        return is_array($result) ? $result : [];
    }

    /** @return array<string, string> */
    public function getListCourier(): array
    {
        return [
            'jne' => 'JNE',
            'sicepat' => 'SICEPAT',
            'ide' => 'IDE',
            'sap' => 'SAP',
            'jnt' => 'J&T',
            'ninja' => 'NINJA',
            'tiki' => 'TIKI',
            'lion' => 'LION',
            'anteraja' => 'ANTERAJA',
            'pos' => 'POS',
            'ncs' => 'NCS',
            'rex' => 'REX',
            'rpx' => 'RPX',
            'sentral' => 'SENTRAL',
            'star' => 'STAR',
            'wahana' => 'WAHANA',
            'dse' => 'DSE',
        ];
    }
}
