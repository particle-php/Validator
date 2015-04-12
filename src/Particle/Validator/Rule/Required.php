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
use Particle\Validator\Value\Container;

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
     * Indicates if the value is required.
     *
     * @var bool
     */
    protected $required;

    /**
     * Optionally contains a callable to overwrite the required requirement on time of validation.
     *
     * @var callable
     */
    protected $requiredCallback;

    /**
     * Indicates if the value can be empty.
     *
     * @var bool
     */
    protected $allowEmpty;

    /**
     * Optionally contains a callable to overwrite the allow empty requirement on time of validation.
     *
     * @var callable
     */
    protected $allowEmptyCallback;

    /**
     * Contains the input container.
     *
     * @var Container
     */
    protected $input;

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
     * Does nothing, because validity is determined in isValid.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        return true;
    }

    /**
     * Determines whether or not the key is set when required, and if there is a value if allow empty is false.
     *
     * @param string $key
     * @param Container $input
     * @return bool
     */
    public function isValid($key, Container $input)
    {
        $this->required = $this->isRequired($input);
        $this->allowEmpty = $this->hasAllowEmpty($input);

        if (!$input->has($key)) {
            $this->shouldBreak = true;
            return $this->required ? $this->error(self::NON_EXISTENT_KEY) : true;
        }

        if (!$this->allowEmpty && strlen($input->get($key)) === 0) {
            $this->shouldBreak = true;
            return $this->error(self::EMPTY_VALUE);
        }

        return $this->validate(true);
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

    /**
     * Determines if the value is required.
     *
     * @param Container $input
     * @return bool
     */
    protected function isRequired(Container $input)
    {
        if (isset($this->requiredCallback)) {
            $this->required = call_user_func_array($this->requiredCallback, [$input->getArrayCopy()]);
        }
        return $this->required;
    }

    /**
     * Determines if the value may be empty.
     *
     * @param Container $input
     * @return bool
     */
    public function hasAllowEmpty(Container $input)
    {
        if (isset($this->allowEmptyCallback)) {
            $this->allowEmpty = call_user_func_array($this->allowEmptyCallback, [$input->getArrayCopy()]);
        }
        return $this->allowEmpty;
    }
}
