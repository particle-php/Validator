<?php
namespace Particle\Validator;

class Validator
{
    /**
     * @var Chain[]
     */
    protected $chains;

    /**
     * @var MessageStack
     */
    protected $messageStack;

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

        foreach ($this->chains as $chain) {
            $valid = $chain->validate($this->messageStack, $values) && $valid;
        }
        return $valid;
    }

    public function getMessages()
    {
        return $this->messageStack->getMessages();
    }
}
