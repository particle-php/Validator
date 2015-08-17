# Extending Validator

Extending Particle\Validator leads to some boilerplate code, because of the fact that the 
rules actually exist as methods on a "Chain" object, because we want IDE-supported code-completion. 
So, in order to write your own rules, you'll need to overwrite two classes: the Validator 
itself, and the Chain.

Overwriting the Validator itself is quite simple:

```php
use Particle\Validator\Validator;

/**
 * @method MyChain required()
 * @method MyChain optional()
 */
class MyValidator extends Validator
{
    /**
     * {@inheritdoc}
     * @return MyChain
     */
    public function buildChain($key, $name, $required, $allowEmpty)
    {
        return new MyChain($key, $name, $required, $allowEmpty);
    }
}
```

As you can see, it returns a different implementation of the Chain object, and that's where 
you can add the the rules to the Chain. Luckily, also overwriting the chain object itself is
rather simple:

```php
use Particle\Validator\Chain;

class MyChain extends Chain
{
    /**
     * @return $this
     */
    public function grumpy($who = 'Grumpy Smurf')
    {
        return $this->addRule(new GrumpyRule($who));
    }
}
```

So we've exposed a new public method to the chain: `grumpy`. However, that rule doesn't exist
in the default validator, so we have to build it:

```php
use Particle\Validator\Rule;

class GrumpyRule extends Rule
{
    const WRONG = 'GrumpyRule::WRONG';
    
    protected $messageTemplates = [
        self::WRONG => '{{ who }} hates the value of "{{ name }}"',
    ];
    
    protected $who;
    
    public function __construct($who)
    {
        $this->who = $who;
    }

    public function validate($value)
    {
        if ($value !== null || $value === null) { // always true, so always grumpy!
            return $this->error(self::WRONG);
        }
        return true;
    }
    
    // these variables can be used in error messages.
    protected function getMessageParameters()
    {
        return array_merge(parent::getMessageParameters, [
            'who' => $this->who,
        ]);
    }
}
```

All that's left is actually using your own validator:

```php
$v = new MyValidator;
$v->required('foo')->grumpy('Silly sally');
$result = $v->validate(['foo' => true]);

// output: 'Silly Sally hates the value of "foo"'
echo $result->getMessages()['foo'][Grumpy::WRONG]; 
```

That's that: you can now go wild on adding rules. If you think a rule should be added to the main
Particle\Validator repository, please create a pull request (or an issue).
