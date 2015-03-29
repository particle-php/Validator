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
 * This rule checks if the value consists solely out of alphabetic characters.
 *
 * @package Particle\Validator\Rule
 */
class Alpha extends Rule
{
    /**
     * A constant that will be used for the error message when the value contains non-alphabetic characters.
     */
    const NOT_ALPHA = 'Alpha::NOT_ALPHA';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_ALPHA => 'The value of "{{ name }}" can only consist out of alphabetic characters'
    ];

    /**
     * @var bool
     */
    protected $allowWhitespace;

    /**
     * Construct the Alpha rule.
     *
     * @param bool $allowWhitespace
     */
    public function __construct($allowWhitespace)
    {
        $this->allowWhitespace = (bool) $allowWhitespace;
    }

    /**
     * Checks whether $value consists solely out of alphabetic characters.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $regex = $this->allowWhitespace ? '~^[\p{L}\s]*$~iu' : '~^[\p{L}]*$~ui';

        if (preg_match($regex, $value) === 0) {
            return $this->error(self::NOT_ALPHA);
        }
        return true;
    }
}
