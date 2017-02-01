<?php

namespace Symsonte\Http\Server\Request;

class MethodMatch
{
    /**
     * @var string
     */
    private $method;

    /**
     * @param string $method
     */
    function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->method);
    }
}