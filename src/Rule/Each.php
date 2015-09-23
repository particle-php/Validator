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
use Particle\Validator\ValidationResult;
use Particle\Validator\Validator;

/**
 * This rule is for validating nested arrays.
 *
 * @package Particle\Validator\Rule
 */
class Each extends Rule
{
    const NOT_AN_ARRAY = 'Each::NOT_AN_ARRAY';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_AN_ARRAY => '{{ name }} must be an array',
    ];

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Validates if each character in $value is a decimal digit.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if (!is_array($value)) {
            return $this->error(self::NOT_AN_ARRAY);
        }

        $result = true;
        foreach ($value as $index => $innerValue) {
            $result = $this->validateValue($index, $innerValue) && $result;
        }
    }

    protected function validateValue($index, $value)
    {
        $innerValidator = new Validator();

        call_user_func($this->callback, $innerValidator);

        $result = $innerValidator->validate($value);

        if (!$innerValidator->validate($value)->isValid()) {
            $this->handleError($index, $result);
            return false;
        }

        return true;
    }

    /**
     * @param mixed $index
     * @param ValidationResult $result
     * @return bool
     */
    protected function handleError($index, $result)
    {
        foreach ($result->getMessages() as $field => $messages) {
            $index = sprintf('%s.%s.%s', $this->key, $index, $field);

            foreach ($messages as $reason => $message) {
                $this->messageStack->append($index, $reason, $message, []);
            }
        }
        return false;
    }
}
