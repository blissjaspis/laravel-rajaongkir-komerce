# Contributing to Laravel RajaOngkir Komerce

Thank you for considering contributing to this package! We welcome contributions from the community.

## How to Contribute

### Reporting Issues

If you find a bug or have a suggestion:

1. Check if the issue already exists in the [issue tracker](../../issues)
2. If not, create a new issue with:
   - A clear title and description
   - Steps to reproduce (for bugs)
   - Expected vs actual behavior
   - Your environment details (PHP version, Laravel version, package version)

### Pull Requests

1. Fork the repository
2. Create a new branch for your feature or fix: `git checkout -b feature/your-feature-name`
3. Make your changes
4. Run tests: `composer test`
5. Run code formatting: `composer format`
6. Commit your changes with a clear message
7. Push to your fork and submit a pull request

### Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/laravel-rajaongkir-komerce.git
cd laravel-rajaongkir-komerce

# Install dependencies
composer install

# Run tests
composer test

# Run code formatting
composer format
```

### Code Style

This package follows the Laravel Pint code style. Please run `composer format` before submitting your pull request.

### Testing

All new features should include tests. We use PHPUnit for testing:

```bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/Unit/RajaOngkirTest.php
```

### Commit Messages

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Reference issues and pull requests where appropriate

## Security

If you discover a security vulnerability, please review our [security policy](../../security/policy) on how to report it responsibly.

## Questions?

Feel free to open an issue for questions or join discussions.

Thank you for contributing!
