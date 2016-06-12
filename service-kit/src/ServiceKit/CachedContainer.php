<?php

namespace Symsonte\ServiceKit;

use Symsonte\Service\Container as BaseContainer;
use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;
use Symsonte\ServiceKit\Declaration\Bag;
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
     * @param string     $parametersFiles
     * @param object[]   $objects
     * @param string     $cacheDir
     * @param array|null $filters
     */
    public function __construct($parametersFiles, $objects, $cacheDir, $filters = [])
    {
        $bag = (new CachedServiceBuilder($parametersFiles, $cacheDir, $filters))->build(new Bag());

        $bag = new Bag(
            $bag->getDeclarations(),
            array_merge(
                $bag->getParameters(),
                ['cache_dir' => $cacheDir]
            ),
            $objects
        );

        $this->container = (new ContainerBuilder())->build($bag);
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
