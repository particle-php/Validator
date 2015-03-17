<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator;

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

        $this->addRule(new Rule\Required($required, $allowEmpty));
    }

    /**
     * Validate the value to be of precisely length $length.
     *
     * @param int $length
     * @return Chain
     */
    public function length($length)
    {
        return $this->addRule(new Rule\Length($length));
    }

    /**
     * Set a callable which may be used to alter the required requirement on validation time.
     *
     * This may be incredibly helpful when doing conditional validation.
     *
     * @param callable $callback
     * @return $this
     */
    public function required(callable $callback)
    {
        $this->rules[0]->setRequired($callback);

        return $this;
    }

    /**
     * Set a callable which may be used to alter the allow empty requirement on validation time.
     *
     * This may be incredibly helpful when doing conditional validation.
     *
     * @param callable $callback
     * @return $this
     */
    public function allowEmpty(callable $callback)
    {
        $this->rules[0]->setAllowEmpty($callback);
        return $this;
    }

    /**
     * Validates the values in the $values array and appends messages to $messageStack. Returns the result as a bool.
     *
     * @param MessageStack $messageStack
     * @param array $values
     * @return bool
     */
    public function validate(MessageStack $messageStack, array $values)
    {
        $valid = true;
        foreach ($this->rules as $rule) {
            $rule->setMessageStack($messageStack);
            $rule->setParameters($this->key, $this->name);

            $valid = $rule->isValid($this->key, $values) && $valid;

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
}
