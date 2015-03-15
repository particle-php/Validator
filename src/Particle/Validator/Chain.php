<?php
namespace Particle\Validator;

class Chain
{
    protected $key;
    protected $name;

    /**
     * @var Rule[]
     */
    protected $rules = [];

    /**
     * @var MessageStack
     */
    protected $messageStack;

    public function __construct($key, $name, $required, $allowEmpty)
    {
        $this->key = $key;
        $this->name = $name;

        $this->addRule(new Rule\Required($required, $allowEmpty));
    }

    public function length($length)
    {
        return $this->addRule(new Rule\Length($length));
    }

    /**
     * @param Callable $callback
     * @return $this
     */
    public function required(Callable $callback)
    {
        $this->rules[0]->setRequired($callback);

        return $this;
    }

    /**
     * @param Callable $callback
     * @return $this
     */
    public function allowEmpty(Callable $callback)
    {
        $this->rules[0]->setAllowEmpty($callback);
        return $this;
    }

    public function validate(MessageStack $messageStack, array $values)
    {
        $valid = true;
        foreach ($this->rules as $rule) {
            $rule->setMessageStack($messageStack);
            $rule->setParameters($this->key, $this->name);

            $valid = $rule->isValid($this->key, $values) && $valid;

            if ($rule->shouldBreakChain()) {
                return $valid;
            }
        }
        return $valid;
    }

    protected function addRule(Rule $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

}
