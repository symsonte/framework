<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorContainer implements Container
{
    /**
     * @var Container[]
     */
    private $containers;

    /**
     * @param Container[] $containers
     */
    public function __construct($containers)
    {
        $this->containers = $containers;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        foreach ($this->containers as $container) {
            if ($container->has($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        foreach ($this->containers as $container) {
            if ($container->has($id)) {
                return $container->get($id);
            }
        }

        throw new NonexistentServiceException($id);
    }
}
