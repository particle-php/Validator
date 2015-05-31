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
 * This rule is for validating if a the value is a valid URL.
 *
 * @package Particle\Validator\Rule
 */
class Url extends Rule
{
    /**
     * A constant that will be used if the value is not a valid URL.
     */
    const INVALID_URL = 'Url::INVALID_URL';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_URL => '{{ name }} must be a valid URL'
    ];

    /**
     * Validates if the value is a valid URL.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $url = filter_var($value, FILTER_VALIDATE_URL);

        if ($url !== false) {
            return true;
        }
        return $this->error(self::INVALID_URL);
    }
}
