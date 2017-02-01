<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ParameterStorer
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param array $parameters
     */
    function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Adds a parameter.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function add($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Returns whether parameter with given key exists.
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * Gets parameter with given key.
     *
     * @param $key
     *
     * @return string
     *
     * @throws NonexistentParameterException if the parameter does not exist
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new NonexistentParameterException($key);
        }

        return $this->parameters[$key];
    }

    /**
     * Gets all parameters.
     *
     * @return array
     */
    public function all()
    {
        return $this->parameters;
    }
}