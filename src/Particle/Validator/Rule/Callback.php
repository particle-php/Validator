<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator\Rule;

use Particle\Validator\Exception\InvalidValueException;
use Particle\Validator\Rule;

/**
 * This rule is for validating a value with a custom callback.
 *
 * @package Particle\Validator\Rule
 */
class Callback extends Rule
{
    /**
     * A constant that will be used to indicate that the callback returned false.
     */
    const INVALID_VALUE = 'Callback::INVALID_VALUE';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_VALUE => 'The value of "{{ name }}" is invalid',
    ];

    /**
     * @var callable
     */
    protected $callback;

    /**
     * Construct the Callback validator.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Validates $value by calling the callback.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        try {
            $result = call_user_func($this->callback, $value, $this->values);

            if ($result === true) {
                return true;
            }
            return $this->error(self::INVALID_VALUE);
        }
        catch (InvalidValueException $exception) {
            $reason = $exception->getIdentifier();
            $this->messageTemplates[$reason] = $exception->getMessage();

            return $this->error($reason);
        }
    }

    public function isValid($key, array $values)
    {
        $this->values = $values;
        return parent::isValid($key, $values);
    }
}
