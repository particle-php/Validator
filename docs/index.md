# Particle\Validator

Particle\Validator is a small and elegant validation library, with an extremely clean API 
which makes validation **fun**!

## Quick usage example

```php
use Particle\Validator\Validator;

$validator = new Validator;
$validator->required('first_name')->lengthBetween(2, 30)->alpha();
$validator->required('last_name')->lengthBetween(2, 40)->alpha();

$validator->overwriteMessages([
    'last_name' => [
        LengthBetween::TOO_LONG => 'Your name is too long.'
    ]
]);

$data = [
    'first_name' => 'John',
    'last_name' => 'Doe',
];

$validator->validate($data); // bool(true)
```

## Why Particle\Validator?

Validation - when done right - will increase the usability of your software, as it will give 
descriptive error messages to a user. Additionally, it will make sure your data is consistent,
 effectively lowering the number of bugs in your software.

A good portion of the development time of every software project is being spent on writing 
validation rules. As such, it would make sense to want a validation library that has the following
design goals:

 - **A clear API**, so you understand it just by looking at it, and adding a rule is a breeze.
 - IDE-supported **code-completion**, so you don't have to look at documentation to write a rule.
 - **Well-documented**, so that if you *do* go to the documentation, you don't have to search for long.
 - **Extensible**, so you can simply add your own rules, and error messages.
 - **Well-tested**, so that you're absolutely sure that you can rely on the validation rules.
 - **Zero** dependencies, so that you can use it in *any* PHP project.
 
Although there are many validation libraries out there, they seem to lack in one or more of the
above design goals.
