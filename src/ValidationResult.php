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
 * The ValidationResult class holds the validation result and the validation messages.
 *
 * @package Particle\Validator
 */
class ValidationResult
{
    /**
     * @var bool
     */
    protected $isValid;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     */
    protected $values;

    /**
     * Construct the validation result.
     *
     * @param bool $isValid
     * @param MessageStack $messages
     * @param array $values
     */
    public function __construct($isValid, MessageStack $messages, array $values)
    {
        $this->isValid = $isValid;
        $this->messages = $messages;
        $this->values = $values;
    }

    /**
     * Returns whether or not the validator has validated the values.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * Returns whether or not the validator has validated the values.
     *
     * @return bool
     */
    public function isNotValid()
    {
        return !$this->isValid;
    }

    /**
     * Overwrite key-validator specific messages (so [first_name => [Length::TOO_SHORT => 'Message']]).
     *
     * @param array $messages
     * @return $this
     */
    public function overwriteMessages(array $messages)
    {
        $this->messages->overwriteMessages($messages);

        return $this;
    }

    /**
     * Returns the array of messages that were collected during validation.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages->getMessages();
    }

    /**
     * Returns all validated values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
