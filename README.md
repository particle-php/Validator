# Particle\Validator

![Build status](https://travis-ci.org/particle-php/Validator.svg?branch=develop)
[![Latest Stable Version](https://poser.pugx.org/particle/validator/v/stable.svg)](https://packagist.org/packages/particle/validator) 
[![Total Downloads](https://poser.pugx.org/particle/validator/downloads.svg)](https://packagist.org/packages/particle/validator)

*Particle\Validator* is a very small validation library, with the easiest and most usable API we could possibly create.

## Small usage example

```php
$v = new Validator;
$v->required('first_name')->length(5);
$v->validate(['first_name' => 'Berry']); // bool(true).

$v->required('last_name')->length(10);
$v->validate(['first_name' => 'Berry']); // bool(false).

$v->getMessages(); // array with error messages.
```
