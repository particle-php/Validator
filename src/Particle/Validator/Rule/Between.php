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
     * A constant for an error message if the value is exceeding the max value.
     */
    const TOO_BIG = 'Between::TOO_BIG';

    /**
     * A constant for an error message if the value is below the min value.
     */
    const TOO_SMALL = 'Between::TOO_SMALL';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::TOO_BIG => '{{ name }} is too big, upper limit is {{ max }}',
        self::TOO_SMALL => '{{ name }} is too small, lower limit is {{ min }}',
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
        $min = $this->min;
        $max = $this->max;

        // inclusive
        if (!$this->inclusive) {
            $min++;
            $max--;
        }
        if ($value < $min) {
            return $this->error(self::TOO_SMALL);
        }
        if ($value > $max) {
            return $this->error(self::TOO_BIG);
        }
        return true;
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
