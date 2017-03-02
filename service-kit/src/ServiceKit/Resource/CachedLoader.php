<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Cacher;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service()
 * @di\service()
 */
class CachedLoader
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var Cacher
     */
    private $cacher;

    /**
     * @param Loader $loader
     * @param Cacher $cacher
     *
     * @ds\arguments({
     *     loaders: '@symsonte.service_kit.resource.loader',
     *     cacher:  '@symsonte.service_kit.resource.cacher'
     * })
     *
     * @di\arguments({
     *     loaders: '@symsonte.service_kit.resource.loader',
     *     cacher:  '@symsonte.service_kit.resource.cacher'
     * })
     */
    public function __construct(Loader $loader, Cacher $cacher)
    {
        $this->loader = $loader;
        $this->cacher = $cacher;
    }

    /**
     * @param mixed $resource
     *
     * @return Bag
     */
    public function load($resource)
    {
        if ($this->cacher->approve($resource)) {
            return $this->cacher->retrieve($resource);
        }

        $bag = $this->loader->load($resource);

        $this->cacher->store(
            $bag,
            $resource
        );

        return $bag;
    }
}
