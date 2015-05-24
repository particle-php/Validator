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
        if (strpos($key, '.') !== false) {
            return $this->traverse($key, false);
        }
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
        if ($this->has($key)) {
            if (strpos($key, '.') !== false) {
                return $this->traverse($key, true);
            }
            return $this->values[$key];
        }
        return null;
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
        if (strpos($key, '.') !== false) {
            $parts = explode('.', $key);
            $root = $this->values;
            $ref = &$root;

            foreach ($parts as $i => $part) {
                if ($i < count($parts) - 1) {
                    $ref[$part] = [];
                }
                $ref = &$ref[$part];
            }
            $ref = $value;

            $this->values[$parts[0]] = $root[$parts[0]];
            return $this;
        }

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

    /**
     * Traverses the key using dot notation. Based on the second parameter, it will return the value or if it was set.
     *
     * @param string $key
     * @param bool $returnValue
     * @return mixed
     */
    protected function traverse($key, $returnValue = true)
    {
        $value = $this->values;
        $parts = explode('.', $key);

        foreach ($parts as $part) {
            if (!array_key_exists($part, $value)) {
                return false;
            }
            $value = $value[$part];
        }
        return $returnValue ? $value : true;
    }
}
