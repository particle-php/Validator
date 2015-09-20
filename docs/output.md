# Output structure

Particle\Validator can output its entire internal structure by means of passing that structure to
a callable. This means that outputting to a certain format is not part of the library, nor will it
ever be, these will live in separate libraries.

Outputting to your own format is quite easy:

```php
$v = new Validator;
$v->required('email')->email();
$v->optional('firstname')->allowEmpty(true)->lengthBetween(0, 20);

$output = $v->output(function (Structure $structure) {
    $output = [];

    // $structure contains all "subjects" that should be validated (e.g. email and firstname).
    foreach ($structure->getSubjects() as $subject) {
        // Subject contains all rules for the subject.

        foreach ($subject->getRules() as $rule) {
            // $rule contains information on the rule that is bound to the subject.
            $output[$subject->getKey()][] = [
                'rule' => $rule->getName(), // e.g. "Email"
                'messages' => $rule->getMessages(), // All validation messages.
                'parameters' => $rule->getParameters(), // All parameters for this rule.
            ];
        }
    }

    return json_encode($output);
});
```

This will yield the following result:

```json
{
    "email": [
        {
            "rule": "Required",
            "messages": {
                "Required::NON_EXISTENT_KEY": "{{ key }} must be provided, but does not exist"
            },
            "parameters": {
                "key": "email",
                "name": "email",
                "required": true,
                "callback": null
            }
        },
        {
            "rule": "NotEmpty",
            "messages": {
                "NotEmpty::EMPTY_VALUE": "{{ name }} must not be empty"
            },
            "parameters": {
                "key": "email",
                "name": "email",
                "allowEmpty": false,
                "callback": null
            }
        },
        {
            "rule": "Email",
            "messages": {
                "Email::INVALID_VALUE": "{{ name }} must be a valid email address"
            },
            "parameters": {
                "key": "email",
                "name": "email"
            }
        }
    ],
    "firstname": [
        {
            "rule": "Required",
            "messages": {
                "Required::NON_EXISTENT_KEY": "{{ key }} must be provided, but does not exist"
            },
            "parameters": {
                "key": "firstname",
                "name": "firstname",
                "required": false,
                "callback": null
            }
        },
        {
            "rule": "NotEmpty",
            "messages": {
                "NotEmpty::EMPTY_VALUE": "{{ name }} must not be empty"
            },
            "parameters": {
                "key": "firstname",
                "name": "firstname",
                "allowEmpty": true,
                "callback": null
            }
        },
        {
            "rule": "LengthBetween",
            "messages": {
                "LengthBetween::TOO_LONG": "{{ name }} must be shorter than {{ max }} characters",
                "LengthBetween::TOO_SHORT": "{{ name }} must be longer than {{ min }} characters"
            },
            "parameters": {
                "key": "firstname",
                "name": "firstname",
                "min": 0,
                "max": 20
            }
        }
    ]
}
```

## Outputting callbacks

Callbacks in the form of closures can not be outputted, because of the fact that there's no string
representation of the closure. If you want to output callable variables, so that you can re-use the
same logic in javascript for example, you should create a callable object that has a `__toString`
method.

For example:

```php
class Statement
{
    protected $representation;
    protected $callable;

    public function __construct($representation, Callable $callable)
    {
        $this->representation = $representation;
        $this->callable = $callable;
    }

    public function __invoke($value, array $context)
    {
        return call_user_func($this->callable, $value, $context);
    }

    public function __toString()
    {
        return $this->representation;
    }
}

$v = new Validator;
$v->required('foo')->callback(new Statement('foo !== bar', function ($value, array $context) {
    return $value !== $context['bar'];
});

$output = $v->output(function (Structure $structure) {
    $subject = $structure->getSubjects()[0];
    $rule = $subject->getRules()[2]; // 2 is the "callback" rule.
    $parameters = $rule->getParameters();

    return $parameters['callback'];
});

echo $output; // "foo !== bar".
```

Of course, you could even use a language that can be parsed by PHP and JS alike, such as
[Symfony Expression Language](http://symfony.com/doc/current/components/expression_language/index.html)
or [Hoa\Ruler](https://github.com/hoaproject/Ruler).
