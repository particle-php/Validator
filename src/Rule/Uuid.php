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
class Uuid extends Regex
{
    /**
     * A constant that will be used if the value is not a valid UUIDv4.
     */
    const INVALID_UUID = 'Uuid::INVALID_UUID';

    /**
     * UUID Version 4.
     */
    const UUID_V4 = 4;

    /**
     * An array of all validation regexes.
     *
     * @var array
     */
    protected $regexes = [
        4 => '~[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}~i',      // UUIDv4 Format
    ];

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_UUID => '{{ name }} must be a valid UUID (v{{ version }})'
    ];

    /**
     * The version of the UUID you'd like to check.
     *
     * @var int
     */
    protected $version;

    /**
     * Construct the UUID validation rule.
     *
     * @param int $version
     */
    public function __construct($version = self::UUID_V4)
    {
        if (!array_key_exists($version, $this->regexes)) {
            throw new \InvalidArgumentException(
                sprintf('Unknown UUID version "%s"', $version)
            );
        }
        $this->version = $version;
        $this->regex = $this->regexes[$version];
    }

    /**
     * Validates if the value is a valid UUIDv4.
     *
     * @param string $value
     * @return bool
     */
    public function validate($value)
    {
        return $this->match($this->regex, $value, self::INVALID_UUID);
    }

    /**
     * Returns the parameters that may be used in a validation message.
     *
     * @return array
     */
    protected function getMessageParameters()
    {
        return array_merge(parent::getMessageParameters(), [
            'version' => $this->version
        ]);
    }
}
