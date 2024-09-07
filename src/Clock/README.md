# S.S.S. Framework Shibare - Clock Component

[PSR-20: Clock](https://www.php-fig.org/psr/psr-20/) Implementation.

## Usage

```sh
$ composer require shibare/clock
```

```php
<?php

require_once 'vendor/autoload.php';

// Get current system clock
var_dump((new \Shibare\Clock\SystemClock())->now());
object(DateTimeImmutable)#4 (3) {
  ["date"]=>
  string(26) "2024-07-28 13:27:54.274642"
  ["timezone_type"]=>
  int(3)
  ["timezone"]=>
  string(3) "UTC"
}

// Fix time(for instance: Request-time on before-http-middleware)
\Shibare\Clock\GlobalClock::setGlobalClock(new \Shibare\Clock\SystemClock());
sleep(1);
var_dump(\Shibare\Clock\global_now());
object(DateTimeImmutable)#4 (3) {
  ["date"]=>
  string(26) "2024-07-28 13:27:54.274642"
  ["timezone_type"]=>
  int(3)
  ["timezone"]=>
  string(3) "UTC"
}
```
