# Included validation-rules

Particle\Validator tries to provide you the most common validations. An overview is listed below. If you want to add custom validators, take a look at the callback validation-rule, or check out "Extending the Validator" in the menu.

* [allowEmpty](#allowempty)(callable $callback)
* [alnum](#alnum)($allowWhitespace = false)
* [alpha](#alpha)($allowWhitespace = false)
* [between](#between)($min, $max)
* [bool](#bool)()
* [callback](#callback)(callable $callable)
* [creditCard](#creditcard)()
* [datetime](#datetime)($format = null)
* [digits](#digits)()
* [each](#email)(callable $callable)
* [email](#email)()
* [equals](#equals)($value)
* [float](#float)()
* [greaterThan](#greaterthan)($value)
* [hash](#hash)($hashAlgorithm, $allowUppercase = false)
* [inArray](#inarray)(array $array, $strict = true)
* [integer](#integer)($strict = false)
* [isArray](#isarray)()
* [json](#json)()
* [length](#length)($length)
* [lengthBetween](#lengthbetween)($min, $max)
* [lessThan](#lessthan)($value)
* [numeric](#numeric)()
* [phone](#phone)($countryCode)
* [regex](#regex)($regex)
* [required](#required)(callable $callback)
* [string](#string)()
* [url](#url)($schemes = [])
* [uuid](#uuid)($version = Uuid::VALID_FORMAT)

## allowEmpty

Set a callable which may be used to alter the allow empty requirement on validation time.
This may be incredibly helpful when doing conditional validation.

```php
$v = new Validator;
$v->required('name')->allowEmpty(function (array $values) {
    return $values['namePresent'] === true;
});
$v->validate(['namePresent' => true, 'name' => 'John'])->isValid(); // true
$v->validate(['namePresent' => true])->isValid(); // true
$v->validate(['namePresent' => false])->isValid(); // false
```

[back to the top](#included-validation-rules)

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

[back to the top](#included-validation-rules)

## alpha

Validate that the value consists only of alphabetic characters (a-z, A-Z).

```php
$v = new Validator;
$v->required('name')->alpha();
$v->validate(['name' => 'Jonh'])->isValid(); // true
$v->validate(['name' => 'Jonh1'])->isValid(); // false
```

It's also possible to allow spaces in the string:

```php
$v = new Validator;
$v->required('name')->alpha(Rule\Alpha::ALLOW_SPACES);
$v->validate(['name' => 'Jonh is the best'])->isValid(); // true
$v->validate(['name' => 'Jonh number 1'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## between

Validate that the value is between `$min` and `$max` (inclusive).

```php
$v = new Validator;
$v->required('age')->between(16, 70);
$v->validate(['age' => 16])->isValid(); // true
$v->validate(['age' => 70])->isValid(); // true
$v->validate(['age' => 71])->isValid(); // false
```

[back to the top](#included-validation-rules)

## bool

Validate that the value is a boolean value.

```php
$v = new Validator;
$v->required('newsletter')->bool();
$v->validate(['newsletter' => true])->isValid(); // true
$v->validate(['newsletter' => 'true'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## callback

Validate by executing a callback function, and returning its result.

If you want to add more than one callback validator, you might want to have different error messages
as well. This is supported by throwing an Exception of type "InvalidValueException". Small example:

```php
$v = new Validator;
$v->required('userId')->callback(function ($value) {
    if (!getUserFromDb($value)) { // non Particle\Validator function
        throw new Particle\Validator\Exception\InvalidValueException(
            'Unable to find the user with id ' . $value,
            'userId'
        );
    }
    return true;
});
```

[back to the top](#included-validation-rules)

## creditCard

Validates that the value is a valid credit card number checking for popular brand formats and using Luhn algorithm for validating the checksum.

> **Note:** If you want to use this rule, you must install the `byrokrat/checkdigit` package.
> Run `composer require byrokrat/checkdigit`.

```php
$v = new Validator;
$v->required('card')->creditCard();
$v->validate(['card' => '4532815084485002'])->isValid(); // true
$v->validate(['card' => '123412341234aaaa'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## datetime

Validates that the value is a date.

```php
$v = new Validator;
$v->required('datetime')->datetime();
$v->validate(['datetime' => '2015-03-29 16:11:09'])->isValid(); // true
$v->validate(['datetime' => '08:00'])->isValid(); // true
$v->validate(['datetime' => 'pizza'])->isValid(); // false
```

If format is passed, it *must* be in that format.

```php
$v = new Validator;
$v->required('datetime')->datetime('Y-m-d H:i:s');
$v->validate(['datetime' => '2015-03-29 16:11:09'])->isValid(); // true
$v->validate(['datetime' => '2015-03-29 16:11'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## digits

Validates that all characters of the value are decimal digits.

```php
$v = new Validator;
$v->required('amount')->digits();
$v->validate(['amount' => '1234567890'])->isValid(); // true
$v->validate(['amount' => '133.7'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## each

The each rule applies rules to a repeated, nested array. Check out the
[using nested arrays](http://validator.particle-php.com/en/latest/nested-values/) page for more
information on this rule.

```php
$v = new Validator;
$v->required('lines')->each(function (Validator $lineValidator) {
    $lineValidator->required('price')->float();
});
$v->validate([
    'lines' => [
        ['price' => 5.50],
        ['price' => 2.55],
    ]
])->isValid(); // true
```

[back to the top](#included-validation-rules)

## email

Validates that the value is a valid email address (format only).

```php
$v = new Validator;
$v->required('email')->email();
$v->validate(['email' => 'john@test.org'])->isValid(); // true
$v->validate(['email' => 'john@test.'])->isValid(); // false
$v->validate(['email' => '@test.org'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## equals

Validates that the value is equal to `$value`.

```php
$v = new Validator;
$v->required('value')->equals(500);
$v->validate(['value' => 500])->isValid(); // true
$v->validate(['value' => 499])->isValid(); // false
$v->validate(['value' => '500'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## float

Validates that the value represents a `float`.

```php
$v = new Validator;
$v->required('x')->float();
$v->validate(['x' => 0.5])->isValid(); // true
$v->validate(['x' => 0])->isValid(); // false
```

[back to the top](#included-validation-rules)

## greaterThan

Validates that the value is greater than $value.

```php
$v = new Validator;
$v->required('value')->greaterThan(9000);
$v->validate(['value' => 9001])->isValid(); // true
$v->validate(['value' => 9000])->isValid(); // false
```

[back to the top](#included-validation-rules)

## hash

Validates that the value is a valid cryptographic hash according to the chosen hashing algorithm. The second parameter may allow uppercase characters in the hashes.
Supported algorithms include:

- `Hash::ALGO_MD5`
- `Hash::ALGO_SHA1`
- `Hash::ALGO_SHA256`
- `Hash::ALGO_SHA512`
- `Hash::ALGO_CRC32`

```php
$v = new Validator;
$v->required('key')->hash(Rule\Hash::ALGO_MD5);
$v->validate(['key' => md5('key')])->isValid(); // true
$v->validate(['key' => 'a8jf0a4'])->isValid(); // false
```

If you want to allow uppercase characters, you need to set `$allowUppercase` to `true`.

```php
$v = new Validator;
$v->required('key')->hash(Rule\Hash::ALGO_MD5, Rule\Hash::ALLOW_UPPERCASE);
$v->validate(['key' => strtoupper(md5('key'))])->isValid(); // true
$v->validate(['key' => 'A8Jf0A4'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## inArray

Validates that the value is in the array with optional "loose" checking.

```php
$v = new Validator;
$v->required('type')->inArray([1, 2, 3]);
$v->validate(['type' => 2])->isValid(); // true
$v->validate(['type' => '2'])->isValid(); // false
```

You can make the in array check looser bij setting strict to false:

```php
$v = new Validator;
$v->required('type')->inArray([1, 2, 3], Rule\InArray::NOT_STRICT);
$v->validate(['type' => 2])->isValid(); // true
$v->validate(['type' => '2'])->isValid(); // true
$v->validate(['type' => 4])->isValid(); // false
```

[back to the top](#included-validation-rules)

## integer

Validates that the value represents a valid integer.

```php
$v = new Validator;
$v->required('x')->integer();
$v->validate(['x' => 3])->isValid(); // true
$v->validate(['x' => '3'])->isValid(); // true
$v->validate(['x' => 3.5])->isValid(); // false
```

You can also do a strict check on integer:

```php
$v = new Validator;
$v->required('x')->integer(Rule\Integer::STRICT);
$v->validate(['x' => -3])->isValid(); // true
$v->validate(['x' => '-3'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## isArray

Validates that the value is an array.

```php
$v = new Validator;
$v->required('data')->isArray();
$v->validate(['data' => []])->isValid(); // true
$v->validate(['data' => 'array'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## json

Validates that the value is a valid JSON string.

```php
$v = new Validator;
$v->required('data')->json();
$v->validate(['data' => '{"name": "John"}'])->isValid(); // true
$v->validate(['data' => '{"name: "John"}'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## length

Validates that the value is precisely of length `$length`.

```php
$v = new Validator;
$v->required('code')->length(4);
$v->validate(['code' => '1234'])->isValid(); // true
$v->validate(['code' => '123'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## lengthBetween

Validates that the length of the value is between `$min` and `$max` (inclusive).

```php
$v = new Validator;
$v->required('code')->lengthBetween(4, 6);
$v->validate(['code' => '123456'])->isValid(); // true
$v->validate(['code' => '1234567'])->isValid(); // false
```

If $max is `null`, it has no upper limit.

```php
$v = new Validator;
$v->required('password')->lengthBetween(8, null);
$v->validate(['password' => 'ThisIsALongAndProperP4ssW0rdWithNoMaxLength'])->isValid(); // true
$v->validate(['password' => '1234567'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## lessThan

Validates that the value is less than `$value`.

```php
$v = new Validator;
$v->required('value')->lessThan(9000);
$v->validate(['value' => 8999])->isValid(); // true
$v->validate(['value' => 9000])->isValid(); // false
```

[back to the top](#included-validation-rules)

## numeric

Validates that the value is numeric (so either a `float`, or an `integer`).

```php
$v = new Validator;
$v->required('value')->numeric();
$v->validate(['value' => 5])->isValid(); // true
$v->validate(['value' => 5.5])->isValid(); // true
$v->validate(['value' => '5'])->isValid(); // true
$v->validate(['value' => 'a'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## phone

Validates that the value is a valid phone number for `$countryCode`. Uses a library based on Google's `libphonenumber`.

> **Note:** If you want to use this rule, you must install the `giggsey/libphonenumber-for-php` package.
> Run `composer require giggsey/libphonenumber-for-php`.

```php
$v = new Validator;
$v->required('phone')->phone('BR')
$v->validate(['phone' => '11-3675-3801'])->isValid(); // true
$v->validate(['phone' => '3675381'])->isValid(); // false

$v = new Validator;
$v->required('phone')->phone('US')
$v->validate(['phone' => '(305) 634-5000'])->isValid(); // true
$v->validate(['phone' => '3-6733-3062'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## regex

Validates that the value matches the regular expression `$regex`.

```php
$v = new Validator;
$v->required('name')->regex('/^john/i');
$v->validate(['name' => 'John'])->isValid(); // true
$v->validate(['name' => 'Jane'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## required

Set a callable which may be used to alter the required requirement on validation time.
This may be incredibly helpful when doing conditional validation.

```php
$v = new Validator;
$v->optional('name')->required(function (array $values) {
    return $values['forceName'] === true;
});
$v->validate(['forceName' => true, 'name' => 'John'])->isValid(); // true
$v->validate(['forceName' => true])->isValid(); // false
$v->validate(['forceName' => false])->isValid(); // true
```

[back to the top](#included-validation-rules)

## string

Validates that the value represents a `string`.

```php
$v = new Validator;
$v->required('name')->string();
$v->validate(['name' => 'John'])->isValid(); // true
$v->validate(['name' => 123])->isValid(); // false
```

[back to the top](#included-validation-rules)

## url

Validates that the value is a valid URL.

```php
$v = new Validator;
$v->required('link')->url();
$v->validate(['link' => 'http://validator.particle-php.com'])->isValid(); // true
$v->validate(['link' => 'http://validator.particle-php'])->isValid(); // false
```

If the schemes array is passed, the URL must be in one of those schemes.

```php
$v = new Validator;
$v->required('link')->url(['ftp://', 'ftps://']);
$v->validate(['link' => 'ftp://particle-php.com'])->isValid(); // true
$v->validate(['link' => 'http://validator.particle-php.com'])->isValid(); // false
```

[back to the top](#included-validation-rules)

## uuid

Validates if the value is a valid UUID and of the given version. The version constant may be combined with other constants to allow multiple versions or the NIL UUID (all zeroes).

```php
use Particle\Validator\Rule\Uuid;
$v = new Validator;

// Requires a UUID V4
$v->required('userId')->uuid(Uuid::UUID_V4);
$v->validate(['uuid' => '44c0ffee-988a-49dc-8bad-a55c0de2d1e4'])->isValid(); // true
$v->validate(['uuid' => '00000000-0000-0000-0000-000000000000'])->isValid(); // false

// Requires a UUID V4 or NIL UUID
$v->required('userId')->uuid(Uuid::UUID_V4 | Uuid::UUID_NIL);
$v->validate(['uuid' => '44c0ffee-988a-49dc-8bad-a55c0de2d1e4'])->isValid(); // true
$v->validate(['uuid' => '00000000-0000-0000-0000-000000000000'])->isValid(); // true
```

[back to the top](#included-validation-rules)
