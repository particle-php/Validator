<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator\Value;

/**
 * This class is used to wrap both input as output arrays.
 *
 * @package Particle\Validator
 */
class Container
{
    /**
     * Contains the values (either input or output).
     *
     * @var array
     */
    protected $values = [];

    /**
     * Construct the Value\Container.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * Determines whether or not the container has a value for key $key.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * Returns the value for the key $key, or null if the value doesn't exist.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->has($key) ? $this->values[$key] : null;
    }

    /**
     * Set the value of $key to $value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * Returns a plain array representation of the Value\Container object.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->values;
    }
}
