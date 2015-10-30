<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator;

use Particle\Validator\Output\Structure;
use Particle\Validator\Output\Subject;
use Particle\Validator\Value\Container;

/**
 * Represents a collection of Rules which may break the chain of validation (but usually don't).
 *
 * @package Particle\Validator
 */
class Chain
{
    /**
     * The key we want to validate.
     *
     * @var string
     */
    protected $key;

    /**
     * The name that we can use in error messages.
     *
     * @var string
     */
    protected $name;

    /**
     * The array of all rules for this chain.
     *
     * @var Rule[]
     */
    protected $rules = [];

    /**
     * The message stack to append messages to.
     *
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * Construct the chain.
     *
     * @param string $key
     * @param string $name
     * @param bool $required
     * @param bool $allowEmpty
     */
    public function __construct($key, $name, $required, $allowEmpty)
    {
        $this->key = $key;
        $this->name = $name;

        $this->addRule(new Rule\Required($required));
        $this->addRule(new Rule\NotEmpty($allowEmpty));
    }

    /**
     * Overwrite the default __clone behaviour to make sure the rules are cloned too.
     */
    public function __clone()
    {
        $rules = [];
        foreach ($this->rules as $rule) {
            $rules[] = clone $rule;
        }
        $this->rules = $rules;
    }

    /**
     * Validate the value to consist only out of alphanumeric characters.
     *
     * @param bool $allowWhitespace
     * @return $this
     */
    public function alnum($allowWhitespace = false)
    {
        return $this->addRule(new Rule\Alnum($allowWhitespace));
    }

    /**
     * Validate that the value only consists our of alphabetic characters.
     *
     * @param bool $allowWhitespace
     * @return $this
     */
    public function alpha($allowWhitespace = false)
    {
        return $this->addRule(new Rule\Alpha($allowWhitespace));
    }

    /**
     * Validate that the value is between $min and $max (inclusive).
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function between($min, $max)
    {
        return $this->addRule(new Rule\Between($min, $max));
    }

    /**
     * Validate that the value is a boolean.
     *
     * @return $this
     */
    public function bool()
    {
        return $this->addRule(new Rule\Boolean());
    }

    /**
     * Validate by executing a callback function, and returning its result.
     *
     * @param callable $callable
     * @return $this
     */
    public function callback(callable $callable)
    {
        return $this->addRule(new Rule\Callback($callable));
    }

    /**
     * Validates that the value is a date. If format is passed, it *must* be in that format.
     *
     * @param string|null $format
     * @return $this
     */
    public function datetime($format = null)
    {
        return $this->addRule(new Rule\Datetime($format));
    }

    /**
     * Validates that all characters of the value are decimal digits.
     *
     * @return $this
     */
    public function digits()
    {
        return $this->addRule(new Rule\Digits());
    }

    /**
     * Validates a value to be a nested array, which can then be validated using a new Validator instance.
     *
     * @param callable $callback
     * @return Chain
     */
    public function each(callable $callback)
    {
        return $this->addRule(new Rule\Each($callback));
    }

    /**
     * Validates that the value is a valid email address (format only).
     * @return $this
     */
    public function email()
    {
        return $this->addRule(new Rule\Email());
    }

    /**
     * Validates that the value is equal to $value.
     *
     * @param string $value
     * @return $this
     */
    public function equals($value)
    {
        return $this->addRule(new Rule\Equal($value));
    }

    /**
     * Validates that the value is greater than $value.
     *
     * @param int $value
     * @return $this
     */
    public function greaterThan($value)
    {
        return $this->addRule(new Rule\GreaterThan($value));
    }

    /**
     * Validates that the value is in the array with optional "loose" checking.
     *
     * @param array $array
     * @param bool $strict
     * @return $this
     */
    public function inArray(array $array, $strict = true)
    {
        return $this->addRule(new Rule\InArray($array, $strict));
    }

    /**
     * Validates the value represents a valid integer
     *
     * @return $this
     * @see \Particle\Validator\Rule\Integer
     */
    public function integer()
    {
        return $this->addRule(new Rule\Integer());
    }

