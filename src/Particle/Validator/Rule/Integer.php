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
 * This rule is for validating if a value represents an integer.
 *
 * @package Particle\Validator\Rule
 */
class Integer extends Rule
{
    /**
     * A constant that will be used when the value does not represent an integer value.
     */
    const NOT_AN_INTEGER = 'Integer::NOT_AN_INTEGER';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_AN_INTEGER => '{{ name }} must be an integer',
    ];

    /**
     * Validates if $value represents an integer.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if (false !== filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }
        return $this->error(self::NOT_AN_INTEGER);
    }
}
