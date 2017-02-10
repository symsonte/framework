<?php

namespace Symsonte\ServiceKit;

use Symsonte\Service\Container as BaseContainer;
use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;
use Symsonte\ServiceKit\Declaration\Bag\ServiceBuilder;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Container implements BaseContainer
{
    /**
     * @var BaseContainer
     */
    private $container;

    /**
     * @param string      $parametersFiles
     * @param string      $cacheDir
     * @param array|null  $filters
     */
    public function __construct($parametersFiles, $cacheDir, $filters = [])
    {
        $containerBuilder = new ContainerBuilder();
        $bagBuilder = new ServiceBuilder($parametersFiles, $cacheDir, $filters);
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
