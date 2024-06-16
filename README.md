[contributors-shield]: https://img.shields.io/github/contributors/jobmetric/laravel-ban-ips.svg?style=for-the-badge
[contributors-url]: https://github.com/jobmetric/laravel-ban-ips/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/jobmetric/laravel-ban-ips.svg?style=for-the-badge&label=Fork
[forks-url]: https://github.com/jobmetric/laravel-ban-ips/network/members
[stars-shield]: https://img.shields.io/github/stars/jobmetric/laravel-ban-ips.svg?style=for-the-badge
[stars-url]: https://github.com/jobmetric/laravel-ban-ips/stargazers
[license-shield]: https://img.shields.io/github/license/jobmetric/laravel-ban-ips.svg?style=for-the-badge
[license-url]: https://github.com/jobmetric/laravel-ban-ips/blob/master/LICENCE.md
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-blue.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://linkedin.com/in/majidmohammadian

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]

# Ban Ip for laravel

This is an annoying IP management package for Laravel that you can use in your projects.

## Install via composer

Run the following command to pull in the latest version:
```bash
composer require jobmetric/laravel-ban-ips
```

## Documentation

To use the services of this package, please follow the instructions below.

### Migrate

After installing the package, you must migrate by running the following command:

```bash
php artisan migrate
```

### Usage

To use the services of this package, you must use the `BanIp` class in your model.

### Store Ban Ip

To store a ban ip, you can use the following code:

```php
use JobMetric\BanIp\Facades\BanIp;

BanIp::store([
    'ip' => 'sample ip',
    'type' => 'hacker',
    'reason' => 'This is a sample reason',
    'banned_at' => now(),
    'expire_at' => now()->addDays(1),
]);
```

> Note:
> 
> The `ip` field is required and must be a string.
> 
> The `type` field is required and must be one of the following values: `hacker`, `spammer`, `bot`, `another`.
> 
> The `reason` field is required and must be a string.
> 
> The `banned_at` field is required and must be a date.
> 
> The `expire_at` field is required and must be a date greater than the `banned_at` field.

### Update Ban Ip

To update a ban ip, you can use the following code:

```php
use JobMetric\BanIp\Facades\BanIp;

BanIp::update([
    'type' => 'hacker',
    'reason' => 'This is a sample reason',
    'banned_at' => now(),
    'expire_at' => now()->addDays(1),
]);
```

> Note:
> 
> The `type` field is sometimes and must be one of the following values: `hacker`, `spammer`, `bot`, `another`.
> 
> The `reason` field is sometimes and must be a string.
> 
> The `banned_at` field is sometimes and must be a date.
> 
> The `expire_at` field is sometimes and must be a date greater than the `banned_at` field.

### Delete Ban Ip

To delete a ban ip, you can use the following code:

```php
use JobMetric\BanIp\Facades\BanIp;

BanIp::delete(/* ban ip id */);
```

### Delete Expired Ban Ip

To delete expired ban ip, you can use the following code:

```php
use JobMetric\BanIp\Facades\BanIp;

BanIp::deleteExpired();
```

## Helper Functions

This package contains several helper functions that you can use as follows:

- `storeBanIp`: This function is used to store a ban ip.
- `updateBanIp`: This function is used to update a ban ip.
- `deleteBanIp`: This function is used to delete a ban ip.
- `deleteExpiredBanIp`: This function is used to delete expired ban ip.

## Rules

There are some rules for using this package:

- `BanIpExistRule`: This rule is used to check if the IP is banned.

## Ban Type

There can be various reasons for the IP ban:

- `hacker`: This type of ban is used for hackers.
- `spammer`: This type of ban is used for spammers.
- `bot`: This type of ban is used for bots.
- `another`: This type of ban is used for other reasons.

## Commands

This package contains several commands that you can use as follows

| Command                | Description                                                                        |
|------------------------|------------------------------------------------------------------------------------|
| `ban-ip:remove`        | Remove ban ip expire time. (This command is executed every minute in your Laravel) |

## Events

This package contains several events for which you can write a listener as follows

| Event              | Description                                     |
|--------------------|-------------------------------------------------|
| `BanIpStoredEvent` | This event is called after storing the ban ip.  |
| `BanIpUpdateEvent` | This event is called after updating the ban ip. |
| `BanIpDeleteEvent` | This event is called after delete the ban ip.   |

## Contributing

Thank you for considering contributing to the Laravel Ban Ip! The contribution guide can be found in the [CONTRIBUTING.md](https://github.com/jobmetric/laravel-ban-ips/blob/master/CONTRIBUTING.md).

## License

The MIT License (MIT). Please see [License File](https://github.com/jobmetric/laravel-ban-ips/blob/master/LICENCE.md) for more information.
