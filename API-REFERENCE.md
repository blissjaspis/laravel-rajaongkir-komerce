# Laravel RajaOngkir API Reference (LLM-Friendly)

> **This document is optimized for AI assistants and developers who need comprehensive technical documentation.**

## Overview

This package provides a Laravel wrapper for the RajaOngkir Komerce API with two address lookup methods:
- **Hierarchical Method**: Step-by-step selection (province → city → district → sub-district)
- **Direct Search Method**: Keyword-based search with autocomplete

## Installation

```bash
composer require bliss-jaspis/laravel-rajaongkir-komerce
```

## Configuration

### Environment Variables

```env
RAJAONGKIR_API_KEY=your-api-key-here
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
```

### Publish Config

```bash
php artisan vendor:publish --provider="BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider" --tag="config"
```

## API Reference

All methods are accessible via the facade:

```php
use BlissJaspis\RajaOngkir\Facades\RajaOngkir;
```

### Core Methods

#### Location Data (Hierarchical Method)

##### `getProvinces(): array`
Returns all provinces in Indonesia.

**Response Structure:**
```json
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
    }
  ]
}
```

**Example:**
```php
$provinces = RajaOngkir::getProvinces();
```

##### `getCity(int $provinceId): array`
Returns cities within a province.

**Parameters:**
- `$provinceId` (int): Province ID from getProvinces()

**Response Structure:**
```json
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
    }
  ]
}
```

**Example:**
```php
$cities = RajaOngkir::getCity(11);
```

##### `getDistrict(int $cityId): array`
Returns districts within a city.

**Parameters:**
- `$cityId` (int): City ID from getCity()

**Response Structure:**
```json
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
  ]
}
```

**Example:**
```php
$districts = RajaOngkir::getDistrict(1);
```

##### `getSubDistrict(int $districtId): array`
Returns sub-districts within a district.

**Parameters:**
- `$districtId` (int): District ID from getDistrict()

**Response Structure:**
```json
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
  ]
}
```

**Example:**
```php
$subdistricts = RajaOngkir::getSubDistrict(1);
```

#### Direct Search Methods

##### `searchDomestic(string $search, int $limit = 10, int $offset = 0): array`
Search for domestic destinations by keyword.

**Parameters:**
- `$search` (string): Search keyword (location name)
- `$limit` (int, optional): Number of results (default: 10, max: 100)
- `$offset` (int, optional): Pagination offset (default: 0)

**Response Structure:**
```json
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

**Example:**
```php
$results = RajaOngkir::searchDomestic('Jakarta Pusat', 5);
```

##### `searchInternational(string $search, int $limit = 10, int $offset = 0): array`
Search for international destinations by keyword.

**Parameters:**
- `$search` (string): Search keyword (country name)
- `$limit` (int, optional): Number of results (default: 10, max: 100)
- `$offset` (int, optional): Pagination offset (default: 0)

**Response Structure:**
```json
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
  ]
}
```

**Example:**
```php
$results = RajaOngkir::searchInternational('Malaysia', 5);
```

#### Cost Calculation

##### `getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): array`
Calculate domestic shipping costs.

**Parameters:**
- `$origin` (string): Origin sub-district ID
- `$destination` (string): Destination sub-district ID
- `$weight` (int): Package weight in grams
- `$courier` (string): Courier code (jne, sicepat, etc.)
- `$filter` (string, optional): 'lowest' or 'highest' price (default: 'lowest')

**Response Structure:**
```json
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
    }
  ]
}
```

**Example:**
```php
$cost = RajaOngkir::getCostDomestic('1', '108', 1000, 'jne', 'lowest');
```

##### `getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest'): array`
Calculate international shipping costs.

**Parameters:**
- `$origin` (string): Origin sub-district ID
- `$destination` (string): Destination country ID
- `$weight` (int): Package weight in grams
- `$courier` (string): Courier code for international shipping
- `$filter` (string, optional): 'lowest' or 'highest' price (default: 'lowest')

**Response Structure:**
```json
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
    }
  ]
}
```

**Example:**
```php
$cost = RajaOngkir::getCostInternational('1', '108', 1000, 'jne', 'lowest');
```

#### Tracking

##### `getWaybill(string $waybill, string $courier): array`
Track package by waybill number.

**Parameters:**
- `$waybill` (string): Tracking number/AWB
- `$courier` (string): Courier code

**Response Structure:**
```json
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
      "shipper_address3": ""
    },
    "delivery_status": {
      "status": "DELIVERED",
      "pod_receiver": "FIKRI EL SARA (085668XXXXXX)",
      "pod_date": "2024-10-11",
      "pod_time": "09:26:13"
    },
    "manifest": [
      // ... tracking history
    ]
  }
}
```

**Example:**
```php
$tracking = RajaOngkir::getWaybill('JT123456789', 'jne');
```

