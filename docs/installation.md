# Installation

Installing Particle\Validator is very easy, if you're using [composer](http://getcomposer.com). 
If you haven't done so, install composer, and use **composer require** to install Particle\Validator.

```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar require particle/validator
```

## First usage

Make sure you include `vendor/autoload.php` in your application, and then create your first validator 
instance:

```php
use Particle\Validator\Validator;

$validator = new Validator;
$validator->required('first_name')->lengthBetween(0, 20);
$validator->optional('age')->integer();
```
