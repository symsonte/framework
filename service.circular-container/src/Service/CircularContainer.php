<?php

namespace Symsonte\Service;

use Symsonte\Service\Declaration\Call\Processor as CallProcessor;
use Symsonte\Service\Declaration\Call\Storer as CallStorer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CircularContainer implements Container
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var CallStorer
     */
    private $callStorer;

    /**
     * @var CallProcessor
     */
    private $callProcessor;

    /**
     * @var array
     */
    private $instantiated = [];

    /**
     * @param Container     $container
     * @param CallStorer    $callStorer
     * @param CallProcessor $callProcessor
     */
    public function __construct(
        Container $container,
        CallStorer $callStorer,
        CallProcessor $callProcessor
    ) {
        $this->container = $container;
        $this->callStorer = $callStorer;
        $this->callProcessor = $callProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $instance = $this->container->get($id);

        if (!isset($this->instantiated[$id])) {
            foreach ($this->callStorer->all() as $internalId => $calls) {
                try {
                    $this->instantiated[$id] = true;

                    $this->callProcessor->process(
                        $this->container->get($internalId),
                        $calls
                    );

                    $this->callStorer->remove($internalId);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $instance;
    }
}
