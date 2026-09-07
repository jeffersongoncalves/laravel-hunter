<div class="filament-hidden">

![Laravel Hunter](https://raw.githubusercontent.com/jeffersongoncalves/laravel-hunter/main/art/jeffersongoncalves-laravel-hunter.png)

</div>

# Laravel Hunter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-hunter.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-hunter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-hunter/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-hunter/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-hunter/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-hunter/actions?query=workflow%3A%22Fix+PHP+code+styling%22+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-hunter.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-hunter)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-hunter.svg?style=flat-square)](LICENSE.md)

A Laravel client for the [Hunter.io](https://hunter.io) v2 API. A fluent `Hunter` facade covers email finding, email verification, domain search, and lead/campaign management, authenticates every request with the `api_key` query parameter, and throws a `HunterException` on a non-2xx response instead of returning a silent error array.

## Features

- **Domain Search** — `domainSearch()`, `emailCount()`
- **Email Finder & Verifier** — `findEmail()`, `verifyEmail()`
- **Account** — `accountInfo()`
- **Leads** — `listLeads()`, `getLead()`, `createLead()`, `deleteLead()`
- **Campaigns** — `listCampaigns()`, `getCampaign()`, `startCampaign()`, `pauseCampaign()`
- **Leads Lists** — `listLeadsLists()`, `getLeadsList()`
- **Thin by design** — every method returns the raw decoded JSON response as an array, no DTOs
- **Fails loud** — required parameters throw `InvalidArgumentException`; a non-2xx API response throws `HunterException` carrying the API's error message and HTTP status code

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-hunter
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="hunter-config"
```

## Configuration

Add to your `.env`:

```env
HUNTER_API_KEY=your-api-key
```

Find your API key at [https://hunter.io/api-keys](https://hunter.io/api-keys).

### Config Options

```php
// config/hunter.php
return [
    'api_key' => env('HUNTER_API_KEY'),
    'base_url' => env('HUNTER_BASE_URL', 'https://api.hunter.io/v2'),
];
```

## Usage

```php
use JeffersonGoncalves\Hunter\Facades\Hunter;
use JeffersonGoncalves\Hunter\Exceptions\HunterException;
```

### Domain Search

```php
Hunter::domainSearch('example.com', limit: 10, type: 'personal');
Hunter::emailCount('example.com', type: 'personal');
```

### Email Finder & Verifier

```php
Hunter::findEmail('example.com', 'Jane', 'Doe');
Hunter::verifyEmail('jane@example.com');
```

### Account

```php
Hunter::accountInfo();
```

### Leads

```php
Hunter::listLeads(limit: 10, offset: 0);
Hunter::getLead('lead-id');
Hunter::createLead('jane@example.com', firstName: 'Jane', lastName: 'Doe', company: 'Acme');
Hunter::deleteLead('lead-id');
```

### Campaigns

```php
Hunter::listCampaigns(limit: 10, offset: 0);
Hunter::getCampaign('campaign-id');
Hunter::startCampaign('campaign-id');
Hunter::pauseCampaign('campaign-id');
```

### Leads Lists

```php
Hunter::listLeadsLists(limit: 10, offset: 0);
Hunter::getLeadsList('list-id');
```

### Handling errors

```php
try {
    $result = Hunter::findEmail('example.com', 'Jane', 'Doe');
} catch (HunterException $e) {
    // $e->getMessage() — the API's errors[0].details, or the raw response body
    // $e->statusCode  — the HTTP status code returned by Hunter.io
} catch (InvalidArgumentException $e) {
    // a required parameter was empty
}
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Formatting

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Simão Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
