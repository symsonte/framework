<?php

namespace Symsonte\Call\Parameter;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Convertion
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $class
     * @param string $method
     * @param string $parameter
     * @param string $value
     */
    public function __construct($class, $method, $parameter, $value)
    {
        $this->class = $class;
        $this->method = $method;
        $this->parameter = $parameter;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
