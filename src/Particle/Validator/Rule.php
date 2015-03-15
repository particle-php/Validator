<?php

namespace Particle\Validator;

abstract class Rule
{
    /**
     * @var array
     */
    protected $values;

    /**
     * @var array
     */
    protected $messageTemplates = [];

    /**
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param mixed $value
     * @return bool
     */
    abstract public function validate($value);

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldBreakChain()
    {
        return false;
    }

    /**
     * @param MessageStack $messageStack
     * @return $this
     */
    public function setMessageStack(MessageStack $messageStack)
    {
        $this->messageStack = $messageStack;
        return $this;
    }

    /**
     * @param string $key
     * @param string $name
     * @return $this
     */
    public function setParameters($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $key
     * @param array $values
     * @return bool
     */
    public function isValid($key, array $values)
    {
        $value = array_key_exists($key, $values) ? $values[$key] : null;
        return $this->validate($value, $values);
    }

    /**
     * @param string $reason
     * @return bool
     */
    protected function error($reason)
    {
        $this->messageStack->append(
            $this->key,
            $reason,
            $this->getMessage($reason)
        );

        return false;
    }

    /**
     * @return array
     */
    protected function getMessageParameters()
    {
        $name = isset($this->name) ? $this->name : str_replace('_', ' ', $this->key);

        return [
            'key' => $this->key,
            'name' => $name,
        ];
    }

    /**
     * @param mixed $reason
     * @return string
     */
    protected function getMessage($reason)
    {
        $messageTemplate = '';
        if (array_key_exists($reason, $this->messageTemplates)) {
            $messageTemplate = $this->messageTemplates[$reason];
        }

        return $this->messageStack->format($messageTemplate, $this->getMessageParameters());
    }
}
