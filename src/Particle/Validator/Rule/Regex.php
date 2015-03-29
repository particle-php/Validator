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
 * This rule is for validating that the value matches a certain regex.
 *
 * @package Particle\Validator\Rule
 */
class Regex extends Rule
{
    /**
     * A constant that will be used when the value doesn't match the regex.
     */
    const NO_MATCH = 'Regex::NO_MATCH';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH => 'The value of "{{ name }}" is invalid'
    ];

    /**
     * The regex that should be matched.
     *
     * @var string
     */
    protected $regex;

    /**
     * Construct the Regex rule.
     *
     * @param string $regex
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Validates that the value matches the predefined regex.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $result = preg_match($this->regex, $value);

        if ($result === 0) {
            return $this->error(self::NO_MATCH);
        }

        return true;
    }
}
