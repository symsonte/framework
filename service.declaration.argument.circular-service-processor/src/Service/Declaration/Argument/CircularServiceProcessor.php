<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration\CircularServiceArgument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CircularServiceProcessor implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function support($argument)
    {
        return $argument instanceof CircularServiceArgument;
    }

    /**
     * @param CircularServiceArgument $argument
     *
     * @return null
     */
    public function process($argument)
    {
    }
}