#### Utility Methods

##### `getListCourier(): array`
Returns array of supported courier codes and names.

**Return Structure:**
```php
[
  "jne" => "JNE",
  "sicepat" => "SICEPAT",
  "ide" => "IDE",
  // ... more couriers
]
```

**Example:**
```php
$couriers = RajaOngkir::getListCourier();
```

## Response Format Standards

### Success Response
```json
{
  "meta": {
    "message": "Success message",
    "code": 200,
    "status": "success"
  },
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "meta": {
    "message": "Error message",
    "code": 400,
    "status": "error"
  },
  "data": null
}
```

## Error Handling

```php
use Illuminate\Http\Client\RequestException;

try {
    $result = RajaOngkir::getProvinces();

    if ($result['meta']['status'] !== 'success') {
        throw new \Exception($result['meta']['message']);
    }

    return $result['data'];
} catch (RequestException $e) {
    $statusCode = $e->response->status();

    switch ($statusCode) {
        case 401:
            throw new \Exception('Invalid API key');
        case 429:
            throw new \Exception('Rate limit exceeded');
        default:
            throw new \Exception('API request failed');
    }
} catch (\Exception $e) {
    throw new \Exception('RajaOngkir service error: ' . $e->getMessage());
}
```

## Common Error Codes

- `400` - Bad Request (invalid parameters)
- `401` - Unauthorized (invalid API key)
- `404` - Not Found (invalid location IDs)
- `429` - Too Many Requests (rate limited)
- `500` - Internal Server Error

## Practical Examples

### Complete Checkout Flow

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BlissJaspis\RajaOngkir\Facades\RajaOngkir;

class CheckoutController extends Controller
{
    public function getShippingCost(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string'
        ]);

        try {
            $cost = RajaOngkir::getCostDomestic(
                $request->origin,
                $request->destination,
                $request->weight,
                $request->courier
            );

            return response()->json([
                'success' => true,
                'data' => $cost['data']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping cost'
            ], 500);
        }
    }
}
```

### Address Search with Autocomplete

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BlissJaspis\RajaOngkir\Facades\RajaOngkir;

class AddressController extends Controller
{
    public function searchDomestic(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        try {
            $results = RajaOngkir::searchDomestic($request->query, 10);

            return response()->json([
                'success' => true,
                'data' => $results['data']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }
}
```

## Testing Examples

```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use BlissJaspis\RajaOngkir\Facades\RajaOngkir;

class RajaOngkirTest extends TestCase
{
    public function test_can_get_provinces()
    {
        $provinces = RajaOngkir::getProvinces();

        $this->assertEquals('success', $provinces['meta']['status']);
        $this->assertIsArray($provinces['data']);
        $this->assertGreaterThan(0, count($provinces['data']));
    }

    public function test_domestic_search_works()
    {
        $results = RajaOngkir::searchDomestic('Jakarta');

        $this->assertEquals('success', $results['meta']['status']);
        $this->assertIsArray($results['data']);
    }

    public function test_shipping_cost_calculation()
    {
        $cost = RajaOngkir::getCostDomestic('1', '2', 1000, 'jne');

        $this->assertEquals('success', $cost['meta']['status']);
        $this->assertIsArray($cost['data']);
        $this->assertArrayHasKey('cost', $cost['data'][0]);
    }
}
```

## Best Practices

### Performance Optimization

1. **Cache location data:**
```php
$provinces = Cache::remember('rajaongkir.provinces', 86400, function () {
    return RajaOngkir::getProvinces();
});
```

2. **Use appropriate search limits:**
```php
$results = RajaOngkir::searchDomestic($query, 20); // Reasonable limit
```

### Rate Limiting

- Implement request throttling
- Cache frequently accessed data
- Use background jobs for bulk operations

## Migration Guide

### From Old RajaOngkir API

1. **Update method calls:**
```php
// Old
$rajaOngkir = new RajaOngkir();
$result = $rajaOngkir->province();

// New
$result = RajaOngkir::getProvinces();
```

2. **Handle response format changes:**
```php
// Old response format
$provinces = $result['rajaongkir']['results'];

// New response format
$provinces = $result['data'];
```

3. **Update configuration:**
```php
// Old config
RAJAONGKIR_API_KEY=your-old-key

// New config
RAJAONGKIR_API_KEY=your-komerce-key
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
```

## Troubleshooting

### Common Issues

#### 401 Unauthorized Error
- Verify API key in `.env`
- Check key is active in RajaOngkir Komerce dashboard

#### 404 Not Found Error
- Verify location IDs exist
- Check ID format (string vs integer)

#### Empty Search Results
- Use more specific search terms
- Try different location name variations

#### Rate Limiting (429)
- Implement caching
- Add delays between requests
- Use background jobs for bulk operations

## Supported Couriers

```php
$couriers = [
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
```