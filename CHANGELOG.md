# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Optional `last_phone_number` parameter on `getWaybill()` for courier AWB confirmation (required by JNE)

## [2.1.0] - 2026-05-22

### Added

- `RajaOngkirClient` contract for dependency injection
- `RajaOngkirResponse` DTO for typed API responses (`meta`, `data`, `status()`, `successful()`)
- `RajaOngkirException` for HTTP and API errors with `statusCode` and `response` payload
- Config options: `timeout`, `retry_times`, `retry_sleep`, and `fake` mode for local development
- HTTP client factory injection (no direct facade dependency inside `RajaOngkir`)
- Explicit `illuminate/http` dependency for HTTP client support
- PHPStan (level 6) with Larastan for static analysis
- PHP Parallel Lint for syntax validation
- GitHub Actions CI workflow with separate `lint` and `tests` jobs
- PHPUnit configuration template (`phpunit.xml.dist`)
- Pint configuration (`pint.json`)
- Publish tag `rajaongkir-komerce-config` for configuration file
- Container and facade integration tests (singleton, config merge, publish)
- Composer scripts: `analyse`, `lint`, `format:check`, `test:lint`, and `check`
- README badges (Tests, Packagist version, downloads, license)
- Packagist metadata (`keywords`, `homepage`, `support`) in `composer.json`
- Documentation index at `docs/_index.md`
- Tests for response parsing, exceptions, and contract binding
- Log warning when `RAJAONGKIR_API_KEY` is not configured (skipped when `fake` mode is enabled)

### Changed

- **BREAKING**: API methods now return `RajaOngkirResponse` instead of `array`
- Facade resolves `RajaOngkirClient` contract
- `getListCourier()` remains a static `array<string, string>` (sourced from documentation, not an API endpoint)
- `RajaOngkir` now receives `apiKey` and `baseUrl` via constructor injection instead of reading `config()` internally
- `RajaOngkirServiceProvider` registers the client as a singleton with an explicit container binding
- Improved PHPDoc and return types across `src/` for PHPStan compatibility
- Updated README with requirements table, dependency injection examples, error handling, and development tooling docs
- Default config publish command now uses `--tag=rajaongkir-komerce-config`

### Fixed

- PHPUnit tests no longer expect `Content-Type` header on GET requests
- Test methods now declare `: void` return types

## [2.0.0] - 2026-03-30

### Added

- Added support for Laravel 13
- Added `getListCourier()` method to return supported courier codes and names
- Added API reference documentation (`API-REFERENCE.md`) for comprehensive technical documentation
- Added migration guide for transitioning from step-by-step to direct search method

### Changed

- **BREAKING**: Dropped support for Laravel 10
- **BREAKING**: Minimum PHP version requirement increased from 8.1 to 8.2
- Updated `illuminate/console` and `illuminate/support` to support versions ^11.0 || ^12.0 || ^13.0
- Updated `orchestra/testbench` to ^10.0 || ^11.0
- Updated `phpunit/phpunit` to ^12.0 || ^13.0
- Updated `laravel/pint` to ^1.29

### Fixed

- Fixed Content-Type header handling in `sendRequest()` method - now only sets `application/x-www-form-urlencoded` for POST requests

## [1.2.0] - 2025-02-10

### Added

- Initial support for both step-by-step and direct search methods
- Support for Laravel 10, 11, and 12
- Domestic and international destination search
- Domestic and international shipping cost calculation
- Waybill tracking support
- Hierarchical location lookup (province → city → district → subdistrict)

### Changed

- Refactored `sendRequest()` method for better header handling

## [1.1.0] - 2025-01-15

### Added

- Basic RajaOngkir API wrapper functionality
- Facade support for easy Laravel integration
- Service provider for automatic package discovery

## [1.0.0] - 2025-01-01

### Added

- Initial release

[Unreleased]: https://github.com/blissjaspis/laravel-rajaongkir-komerce/compare/v2.1.0...HEAD
[2.1.0]: https://github.com/blissjaspis/laravel-rajaongkir-komerce/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/blissjaspis/laravel-rajaongkir-komerce/compare/v1.2.0...v2.0.0
[1.2.0]: https://github.com/blissjaspis/laravel-rajaongkir-komerce/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/blissjaspis/laravel-rajaongkir-komerce/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/blissjaspis/laravel-rajaongkir-komerce/releases/tag/v1.0.0
