<?php

namespace Particle\Validator;

class MessageStack
{
    protected $messages = [];

    public function append($key, $reason, $message)
    {
        $this->messages[$key][$reason] = $message;
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
    public function format($message, array $parameters)
    {
        $replace = function($matches) use ($parameters) {
            if (array_key_exists($matches[1], $parameters)) {
                return $parameters[$matches[1]];
            }
            return $matches[0];
        };

        return preg_replace_callback('~{{\s*([^}\s]+)\s*}}~', $replace, $message);
    }
}
