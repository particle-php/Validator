# Working with nested values

Particle\Validator can validate multi-dimensional arrays, using a specific notation. You may use it
as demonstrated below. Validator will also return a multi-dimensional array when you request the 
validated values.

```php
$values = [
    'user' => [
        'username' => 'bob', 
    ]
];

$v = new Validator;
$v->required('user.username')->alpha();

$v->validate($values); // bool(true)
$v->getValues() === $values; /// bool(true)
```
