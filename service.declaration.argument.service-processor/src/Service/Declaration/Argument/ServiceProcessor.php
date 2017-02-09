<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Container;
use Symsonte\Service\Declaration\ServiceArgument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ServiceProcessor implements Processor
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param ServiceArgument $argument
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

        return $this->container->get($argument->getId());
    }

    /**
     * {@inheritdoc}
     */
    private function support($argument)
    {
        return $argument instanceof ServiceArgument;
    }
}
