<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Container;
use Symsonte\Service\Declaration\TaggedServicesArgument;
use Symsonte\Service\Declaration\TagStorer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class TaggedServicesProcessor implements Processor
{
    /**
     * @var TagStorer
     */
    private $storer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param TagStorer $storer
     */
    function __construct(TagStorer $storer)
    {
        $this->storer = $storer;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param TaggedServicesArgument $argument
     *
     * @return object[]
     *
     * @throws UnsupportedArgumentException if the argument is not supported
     */
    public function process($argument)
    {
        if ($this->support($argument) === false) {
            throw new UnsupportedArgumentException($argument);
        }

        $instances = [];
        $ids = $this->storer->get($argument->getTag());
        foreach ($ids as $id) {
            $instances[] = $this->container->get($id);
        }

        return $instances;
    }

    /**
     * @param $argument
     *
     * @return bool
     */
    private function support($argument)
    {
        return $argument instanceof TaggedServicesArgument;
    }
}