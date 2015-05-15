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
 * This rule will validate a value to be between min and max.
 *
 * @package Particle\Validator\Rule
 */
class Between extends Rule
{
    /**
     * A constant for an error message if the value is not between min and max.
     */
    const NOT_BETWEEN = 'Between::NOT_BETWEEN';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_BETWEEN => '{{ name }} must be between {{ min }} and {{ max }}'
    ];

    /**
     * The lower boundary.
     *
     * @var int
     */
    protected $min;

    /**
     * The upper boundary.
     *
     * @var int
     */
    protected $max;

    /**
     * @var bool
     */
    protected $inclusive;

    /**
     * Construct the Between rule.
     *
     * @param int $min
     * @param int $max
     * @param bool $inclusive
     */
    public function __construct($min, $max, $inclusive = true)
    {
        $this->min = $min;
        $this->max = $max;
        $this->inclusive = (bool) $inclusive;
    }

    /**
     * Checks whether or not $value is between min and max for this rule.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        // inclusive
        if ($this->inclusive && $value >= $this->min && $value <= $this->max) {
            return true;
        }
        // exclusive
        if ($value > $this->min && $value < $this->max) {
            return true;
        }
        return $this->error(self::NOT_BETWEEN);
    }

    /**
     * Returns the parameters that may be used in a validation message.
     *
     * @return array
     */
    protected function getMessageParameters()
    {
        return array_merge(parent::getMessageParameters(), [
            'min' => $this->min,
            'max' => $this->max
        ]);
    }
}
