# GraylogUtilities

[![License: MIT][license-mit]](LICENSE)
[![Packagist Version][packagist-badge]][packagist]
[![GitHub branch check runs]][github-actions]

Utilities for logging.

## Usage

`composer require kba-team/graylog-utilities`

## Development

The library supports PHP 7.4 and PHP 8.0–8.4. Install dependencies for a
specific PHP version with Docker, then run the checks using that PHP version:

```sh
make install PHP_VERSION=8.4
make lint test analyze validate audit
```

Use `make install PHP_VERSION=7.4 DEPENDENCIES_LOWEST=1` to check the lowest
allowed dependency versions. `make clean` removes installed dependencies and
Composer/PHPUnit artifacts. The GitHub Actions matrix runs these checks across
the supported PHP versions.

### LogTypes

```php
<?php
$logTypes = new \kbATeam\GraylogUtilities\LogTypes();
$logTypes->add(\Psr\Log\LogLevel::ALERT);
$logTypes->add(\Psr\Log\LogLevel::CRITICAL);
var_dump($logTypes->get());
```
Output:
```
array(2) {
  [0]=>
  string(5) "alert"
  [1]=>
  string(8) "critical"
}
```

### Obfuscator

```php
<?php
$obfuscator = new \kbATeam\GraylogUtilities\Obfuscator();
$obfuscator->addKey('password');
$data = [
    'foo' => 'bar',
    'password' => 'secret'
];
var_dump($obfuscator->obfuscate($data));
```
Output:
```
array(2) {
  ["foo"]=>
  string(3) "bar"
  ["password"]=>
  string(6) "********"
}
```

[license-mit]: https://img.shields.io/badge/license-MIT-blue.svg
[packagist-badge]: https://img.shields.io/packagist/v/kba-team/graylog-utilities
[packagist]: https://packagist.org/packages/kba-team/graylog-utilities
[GitHub branch check runs]: https://img.shields.io/github/check-runs/the-kbA-team/GraylogUtilities/main
[github-actions]: https://github.com/the-kbA-team/GraylogUtilities/actions
