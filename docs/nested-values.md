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

$result = $v->validate($values);
$result->getValues() === $values; // bool(true)
```

## Working with the each validator

In addition to validating multi-dimensional arrays, it's also possible to validate a repeating
nested array (and even a repeating nested array in that array, etc):

```php
$values = [
    'invoices' => [
        [
            'id' => 1, 
            'date' => '2015-10-28',
            'amount' => 2500
            'lines' => [
                [
                    'amount' => 500,
                    'description' => 'First line',
                ],
                [
                    'amount' => 2000,
                    'description' => 'Second line',
                ],
            ],
        ],
        [
            'id' => 2, 
            'date' => '2015-11-28',
            'amount' => 2000
            'lines' => [
                [
                    'amount' => 2000,
                    'description' => 'Second line of second invoice',
                ],
            ],
        ],
    ],
];

$v = new Validator();

$v->required('invoices')->each(function (Validator $validator) {
    $validator->required('id')->integer();
    $validator->required('amount')->integer();
    $validator->required('date')->datetime('Y-m-d');
    
    $validator->each('lines', function (Validator $validator) {
         $validator->required('amount')->integer();
         $validator->required('description')->lengthBetween(0, 100);
    });
});

$result = $v->validate($values);
$result->isValid(); // bool(true)
$result->getValues() === $values; // bool(true)
```
