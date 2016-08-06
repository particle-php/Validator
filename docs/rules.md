# Included validaton-rules

Particle\Validator tries to provide you the most common validations. An overview is listed below. If you want to add custom validators, take a look at the callback validation-rule, or check out "Extending the Validator" in the menu.

* [alnum](#alnum)($allowWhitespace = false)
* [alpha](#alpha)($allowWhitespace = false)
* [between](#between)($min, $max)
* [bool](#bool)()
* [callback](#callback)(callable $callable)
* [creditCard](#creditcard)()
* [datetime](#datetime)($format = null)
* [digits](#digits)()
* [float](#float)()
* [integer](#integer)($strict = false)
* [email](#email)()
* [equals](#equals)($value)
* [greaterThan](#greaterthan)($value)
* [hash](#hash)($hashAlgorithm, $allowUppercase = false)
* [inArray](#inarray)(array $array, $strict = true)
* [isArray](#isarray)()
* [json](#json)()
* [length](#length)($length)
* [lengthBetween](#lengthbetween)($min, $max)
* [lessThan](#lessthan)($value)
* [numeric](#numeric)()
* [phone](#phone)($countryCode)
* [regex](#regex)($regex)
* [string](#string)()
* [url](#url)($schemes = [])
* [required](#required)(callable $callback)
* [allowEmpty](#allowempty)(callable $callback)

## alnum

Validate that the value consists only of alphanumeric characters (a-z, A-Z, 0-9).

```php
$v = new Validator;
$v->required('name')->alnum();
$v->validate(['name' => 'Jonh01'])->isValid(); // true
$v->validate(['name' => 'Jonh!'])->isValid(); // false
```

It's also possible to allow spaces in the string:

```php
$v = new Validator;
$v->required('name')->alnum(Rule\Alnum::ALLOW_SPACES);
$v->validate(['name' => 'Jonh number 1'])->isValid(); // true
$v->validate(['name' => 'Jonh #1'])->isValid(); // false
```

## alpha

Validate that the value consists only of alphabetic characters.

```php
// @todo: code example
```

## between

Validate that the value is between `$min` and `$max` (inclusive).

```php
// @todo: code example
```

## bool

Validate that the value is a boolean value.

```php
// @todo: code example
```

## callback

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

## creditCard

Validates that the value is a valid credit card number checking for popular brand formats and using Luhn algorithm for validating the checksum.

**Note:** If you want to use this rule, you must install the `byrokrat/checkdigit` package.
Run `composer require byrokrat/checkdigit`.

```php
// @todo: code example
```

## datetime

Validates that the value is a date. If format is passed, it *must* be in that format.

```php
// @todo: code example
```

## digits

Validates that all characters of the value are decimal digits.

```php
// @todo: code example
```

## float

Validates that the value represents a `float`.

```php
// @todo: code example
```

## integer

Validates that the value represents a valid integer.

```php
// @todo: code example
```

## email

Validates that the value is a valid email address (format only).

```php
// @todo: code example
```

## equals

Validates that the value is equal to `$value`.

```php
// @todo: code example
```

## greaterThan

Validates that the value is greater than $value.

```php
// @todo: code example
```

## hash

Validates that the value is a valid cryptographic hash according to the chosen hashing algorithm. The second parameter may allow uppercase characters in the hashes.
Supported algorithms include Hash::ALGO_MD5, Hash::ALGO_SHA1, Hash::ALGO_SHA256, Hash::ALGO_SHA512 and Hash::ALGO_CRC32.

```php
// @todo: code example
```

## inArray

Validates that the value is in the array with optional "loose" checking.

```php
// @todo: code example
```

## isArray

Validates that the value is an array.

```php
// @todo: code example
```

## json

Validates that the value is a valid JSON string.

```php
// @todo: code example
```

## length

Validates that the value is precisely of length `$length`.

```php
// @todo: code example
```

## lengthBetween

Validates that the length of the value is between `$min` and `$max` (inclusive).
If $max is null, it has no upper limit.

```php
// @todo: code example
```

## lessThan

Validates that the value is less than `$value`.

```php
// @todo: code example
```

## numeric

Validates that the value is numeric (so either a `float`, or an `integer`).

```php
// @todo: code example
```

## phone

Validates that the value is a valid phone number for `$countryCode`. Uses a library based on Google's `libphonenumber`.

**Note:** If you want to use this rule, you must install the `giggsey/libphonenumber-for-php` package.
Run `composer require giggsey/libphonenumber-for-php`.

```php
// @todo: code example
```

## regex

Validates that the value matches the regular expression `$regex`.

```php
// @todo: code example
```

## string

Validates that the value represents a `string`.

```php
// @todo: code example
```

## url

Validates that the value is a valid URL. If the schemes array is passed, the URL must be in one of those schemes.

```php
// @todo: code example
```

## required

Set a callable which may be used to alter the required requirement on validation time.
This may be incredibly helpful when doing conditional validation.

```php
// @todo: code example
```

## allowEmpty

Set a callable which may be used to alter the allow empty requirement on validation time.
This may be incredibly helpful when doing conditional validation.

```php
// @todo: code example
```
