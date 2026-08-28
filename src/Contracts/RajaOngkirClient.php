<?php

namespace BlissJaspis\RajaOngkir\Contracts;

use BlissJaspis\RajaOngkir\Data\RajaOngkirResponse;

interface RajaOngkirClient
{
    public function getProvinces(): RajaOngkirResponse;

    public function getCity(int|string $provinceId): RajaOngkirResponse;

    public function getDistrict(int|string $cityId): RajaOngkirResponse;

    public function getSubDistrict(int|string $districtId): RajaOngkirResponse;

    public function getWaybill(string $waybill, string $courier, string|int|null $lastPhoneNumber = null): RajaOngkirResponse;

    public function getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): RajaOngkirResponse;

    public function getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): RajaOngkirResponse;

    public function searchDomestic(string $search, int $limit = 10, int $offset = 0): RajaOngkirResponse;

    public function searchInternational(string $search, int $limit = 10, int $offset = 0): RajaOngkirResponse;

    /** @return array<string, string> */
    public function getListCourier(): array;
}
