# Laravel RajaOngkir From Komerce

> **Note**
> This package supports Laravel versions 10, 11, and 12.

This package provides a simple and easy-to-use Laravel wrapper for the RajaOngkir Komerce API.

## Installation

You can install the package via composer:

```bash
composer require bliss-jaspis/laravel-rajaongkir-komerce
```

You must publish the configuration file with:

```bash
php artisan vendor:publish --provider="BlissJaspis\RajaOngkir\Providers\RajaOngkirServiceProvider" --tag="config"
```

This will create a `config/rajaongkir-komerce.php` file in your `config` directory.

Add the following to your `.env` file:

```env
RAJAONGKIR_API_KEY=your-api-key
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
```

## Usage

You can use this package by injecting the `BlissJaspis\RajaOngkir\RajaOngkir` class into your controller or service.

```php
use BlissJaspis\RajaOngkir\Facades\RajaOngkir;

class YourController
{
    // ...

    public function getProvinces()
    {
        $provinces = RajaOngkir::getProvinces();

        return $provinces;
    }
}
```

### Available Methods

- `getProvinces()`
- `getCity(string $provinceId)`
- `getDistrict(string $cityId)`
- `getSubDistrict(string $districtId)`
- `getWaybill(string $waybill, string $courier)`
- `getCostDomestic(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')`
- `getCostInternational(string $origin, string $destination, int $weight, string $courier, string $filter = 'lowest')`
- `searchDomestic(string $search, int $limit = 10, int $offset = 0)`
- `searchInternational(string $search, int $limit = 10, int $offset = 0)`

### Example

Here is an example of how to get all provinces:

```php
$provinces = $this->rajaOngkir->getProvinces();
```

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