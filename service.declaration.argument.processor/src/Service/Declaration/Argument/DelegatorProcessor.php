<?php

namespace Symsonte\Service\Declaration\Argument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorProcessor implements Processor
{
    /**
     * @var Processor[]
     */
    private $processors;

    /**
     * @param Processor[] $processors
     */
    function __construct($processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function process($argument)
    {
        foreach ($this->processors as $processor) {
            try {
                return $processor->process($argument);
            } catch (UnsupportedArgumentException $e) {
                continue;
            }
        }

        throw new UnsupportedArgumentException($argument);
    }
}