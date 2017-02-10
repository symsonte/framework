<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Resource\FileResource;
use Symsonte\Resource\OrdinaryCacher;
use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CachedServiceBuilder implements Builder
{
    /**
     * @var string
     */
    private $parametersFile;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string[]
     */
    private $filters;

    /**
     * @param string   $parametersFile
     * @param string   $cacheDir
     * @param string[] $filters
     */
    public function __construct($parametersFile, $cacheDir, array $filters)
    {
        $this->parametersFile = $parametersFile;
        $this->cacheDir = $cacheDir;
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $setupBuilder = new SetupBuilder(
            $this->parametersFile,
            $this->cacheDir,
            $this->filters
        );
        $containerBuilder = new ContainerBuilder();
        $container = $containerBuilder->build($setupBuilder->build());
        /** @var OrdinaryCacher $cacher */
        $cacher = $container->get('symsonte.resource.ordinary_cacher');

        $serviceBuilder = new ServiceBuilder(
            $this->parametersFile,
            $this->cacheDir,
            $this->filters
        );

        $resource = new FileResource(__FILE__);

        if ($cacher->approve($resource)) {
            return $cacher->retrieve($resource);
        }

        $bag = $serviceBuilder->build();

        $cacher->store($bag, $resource);

        return $bag;
    }
}
