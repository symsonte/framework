<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration\Argument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Processor
{
    /**
     * Processes given argument.
     *
     * @param mixed $argument
     *
     * @return mixed
     *
     * @throws UnsupportedArgumentException if given argument is not supported.
     */
    public function process($argument);
}