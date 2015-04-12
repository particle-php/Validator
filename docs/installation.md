# Installation

Installing Particle\Validator is very easy, if you're using [composer](http://getcomposer.com). 
If you haven't done so, install composer, and use `composer require` to install Particle\Validator.

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar require particle/validator
```

## First usage

To make sure all Particle\Validator classes can be autoloaded, you have to include "vendor/autoload.php":

```php
require_once __DIR__ . '/../vendor/autoload.php';

$v = new Particle\Validator;
```