    /**
     * Validate the value to be of precisely length $length.
     *
     * @param int $length
     * @return $this
     */
    public function length($length)
    {
        return $this->addRule(new Rule\Length($length));
    }

    /**
     * Validates that the length of the value is between $min and $max.
     *
     * If $max is null, it has no upper limit. The default is inclusive.
     *
     * @param int $min
     * @param int|null $max
     * @return $this
     */
    public function lengthBetween($min, $max)
    {
        return $this->addRule(new Rule\LengthBetween($min, $max));
    }

    /**
     * Validates that the value is less than $value.
     *
     * @param int $value
     * @return $this
     */
    public function lessThan($value)
    {
        return $this->addRule(new Rule\LessThan($value));
    }

    /**
     * Mount a rule object onto this chain.
     *
     * @param Rule $rule
     * @return Chain
     */
    public function mount(Rule $rule)
    {
        return $this->addRule($rule);
    }

    /**
     * Validates that the value is either a integer or a float.
     *
     * @return $this
     */
    public function numeric()
    {
        return $this->addRule(new Rule\Numeric());
    }

    /**
     * Validates that the value matches the regular expression $regex.
     *
     * @param string $regex
     * @return $this
     */
    public function regex($regex)
    {
        return $this->addRule(new Rule\Regex($regex));
    }

    /**
     * Validates that the value is a valid URL. The schemes array is to selectively whitelist URL schemes.
     *
     * @param array $schemes
     * @return $this
     */
    public function url(array $schemes = [])
    {
        return $this->addRule(new Rule\Url($schemes));
    }

    /**
     * Validates that the value is a valid UUID
     *
     * @param int $version
     * @return Chain
     */
    public function uuid($version = Rule\Uuid::UUID_V4)
    {
        return $this->addRule(new Rule\Uuid($version));
    }

    /**
     * Set a callable or boolean value which may be used to alter the required requirement on validation time.
     *
     * This may be incredibly helpful when doing conditional validation.
     *
     * @param callable|bool $required
     * @return $this
     */
    public function required($required)
    {
        $this->getRequiredRule()->setRequired($required);
        return $this;
    }

    /**
     * Set a callable or boolean value which may be used to alter the allow empty requirement on validation time.
     *
     * This may be incredibly helpful when doing conditional validation.
     *
     * @param callable|bool $allowEmpty
     * @return $this
     */
    public function allowEmpty($allowEmpty)
    {
        $this->getNotEmptyRule()->setAllowEmpty($allowEmpty);
        return $this;
    }

    /**
     * Attach a representation of this Chain to the Output\Structure $structure.
     *
     * @internal
     * @param Structure $structure
     * @param MessageStack $messageStack
     * @return Structure
     */
    public function output(Structure $structure, MessageStack $messageStack)
    {
        $subject = new Subject($this->key, $this->name);

        foreach ($this->rules as $rule) {
            $rule->output($subject, $messageStack);
        }

        $structure->addSubject($subject);

        return $structure;
    }

    /**
     * Validates the values in the $values array and appends messages to $messageStack. Returns the result as a bool.
     *
     * @param MessageStack $messageStack
     * @param Container $input
     * @param Container $output
     * @return bool
     */
    public function validate(MessageStack $messageStack, Container $input, Container $output)
    {
        $valid = true;
        $output->set($this->key, $input->get($this->key));

        foreach ($this->rules as $rule) {
            $rule->setMessageStack($messageStack);
            $rule->setParameters($this->key, $this->name);

            $valid = $rule->isValid($this->key, $input) && $valid;

            if ($rule->shouldBreakChain()) {
                return $valid;
            }
        }
        return $valid;
    }

    /**
     * Shortcut method for storing a rule on this chain, and returning the chain.
     *
     * @param Rule $rule
     * @return $this
     */
    protected function addRule(Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Returns the first rule, which is always the required rule.
     *
     * @return Rule\Required
     */
    protected function getRequiredRule()
    {
        return $this->rules[0];
    }

    /**
     * Returns the second rule, which is always the allow empty rule.
     *
     * @return Rule\NotEmpty
     */
    protected function getNotEmptyRule()
    {
        return $this->rules[1];
    }
}
