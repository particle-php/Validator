<?php

namespace Particle\Validator;

class MessageStack
{
    protected $messages = [];

    protected $overwrites = [];

    public function append($key, $reason, $message, array $parameters)
    {
        if (isset($this->overwrites[$key][$reason])) {
            $message = $this->overwrites[$key][$reason];
        }

        $this->messages[$key][$reason] = $this->format($message, $parameters);
    }

    public function getMessages()
    {
        return $this->messages;
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

    /**
     * @param array $messages
     */
    public function setMessages(array $messages)
    {
        $this->overwrites = $messages;
    }
}
