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
 * This rule checks if the value consists solely out of alphanumeric characters.
 *
 * @package Particle\Validator\Rule
 */
class Alnum extends Rule
{
    /**
     * A constant that will be used for the error message when the value is not alphanumeric.
     */
    const NOT_ALNUM = 'Alnum::NOT_ALNUM';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_ALNUM => 'The value of "{{ name }}" can only consist out of numeric and alphabetic characters'
    ];

    /**
     * Indicates whether or not this rule should accept spaces.
     *
     * @var bool
     */
    protected $allowSpaces;

    /**
     * Construct the validation rule.
     *
     * @param bool $allowSpaces
     */
    public function __construct($allowSpaces)
    {
        $this->allowSpaces = (bool) $allowSpaces;
    }

    /**
     * Checks whether $value consists solely out of alphanumeric characters.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $pattern = $this->allowSpaces ? '~^[\p{L}0-9\s]*~iu' : '~^[\p{L}0-9]*$~iu';

        if (preg_match($pattern, $value) === 0) {
            return $this->error(self::NOT_ALNUM);
        }
        return true;
    }
}
