# Default rules

There is a large number of default rules in Particle\Validator.

## List of rules

### alnum($allowWhitespace = false)

Validate that the value consists only of alphanumeric characters.

### alpha($allowWhitespace = false)

Validate that the value consists only of alphabetic characters.

### between($min, $max)

Validate that the value is between `$min` and `$max` (inclusive).

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

### creditCard()

Validates that the value is a valid credit card number checking for popular brand formats and using Luhn algorithm for validating the checksum.

### datetime($format = null)

Validates that the value is a date. If format is passed, it *must* be in that format.

### digits()

Validates that all characters of the value are decimal digits.

### float

Validates that the value represents a `float`.

### integer($strict = false)

Validates that the value represents a valid integer.

### email()

Validates that the value is a valid email address (format only).

### equals($value)

Validates that the value is equal to `$value`.

### greaterThan($value)

Validates that the value is greater than $value.

### inArray(array $array, $strict = true)

Validates that the value is in the array with optional "loose" checking.

### isArray()

Validates that the value is an array.

### json()

Validates that the value is a valid JSON string.

### length($length)

Validates that the value is precisely of length `$length`.

### lengthBetween($min, $max)

Validates that the length of the value is between `$min` and `$max` (inclusive).
If $max is null, it has no upper limit.

### lessThan($value)

Validates that the value is less than `$value`.

### numeric

Validates that the value is numeric (so either a `float`, or an `integer`).

### phone($countryCode)

Validates that the value is a valid phone number for `$countryCode`. Uses a library based on Google's `libphonenumber`.

### regex($regex)

Validates that the value matches the regular expression `$regex`.

### url($schemes = [])

Validates that the value is a valid URL. If the schemes array is passed, the URL must be in one of those schemes.

### required(callable $callback)

Set a callable which may be used to alter the required requirement on validation time.
This may be incredibly helpful when doing conditional validation.

### allowEmpty(callable $callback)

Set a callable which may be used to alter the allow empty requirement on validation time.
This may be incredibly helpful when doing conditional validation.
