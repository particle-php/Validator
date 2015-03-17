<?php

namespace Particle\Validator;

class MessageStack
{
    /**
     * Contains a list of all validation messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Contains an array of field and reason specific message overwrites.
     *
     * @var array
     */
    protected $overwrites = [];

    /**
     * Contains an array of global message overwrites.
     *
     * @var array
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
        if (isset($this->defaultMessages[$reason])) {
            $message = $this->defaultMessages[$reason];
        }

        if (isset($this->overwrites[$key][$reason])) {
            $message = $this->overwrites[$key][$reason];
        }

        $this->messages[$key][$reason] = $this->format($message, $parameters);
    }

    /**
     * Returns a list of all messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
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
     * @param string $message
     * @param array $parameters
     * @return mixed
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
