<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator;

use Particle\Validator\Value\Container;

/**
 * The Rule class is the abstract parent of all rules of Particle and defines their common behaviour.
 *
 * @package Particle\Validator
 */
abstract class Rule
{
    /**
     * Contains an array of all values to be validated.
     *
     * @var array
     */
    protected $values;

    /**
     * Contains an array of messages to be returned on validation errors.
     *
     * @var array
     */
    protected $messageTemplates = [];

    /**
     * Contains a reference to the MessageStack to append errors to.
     *
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * The key we have to validate the value of.
     *
     * @var string
     */
    protected $key;

    /**
     * The name may be used in validation error messages.
     *
     * @var string
     */
    protected $name;

    /**
     * This method should validate, possibly log errors, and return the result as a boolean.
     *
     * @param mixed $value
     * @return bool
     */
    abstract public function validate($value);

    /**
     * This indicates whether or not the rule can and should break the chain it's in.
     *
     * @return bool
     */
    public function shouldBreakChain()
    {
        return false;
    }

    /**
     * Registers the message stack to append errors to.
     *
     * @param MessageStack $messageStack
     * @return $this
     */
    public function setMessageStack(MessageStack $messageStack)
    {
        $this->messageStack = $messageStack;
        return $this;
    }

    /**
     * Get all message templates for this rule
     *
     * @return array
     */
    public function getMessageTemplates()
    {
        return $this->messageTemplates;
    }

    /**
     * Sets the default parameters for each validation rule (key and name).
     *
     * @param string $key
     * @param string $name
     * @return $this
     */
    public function setParameters($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
        return $this;
    }

    /**
     * Determines whether or not the value of $key is valid in the array $values and returns the result as a bool.
     *
     * @param string $key
     * @param Container $input
     * @return bool
     */
    public function isValid($key, Container $input)
    {
        return $this->validate($input->get($key));
    }

    /**
     * Appends the error for reason $reason to the MessageStack.
     *
     * @param string $reason
     * @return bool
     */
    protected function error($reason)
    {
        $this->messageStack->append(
            $this->key,
            $reason,
            $this->getMessage($reason),
            $this->getMessageParameters()
        );

        return false;
    }

    /**
     * Return an array of all parameters that might be replaced in the validation error messages.
     *
     * @return array
     */
    public function getMessageParameters()
    {
        $name = isset($this->name) ? $this->name : str_replace('_', ' ', $this->key);

        return [
            'key' => $this->key,
            'name' => $name,
        ];
    }

    /**
     * Returns an error message for the reason $reason, or an empty string if it doesn't exist.
     *
     * @param mixed $reason
     * @return string
     */
    protected function getMessage($reason)
    {
        $messageTemplate = '';
        if (array_key_exists($reason, $this->messageTemplates)) {
            $messageTemplate = $this->messageTemplates[$reason];
        }

        return $messageTemplate;
    }
}
