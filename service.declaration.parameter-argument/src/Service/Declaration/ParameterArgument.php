<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ParameterArgument implements Argument
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Gets key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}