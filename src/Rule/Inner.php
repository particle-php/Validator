<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Particle (http://particle-php.com)
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
class Inner extends Rule
{
    const NOT_AN_ARRAY = 'Inner::NOT_AN_ARRAY';

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
     * Validates if $value is array, validate inner array of $value, and return result.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if (!is_array($value)) {
            return $this->error(self::NOT_AN_ARRAY);
        }

        $validator = new Validator();

        call_user_func($this->callback, $validator, $value);

        $result = $validator->validate($value);

        if (!$result->isValid()) {
            $this->handleError($result);
            return false;
        }

        return true;
    }

    /**
     * @param ValidationResult $result
     */
    protected function handleError($result)
    {
        foreach ($result->getFailures() as $failure) {
            $failure->overwriteKey(
                sprintf('%s.%s', $this->key, $failure->getKey())
            );

            $this->messageStack->append($failure);
        }
    }
}
