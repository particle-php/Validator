<?php
namespace Particle\Validator;

class Validator
{
    /**
     * The default context (if no context is currently active).
     */
    const DEFAULT_CONTEXT = 'default';

    /**
     * Contains an array of context => Chain objects.
     *
     * @var array
     */
    protected $chains = [];

    /**
     * Contains an array of context => messages.
     *
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * Message overwrites.
     *
     * @var array
     */
    protected $messageOverwrites = [];

    /**
     * Construct the validator.
     */
    public function __construct()
    {
        $this->context = self::DEFAULT_CONTEXT;
    }

    /**
     * Creates a new required Validation Chain for the key $key.
     *
     * @param string $key
     * @param string|null $name
     * @param bool $allowEmpty
     * @return Chain
     */
    public function required($key, $name = null, $allowEmpty = false)
    {
        return $this->buildChain($key, $name, true, $allowEmpty);
    }

    /**
     * Creates a new optional Validation Chain for the key $key.
     *
     * @param string $key
     * @param string|null $name
     * @param bool $allowEmpty
     * @return Chain
     */
    public function optional($key, $name = null, $allowEmpty = true)
    {
        return $this->buildChain($key, $name, false, $allowEmpty);
    }

    /**
     * Validates the values in the $values array and returns the result as a bool.
     *
     * @param array $values
     * @param string $context
     * @return bool
     */
    public function validate(array $values, $context = self::DEFAULT_CONTEXT)
    {
        $valid = true;
        $messageStack = $this->buildMessageStack($context);

        foreach ($this->chains[$context] as $chain) {
            /** @var Chain $chain */
            $valid = $chain->validate($messageStack, $values) && $valid;
        }
        return $valid;
    }

    /**
     * Returns an array of all validation failures.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messageStack->getMessages();
    }

    /**
     * Create a new validation context with the closure $closure.
     *
     * @param string $name
     * @param \Closure $closure
     */
    public function context($name, \Closure $closure)
    {
        $this->context = $name;
        $closure($this);
        $this->context = self::DEFAULT_CONTEXT;
    }

    /**
     * Overwrite the default messages with specific messages per key.
     *
     * @param array $messages
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messageOverwrites[$this->context] = $messages;
        return $this;
    }

    /**
     * Builds, stores and returns a new Chain object.
     *
     * @param string $key
     * @param string $name
     * @param bool $required
     * @param bool $allowEmpty
     * @return Chain
     */
    protected function buildChain($key, $name, $required, $allowEmpty)
    {
        if (isset($this->chains[$this->context][$key])) {
            return $this->chains[$this->context][$key];
        }
        return $this->chains[$this->context][$key] = new Chain($key, $name, $required, $allowEmpty);
    }

    /**
     * Build a new MessageStack, set the message overwrites and return it.
     *
     * @param string $context
     * @return MessageStack
     */
    protected function buildMessageStack($context)
    {
        $this->messageStack = new MessageStack();

        if (isset($this->messageOverwrites[$context])) {
            $this->messageStack->setMessages($this->messageOverwrites[$context]);
        }

        return $this->messageStack;
    }
}
