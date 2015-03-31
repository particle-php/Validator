<?php
namespace Particle\Validator\Value;

class Container
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function has($key)
    {
        return isset($this->values[$key]);
    }

    public function get($key)
    {
        return $this->has($key) ? $this->values[$key] : null;
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    public function getArrayCopy()
    {
        return $this->values;
    }
}
