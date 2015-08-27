# Multi-field validation

There are situations where you need to use multiple values in your validation. A common use case is having two different
password fields and need to ensure both are identical. 

## Callback validator

The first option you can use is the callback validator. The callable that you pass to the callback validator receives 
two parameters. The first parameter is the value to be validated, and the second is an array of all values that are
being validated. In that way, you can compare the values:

```php
$v = new Validator;
$v->required('password')->callback(function ($value, $values) {
    if ($value !== $values['confirm_password']) {
        throw new Particle\Validator\Exception\InvalidValueException(
            'Passwords do not match',
            'password'
        );
    }
    return true;
});
```

## Custom rule

Callback validators are excellent for validations that go beyond the default rules, but are not easy to re-use. If you
want to repeatedly use the same custom validation, you can extend Particle Validator to add new rules. Inside your
custom rule, you also have access to all other values. Given the above example of comparing two password fields, the
custom rule would look something like this:

```php
use Particle\Validator\Rule;

class ConfirmedPasswordRule extends Rule
{
    const NO_MATCH = 'ConfirmedPasswordRule::NO_MATCH';
    
    protected $messageTemplates = [
        self::NO_MATCH => 'Passwords do not match',
    ];
    
    public function validate($value)
    {
        if ($value !== $this->values['confirm_password']) {
            return $this->error(self::NO_MATCH);
        }
        return true;
    }    
}
```

Notice the usage of `$this->values` in this validate method, which can be used to access other values that are up
for validation.
