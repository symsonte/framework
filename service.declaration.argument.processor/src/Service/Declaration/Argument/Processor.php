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
     * @throws UnsupportedArgumentException if given argument is not supported.
     *
     * @return mixed
     */
    public function process($argument);
}
