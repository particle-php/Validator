<?php
namespace Particle\Validator;

class Validator
{
    /**
     * @var Chain[]
     */
    protected $chains = [];

    /**
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * Message overwrites.
     *
     * @var array
     */
    protected $overwrites = [];

    public function required($key, $name = null, $allowEmpty = false)
    {
        return $this->chains[$key] = new Chain($key, $name, true, $allowEmpty);
    }

    public function optional($key, $name = null, $allowEmpty = true)
    {
        return $this->chains[$key] = new Chain($key, $name, false, $allowEmpty);
    }

    public function validate(array $values)
    {
        $valid = true;
        $this->messageStack = new MessageStack();
        $this->messageStack->setMessages($this->overwrites);

        foreach ($this->chains as $chain) {
            $valid = $chain->validate($this->messageStack, $values) && $valid;
        }
        return $valid;
    }

    public function getMessages()
    {
        return $this->messageStack->getMessages();
    }

    /**
     * Overwrite the default messages.
     *
     * @param array $messages
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->overwrites = $messages;
        return $this;
    }
}
