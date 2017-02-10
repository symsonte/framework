<?php

namespace Symsonte;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonexistentMethodException extends \InvalidArgumentException
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
}