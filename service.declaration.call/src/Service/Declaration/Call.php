<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Call
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var Argument[]
     */
    private $arguments;

    /**
     * @param string     $method
     * @param Argument[] $arguments
     */
    public function __construct($method, $arguments = [])
    {
        $this->method = $method;
        $this->arguments = $arguments;
    }

    /**
     * Gets method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets arguments.
     *
     * @return Argument[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
