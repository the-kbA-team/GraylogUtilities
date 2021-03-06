# GraylogUtilities

[![License: MIT][license-mit]](LICENSE)
[![Packagist Version][packagist-badge]][packagist]
[![Build Status][build-status-master]][travis-ci]
[![Maintainability][maintainability-badge]][maintainability]
[![Test Coverage][coverage-badge]][coverage]

Utilities for logging.

## Usage

`composer requrire kba-team/graylog-utilities`

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
[travis-ci]: https://travis-ci.org/the-kbA-team/GraylogUtilities
[build-status-master]: https://api.travis-ci.org/the-kbA-team/GraylogUtilities.svg?branch=master
[maintainability-badge]: https://api.codeclimate.com/v1/badges/de31ab102727a0451337/maintainability
[maintainability]: https://codeclimate.com/github/the-kbA-team/GraylogUtilities/maintainability
[coverage-badge]: https://api.codeclimate.com/v1/badges/de31ab102727a0451337/test_coverage
[coverage]: https://codeclimate.com/github/the-kbA-team/GraylogUtilities/test_coverage
