# Default rules

There is a large number of default rules in Particle\Validator.

## List of rules

### alnum($allowWhitespace = false)

Validate the value to consist only out of alphanumeric characters.

### alpha($allowWhitespace = false)

Validate that the value only consists our of alphabetic characters.

### between($min, $max)

Validate that the value is between $min and $max (inclusive).

### bool()

Validate that the value is a boolean value.

### callback(callable $callable)

Validate by executing a callback function, and returning its result.

If you want to add more than one callback validator, you might want to have different error messages
as well. This is supported by throwing an Exception of type "InvalidValueException". Small example:

```php
$v = new Validator;
$v->required('userId')->callback(function ($value) {
    if (!getUserFromDb($value)) {
        throw new Particle\Validator\Exception\InvalidValueException(
            'Unable to find the user with id ' . $value,
            'userId'
        );
    }
    return true;
});
```


### datetime($format = null)

Validates that the value is a date. If format is passed, it *must* be in that format.

### digits()

Validates that all characters of the value are decimal digits.

### integer()

Validates the value represents a valid integer

### email()

Validates that the value is a valid email address (format only).

### equals($value)

Validates that the value is equal to $value.

### inArray(array $array, $strict = true)

Validates that the value is in the array with optional "loose" checking.

### length($length)

Validate the value to be of precisely length $length.

### lengthBetween($min, $max)

Validates that the length of the value is between $min and $max (inclusive).
If $max is null, it has no upper limit.

### numeric

Validates that the value is numeric (so either a float, or an integer).

### regex($regex)

Validates that the value matches the regular expression $regex.

### url()

Validates that the value is a valid URL.

### required(callable $callback)

Set a callable which may be used to alter the required requirement on validation time.
This may be incredibly helpful when doing conditional validation.

### allowEmpty(callable $callback)

Set a callable which may be used to alter the allow empty requirement on validation time.
This may be incredibly helpful when doing conditional validation.
