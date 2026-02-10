# Laravel RajaOngkir From Komerce

> **Note**
> This package supports Laravel versions 10, 11, and 12.

This package provides a simple and easy-to-use Laravel wrapper for the RajaOngkir Komerce API. It supports two methods of address lookup: hierarchical step-by-step selection and direct search with autocomplete functionality.

## Installation

You can install the package via composer:

```bash
composer require bliss-jaspis/laravel-rajaongkir-komerce
```

You must publish the configuration file with:

```bash
php artisan vendor:publish --provider="BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider" --tag="config"
```

Add the following to your `.env` file:

```env
RAJAONGKIR_API_KEY=your-api-key
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
```

## Usage

There are two ways to use Rajaongkir-Komerce:

1. **Step-by-step method**. This was the old method before Komerce acquired Rajaongkir and changed the API.
2. **Direct Search**. This is the latest method used by Komerce after they acquired Rajaongkir.

For existing users whose data is still using the old method, you can continue using method 1.

This package supports 2 ways to access RajaOngkir data:

1. Step-by-step location lookup (`province -> city -> district -> subdistrict`)
2. Direct search and shipping cost lookup (search by keyword, then calculate cost)

You can call the API through the facade in controllers/services:

```php
use BlissJaspis\RajaOngkir\Facades\RajaOngkir;
```

### Choosing Between Methods

**Use Method 1 (Step-by-step)** if:
- You have existing user data stored in the hierarchical format
- You prefer structured location selection with multiple dropdowns
- You're migrating from the old Rajaongkir API structure

**Use Method 2 (Direct Search)** if:
- You're building a new application
- You want a better user experience with search/autocomplete
- You want to simplify your frontend code and reduce API calls
- You want to provide international shipping options

Method 2 is recommended for new implementations as it provides a more modern and user-friendly approach.

### Method 1: Step-by-Step Location Lookup

Use this method when users select addresses hierarchically.

#### 1. Finding Provinces
```php
$provinces = RajaOngkir::getProvinces();

{
  "meta": {
    "message": "Success Get Provinces",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 11,
      "name": "DKI Jakarta"
    },
    {
      "id": 6,
      "name": "Jawa Barat"
    }
    // ... more provinces
  ]
}
```

#### 2. Finding Cities
```php
$provinceId = 11;
$cities = RajaOngkir::getCity($provinceId);

{
  "meta": {
    "message": "Success Get Cities",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 1,
      "name": "Jakarta Pusat"
    },
    {
      "id": 2,
      "name": "Jakarta Selatan"
    }
    // ... more cities
  ]
}
```

#### 3. Finding Districts
```php
$cityId = 1;
$districts = RajaOngkir::getDistrict($cityId);

{
  "meta": {
    "message": "Success Get Districts",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 1,
      "name": "Kemayoran"
    }
    // ... more districts
  ]
}
```

#### 4. Finding Subdistricts
```php
$districtId = 1;
$subdistricts = RajaOngkir::getSubDistrict($districtId);

{
  "meta": {
    "message": "Success Get Subdistricts",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 1,
      "name": "Sumur Batu"
    }
    // ... more subdistricts
  ]
}
```

### Method 2: Direct Search and Cost Lookup

Use this method when users type destination keywords directly, then you calculate shipping.

#### 1. Searching Domestic Destinations
```php
$search = 'sinduharjo';
$limit = 10; // optional
$offset = 0; // optional
$destinations = RajaOngkir::searchDomestic($search, $limit, $offset);

{
  "meta": {
    "message": "Success Get Domestic Destinations",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 31555,
      "label": "SINDUHARJO, NGAGLIK, SLEMAN, DI YOGYAKARTA, 55581",
      "subdistrict_name": "SINDUHARJO",
      "district_name": "NGAGLIK",
      "city_name": "SLEMAN",
      "province_name": "DI YOGYAKARTA",
      "zip_code": "55581"
    }
  ]
}
```

