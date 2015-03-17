<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator\Rule;

use Particle\Validator\Rule;

/**
 * This class is responsible for checking if a required value is set, and if
 * a value is not allowed to be empty, also check if the value isn't empty.
 *
 * @package Particle\Validator\Rule
 */
class Required extends Rule
{
    /**
     * The error code when a required field doesn't exist.
     */
    const NON_EXISTENT_KEY = 'Required::NON_EXISTENT_KEY';

    /**
     * The error code for when a value is empty while this is not allowed.
     */
    const EMPTY_VALUE = 'Required::EMPTY_VALUE';

    /**
     * The templates for the possible messages this validator can return.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NON_EXISTENT_KEY => 'The key "{{ key }}" is required, but does not exist',
        self::EMPTY_VALUE => 'The value of "{{ name }}" can not be empty'
    ];

    /**
     * Denotes whether or not the chain should be stopped after this rule.
     *
     * @var bool
     */
    protected $shouldBreak;

    /**
     * Optionally contains a callable to overwrite the required requirement on time of validation.
     *
     * @var callable
     */
    protected $requiredCallback;

    /**
     * Optionally contains a callable to overwrite the allow empty requirement on time of validation.
     *
     * @var callable
     */
    protected $allowEmptyCallback;

    /**
     * Construct the Required validator.
     *
     * @param bool $required
     * @param bool $allowEmpty
     */
    public function __construct($required, $allowEmpty)
    {
        $this->required = $required;
        $this->allowEmpty = $allowEmpty;
    }

    /**
     * @return bool
     */
    public function shouldBreakChain()
    {
        return $this->shouldBreak;
    }

    /**
     * Determines whether or not the key is set when required, and if there is a value if allow empty is false.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if (isset($this->requiredCallback)) {
            $this->required = call_user_func_array($this->requiredCallback, [$this->values]);
        }

        if (isset($this->allowEmptyCallback)) {
            $this->allowEmpty = call_user_func_array($this->allowEmptyCallback, [$this->values]);
        }

        if ($this->required && !array_key_exists($value, $this->values)) {
            $this->shouldBreak = true;
            return $this->error(self::NON_EXISTENT_KEY);
        }

        if (!$this->required && !array_key_exists($value, $this->values)) {
            $this->shouldBreak = true;
            return true;
        }

        if (!$this->allowEmpty && strlen($this->values[$value]) === 0) {
            $this->shouldBreak = true;
            return $this->error(self::EMPTY_VALUE);
        }

        return true;
    }

    /**
     * Overwrite of the normal isValid, because the Required validator has to check on key, not just value.
     *
     * @param string $key
     * @param array $values
     * @return bool
     */
    public function isValid($key, array $values)
    {
        $this->values = $values;

        return $this->validate($key);
    }

    /**
     * Set a callable to potentially alter the required requirement at the time of validation.
     *
     * This may be incredibly useful for conditional validation.
     *
     * @param callable $required
     * @return $this
     */
    public function setRequired(callable $required)
    {
        $this->requiredCallback = $required;
        return $this;
    }

    /**
     * Set a callable to potentially alter the allow empty requirement at the time of validation.
     *
     * This may be incredibly useful for conditional validation.
     *
     * @param callable $allowEmpty
     * @return $this
     */
    public function setAllowEmpty(callable $allowEmpty)
    {
        $this->allowEmptyCallback = $allowEmpty;
        return $this;
    }
}
