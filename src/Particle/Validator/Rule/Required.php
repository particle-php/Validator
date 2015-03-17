<?php
namespace Particle\Validator\Rule;

use Particle\Validator\Rule;

class Required extends Rule
{
    const NON_EXISTENT_KEY = 'Required::NON_EXISTENT_KEY';
    const EMPTY_VALUE = 'Required::EMPTY_VALUE';

    protected $messageTemplates = [
        self::NON_EXISTENT_KEY => 'The key "{{ key }}" is required, but does not exist',
        self::EMPTY_VALUE => 'The value of "{{ name }}" can not be empty'
    ];

    /**
     * @var bool
     */
    protected $shouldBreak;


    protected $requiredCallback;
    protected $allowEmptyCallback;

    /**
     * @param bool $required
     * @param bool $allowEmpty
     */
    public function __construct($required, $allowEmpty)
    {
        $this->required = $required;
        $this->allowEmpty = $allowEmpty;
    }

    /**
     * @return bool
     */
    public function shouldBreakChain()
    {
        return $this->shouldBreak;
    }

    public function validate($value)
    {
        if (isset($this->requiredCallback)) {
            $this->required = call_user_func_array($this->requiredCallback, [$this->values]);
        }

        if (isset($this->allowEmptyCallback)) {
            $this->allowEmpty = call_user_func_array($this->allowEmptyCallback, [$this->values]);
        }

        if ($this->required && !array_key_exists($value, $this->values)) {
            $this->shouldBreak = true;
            return $this->error(self::NON_EXISTENT_KEY);
        }

        if (!$this->required && !array_key_exists($value, $this->values)) {
            $this->shouldBreak = true;
            return true;
        }

        if (!$this->allowEmpty && strlen($this->values[$value]) === 0) {
            $this->shouldBreak = true;
            return $this->error(self::EMPTY_VALUE);
        }

        return true;
    }

    public function isValid($key, array $values)
    {
        $this->values = $values;

        return $this->validate($key);
    }

    /**
     * @param callable $required
     * @return $this
     */
    public function setRequired(callable $required)
    {
        $this->requiredCallback = $required;
        return $this;
    }

    /**
     * @param callable $allowEmpty
     * @return $this
     */
    public function setAllowEmpty(callable $allowEmpty)
    {
        $this->allowEmptyCallback = $allowEmpty;
        return $this;
    }
}
