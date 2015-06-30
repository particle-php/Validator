<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator;

use Particle\Validator\Value\Container;
use Particle\Validator\Exception\ValidationNotRunException;

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
    protected $chains = [
        self::DEFAULT_CONTEXT => [],
    ];

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
     * An array containing all the default messages.
     *
     * @var array
     */
    protected $defaultMessages = [];

    /**
     * Contains a Value\Container to keep track of all values we *have* validated.
     *
     * @var Container
     */
    protected $output;

    /**
     * Contains the name of the current context.
     *
     * @var string
     */
    protected $context;

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
        return $this->getChain($key, $name, true, $allowEmpty);
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
        return $this->getChain($key, $name, false, $allowEmpty);
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
        $this->output = new Container();
        $input = new Container($values);

        foreach ($this->chains[$context] as $chain) {
            /** @var Chain $chain */
            $valid = $chain->validate($messageStack, $input, $this->output) && $valid;
        }
        return $valid;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        if (!$this->output instanceof Container) {
            return [];
        }
        return $this->output->getArrayCopy();
    }

    /**
     * Returns an array of all validation failures.
     *
     * @throws ValidationNotRunException
     * @return array
     */
    public function getMessages()
    {
        if (is_null($this->messageStack)) {
            throw new ValidationNotRunException();
        }

        return $this->messageStack->getMessages();
    }

    /**
     * Copy the rules and messages of the context $otherContext to the current context.
     *
     * @param string $otherContext
     * @param callable|null $callback
     * @return $this
     */
    public function copyContext($otherContext, callable $callback = null)
    {
        $this->copyMessages($otherContext);
        $this->copyChains($otherContext, $callback);

        return $this;
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

    /** * Overwrite the messages for specific keys.
     *
     * @param array $messages
     * @return $this
     */
    public function overwriteMessages(array $messages)
    {
        $this->messageOverwrites[$this->context] = $messages;
        return $this;
    }

    /**
     * Overwrite the default messages with custom messages.
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
     * Retrieves a Chain object, or builds one if it doesn't exist yet.
     *
     * @param string $key
     * @param string $name
     * @param bool $required
     * @param bool $allowEmpty
     * @return Chain
     */
    protected function getChain($key, $name, $required, $allowEmpty)
    {
        if (isset($this->chains[$this->context][$key])) {
            /** @var Chain $chain */
            $chain = $this->chains[$this->context][$key];
            $chain->required($required);
            $chain->allowEmpty($allowEmpty);

            return $chain;
        }
        return $this->chains[$this->context][$key] = $this->buildChain($key, $name, $required, $allowEmpty);
    }

    /**
     * Build a new Chain object and return it.
     *
     * @param string $key
     * @param string $name
     * @param bool $required
     * @param bool $allowEmpty
     * @return Chain
     */
    protected function buildChain($key, $name, $required, $allowEmpty)
    {
        return new Chain($key, $name, $required, $allowEmpty);
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
        $this->messageStack->overwriteDefaultMessages($this->defaultMessages);

        foreach ([self::DEFAULT_CONTEXT, $context] as $currentContext) {
            if (isset($this->messageOverwrites[$currentContext])) {
                $this->messageStack->overwriteMessages($this->messageOverwrites[$currentContext]);
            }
        }

        return $this->messageStack;
    }

    /**
     * Copies the messages of the context $otherContext to the current context.
     *
     * @param string $otherContext
     */
    protected function copyMessages($otherContext)
    {
        if (isset($this->messageOverwrites[$otherContext])) {
            $this->messageOverwrites[$this->context] = $this->messageOverwrites[$otherContext];
        }
    }

    /**
     * Copies the chains of the context $otherContext to the current context.
     *
     * @param string $otherContext
     * @param callable|null $callback
     */
    protected function copyChains($otherContext, $callback)
    {
        if (isset($this->chains[$otherContext])) {
            $clonedChains = [];
            foreach ($this->chains[$otherContext] as $key => $chain) {
                $clonedChains[$key] = clone $chain;
            }

            $this->chains[$this->context] = $this->runChainCallback($clonedChains, $callback);
        }
    }

    /**
     * Executes the callback $callback and returns the resulting chains.
     *
     * @param Chain[] $chains
     * @param callable|null $callback
     * @return Chain[]
     */
    protected function runChainCallback($chains, $callback)
    {
        if ($callback !== null) {
            $callback($chains);
        }

        return $chains;
    }
}
