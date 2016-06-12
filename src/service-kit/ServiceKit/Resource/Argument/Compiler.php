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
     * @throws UnsupportedArgumentException if the parameter is not supported.
     *
     * @return Argument
     */
    public function compile($argument);
}
