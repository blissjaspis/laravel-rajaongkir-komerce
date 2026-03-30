# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
