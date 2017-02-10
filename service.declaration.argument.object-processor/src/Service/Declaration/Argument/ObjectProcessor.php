<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration\ObjectArgument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ObjectProcessor implements Processor
{
    /**
     * @param ObjectArgument $argument
     *
     * @throws UnsupportedArgumentException if the argument is not supported.
     *
     * @return object
     */
    public function process($argument)
    {
        if (!$argument instanceof ObjectArgument) {
            throw new UnsupportedArgumentException($argument);
        }

        return $argument->getObject();
    }
}
