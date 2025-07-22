<?php

namespace BlissJaspis\RajaOngkir\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getProvinces()
 * @method static array getCity(int $provinceId)
 * @method static array getDistrict(int $cityId)
 * @method static array getSubdistrict(int $districtId)
 * @method static array getWaybill(string $waybill, string $courier)
 * @method static array getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
 * @method static array getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')
 * @method static array searchDomestic(string $search, int $limit = 10, int $offset = 0)
 * @method static array searchInternational(string $search, int $limit = 10, int $offset = 0)
 * @method static array getListCourier()
 * 
 * @see \BlissJaspis\RajaOngkir\RajaOngkir
 */
final class RajaOngkir extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BlissJaspis\RajaOngkir\RajaOngkir::class;
    }
}