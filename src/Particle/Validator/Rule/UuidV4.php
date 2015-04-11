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
 * This rule is for validating if a the value is a valid UUIDv4.
 *
 * @package Particle\Validator\Rule
 */
class UuidV4 extends Rule
{
    /**
     * A constant that will be used if the value is not a valid UUIDv4.
     */
    const INVALID_UUIDV4 = 'UuidV4::INVALID_UUIDV4';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_UUIDV4 => 'The value of "{{ name }}" must be a valid UUIDv4'
    ];

    /**
     * Validates if the value is a valid UUIDv4.
     *
     * @param string $value
     * @return bool
     */
    public function validate($value)
    {
        $result = preg_match('~[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}~i', $value);

        if ($result === 0) {
            return $this->error(self::INVALID_UUIDV4);
        }

        return true;
    }
}
