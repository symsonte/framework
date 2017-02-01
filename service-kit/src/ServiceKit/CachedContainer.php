<?php

namespace Symsonte\ServiceKit;

use Symsonte\Service\Container as BaseContainer;
use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;
use Symsonte\ServiceKit\Declaration\Bag\CachedServiceBuilder;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CachedContainer implements BaseContainer
{
    /**
     * @var BaseContainer
     */
    private $container;

    /**
     * @param string     $parametersFile
     * @param string     $cacheDir
     * @param array|null $filters
     */
    public function __construct($parametersFile, $cacheDir, $filters = [])
    {
        $containerBuilder = new ContainerBuilder();
        $bagBuilder = new CachedServiceBuilder($parametersFile, $cacheDir, $filters);
        $bag = $bagBuilder->build();

        $this->container = $containerBuilder->build($bag);
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
        return $this->container->get($id);
    }
}
