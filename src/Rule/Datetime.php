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
 * This Rule is for validating a date/time.
 *
 * @package Particle\Validator\Rule
 */
class Datetime extends Rule
{
    /**
     * A constant that will be used when an invalid date/time is passed.
     */
    const INVALID_VALUE = 'DateTime::INVALID_VALUE';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_VALUE => '{{ name }} must be a valid date',
    ];

    /**
     * @var string
     */
    protected $format;

    /**
     * Construct the Datetime validator.
     *
     * @param string $format
     */
    public function __construct($format = null)
    {
        $this->format = $format;
    }

    /**
     * Validates if $value is in the correct date / time format and that it's a valid date.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if (!($this->datetime($value, $this->format) instanceof \DateTime)) {
            return $this->error(self::INVALID_VALUE);
        }
        return true;
    }

    /**
     * Takes $value and $format and attempts to build a valid DateTime object with it.
     *
     * @param string $value
     * @param string $format
     * @return \DateTime|false
     */
    protected function datetime($value, $format = null)
    {
        if ($format !== null) {
            $dateTime = date_create_from_format($format, $value);
            if ($dateTime instanceof \DateTime
                && $dateTime->getLastErrors()['warning_count'] === 0
                && $this->isFormatRespected($format, $dateTime, $value)
            ) {
                return $dateTime;
            }
            return false;
        }
        return @date_create($value);
    }

    /**
     * checks if the value respects the format passed to the rule.
     *
     * @param string $format the format passed to DateTime
     * @param \DateTime $dateTime
     * @param $value the value to be checked
     * @return bool
     */
    protected function isFormatRespected($format, \DateTime $dateTime, $value)
    {
        return $dateTime->format($format) === $value;
    }
}
