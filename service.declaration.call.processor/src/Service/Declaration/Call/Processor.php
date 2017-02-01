<?php

namespace Symsonte\Service\Declaration\Call;

use Symsonte\Service\Declaration\Argument\Processor as ArgumentProcessor;
use Symsonte\Service\Declaration\Call;

class Processor
{
    /**
     * @var ArgumentProcessor
     */
    private $argumentProcessor;

    /**
     * @param ArgumentProcessor $argumentProcessor
     */
    function __construct(ArgumentProcessor $argumentProcessor)
    {
        $this->argumentProcessor = $argumentProcessor;
    }

    /**
     * @param object $instance
     * @param Call[] $calls
     */
    public function process($instance, $calls)
    {
        foreach ($calls as $call) {
            $arguments = [];
            foreach ($call->getArguments() as $argument) {
                $arguments[] = $this->argumentProcessor->process($argument);
            }

            call_user_func_array(array($instance, $call->getMethod()), $arguments);
        }
    }
}