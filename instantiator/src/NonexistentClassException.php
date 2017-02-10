<?php

namespace Symsonte;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonexistentClassException extends \InvalidArgumentException
{
    /**
     * @param string $class
     */
    public function __construct($class)
    {
        parent::__construct(sprintf('Class "%s" was not found.', $class));

        $this->class = $class;
    }
}
