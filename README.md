![image](https://cloud.githubusercontent.com/assets/6495166/7207286/8b48105e-e538-11e4-9dfa-97c7fb2398aa.png)
===

[![Travis-CI](https://img.shields.io/travis/particle-php/Validator/master.svg)](https://travis-ci.org/particle-php/Validator)
[![Packagist](https://img.shields.io/packagist/v/particle/validator.svg)](https://packagist.org/packages/particle/validator)
[![Packagist downloads](https://img.shields.io/packagist/dt/particle/validator.svg)](https://packagist.org/packages/particle/validator)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/particle-php/Validator.svg)](https://scrutinizer-ci.com/g/particle-php/Validator/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/coverage/g/particle-php/Validator/master.svg)](https://scrutinizer-ci.com/g/particle-php/Validator/?branch=master)

*Particle\Validator* is a very small validation library, with the easiest and most usable API we could possibly create.

## Small usage example

```php
$v = new Validator;
$v->required('first_name')->length(5);
$v->isValid(['first_name' => 'Berry']); // bool(true).

$v->required('last_name')->length(10);
$v->isValid(['first_name' => 'Berry']); // bool(false).

$v->getMessages(); // array with error messages.
```

## Features

* Validate an array of data and get an array of error messages
* Override the error messages
* Get the validated data array
* Validate different contexts (insert, update, ect)
* IDE auto-completion for easy development

===

Find more information and advanced usage examples at [validator.particle-php.com](http://validator.particle-php.com)
