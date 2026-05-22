<?php

namespace BlissJaspis\RajaOngkir\Facades;

use BlissJaspis\RajaOngkir\Contracts\RajaOngkirClient;
use BlissJaspis\RajaOngkir\Data\RajaOngkirResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static RajaOngkirResponse getProvinces()
 * @method static RajaOngkirResponse getCity(int|string $provinceId)
 * @method static RajaOngkirResponse getDistrict(int|string $cityId)
 * @method static RajaOngkirResponse getSubDistrict(int|string $districtId)
 * @method static RajaOngkirResponse getWaybill(string $waybill, string $courier)
 * @method static RajaOngkirResponse getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
 * @method static RajaOngkirResponse getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
 * @method static RajaOngkirResponse searchDomestic(string $search, int $limit = 10, int $offset = 0)
 * @method static RajaOngkirResponse searchInternational(string $search, int $limit = 10, int $offset = 0)
 * @method static array<string, string> getListCourier()
 *
 * @see RajaOngkirClient
 */
final class RajaOngkir extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RajaOngkirClient::class;
    }
}