#### 2. Searching International Destinations
```php
$search = 'Malaysia';
$limit = 10; // optional
$offset = 0; // optional
$destinations = RajaOngkir::searchInternational($search, $limit, $offset);

{
  "meta": {
    "message": "Success Get International Destinations",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 108,
      "name": "Malaysia"
    }
    // ... more destinations
  ]
}
```

#### 3. Calculating Domestic Shipping Cost
```php
$origin = 1;
$destination = 108;
$weight = 1000;
$courier = 'jne';
$filter = 'lowest';

$cost = RajaOngkir::getCostDomestic($origin, $destination, $weight, $courier, $filter);

{
  "meta": {
    "message": "Success Calculate Domestic Shipping cost",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "name": "Nusantara Card Semesta",
      "code": "ncs",
      "service": "DARAT",
      "description": "Regular Darat",
      "cost": 8000,
      "etd": "6-7 day"
    },
    {
      "name": "Citra Van Titipan Kilat (TIKI)",
      "code": "tiki",
      "service": "ECO",
      "description": "Economy Service",
      "cost": 15000,
      "etd": "5 day"
    }
  ]
}
```

#### 4. Calculating International Shipping Cost
```php
$origin = 1;
$destination = 108;
$weight = 1000;
$courier = 'jne';
$filter = 'lowest';

$cost = RajaOngkir::getCostInternational($origin, $destination, $weight, $courier, $filter);

{
  "meta": {
    "message": "Success Calculate International Shipping Cost",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "name": "Rayspeed Indonesia",
      "code": "ray",
      "service": "Regular Service",
      "description": "Retail",
      "currency": "IDR",
      "cost": 55000,
      "etd": ""
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "INTERPACK",
      "description": "Active",
      "currency": "IDR",
      "cost": 65000,
      "etd": "2-3 day"
    }
  ]
}
```

#### 5. Tracking Waybill
```php
$waybill = 'MT685U91';
$courier = 'jne';

$waybill = RajaOngkir::getWaybill($waybill, $courier);

{
  "meta": {
    "message": "Success Get Waybill",
    "code": 200,
    "status": "success"
  },
  "data": {
    "delivered": true,
    "summary": {
      "courier_code": "wahana",
      "courier_name": "Wahana Prestasi Logistik",
      "waybill_number": "MT685U91",
      "service_code": "",
      "waybill_date": "2024-10-08",
      "shipper_name": "TOKO ALAT LUKIS (08112XXXXXX)",
      "receiver_name": "FIKRI EL SARA (085668XXXXXX)",
      "origin": "",
      "destination": "di Kota Sukabumi",
      "status": "DELIVERED"
    },
    "details": {
      "waybill_number": "MT685U91",
      "waybill_date": "2024-10-08",
      "waybill_time": "11:14:29",
      "weight": "",
      "origin": "",
      "destination": "di Kota Sukabumi",
      "shipper_name": "TOKO ALAT LUKIS (08112XXXXXX)",
      "shipper_address1": "",
      "shipper_address2": "",
      "shipper_address3": "",
    }
    "delivery_status": {
      "status": "DELIVERED",
      "pod_receiver": "FIKRI EL SARA (085668XXXXXX)",
      "pod_date": "2024-10-11",
      "pod_time": "09:26:13"
    },
    "manifest": [
      // ... more manifest
    ]
  }
}
```

### Other Useful Methods

- `getListCourier()` returns supported courier codes and names.
```php
$couriers = RajaOngkir::getListCourier();

{
  "meta": {
    "message": "Success Get List Courier",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "code": "jne",
      "name": "JNE"
    },
    {
      "code": "sicepat",
      "name": "SICEPAT"
    }
  ]
}
```

## API Reference

For comprehensive API documentation including:
- Complete method signatures with parameter types
- Detailed response structures
- Error handling examples
- Testing examples
- Best practices
- Migration guides
- Troubleshooting

Please see [API-REFERENCE.md](API-REFERENCE.md) for detailed technical documentation optimized for developers and AI assistants.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bliss Jaspis](https://github.com/blissjaspis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.