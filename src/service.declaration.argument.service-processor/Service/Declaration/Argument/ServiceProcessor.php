<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Container;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\Service\NonexistentServiceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({tags: ['symsonte.service.declaration.argument.processor']})
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

        try {
            return $this->container->get($argument->getId());
        } catch (NonexistentServiceException $e) {
            if ($argument->isOptional()) {
                return;
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    private function support($argument)
    {
        return $argument instanceof ServiceArgument;
    }
}
