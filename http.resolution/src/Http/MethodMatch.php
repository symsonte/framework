<?php

namespace Symsonte\Http;

class MethodMatch
{
    /**
     * @var string[]
     */
    private $methods;

    /**
     * @param string[] $methods
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @return string[]
     */
    public function getMethods()
    {
        return array_map('strtoupper', $this->methods);
    }
}
