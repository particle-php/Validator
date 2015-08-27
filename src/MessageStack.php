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
 * The MessageStack is responsible for keeping track of all validation messages, including their overwrites.
 *
 * @package Particle\Validator
 */
class MessageStack
{
    /**
     * Contains a list of all validation messages.
     *
     * @var array<string $key, array<string $reason, array<string message, array<mixed $parameters>>>>
     */
    protected $messages = [];

    /**
     * Contains an array of field and reason specific message overwrites.
     *
     * @var array<string $key, array<string $reason, string message>>
     */
    protected $overwrites = [];

    /**
     * Contains an array of global message overwrites.
     *
     * @var array<string $key, array<string $reason, string message>>
     */
    protected $defaultMessages = [];

    /**
     * Will append an error message for the target $key with $reason to the stack.
     *
     * @param string $key
     * @param string $reason
     * @param string $message
     * @param array $parameters
     */
    public function append($key, $reason, $message, array $parameters)
    {
        $this->messages[$key][$reason] = [
            'message' => $message,
            'parameters' => $parameters,
        ];
    }

    /**
     * Returns a list of all messages.
     *
     * @return array
     */
    public function getMessages()
    {
        $finalMessages = [];

        foreach ($this->messages as $key => $reasons) {
            foreach ($reasons as $reason => $message) {
                $finalMessages[$key][$reason] = $this->format(
                    $this->overwriteFinalMessage($message['message'], $key, $reason),
                    $message['parameters']
                );
            }
        }

        return $finalMessages;
    }

    /**
     * Overwrite key-validator specific messages (so [first_name => [Length::TOO_SHORT => 'Message']]).
     *
     * @param array $messages
     * @return $this
     */
    public function overwriteMessages(array $messages)
    {
        $this->overwrites = $messages;
        return $this;
    }

    /**
     * Overwrite the default validator-specific messages (so [Length::TOO_SHORT => 'Generic message'].
     *
     * @param array $messages
     * @return $this
     */
    public function overwriteDefaultMessages(array $messages)
    {
        $this->defaultMessages = $messages;
        return $this;
    }

    /**
     * Overwrites a message if overwrites are set
     *
     * @param string $message
     * @param string $key
     * @param string $reason
     * @return string
     */
    protected function overwriteFinalMessage($message, $key, $reason)
    {
        if (isset($this->defaultMessages[$reason])) {
            $message = $this->defaultMessages[$reason];
        }

        if (isset($this->overwrites[$key][$reason])) {
            $message = $this->overwrites[$key][$reason];
        }

        return $message;
    }

    /**
     * @param string $message
     * @param array $parameters
     * @return string
     */
    protected function format($message, array $parameters)
    {
        $replace = function ($matches) use ($parameters) {
            if (array_key_exists($matches[1], $parameters)) {
                return $parameters[$matches[1]];
            }
            return $matches[0];
        };

        return preg_replace_callback('~{{\s*([^}\s]+)\s*}}~', $replace, $message);
    }
}
