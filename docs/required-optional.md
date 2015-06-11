# Required and allowEmpty

`Required` and `optional` are special cases within Particle\Validator. Both `required`
and `optional` will only check on the existence of the **key**, not the value. You can 
check on a value being set with "allowEmpty", which is the third parameter to both the
`required` as the `optional` methods (and false by default).

## Examples in code

A bit of code says more than a thousand words, so we'll cover all possible use-cases below.

### Validate a required value, which is not allowed to be empty

```php
$v->required('foo')->lengthBetween(0, 100);
$v->validate(['foo' => ''])->isValid(); // false, because allowEmpty is false by default.
```

### Validate a required value, which is allowed to be empty.

```php
$v->required('foo', 'foo', true); // third parameter is "allowEmpty".
$v->validate(['foo' => ''])->isValid(); // true, because allowEmpty is true.
```

### Validate an optional value, which is not allowed to be empty

```php
$v->optional('foo')->lengthBetween(0, 100);
$v->validate(['foo' => ''])->isValid(); // false, because allowEmpty is false and the key exists.
```

### Validate a non-existing optional value, which is not allowed to be empty

```php
$v->optional('foo')->lengthBetween(20, 100);
$v->validate([])->isValid(); // true, because the optional key is not present.
```

### Validate an optional value, which is allowed to be empty.

```php
$v->optional('foo', 'foo', true)->lengthBetween(0, 100);
$v->validate(['foo' => ''])->isValid(); // true, because allowEmpty is true.
```
