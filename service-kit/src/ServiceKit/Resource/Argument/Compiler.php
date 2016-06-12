<?php

namespace Symsonte\ServiceKit\Resource\Argument;

use Symsonte\Service\Declaration\Argument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Compiler
{
    /**
     * @param string $argument
     *
     * @return Argument
     *
     * @throws UnsupportedArgumentException if the parameter is not supported.
     */
    public function compile($argument);
}