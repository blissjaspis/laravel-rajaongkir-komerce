<?php

namespace BlissJaspis\RajaOngkir\Facades;

use BlissJaspis\RajaOngkir\RajaOngkir as RajaOngkirService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, mixed> getProvinces()
 * @method static array<string, mixed> getCity(int|string $provinceId)
 * @method static array<string, mixed> getDistrict(int|string $cityId)
 * @method static array<string, mixed> getSubDistrict(int|string $districtId)
 * @method static array<string, mixed> getWaybill(string $waybill, string $courier)
 * @method static array<string, mixed> getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
 * @method static array<string, mixed> getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
 * @method static array<string, mixed> searchDomestic(string $search, int $limit = 10, int $offset = 0)
 * @method static array<string, mixed> searchInternational(string $search, int $limit = 10, int $offset = 0)
 * @method static array<string, string> getListCourier()
 *
 * @see RajaOngkirService
 */
final class RajaOngkir extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RajaOngkirService::class;
    }
}
