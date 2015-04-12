# Default rules

There is a large number of default rules in Particle\Validator.

## Required and allowEmpty

Required and optional are special cases within Particle\Validator. They are only about whether or not the *key* is 
required, not the value. In case of a non-required value, you may use allowEmpty, which is the third parameter to
`required` and `optional`.

## List of rules

### alnum($allowWhitespace = false)

Validate the value to consist only out of alphanumeric characters.

### alpha($allowWhitespace = false)

Validate that the value only consists our of alphabetic characters.

### between($min, $max, $inclusive = true)

Validate that the value is between $min and $max (inclusive by default).

### callback(callable $callable)

Validate by executing a callback function, and returning its result.

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

### lengthBetween($min, $max, $inclusive = true)

Validates that the length of the value is between $min and $max.
If $max is null, it has no upper limit. The default is inclusive.

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
