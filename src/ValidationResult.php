<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator;

/**
 * The ValidationResult class is used when you want to communicate both result as messages to a different layer in your
 * application.
 *
 * @package Particle\Validator
 */
class ValidationResult
{
    /**
     * @var bool
     */
    protected $result;

    /**
     * @var array
     */
    protected $messages;

    /**
     * Construct the validation result.
     *
     * @param bool $result
     * @param array $messages
     */
    public function __construct($result, array $messages)
    {
        $this->result = $result;
        $this->messages = $messages;
    }

    /**
     * Returns whether or not the validator has validated the values.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->result;
    }

    /**
     * Returns the array of messages that were collected during validation.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
