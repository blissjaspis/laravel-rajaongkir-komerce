<?php

namespace BlissJaspis\RajaOngkir;

use Illuminate\Support\Facades\Http;

class RajaOngkir
{
    protected $apiKey;

    protected $baseUrl;

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    public function __construct()
    {
        $this->apiKey = config('rajaongkir-komerce.api_key');
        $this->baseUrl = config('rajaongkir-komerce.base_url');
    }

    public function getProvinces()
    {
        return $this->sendRequest('get', '/destination/province');
    }

    public function getCity($provinceId)
    {
        return $this->sendRequest('get', '/destination/city/' . $provinceId);
    }

    public function getDistrict($cityId)
    {
        return $this->sendRequest('get', '/destination/district/' . $cityId);
    }

    public function getSubDistrict($districtId)
    {
        return $this->sendRequest('get', '/destination/sub-district/' . $districtId);
    }

    public function getWaybill(string $waybill, string $courier)
    {
        return $this->sendRequest('post', '/track/waybill', [
            'awb' => $waybill,
            'courier' => $courier,
        ]);
    }

    public function getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
    {
        return $this->sendRequest('post', '/calculate/domestic-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => $filter,
        ]);
    }

    public function getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
    {
        return $this->sendRequest('post', '/calculate/international-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => $filter,
        ]);
    }

    public function searchDomestic(string $search, int $limit = 10, int $offset = 0)
    {
        return $this->sendRequest('get', '/destination/domestic-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function searchInternational(string $search, int $limit = 10, int $offset = 0)
    {
        return $this->sendRequest('get', '/destination/international-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    private function sendRequest(string $method, string $endpoint, array $data = [])
    {
        $request = Http::withHeaders([
            'key' => $this->apiKey,
            ...$this->headers,
        ]);

        $response = match (strtoupper($method)) {
            'POST' => $request->post($this->baseUrl . $endpoint, $data),
            default => $request->get($this->baseUrl . $endpoint, $data),
        };

        return $response->throw()->json();
    }
}