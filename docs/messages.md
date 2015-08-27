# Overwriting the default messages

Particle\Validator knows the concept of *default messages* and *specific messages*, so that 
means that you can either overwrite the defaults, or create a specific message for a single 
validation rule:

```php
$v = new Validator;
$v->required('first_name')->lengthBetween(0, 5);
$v->required('last_name')->lengthBetween(0, 5);

$v->overwriteDefaultMessages([
    LengthBetween::TOO_LONG => 'It\'s too long, that value'
]);

$result = $v->validate([
    'first_name' => 'this is too long',
    'last_name' => 'this is also too long',
]);

$result->overwriteMessages([
    'first_name' => [
        LengthBetween::TOO_LONG => 'First name is too long, mate'
    ]
]);

var_dump($result->getMessages());
/**
 *  [
 *     'first_name' => [
 *         LengthBetween::TOO_LONG => 'First name is too long, mate'
 *     ],
 *     'last_name' => [
 *         LengthBetween::TOO_LONG => 'It\'s too long, that value'
 *     ]
 * ];
 */
```
