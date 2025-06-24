# GraylogUtilities

[![License: MIT][license-mit]](LICENSE)
[![Packagist Version][packagist-badge]][packagist]
[![GitHub branch check runs]][github-actions]

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
[GitHub branch check runs]: https://img.shields.io/github/check-runs/the-kbA-team/GraylogUtilities/main
[github-actions]: https://github.com/the-kbA-team/GraylogUtilities/actions
