<?php

namespace BlissJaspis\RajaOngkir;

use BlissJaspis\RajaOngkir\Contracts\RajaOngkirClient;
use BlissJaspis\RajaOngkir\Data\RajaOngkirResponse;
use BlissJaspis\RajaOngkir\Exceptions\RajaOngkirException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class RajaOngkir implements RajaOngkirClient
{
    /** @var array<string, string> */
    protected array $headers = [
        'Accept' => 'application/json',
    ];

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl,
        protected int $timeout = 30,
        protected int $retryTimes = 0,
        protected int $retryMilliseconds = 100,
        protected ?HttpFactory $http = null,
    ) {}

    public function getProvinces(): RajaOngkirResponse
    {
        return $this->sendRequest('get', '/destination/province');
    }

    public function getCity(int|string $provinceId): RajaOngkirResponse
    {
        return $this->sendRequest('get', '/destination/city/'.$provinceId);
    }

    public function getDistrict(int|string $cityId): RajaOngkirResponse
    {
        return $this->sendRequest('get', '/destination/district/'.$cityId);
    }

    public function getSubDistrict(int|string $districtId): RajaOngkirResponse
    {
        return $this->sendRequest('get', '/destination/sub-district/'.$districtId);
    }

    public function getWaybill(string $waybill, string $courier, string|int|null $lastPhoneNumber = null): RajaOngkirResponse
    {
        $payload = [
            'awb' => $waybill,
            'courier' => $courier,
        ];

        if ($lastPhoneNumber !== null && $lastPhoneNumber !== '') {
            $payload['last_phone_number'] = $lastPhoneNumber;
        }

        return $this->sendRequest('post', '/track/waybill', $payload);
    }

    public function getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): RajaOngkirResponse
    {
        return $this->sendRequest('post', '/calculate/domestic-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => $filter,
        ]);
    }

    public function getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): RajaOngkirResponse
    {
        return $this->sendRequest('post', '/calculate/international-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => $filter,
        ]);
    }

    public function searchDomestic(string $search, int $limit = 10, int $offset = 0): RajaOngkirResponse
    {
        return $this->sendRequest('get', '/destination/domestic-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    public function searchInternational(string $search, int $limit = 10, int $offset = 0): RajaOngkirResponse
    {
        return $this->sendRequest('get', '/destination/international-destination', [
            'search' => $search,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function sendRequest(string $method, string $endpoint, array $data = []): RajaOngkirResponse
    {
        $headers = $this->headers;

        if (strtoupper($method) === 'POST') {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        try {
            $request = $this->httpClient()->withHeaders([
                'key' => $this->apiKey,
                ...$headers,
            ]);

            $response = match (strtoupper($method)) {
                'POST' => $request->asForm()->post($endpoint, $data),
                default => $request->get($endpoint, $data),
            };

            $result = $response->throw()->json();
        } catch (RequestException $exception) {
            throw RajaOngkirException::fromRequestException($exception);
        }

        if (! is_array($result)) {
            throw new RajaOngkirException('Invalid response from RajaOngkir API.');
        }

        $parsed = RajaOngkirResponse::fromArray($result);

        if (! $parsed->successful() && $parsed->status() !== null) {
            throw new RajaOngkirException(
                message: (string) ($parsed->meta['message'] ?? 'RajaOngkir API request failed.'),
                response: $result,
            );
        }

        return $parsed;
    }

    protected function httpClient(): PendingRequest
    {
        $factory = $this->http ?? Http::getFacadeRoot();
        $client = $factory->baseUrl($this->baseUrl)->timeout($this->timeout);

        if ($this->retryTimes > 0) {
            $client = $client->retry($this->retryTimes, $this->retryMilliseconds);
        }

        return $client;
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
