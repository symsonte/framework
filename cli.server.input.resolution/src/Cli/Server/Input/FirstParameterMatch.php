<?php

namespace Symsonte\Cli\Server\Input;

class FirstParameterMatch
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $pattern
     */
    function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}