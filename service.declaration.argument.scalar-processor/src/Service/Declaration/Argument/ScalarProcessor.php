<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration\ScalarArgument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ScalarProcessor implements Processor
{
    /**
     * @param ScalarArgument $argument
     *
     * @throws UnsupportedArgumentException if the argument is not supported.
     *
     * @return object
     */
    public function process($argument)
    {
        if ($this->support($argument) === false) {
            throw new UnsupportedArgumentException($argument);
        }

        return $argument->getValue();
    }

    /**
     * {@inheritdoc}
     */
    private function support($argument)
    {
        return $argument instanceof ScalarArgument;
    }
}
